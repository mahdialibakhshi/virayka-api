<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\ProductImageController;
use App\Models\Attribute;
use App\Models\AttributeGroup;
use App\Models\AttributeValues;
use App\Models\Blog;
use App\Models\Cart;
use App\Models\Comment;
use App\Models\FunctionalTypes;
use App\Models\Label;
use App\Models\ProductAttrVariation;
use App\Models\ProductColorVariation;
use App\Models\ProductOption;
use App\Models\ProductRate;
use App\Models\Setting;
use App\Models\Wishlist;
use Carbon\Carbon;
use App\Models\Tag;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Models\ProductAttribute;
use App\Models\ProductVariation;
use CreateProductVariationsTable;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\ProductAttributeController;
use App\Http\Controllers\Admin\ProductVariationController;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Image;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('quantity', 'desc')->paginate(20);
        $show_per_page = 1;
        foreach ($products as $product) {
            $categories = $product->category_id;
            $categories = json_decode($categories);
            if (is_array($categories)) {
                $category_name = '';
                foreach ($categories as $key => $category) {
                    $category = Category::where('id', $category)->first()->name;
                    if ($key == 0) {
                        $category_name = $category;
                    } else {
                        $category_name = $category . '/' . $category_name;
                    }
                }
                $product['category_name'] = $category_name;
            } else {
                if ($categories != null) {
                    $category_name = Category::where('id', $categories)->first()->name;
                } else {
                    $category_name = '-';
                }
                $product['category_name'] = $category_name;
            }
        }
        $categories = Category::where('parent_id', 0)->get();
        $brands = Brand::all();
        return view('admin.products.index', compact('products',
            'show_per_page',
            'categories',
            'brands'));
    }

    public function products_pagination($show_per_page)
    {
        if ($show_per_page === 'all') {
            $products_count = Product::latest()->count();
            $products = Product::latest()->paginate($products_count);
        } elseif ($show_per_page == 'default') {
            $products = Product::latest()->paginate(20);
            $show_per_page = null;
        } else {
            $products = Product::latest()->paginate($show_per_page);
        }
        foreach ($products as $product) {
            $categories = $product->category_id;
            $categories = json_decode($categories);
            if (is_array($categories)) {
                $category_name = '';
                foreach ($categories as $key => $category) {
                    $category = Category::where('id', $category)->first()->name;
                    if ($key == 0) {
                        $category_name = $category;
                    } else {
                        $category_name = $category . '/' . $category_name;
                    }
                }
                $product['category_name'] = $category_name;
            } else {
                if ($categories != null) {
                    $category_name = Category::where('id', $categories)->first()->name;
                } else {
                    $category_name = '-';
                }
                $product['category_name'] = $category_name;
            }
        }
        $categories = Category::where('parent_id', 0)->get();
        $brands = Brand::all();
        return view('admin.products.index', compact('products', 'show_per_page', 'categories', 'brands'));
    }

    public function create()
    {
        $brands = Brand::all();
        $tags = Tag::all();
        $categories = Category::where('is_active', 1)->get();
        $labels = Label::all();
        $functionalTypes = FunctionalTypes::latest()->get();
        return view('admin.products.create', compact('brands',
            'tags',
            'categories',
            'labels', 'functionalTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:products,name',
            'product_code' => 'nullable|unique:products,product_code',
            'primary_image' => 'nullable|mimes:jpg,jpeg,png,svg',
            'images' => $request->has('images') ? 'required' : 'nullable',
            'images.*' => 'mimes:jpg,jpeg,png,svg',
        ]);
        if ($request->alias != null) {
            $request->validate([
                'alias' => 'required|unique:products,alias',
            ]);
            $alias = $request->alias;
            $alias = parent::aliasCreator($alias);
        } else {
            $alias = null;
        }

        try {
            DB::beginTransaction();
            //parentCategory
            $mainCategoryIds = [];
            $mainCategoryId = null;
            if ($request->category_id != null) {
                foreach ($request->category_id as $cat_id) {
                    $parentId = Category::where('id', $cat_id)->first()->parent_id;
                    while ($parentId != 0) {
                        $mainCategoryId = Category::where('id', $parentId)->first()->id;
                        $parentId = Category::where('id', $parentId)->first()->parent_id;
                    }
                    array_push($mainCategoryIds, $mainCategoryId);
                }
            }
            if ($request->has('primary_image')) {
                $productImageController = new ProductImageController();
                $fileNameImages = $productImageController->upload($request->primary_image, $request->images);
                $primary_image = $fileNameImages['fileNamePrimaryImage'];
            } else {
                $primary_image = null;
            }
            if ($request->price == null) {
                $price = 0;
            } else {
                $price = str_replace(',', '', $request->price);
            }
            //create product
            $product = Product::create([
                'name' => $request->name,
                'alias' => $alias,
                'similarWords' => $request->similarWords,
                'is_active' => $request->is_active,
                'shortDescription' => $request->shortDescription,
                'description' => $request->description,
                'primary_image' => $primary_image,
                'price' => $price,
                'sale_price' => $price,
                'quantity' => $request->quantity == null ? 0 : $request->quantity,
                'label' => $request->label,
                'brand_id' => $request->brand,
                'video' => $request->video,
                'product_code' => $request->product_code,
            ]);
            if ($request->alias == null) {
                $product->update([
                    'alias' => $product->id,
                    'priority_show' => $product->id,
                ]);
            }
            //create product code unique
            $config = Setting::first();
            if ($request->has('primary_image')) {
                foreach ($fileNameImages['fileNameImages'] as $fileNameImage) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image' => $fileNameImage
                    ]);
                }
            }
            //
            //category_product
            $categories = $request->category_id;
            if (!empty($categories)) {
                foreach ($categories as $category) {
                    $product->Categories()->attach($category);
                }
            }

            $types = $request->functional_type_id;
            if (!empty($types)){
                foreach ($types as $type) {
                    $product->functionalTypes()->attach($type);
                }
            }
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            alert()->error('مشکل در ایجاد محصول', $ex->getMessage())->persistent('حله');
            return redirect()->back();
        }

        alert()->success('محصول مورد نظر ایجاد شد', 'باتشکر');
        if ($request->close == 'saveClose') {
            return redirect()->route('admin.products.index');
        }
        if ($request->close == 'save') {
            return redirect()->route('admin.products.edit', ['product' => $product->id]);
        }
    }

    public function show(Product $product)
    {
        $categories = Category::where('parent_id', '!=', 0)->get();
        $images = $product->images;
        $brands = Brand::all();
        $labels = Label::all();
        return view('admin.products.show',
            compact(
                'product',
                'categories',
                'images',
                'brands',
                'labels'));
    }

    public function edit(Product $product)
    {
        $previous_url = URL::previous();
        $previous_url_explode = explode('?page', $previous_url);
        if (count($previous_url_explode) > 1 or $previous_url == route('admin.products.index')) {
            $pre_url = $previous_url;
            session()->put('pre_url', $pre_url);
        } else {
            $pre_url = session()->get('pre_url');
        }
        //get cat ids
        $product_categories = [];
        $category_id = $product->category_id;
        $category_id = json_decode($category_id);
        if (is_array($category_id)) {
            $product_categories = $category_id;
        } else {
            array_push($product_categories, $category_id);
        }
        //get product variations
        $product_attr_variations = ProductAttrVariation::where('product_id', $product->id)->get();
        if (sizeof($product_attr_variations) > 0) {
            $product_has_variations = 1;
        } else {
            $product_has_variations = 0;
        }
        $categories = Category::where('is_active', 1)->get();
        $labels = Label::all();
        $relatedProductsIds = explode(',', $product->relatedProducts);
        $relatedProducts = Product::whereIn('id', $relatedProductsIds)->get();
        $brands = Brand::all();
        $functionalTypes = FunctionalTypes::latest()->get();
        return view('admin.products.edit', compact('product',
            'categories',
            'labels',
            'relatedProducts',
            'product_has_variations',
            'brands',
            'product_categories',
            'pre_url', 'functionalTypes'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|unique:products,name,' . $product->id,
            'product_code' => 'nullable|unique:products,product_code,' . $product->id,
        ]);
        if ($request->has('showOnIndex')) {
            $showOnIndex = 1;
        } else {
            $showOnIndex = 0;
        }
        if ($request->has('has_discount')) {
            $has_discount = 1;
        } else {
            $has_discount = 0;
        }
        if ($request->alias != null) {
            $request->validate([
                'alias' => 'required|unique:products,alias,' . $product->id,
            ]);
            $alias = $request->alias;
            $alias = parent::aliasCreator($alias);
        } else {
            $alias = $product->id;
        }

        try {
            DB::beginTransaction();
            if ($request->relatedProduct == null) {
                $relatedProducts = null;
            } else {
                $relatedProducts = array_unique($request->relatedProduct);
                $relatedProducts = join(',', $relatedProducts);
            }
            //parentCategory
            $mainCategoryIds = [];
            $mainCategoryId = null;
            if ($request->category_id != null) {
                foreach ($request->category_id as $cat_id) {
                    $parentId = Category::where('id', $cat_id)->first()->parent_id;
                    while ($parentId != 0) {
                        $mainCategoryId = Category::where('id', $parentId)->first()->id;
                        $parentId = Category::where('id', $parentId)->first()->parent_id;
                    }
                    array_push($mainCategoryIds, $mainCategoryId);
                }
            }
            if ($request->price == null) {
                $price = $product->price;
            } else {
                $price = str_replace(',', '', $request->price);
            }
            $previous_quantity = $product->quantity;
            $new_quantity = $request->quantity;

         	$types = $request->functional_type_id;
            $product->functionalTypes()->detach();
            if (!empty($types)){
                foreach ($types as $type) {
                    $product->functionalTypes()->attach($type);
                }
            }
            //category_product
            $categories = $request->category_id;
            $product->Categories()->detach();
            if (!empty($categories)) {
                foreach ($categories as $category) {
                    $product->Categories()->attach($category);
                }
            }

            $product->update([
                'name' => $request->name,
                'alias' => $alias,
                'similarWords' => $request->similarWords,
                'is_active' => $request->is_active,
                'description' => $request->description,
                'shortDescription' => $request->shortDescription,
                'price' => $price,
                'sale_price' => $request->sale_price == null ? $product->sale_price : str_replace(',', '', $request->sale_price),
                'percent_sale_price' => $request->percent_sale_price == null ? $product->percent_sale_price : $request->percent_sale_price,
                'quantity' => $request->quantity == null ? 0 : $request->quantity,
                'label' => $request->label,
                'brand_id' => $request->brand,
                'relatedProducts' => $relatedProducts,
                'showOnIndex' => $showOnIndex,
                'has_discount' => $has_discount,
                'DateOnSaleFrom' => convertShamsiToGregorianDate($request->date_on_sale_from),
                'DateOnSaleTo' => convertShamsiToGregorianDate($request->date_on_sale_to),
                'video' => $request->video,
                'product_code' => $request->product_code,
            ]);
            $this->send_sms_to_user_if_product_exist($previous_quantity, $new_quantity, $product->id);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            alert()->error('مشکل در ویرایش محصول', $ex->getMessage())->persistent('حله');
            return redirect()->back();
        }

        alert()->success('محصول مورد نظر ویرایش شد', 'باتشکر');
        if ($request->close == 'saveClose') {
            return redirect()->to($request->previous_rout);
        }
        if ($request->close == 'save') {
            return redirect()->back();
        }
    }

    public function search(Request $request)
    {
        $product_id = $request->product_id;
        $keyWord = $request->keyword;
        if ($keyWord == null) {
            $relatedProducts = Product::where('id', '!=', $product_id)->where('is_active', 1)->latest()->get();
        } else {
            $relatedProducts = Product::where('id', '!=', $product_id)->where('is_active', 1)->where(function ($query) use ($keyWord) {
                $query->where('name', 'LIKE', "%$keyWord%")
                    ->orWhere('similarWords', 'LIKE', "%$keyWord%");
            })->get();
        }
        return response()->json($relatedProducts);
    }

    public function ajax(Request $request)
    {
        $id = $request->id;
        $product = Product::where('id', $id)->first();
        return response()->json($product);
    }

    public function get(Request $request)
    {
        try {
            DB::beginTransaction();
            $config = Setting::first();
            $name = $request->name;
            $brand = $request->brand;
            $category = $request->category;
            $new_or_special_product = $request->new_or_special_product;
            //products id Category
            $Category=Category::where('id',$category)->first();
            $products=Product::where('name','LIKE','%'.$name.'%')
                ->when($new_or_special_product==1,function ($query) use ($new_or_special_product){
                    return $query->where('Set_as_new',1);
                })
                ->when($new_or_special_product==2,function ($query) use ($new_or_special_product){
                    return $query->where('specialSale',1);
                })
                ->when($new_or_special_product==3,function ($query) use ($new_or_special_product){
                    return $query->where('amazing_sale',1);
                })
                ->when($brand!=0,function ($query) use ($brand){
                    return $query->where('brand_id',$brand);
                })->when($request->category!=0,function ($query) use ($Category){
                    return $query->whereHas('categories',function ($query) use($Category){
                        $query->where('id',$Category->id);
                    });
                })

                ->get();
            $view=view('admin.sections.products',compact('products'))->render();

            DB::commit();
            return response()->json([1, $view]);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([0, $exception->getMessage()]);
        }
    }

    public function remove(Request $request)
    {

        $product_id = $request->product_id;
        try {
            DB::beginTransaction();
            $product = Product::where('id', $product_id)->first();
            //delete attributes
            $productAttributes = ProductAttribute::where('product_id', $product_id)->delete();
            //delete product attr variation
            $product_attr_variation = ProductAttrVariation::where('product_id', $product_id)->delete();
            //delete product color variation
            $products_color_variation = ProductColorVariation::where('product_id', $product_id)->delete();
            //delete rates
            $productRates = ProductRate::where('product_id', $product_id)->delete();
            //delete comments
            $productComments = Comment::where('product_id', $product_id)->delete();
            //delete wishlist
            $productWishlist = Wishlist::where('product_id', $product_id)->delete();
            //delete images
            $productImages = ProductImage::where('product_id', $product_id)->get();
            if (sizeof($productImages) > 0) {
                foreach ($productImages as $item) {
                    $path = public_path(env('PRODUCT_IMAGES_UPLOAD_PATH') . $item->image);
                    if (file_exists($path) and !is_dir($path)) {
                        unlink($path);
                    }
                    $path_thumbnail = public_path(env('PRODUCT_IMAGES_THUMBNAIL_UPLOAD_PATH') . $item->image);
                    if (file_exists($path_thumbnail) and !is_dir($path_thumbnail)) {
                        unlink($path_thumbnail);
                    }
                }
                $item->delete();
            }
            //delete variations
            $productVariations = ProductVariation::where('product_id', $product->id)->delete();
            //delete product in cart
            Cart::where('product_id', $product->id)->delete();
            $product->delete();
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json([0 => $ex->getMessage()]);
        }
        $msg = 'کالای مورد نظر با موفقیت حذف شد';
        return response()->json([1, $msg]);

    }

    public function changeStatus(Request $request)
    {

        $product = Product::where('id', $request->product_id)->first();
        $is_active = $product->getRawOriginal('is_active');
        if ($is_active == 0) {
            $newActiveStatus = 1;
        }
        if ($is_active == 1) {
            $newActiveStatus = 0;
        }
        $product->update([
            'is_active' => $newActiveStatus,
        ]);
        return \response()->json([1, $newActiveStatus]);
    }

    public function specialSale(Request $request)
    {

        $product = Product::where('id', $request->product_id)->first();
        $specialSale = $product->getRawOriginal('specialSale');
        if ($specialSale == 0) {
            $newSpecialSale = 1;
            $label=1;
        }
        if ($specialSale == 1) {
            $newSpecialSale = 0;
            $label=0;
        }
        $product->update([
            'specialSale' => $newSpecialSale,
            'label' => $label,
        ]);
        return \response()->json([1, $newSpecialSale]);
    }

    public function Set_as_new(Request $request)
    {

        $product = Product::where('id', $request->product_id)->first();
        $Set_as_new = $product->getRawOriginal('Set_as_new');
        if ($Set_as_new == 0) {
            $new = 1;
        }
        if ($Set_as_new == 1) {
            $new = 0;
        }
        $product->update([
            'Set_as_new' => $new,
        ]);
        return \response()->json([1, $new]);
    }

    public function amazing_sale(Request $request)
    {

        $product = Product::where('id', $request->product_id)->first();
        $amazing_sale = $product->getRawOriginal('amazing_sale');
        $new = 0;
        if ($amazing_sale == 0) {
            $new = 1;
        }
        $product->update([
            'amazing_sale' => $new,
        ]);
        return \response()->json([1, $new]);
    }

    public function priority_show_update(Request $request)
    {

        $priority_show = $request->priority_show;
        $product = Product::where('id', $request->product_id)->first();
        $product->update([
            'priority_show' => $priority_show,
        ]);
        return \response()->json([1]);
    }

//    ============================================== product attributes ================================================
    public function product_attributes_index(Product $product)
    {
        $previous_url = URL::previous();
        $previous_url_explode = explode('?page', $previous_url);
        if (count($previous_url_explode) > 1 or $previous_url == route('admin.products.index')) {
            $pre_url = $previous_url;
            session()->put('pre_url', $pre_url);
        } else {
            $pre_url = session()->get('pre_url');
        }
        $attributes = Attribute::all();
        $attribute_values = AttributeValues::orderBy('name')->get();
        $product_attributes = ProductAttribute::where('product_id', $product->id)->get();
        return view('admin.products.attributes.index',
            compact('product_attributes', 'product', 'attributes', 'attribute_values', 'pre_url'));
    }

    public function product_attributes_add_or_update(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'attribute_id' => 'required',
            'attribute_product_val' => 'required',
        ]);
        $attribute_product_val = $request->attribute_product_val;
        if (is_array($attribute_product_val)) {
            if (count($attribute_product_val) > 1) {
                $names = '';
                $count = count($attribute_product_val);
                foreach ($attribute_product_val as $key => $item) {
                    $attr_value = AttributeValues::findOrFail($item)->name;
                    $key == 0 ? $names = $attr_value : $names = $names . ',' . $attr_value;
                }
                $attribute_product_val = $names;
            } else {
                $attribute_product_val = implode(',', $request->attribute_product_val);
            }
        }

        $attribute_value = ProductAttribute::where('attribute_id', $request->attribute_id)->where('product_id', $request->product_id)->first();
        if ($attribute_value) {
            $attribute_value->update([
                'value' => $attribute_product_val,
                'short_text' => $request->short_text,
                'priority' => $request->priority,
            ]);
            $msg = 'ویژگی مورد نظر باموفقیت ویرایش شد';

        } else {
            ProductAttribute::create([
                'attribute_id' => $request->attribute_id,
                'product_id' => $request->product_id,
                'value' => $attribute_product_val,
                'short_text' => $request->short_text,
                'priority' => $request->priority,
            ]);
            $msg = 'ویژگی مورد نظر باموفقیت اضافه شد';
        }
        return response()->json(['success', $msg]);
    }


    public function product_attributes_remove(ProductAttribute $attribute)
    {
        try {
            DB::beginTransaction();
            $attribute->delete();
            DB::commit();
            alert()->success('مشخصه‌ی فنی مورد نظر با موفقفیت حذف شد', 'باتشکر');
            return redirect()->back();
        } catch (\Exception $exception) {
            DB::rollBack();
            alert()->error($exception->getMessage());
            return redirect()->back();
        }
    }

    public function product_attributes_change_active(Request $request)
    {
        $id = $request->id;
        $is_active = $request->is_active;
        $new_active = $is_active == 1 ? 0 : 1;
        try {
            DB::beginTransaction();
            $product_attributes = ProductAttribute::where('id', $id)->first();
            $product_attributes->update([
                'is_active' => $new_active,
            ]);
            if ($new_active == 1) {
                $button = '<button title="نمایش زیر تصویر محصول" onclick="change_active(' . $product_attributes->id . ',' . $product_attributes->is_active . ',this)" class="btn btn-sm btn-success">فعال</button>';
            } else {
                $button = '<button title="نمایش زیر تصویر محصول" onclick="change_active(' . $product_attributes->id . ',' . $product_attributes->is_active . ',this)" class="btn btn-sm btn-danger">غیرفعال</button>';
            }
            DB::commit();
            return \response()->json([1, $button]);
        } catch (\Exception $exception) {
            DB::rollBack();
            return \response()->json([0, $exception->getMessage()]);
        }
    }

    public function product_attributes_change_original(Request $request)
    {
        $id = $request->id;
        $is_original = $request->is_original;
        $new_original = $is_original == 1 ? 0 : 1;
        try {
            DB::beginTransaction();
            $product_attributes = ProductAttribute::where('id', $id)->first();
            $product_attributes->update([
                'is_original' => $new_original,
            ]);
            if ($new_original == 1) {
                $button = '<button title="نمایش زیر تصویر محصول" onclick="change_original(' . $product_attributes->id . ',' . $product_attributes->is_original . ',this)" class="btn btn-sm btn-success">فعال</button>';
            } else {
                $button = '<button title="نمایش زیر تصویر محصول" onclick="change_original(' . $product_attributes->id . ',' . $product_attributes->is_original . ',this)" class="btn btn-sm btn-danger">غیرفعال</button>';
            }
            DB::commit();
            return \response()->json([1, $button]);
        } catch (\Exception $exception) {
            DB::rollBack();
            return \response()->json([0, $exception->getMessage()]);
        }
    }

//    ============================================== product variations ================================================
    public function product_variations_index(Product $product)
    {
        $product_variations = ProductVariation::where('product_id', $product->id)->get();
        return view('admin.products.variations.index', compact('product_variations', 'product'));
    }

    public function product_variations_create(Product $product)
    {
        $attributes = Attribute::all();
        $attribute_values = AttributeValues::all();
        return view('admin.products.variations.create', compact('product', 'attributes', 'attribute_values'));
    }

    public function product_variations_store(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'attribute_id' => 'required',
            'price' => 'nullable',
            'quantity' => 'required',
            'salePrice' => 'nullable|integer',
            'percentSalePrice' => 'nullable|integer',
            'date_on_sale_from' => 'nullable|date',
            'date_on_sale_to' => 'nullable|date',
            'primary_image' => 'nullable|mimes:jpg,jpeg,png,svg',
        ]);
        if ($request->price == null) {
            $product = Product::where('id', $request->product_id)->first();
            $price = $product->price;
            $salePrice = $product->price;
            $percentSalePrice = 0;
        } else {
            $price = $request->price;
            $salePrice = $request->salePrice;
            $percentSalePrice = $request->percentSalePrice;
        }
        $product_exist = ProductVariation::where('product_id', $request->product_id)
            ->where('attribute_id', $request->attribute_id)
            ->where('value', $request->attribute_input_value)->exists();
        if ($product_exist) {
            alert()->warning('شما از قبل این محصول را ایجاد کرده اید', 'دقت کنید');
            return back();
        }

        try {
            DB::beginTransaction();
            if ($request->has('has_discount')) {
                $has_discount = 1;
            } else {
                $has_discount = 0;
            }
            if ($request->has('showOnIndex')) {
                $showOnIndex = 1;
            } else {
                $showOnIndex = 0;
            }
            if ($request->has('primary_image')) {
                $productImageController = new ProductImageController();
                $fileNameImages = $productImageController->upload($request->primary_image, null);
                $fileNameImages = $fileNameImages['fileNamePrimaryImage'];
            } else {
                $fileNameImages = null;
            }
            //create product variation
            $ProductVariation = ProductVariation::create([
                'attribute_id' => $request->attribute_id,
                'product_id' => $request->product_id,
                'value' => $request->attribute_input_value,
                'primary_image' => $fileNameImages,
                'price' => $price,
                'sale_price' => $salePrice,
                'percentSalePrice' => $percentSalePrice,
                'quantity' => $request->quantity,
                'date_on_sale_from' => convertShamsiToGregorianDate($request->date_on_sale_from),
                'date_on_sale_to' => convertShamsiToGregorianDate($request->date_on_sale_to),
                'showOnIndex' => $showOnIndex,
                'has_discount' => $has_discount,
            ]);
            parent::updateProductWithAttr($request->product_id);
            DB::commit();
            alert()->success('محصول مورد نظر ایجاد شد', 'باتشکر');
            return redirect()->route('admin.product.variations.index', ['product' => $request->product_id,]);
        } catch (\Exception $exception) {
            DB::rollBack();
            dd($exception->getMessage());
        }
    }

    public function product_variations_edit(ProductVariation $variation)
    {
        $attributes = Attribute::all();
        $attribute_values = AttributeValues::all();
        return view('admin.products.variations.edit', compact('variation', 'attributes', 'attribute_values'));
    }

    public function product_variations_update(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'attribute_id' => 'required',
            'price' => 'nullable',
            'quantity' => 'required',
            'salePrice' => 'nullable|integer',
            'percentSalePrice' => 'nullable|integer',
            'date_on_sale_from' => 'nullable|date',
            'date_on_sale_to' => 'nullable|date',
            'primary_image' => 'nullable|mimes:jpg,jpeg,png,svg',
        ]);
        if ($request->price == null) {
            $product = Product::where('id', $request->product_id)->first();
            $price = $product->price;
            $salePrice = $product->price;
            $percentSalePrice = 0;
        } else {
            $price = $request->price;
            $salePrice = $request->salePrice;
            $percentSalePrice = $request->percentSalePrice;
        }
        $product_variation = ProductVariation::where('product_id', $request->product_id)
            ->where('attribute_id', $request->attribute_id)
            ->where('value', $request->attribute_input_value)->first();

        try {
            DB::beginTransaction();
            if ($request->has('has_discount')) {
                $has_discount = 1;
            } else {
                $has_discount = 0;
            }
            if ($request->has('showOnIndex')) {
                $showOnIndex = 1;
            } else {
                $showOnIndex = 0;
            }
            $image = $product_variation->primary_image;
            if ($request->has('primary_image')) {
                //remove image
                $path = public_path(env('PRODUCT_IMAGES_UPLOAD_PATH') . $image);
                if (file_exists($path) and !is_dir($path)) {
                    unlink($path);
                }
                $productImageController = new ProductImageController();
                $fileNameImages = $productImageController->upload($request->primary_image, null);
                $fileNameImages = $fileNameImages['fileNamePrimaryImage'];
            } else {
                $fileNameImages = $image;
            }
            //create product variation
            $product_variation->update([
                'attribute_id' => $request->attribute_id,
                'product_id' => $request->product_id,
                'value' => $request->attribute_input_value,
                'primary_image' => $fileNameImages,
                'price' => $price,
                'sale_price' => $salePrice,
                'percentSalePrice' => $percentSalePrice,
                'quantity' => $request->quantity,
                'date_on_sale_from' => convertShamsiToGregorianDate($request->date_on_sale_from),
                'date_on_sale_to' => convertShamsiToGregorianDate($request->date_on_sale_to),
                'showOnIndex' => $showOnIndex,
                'has_discount' => $has_discount,
            ]);
            parent::updateProductWithAttr($request->product_id);
            DB::commit();
            alert()->success('محصول مورد نظر ویرایش شد', 'باتشکر');
            return redirect()->route('admin.product.variations.index', ['product' => $request->product_id,]);
        } catch (\Exception $exception) {
            DB::rollBack();
            dd($exception->getMessage());
        }
    }

    public function product_variations_remove_image(ProductVariation $variation)
    {
        //remove image
        $path = public_path(env('PRODUCT_IMAGES_UPLOAD_PATH') . $variation->primary_image);
        if (file_exists($path) and !is_dir($path)) {
            unlink($path);
            $variation->update([
                'primary_image' => null,
            ]);
            alert()->success('تصویر با موفقیت حذف شد', 'با تشکر');
            return redirect()->back();
        }
        alert()->error('!تصویر  حذف نشد', 'متاسفیم');
        return redirect()->back();
    }

    public function product_variations_remove(Request $request)
    {
        $product_id = $request->product_id;
        try {
            DB::beginTransaction();
            $variation = ProductVariation::where('product_id', $product_id)->where('id', $request->variation_id)->first();
            //remove image
//            $path = public_path(env('PRODUCT_IMAGES_UPLOAD_PATH') . $variation->primary_image);
//            if (file_exists($path) and !is_dir($path)) {
//                unlink($path);
//                $variation->update([
//                    'primary_image' => null,
//                ]);
//            }
            $variation->delete();
            parent::updateProductWithAttr($variation->product_id);
            DB::commit();
            return \response()->json([1]);
        } catch (\Exception $exception) {
            DB::rollBack();
            return \response()->json([0]);
        }


    }


//    ============================================== product options ================================================
    public function product_options_index(Product $product)
    {
        $previous_url = URL::previous();
        $previous_url_explode = explode('?page', $previous_url);
        if (count($previous_url_explode) > 1 or $previous_url == route('admin.products.index')) {
            $pre_url = $previous_url;
            session()->put('pre_url', $pre_url);
        } else {
            $pre_url = session()->get('pre_url');
        }
        $product_options = ProductOption::where('product_id', $product->id)->get();
        return view('admin.products.options.index', compact('product_options', 'product', 'pre_url'));
    }

    public function product_options_create(Product $product)
    {
        $attributes = Attribute::all();
        $attribute_values = AttributeValues::all();
        return view('admin.products.options.create', compact('product', 'attributes', 'attribute_values'));
    }

    public function product_options_store(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'attribute_id' => 'required',
            'price' => 'required',
        ]);

        $option_exist = ProductOption::where('product_id', $request->product_id)
            ->where('attribute_id', $request->attribute_id)
            ->where('value', $request->attribute_input_value)->exists();
        if ($option_exist) {
            alert()->warning('شما از قبل این مورد را ایجاد کرده اید', 'دقت کنید');
            return back();
        }

        try {
            //create product option
            ProductOption::create([
                'attribute_id' => $request->attribute_id,
                'product_id' => $request->product_id,
                'value' => $request->attribute_input_value,
                'price' => $request->price,
            ]);

            DB::commit();
            alert()->success('ویژگی مورد نظر ایجاد شد', 'باتشکر');
            return redirect()->route('admin.product.options.index', ['product' => $request->product_id,]);
        } catch (\Exception $exception) {
            DB::rollBack();
            dd($exception->getMessage());
        }
    }

    public function product_options_edit(ProductOption $option)
    {
        $attributes = Attribute::all();
        $attribute_values = AttributeValues::all();
        return view('admin.products.options.edit', compact('option', 'attributes', 'attribute_values'));
    }

    public function product_options_update(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'attribute_id' => 'required',
            'price' => 'required',
        ]);

        $product_option = ProductOption::where('product_id', $request->product_id)
            ->where('attribute_id', $request->attribute_id)
            ->where('value', $request->attribute_input_value)->first();

        try {
            //update product option
            $product_option->update([
                'attribute_id' => $request->attribute_id,
                'product_id' => $request->product_id,
                'value' => $request->attribute_input_value,
                'price' => $request->price,
            ]);
            DB::commit();
            alert()->success('ویژگی مورد نظر ویرایش شد', 'باتشکر');
            return redirect()->route('admin.product.options.index', ['product' => $request->product_id,]);
        } catch (\Exception $exception) {
            DB::rollBack();
            dd($exception->getMessage());
        }
    }

    public function product_options_remove(Request $request)
    {
        $product_id = $request->product_id;
        try {
            DB::beginTransaction();
            $option = ProductOption::where('product_id', $product_id)->where('id', $request->option_id)->first();
            $option->delete();
            DB::commit();
            return \response()->json([1, 'ویژگی مورد نظر با موفقیت حذف شد']);
        } catch (\Exception $exception) {
            DB::rollBack();
            return \response()->json([0]);
        }
    }

    //    ============================================== product Copy ================================================
    public function product_copy(Request $request)
    {
        try {
            DB::beginTransaction();
            //copy tb products
            $product = Product::findOrfail($request->product_id);
            $copy_image_name = $this->copy_image(env('PRODUCT_IMAGES_UPLOAD_PATH'), $product->primary_image);
            $copy_image_thumbnail = $this->copy_image(env('PRODUCT_IMAGES_THUMBNAIL_UPLOAD_PATH'), $product->primary_image);
            $new_product = Product::create([
                'name' => $product->name . '_copy',
                'similarWords' => $product->similarWords,
                'brand_id' => $product->brand_id,
                'category_id' => $product->category_id,
                'main_category_id' => $product->main_category_id,
                'primary_image' => $copy_image_name,
                'video' => $product->video,
                'shortDescription' => $product->shortDescription,
                'status' => $product->status,
                'is_active' => $product->getRawOriginal('is_active'),
                'price' => $product->price,
                'sale_price' => $product->sale_price,
                'percent_sale_price' => $product->percent_sale_price,
                'quantity' => $product->quantity,
                'label' => $product->label,
                'relatedProducts' => $product->relatedProducts,
                'delivery_amount' => $product->delivery_amount,
                'delivery_amount_per_product' => $product->delivery_amount_per_product,
                'showOnIndex' => $product->showOnIndex,
                'has_discount' => $product->has_discount,
                'specialSale' => $product->getRawOriginal('specialSale'),
                'Set_as_new' => $product->getRawOriginal('Set_as_new'),
                'amazing_sale' => $product->getRawOriginal('amazing_sale'),
                'priority_show' => $product->priority_show,
                'DateOnSaleFrom' => $product->DateOnSaleFrom,
                'DateOnSaleTo' => $product->DateOnSaleTo,
            ]);
            $new_product->update([
                'alias' => $new_product->id,
            ]);
            //copy product_attributes
            $product_attributes = ProductAttribute::where('product_id', $request->product_id)->get();
            if (count($product_attributes) > 0) {
                foreach ($product_attributes as $product_attribute) {
                    ProductAttribute::create([
                        'attribute_id' => $product_attribute->attribute_id,
                        'value' => $product_attribute->value,
                        'short_text' => $product_attribute->short_text,
                        'is_original' => $product_attribute->is_original,
                        'is_active' => $product_attribute->is_active,
                        'priority' => $product_attribute->priority,
                        'product_id' => $new_product->id,
                    ]);
                }
            }
            //copy product_attr_variation
            $product_attribute_variations = ProductAttrVariation::where('product_id', $request->product_id)->get();
            if (count($product_attribute_variations) > 0) {
                foreach ($product_attribute_variations as $product_attribute_variation) {
                    ProductAttrVariation::create([
                        'product_id' => $new_product->id,
                        'attr_id' => $product_attribute_variation->attr_id,
                        'attr_value' => $product_attribute_variation->attr_value,
                        'color_attr_id' => $product_attribute_variation->color_attr_id,
                        'color_attr_value' => $product_attribute_variation->color_attr_value,
                        'quantity' => $product_attribute_variation->quantity,
                        'price' => $product_attribute_variation->price,
                        'percent_sale_price' => $product_attribute_variation->percent_sale_price,
                    ]);
                }
            }
            //copy product_color_variation
            $product_color_variations = ProductColorVariation::where('product_id', $request->product_id)->get();
            if (count($product_color_variations) > 0) {
                foreach ($product_color_variations as $product_color_variation) {
                    $copy_image_name = $this->copy_image(env('PRODUCT_VARIATION_COLOR_UPLOAD_PATH'), $product_color_variation->image);
                    ProductColorVariation::create([
                        'product_id' => $new_product->id,
                        'attr_id' => $product_color_variation->attr_id,
                        'attr_value' => $product_color_variation->attr_value,
                        'image' => $copy_image_name,
                    ]);
                }
            }
            //copy product_images
            $product_images = ProductImage::where('product_id', $request->product_id)->get();
            if (count($product_images) > 0) {
                foreach ($product_images as $product_image) {
                    $copy_image_name = $this->copy_image(env('PRODUCT_IMAGES_UPLOAD_PATH'), $product_image->image);
                 	 $this->copy_image(env('PRODUCT_IMAGES_THUMBNAIL_UPLOAD_PATH'), $product_image->image);
                    ProductImage::create([
                        'product_id' => $new_product->id,
                        'image' => $copy_image_name,
                        'set_as_second_image' => $product_image->set_as_second_image,
                    ]);
                }
            }
            //copy product_options
            $product_options = ProductOption::where('product_id', $request->product_id)->get();
            if (count($product_options) > 0) {
                foreach ($product_options as $product_option) {
                    ProductOption::create([
                        'product_id' => $new_product->id,
                        'attribute_id' => $product_option->attribute_id,
                        'value' => $product_option->value,
                        'price' => $product_option->price,
                    ]);
                }
            }
            //copy product_options
            $product_options = ProductOption::where('product_id', $request->product_id)->get();
            if (count($product_options) > 0) {
                foreach ($product_options as $product_option) {
                    ProductOption::create([
                        'product_id' => $new_product->id,
                        'attribute_id' => $product_option->attribute_id,
                        'value' => $product_option->value,
                        'price' => $product_option->price,
                    ]);
                }
            }
            //copy product_rates ==> no need!(why?)

            //copy product_tag ==> in this project whe don't have this

            //copy product_variations
            $product_variations = ProductVariation::where('product_id', $request->product_id)->get();
            if (count($product_variations) > 0) {
                foreach ($product_variations as $product_variation) {
                    ProductVariation::create([
                        'product_id' => $new_product->id,
                        'attribute_id' => $product_variation->attribute_id,
                        'value' => $product_variation->value,
                        'primary_image' => $product_variation->primary_image,
                        'price' => $product_variation->price,
                        'quantity' => $product_variation->quantity,
                        'showOnIndex' => $product_variation->showOnIndex,
                        'has_discount' => $product_variation->has_discount,
                        'sku' => $product_variation->sku,
                        'sale_price' => $product_variation->sale_price,
                        'percentSalePrice' => $product_variation->percentSalePrice,
                        'date_on_sale_from' => $product_variation->date_on_sale_from,
                        'date_on_sale_to' => $product_variation->date_on_sale_to,
                    ]);
                }
            }
            DB::commit();
            return \response([1, 'ok']);
        } catch (\Exception $exception) {
            DB::rollBack();
            return \response()->json([0, $exception->getMessage()]);
        }

    }

    public function copy_image($env, $image)
    {
        if ($image != null) {
            $copy_image_name = 'copy_' . $image;
            $first_path = public_path($env . $image);
            $second_path = public_path($env . $copy_image_name);
            copy($first_path, $second_path);
        } else {
            $copy_image_name = null;
        }
        return $copy_image_name;
    }

    //    ============================================== Update Prices ================================================

    public function update_single()
    {
        $categories = Category::all();
        $brands = Brand::all();
        $products_id = [];
        $products = Product::all();
        foreach ($products as $product) {
            array_push($products_id, $product->id);
        }
        $products_variation_ids = [];
        $products_variations = ProductAttrVariation::all();
        foreach ($products_variations as $products_variation) {
            array_push($products_variation_ids, $products_variation->product_id);
        }
        $products_variation_ids = array_unique($products_variation_ids);
        $single_products_ids = array_diff($products_id, $products_variation_ids);

        $products = Product::whereIn('id', $single_products_ids)->paginate(40);
        $show_per_page = 1;

        foreach ($products as $product) {
            $product_categories = $product->category_id;
            $product_categories = json_decode($product_categories);
            if (is_array($product_categories)) {
                $category_name = '';
                foreach ($product_categories as $key => $category) {
                    $category = Category::where('id', $category)->first()->name;
                    if ($key == 0) {
                        $category_name = $category;
                    } else {
                        $category_name = $category . '/' . $category_name;
                    }
                }
                $product['category_name'] = $category_name;
            } else {
                if ($product_categories != null) {
                    $category_name = Category::where('id', $product_categories)->first()->name;
                } else {
                    $category_name = '-';
                }
                $product['category_name'] = $category_name;
            }
        }
        return view('admin.products.update.single', compact('products', 'categories', 'brands', 'single_products_ids', 'show_per_page'));
    }

    public function update_single_pagination($show_per_page)
    {
        $categories = Category::all();
        $brands = Brand::all();
        $products_id = [];
        $products = Product::all();
        foreach ($products as $product) {
            array_push($products_id, $product->id);
        }
        $products_variation_ids = [];
        $products_variations = ProductAttrVariation::all();
        foreach ($products_variations as $products_variation) {
            array_push($products_variation_ids, $products_variation->product_id);
        }
        $products_variation_ids = array_unique($products_variation_ids);
        $single_products_ids = array_diff($products_id, $products_variation_ids);

        if ($show_per_page === 'all') {
            $products_count = Product::whereIn('id', $single_products_ids)->count();
            $products = Product::whereIn('id', $single_products_ids)->paginate($products_count);
        } elseif ($show_per_page == 'default') {
            $products = Product::whereIn('id', $single_products_ids)->paginate(40);
            $show_per_page = null;
        } else {
            $products = Product::whereIn('id', $single_products_ids)->paginate($show_per_page);
        }

        foreach ($products as $product) {
            $product_categories = $product->category_id;
            $product_categories = json_decode($product_categories);
            if (is_array($product_categories)) {
                $category_name = '';
                foreach ($product_categories as $key => $category) {
                    $category = Category::where('id', $category)->first()->name;
                    if ($key == 0) {
                        $category_name = $category;
                    } else {
                        $category_name = $category . '/' . $category_name;
                    }
                }
                $product['category_name'] = $category_name;
            } else {
                if ($product_categories != null) {
                    $category_name = Category::where('id', $product_categories)->first()->name;
                } else {
                    $category_name = '-';
                }
                $product['category_name'] = $category_name;
            }
        }
        return view('admin.products.update.single', compact('products', 'categories', 'brands', 'single_products_ids', 'show_per_page'));
    }

    public function update_single_product_quantity(Request $request)
    {
        try {
            $product = Product::where('id', $request->product_id)->first();
            $product->update([
                'quantity' => $request->quantity,
            ]);
            return \response()->json([1]);
        } catch (\Exception $exception) {
            return \response()->json([0, $exception->getMessage()]);
        }
    }

    public function update_single_price(Request $request)
    {
        try {
            $product = Product::where('id', $request->product_id)->first();
            $price = str_replace(',', '', $request->price);
            $product->update([
                'price' => $price,
            ]);
            return \response()->json([1]);
        } catch (\Exception $exception) {
            return \response()->json([0, $exception->getMessage()]);
        }
    }

    public function single_product_search(Request $request)
    {
        try {
            DB::beginTransaction();
            $name = $request->name;
            $brand = $request->brand;
            $category = $request->category;
            $single_products_ids = $request->single_products_ids;
            $single_products_ids = join(',', $single_products_ids);
            if ($name != null) {
                $where_name = "and name Like '%$name%'";
            } else {
                $where_name = '';
            }
            if ($brand != 0) {
                $where_brand = "and brand_id=$brand";
            } else {
                $where_brand = '';
            }
            if ($category != 0) {
                $where_category = "and (category_id=$category
                  or main_category_id=$category
                  or category_id Like '%" . json_encode($category) . "%'
                  or main_category_id Like '%" . json_encode($category) . "%'
                  )";
            } else {
                $where_category = '';
            }
            $html = '';
            $products = DB::select("select * from products where id IN ($single_products_ids) and deleted_at is null $where_name $where_brand $where_category ");
            foreach ($products as $key => $product) {
                $num = $key + 1;
                //product category
                $categories = $product->category_id;
                $categories = json_decode($categories);
                if (is_array($categories)) {
                    $category_name = '';
                    foreach ($categories as $category) {
                        $category = Category::where('id', $category)->first()->name;
                        $category_name = $category . '/' . $category_name;
                    }
                    $product_category_name = $category_name;
                } else {
                    if ($categories != null) {
                        $category_name = Category::where('id', $categories)->first()->name;
                    } else {
                        $category_name = '-';
                    }
                    $product_category_name = $category_name;
                }
                if ($product->brand_id == null) {
                    $brand = '-';
                } else {
                    $brand = Brand::where('id', $product->brand_id)->first()->name;
                }
                $html .= '<tr>
                            <th>
                                ' . $num . '
                            </th>
                            <th class="position-relative">
                                <a href="' . route('admin.products.show', ['product' => $product->id]) . '">
                                    ' . $product->name . '
                                </a>
                            </th>
                            <th>
                               ' . $product_category_name . '
                            </th>
                            <th>
                                ' . $brand . '
                            </th>
                            <th>
                                <img class="img-thumbnail" src="' . imageExist(env('PRODUCT_IMAGES_UPLOAD_PATH'), $product->primary_image) . '">
                            </th>
                            <th>
                                <input onchange="update_single_product_quantity(' . $product->id . ',this)" class="form-control form-control-sm" value="' . $product->quantity . '">
                            </th>
                            <th>
                                <input onkeyup="price_format(this)" onchange="update_single_price(' . $product->id . ',this)" class="form-control form-control-sm" value="' . number_format($product->price) . '">
                            </th>
                        </tr>';
            }

            return \response()->json([1, $html]);
        } catch (\Exception $exception) {
            return \response()->json([0, $exception->getMessage()]);
        }
    }

    public function update_multi()
    {
        $categories = Category::all();
        $brands = Brand::all();
        $multi_products_ids = [];
        $products_variations = ProductAttrVariation::select('product_id')->distinct()->get();
        foreach ($products_variations as $products_variation) {
            array_push($multi_products_ids, $products_variation->product_id);
        }

        if (isset($_GET['show_per_page'])) {
            $show_per_page = $_GET['show_per_page'];
            if ($show_per_page === 'all') {
                $variations_count = ProductAttrVariation::count();
                $variations = ProductAttrVariation::paginate($variations_count);
            } elseif ($show_per_page == 'default') {
                $variations = ProductAttrVariation::paginate(40);
                $show_per_page = null;
            } else {
                $variations = ProductAttrVariation::paginate($show_per_page);
            }
        } else {
            $variations = ProductAttrVariation::paginate(40);
            $show_per_page = 1;
        }
        return view('admin.products.update.multi', compact('variations',
            'categories', 'brands', 'multi_products_ids', 'show_per_page'));
    }

    public function update_multi_pagination($show_per_page)
    {
        $categories = Category::all();
        $brands = Brand::all();
        $multi_products_ids = [];
        $products_variations = ProductAttrVariation::select('product_id')->distinct()->get();
        foreach ($products_variations as $products_variation) {
            array_push($multi_products_ids, $products_variation->product_id);
        }

        if ($show_per_page === 'all') {
            $variations_count = ProductAttrVariation::count();
            $variations = ProductAttrVariation::paginate($variations_count);
        } elseif ($show_per_page == 'default') {
            $variations = ProductAttrVariation::paginate(40);
            $show_per_page = null;
        } else {
            $variations = ProductAttrVariation::paginate($show_per_page);
        }
        return view('admin.products.update.multi', compact('variations',
            'categories', 'brands', 'multi_products_ids', 'show_per_page'));
    }

    public function update_multi_product_quantity(Request $request)
    {
        try {
            $variation = ProductAttrVariation::where('id', $request->variation_id)->first();
            $variation->update([
                'quantity' => $request->quantity,
            ]);
            $product_id = $variation->product_id;

            parent::updateProductWithAttr($product_id);
            return \response()->json([1]);
        } catch (\Exception $exception) {
            return \response()->json([0, $exception->getMessage()]);
        }
    }

    public function update_multi_price(Request $request)
    {
        try {
            $variation = ProductAttrVariation::where('id', $request->variation_id)->first();
            $price = str_replace(',', '', $request->price);
            $variation->update([
                'price' => $price,
            ]);
            $product_id = $variation->product_id;
            parent::updateProductWithAttr($product_id);
            return \response()->json([1]);
        } catch (\Exception $exception) {
            return \response()->json([0, $exception->getMessage()]);
        }
    }

    public function multi_product_search(Request $request)
    {
        try {
            DB::beginTransaction();
            $name = $request->name;
            $brand = $request->brand;
            $category = $request->category;
            $multi_products_ids = $request->multi_products_ids;
            $multi_products_ids = join(',', $multi_products_ids);
            if ($name != null) {
                $where_name = "and name Like '%$name%'";
            } else {
                $where_name = '';
            }
            if ($brand != 0) {
                $where_brand = "and brand_id=$brand";
            } else {
                $where_brand = '';
            }
            if ($category != 0) {
                $where_category = "and (category_id=$category
                  or main_category_id=$category
                  or category_id Like '%" . json_encode($category) . "%'
                  or main_category_id Like '%" . json_encode($category) . "%'
                  )";
            } else {
                $where_category = '';
            }
            $html = '';
            $products = DB::select("select * from products where id IN ($multi_products_ids) and deleted_at is null  $where_name $where_brand $where_category ");
            $product_ids = [];
            foreach ($products as $product) {
                array_push($product_ids, $product->id);
            }
            $variations = ProductAttrVariation::whereIn('product_id', $product_ids)->get();;
            foreach ($variations as $key => $variation) {
                if (!isset($variation->Product->id)) {
                    dd(['error' => $variation]);
                }
                $num = $key + 1;
                if (isset($variation->Product->brand_id->name)) {
                    $brand = $variation->Product->brand_id->name;
                } else {
                    $brand = '-';
                }
                $html .= '<tr>
                            <th>
                                ' . $num . '
                            </th>
                            <th class="position-relative">
                                <a href="' . route('admin.products.show', ['product' => $variation->Product->id]) . '">
                                    ' . $variation->Product->name . '
                                </a>
                            </th>
                            <th class="position-relative">
                                    ' . $variation->Color->name . '
                            </th>
                             <th class="position-relative">
                                    ' . $variation->AttributeValue->name . '
                            </th>
                            <th>
                                ' . $variation->category_name($variation->product_id) . '
                            </th>
                            <th>
                               ' . $brand . '
                            </th>
                            <th>
                                <img class="img-thumbnail"
                                     src="' . imageExist(env('PRODUCT_IMAGES_UPLOAD_PATH'), $variation->Product->primary_image) . '">
                            </th>
                            <th>
                                <input onchange="update_multi_product_quantity(' . $variation->id . ',this)"
                                       class="form-control form-control-sm" value="' . $variation->quantity . '">
                            </th>
                            <th>
                                <input onkeyup="price_format(this)"
                                       onchange="update_multi_price(' . $variation->id . ',this)"
                                       class="form-control form-control-sm"
                                       value="' . number_format($variation->price) . '">
                            </th>
                            <th>
                        </tr>';
            }

            return \response()->json([1, $html]);
        } catch (\Exception $exception) {
            return \response()->json([0, $exception->getMessage()]);
        }
    }

}
