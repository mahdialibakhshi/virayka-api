<?php

namespace App\Http\Controllers\Admin;

use App\Models\Attribute;
use App\Models\AttributeGroup;
use App\Models\AttributeValues;
use App\Models\Cart;
use App\Models\ProductAttribute;
use App\Models\ProductAttrVariation;
use App\Models\ProductColorVariation;
use App\Models\ProductOption;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AttributeController extends Controller
{
    public function index()
    {
        $attributes = Attribute::orderby('priority','asc')->paginate(20);
        return view('admin.attributes.index', compact('attributes'));
    }

    public function create()
    {
        $attr_groups = AttributeGroup::orderBy('name')->get();
        return view('admin.attributes.create', compact('attr_groups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:attributes,name',
            'image' => 'nullable|mimes:jpg,jpeg,png,svg',
        ]);
        if ($request->has('limit_select')) {
            $limit_select = 1;
        } else {
            $limit_select = 0;
        }
        if ($request->has('is_dependence')) {
            $is_dependence = 1;
        } else {
            $is_dependence = 0;
        }
        if ($request->has('is_filter')) {
            $is_filter = 1;
        } else {
            $is_filter = 0;
        }
        if ($request->has('image')) {
            $fileNameImage = generateFileName($request->image->getClientOriginalName());
            $request->image->move(public_path(env('ATTR_UPLOAD_PATH')), $fileNameImage);
        } else {
            $fileNameImage = null;
        }
        Attribute::create([
            'name' => $request->name,
            'limit_select' => $limit_select,
            'is_dependence' => $is_dependence,
            'is_filter' => $is_filter,
            'image' => $fileNameImage,
            'group_id' => $request->group_id,
        ]);

        alert()->success('ویژگی مورد نظر ایجاد شد', 'باتشکر');
        return redirect()->route('admin.attributes.index');
    }

    public function show(Attribute $attribute)
    {
        return view('admin.attributes.show', compact('attribute'));
    }

    public function edit(Attribute $attribute)
    {
        $attr_groups = AttributeGroup::orderBy('name')->get();
        return view('admin.attributes.edit', compact('attribute', 'attr_groups'));
    }

    public function update(Request $request, Attribute $attribute)
    {
        $request->validate([
            'name' => 'required|unique:attributes,name,' . $attribute->id,
            'image' => 'nullable|mimes:jpg,jpeg,png,svg',
        ]);
        if ($request->has('limit_select')) {
            $limit_select = 1;
        } else {
            $limit_select = 0;
        }
        if ($request->has('is_dependence')) {
            $is_dependence = 1;
        } else {
            $is_dependence = 0;
        }
        if ($request->has('is_filter')) {
            $is_filter = 1;
        } else {
            $is_filter = 0;
        }
        if ($request->has('image')) {
            $path = public_path(env('ATTR_UPLOAD_PATH') . $attribute->image);
            unlink_image_helper_function($path);
            $fileNameImage = generateFileName($request->image->getClientOriginalName());
            $request->image->move(public_path(env('ATTR_UPLOAD_PATH')), $fileNameImage);
        } else {
            $fileNameImage = $attribute->image;
        }
        $attribute->update([
            'name' => $request->name,
            'limit_select' => $limit_select,
            'is_dependence' => $is_dependence,
            'is_filter' => $is_filter,
            'image' => $fileNameImage,
            'group_id' => $request->group_id,
            'priority' => $request->priority,
        ]);

        alert()->success('ویژگی مورد نظر ویرایش شد', 'باتشکر');
        return redirect()->route('admin.attributes.index');
    }

    public function attributes_values_index(Attribute $attribute)
    {

        $values = AttributeValues::where('attribute_id', $attribute->id)->orderby('name', 'desc')->get();
        return view('admin.attributes.values.index', compact('values', 'attribute'));
    }

    public function attributes_values_add_or_update(Request $request)
    {
        $attribute_value = AttributeValues::where('id', $request->attribute_value_id)->first();
        if (!$attribute_value == null) {
            $attribute_values_count = AttributeValues::where('name', $request->attribute_value)->where('id', $request->attribute_value_id)->count();
            if ($attribute_values_count > 1) {
                return response()->json(['error', 'این مقدار برای این مشخصه فنی از قبل ایجاد شده است.']);
            }
            $request->validate([
                'attribute_id' => 'required',
                'image' => 'nullable|mimes:jpg,jpeg,png,svg|max:1024',
            ]);
            if ($request->has('image')) {
                $previous_image = $attribute_value->image;
                $path = public_path(env('ATTR_UPLOAD_PATH') . $previous_image);
                if (file_exists($path) and !is_dir($path)) {
                    unlink($path);
                }
                $productImageController = new ProductImageController();
                $image = $productImageController->AttributeUpload($request->image);
            } else {
                $image = $attribute_value->image;
            }
            $attribute_value->update([
                'name' => $request->attribute_value,
                'image' => $image,
            ]);
            $msg = 'گزینه مورد نظر باموفقیت ویرایش شد';

        } else {
            $attribute_value_exist = AttributeValues::where('attribute_id', $request->attribute_id)->where('name', $request->attribute_value)->exists();
            if ($attribute_value_exist) {
                return response()->json(['error', 'این مقدار برای این مشخصه فنی از قبل ایجاد شده است.']);
            }
            $request->validate([
                'attribute_id' => 'required',
                'image' => 'nullable|mimes:jpg,jpeg,png,svg|max:1024',
            ]);
            if ($request->has('image')) {
                $productImageController = new ProductImageController();
                $image = $productImageController->AttributeUpload($request->image);
            } else {
                $image = null;
            }
            AttributeValues::create([
                'attribute_id' => $request->attribute_id,
                'name' => $request->attribute_value,
                'image' => $image,
            ]);
            $msg = 'گزینه مورد نظر باموفقیت اضافه شد';
        }
        return response()->json(['success', $msg]);


    }

    public function attributes_value_remove(Request $request)
    {

        $attr_value_id = $request->attr_value_id;
        $attr_value = AttributeValues::where('id', $attr_value_id)->first();

        $attr_id = $attr_value->id;
        $product_variation_colors = ProductColorVariation::where('attr_value', $attr_id)->get();
        if (sizeof($product_variation_colors) > 0) {
            try {
                $msg = 'این مقدار در کالاهای زیر درحال استفاده است.ابتدا باید این مقدار را از تمامی کالاهای مربوطه حذف نمایید.';
                $items = [];
                foreach ($product_variation_colors as $product_variation_color) {
                    $item['name'] = $product_variation_color->Product->name;
                    $item['link'] = route('admin.product.variations.attribute.edit', ['product' => $product_variation_color->Product->id]);
                    array_push($items, $item);
                }
                return response()->json([0, $msg, $items]);
            }catch (\Exception $exception){
                return response()->json([0, $exception->getMessage()]);
            }

        }
        $productAttributes = ProductAttribute::where('value', $attr_id)->get();
        if (sizeof($productAttributes) > 0) {
            try {
                $msg = 'این مقدار در کالاهای زیر درحال استفاده است.ابتدا باید این مقدار را از تمامی کالاهای مربوطه حذف نمایید.';
                $items = [];
                foreach ($productAttributes as $productAttribute) {
                    $item['name'] = $productAttribute->product->name;
                    $item['link'] = route('admin.product.attributes.index', ['product' => $productAttribute->product->id]);
                    array_push($items, $item);
                }
                return response()->json([0, $msg, $items]);
            }catch (\Exception $exception){
                return response()->json([0, $exception->getMessage()]);
            }

        }
        //check attr in use product options
        $product_options = ProductOption::where('value', $attr_value_id)->get();
        if (sizeof($product_options) > 0) {
            try {
                $msg = 'کالاهای زیر مربوط به این مشخصه‌ی فنی هستند.ابتدا باید مشخصه‌ی فنی را از تمامی کالاهای مربوطه حذف نمایید.';
                $items = [];
                foreach ($product_options as $product_option) {
                    $item['name'] = $product_option->product->name;
                    $item['link'] = route('admin.product.attributes.index', ['product' => $product_option->product->id]);
                    array_push($items, $item);
                }
                return response()->json([0, $msg, $items]);
            }catch (\Exception $exception){
                return response()->json([0, $exception->getMessage()]);
            }
        }
        //check attr in use product variations
        $productAttributeVariations = ProductAttrVariation::where('attr_value', $attr_value_id)->get();
        if (sizeof($productAttributeVariations) > 0) {
            try {
                $msg = 'کالاهای زیر مربوط به این مشخصه‌ی فنی هستند.ابتدا باید مشخصه‌ی فنی را از تمامی کالاهای مربوطه حذف نمایید.';
                $items = [];
                foreach ($productAttributeVariations as $productAttributeVariation) {
                    $item['name'] = $productAttributeVariation->Product->name;
                    $item['link'] = route('admin.product.variations.attribute.edit', ['product' => $productAttributeVariation->Product->id]);
                    array_push($items, $item);
                }
                return response()->json([0, $msg, $items]);
            }catch (\Exception $exception){
                return response()->json([0, $exception->getMessage()]);
            }

        }
        //check attr in use product variations
        $productAttributeVariations = ProductAttrVariation::where('color_attr_value', $attr_value_id)->get();
        if (sizeof($productAttributeVariations) > 0) {
            try {
                $msg = 'کالاهای زیر مربوط به این مشخصه‌ی فنی هستند.ابتدا باید مشخصه‌ی فنی را از تمامی کالاهای مربوطه حذف نمایید.';
                $items = [];
                foreach ($productAttributeVariations as $productAttributeVariation) {
                    $item['name'] = $productAttributeVariation->Product->name;
                    $item['link'] = route('admin.product.variations.attribute.edit', ['product' => $productAttributeVariation->Product->id]);
                    array_push($items, $item);
                }
                return response()->json([0, $msg, $items]);
            }catch (\Exception $exception){
                return response()->json([0, $exception->getMessage()]);
            }
        }

        $path = public_path(env('ATTR_UPLOAD_PATH') . $attr_value->image);
        if (file_exists($path) and !is_dir($path)) {
            unlink($path);
        }
        //check if in use in cart
       $remove_from_cart=$this->remove_from_cart($attr_value_id);
        if ($remove_from_cart[0]===0){
            return response()->json([0, $remove_from_cart[1]]);
        }


        $attr_value->delete();
        $msg = 'مقدار مورد نظر با موفقیت حذف شد';
        return response()->json([1, $msg]);
    }

    protected function remove_from_cart($attr_value)
    {
        try {
            $carts = Cart::all();
            foreach ($carts as $cart) {
                $product_options = $cart->option_ids;
                $new_product_options=[];
                if (!$product_options == null) {
                    $product_options=json_decode($product_options);
                    foreach ($product_options as $product_option) {
                        $option_value_id=ProductOption::where('id',$product_option)->first()->VariationValue->id;
                        if ($option_value_id!=$attr_value){
                            $new_product_options[]=$product_option;
                        }
                    }
                }
                if (count($new_product_options)>0){
                    $new_product_options=json_decode($new_product_options);
                }else{
                    $new_product_options=null;
                }
                $cart->update([
                    'option_ids'=>$new_product_options
                ]);
            }
            return [1];
        }catch (\Exception $exception){
            return [0,$exception->getMessage()];
        }

    }

    public function remove(Request $request)
    {
        $attr_id = $request->attr_id;
        //چک کردن این که این مقدار در بخش مشخصات فنی در حال استفاده است یا خیر
        $productAttributes = ProductAttribute::where('attribute_id', $attr_id)->get();
        if (sizeof($productAttributes) > 0) {
            $msg = 'کالاهای زیر مربوط به این مشخصه‌ی فنی هستند.ابتدا باید مشخصه‌ی فنی را از تمامی کالاهای مربوطه حذف نمایید.';
            $items = [];
            foreach ($productAttributes as $productAttribute) {
                $item['name'] = $productAttribute->product->name;
                $item['link'] = route('admin.product.attributes.index', ['product' => $productAttribute->product->id]);
                array_push($items, $item);
            }
            return response()->json([0, $msg, $items]);
        }
        //چک کردن این که این مقدار در بخش اقلام افزوده در حال استفاده است یا خیر
        //check attr in use product options
        $product_options = ProductOption::where('attribute_id', $attr_id)->get();
        if (sizeof($product_options) > 0) {
            $msg = 'این مشخصه فنی در بخش اقلام افزوده ی مربوط به کالاهای زیر در حال استفاده می باشد';
            $items = [];
            foreach ($product_options as $product_option) {
                $item['name'] = $product_option->product->name;
                $item['link'] = route('admin.product.attributes.index', ['product' => $product_option->product->id]);
                array_push($items, $item);
            }
            return response()->json([0, $msg, $items]);
        }
        //چک کردن این که این کالا در بخش محصولات چندتایی در حال استفاده است یا خیر
        $product_attr_variation = ProductAttrVariation::where('attr_id', $attr_id)->orWhere('color_attr_id', $attr_id)->get();
        if (sizeof($product_attr_variation) > 0) {
            $msg = 'این مشخصه فنی در بخش کالاهای چندتایی به همراه رنگ بندی در حال استفاده می باشد';
            $items = [];
            foreach ($product_attr_variation as $product_item) {
                $item['name'] = $product_item->Product->name;
                $item['link'] = route('admin.product.attributes.index', ['product' => $product_item->Product->id]);
                array_push($items, $item);
            }
            return response()->json([0, $msg, $items]);
        }
        //product_color_variation
        //پاک کردن عکسهای مربوط به وریشن کالا
        $product_color_variation = ProductColorVariation::where('attr_id', $attr_id)->get();
        if (sizeof($product_color_variation) > 0) {
            foreach ($product_color_variation as $item) {
                $path = public_path(env('PRODUCT_VARIATION_COLOR_UPLOAD_PATH') . $item->image);
                if (file_exists($path) and !is_dir($path)) {
                    unlink($path);
                }
                $item->delete();
            }
        }
        //حذف کردن تمامی مقادیر مربوط به اتریبیوت
        $attributeValues = AttributeValues::where('attribute_id', $attr_id)->get();
        foreach ($attributeValues as $attributeValue) {
            $path = public_path(env('ATTR_UPLOAD_PATH') . $attributeValue->image);
            if (file_exists($path) and !is_dir($path)) {
                unlink($path);
            }
            $attributeValue->delete();
        }
        //حذف کردن اتریبیوت
        $attribute = Attribute::find($attr_id);
        $attribute->delete();
        $msg = 'مشخصه فنی مورد نظر با موفقیت حذف شد';
        return response()->json([1, $msg]);
    }

    public function priority_show_update(Request $request)
    {
        $priority_show = $request->priority_show;
        $value = AttributeValues::where('id', $request->value_id)->first();
        $value->update([
            'priority_show' => $priority_show,
        ]);
        return \response()->json([1]);
    }

    public function attribute_group_index()
    {
        $attr_groups = AttributeGroup::orderBy('priority','asc')->paginate(20);
        return view('admin.attributes.groups.index', compact('attr_groups'));
    }

    public function attribute_group_create()
    {
        return view('admin.attributes.groups.create');
    }

    public function attribute_group_store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:attribute_group,name',
        ]);
        AttributeGroup::create([
            'name' => $request->name,
        ]);

        alert()->success('ویژگی مورد نظر ایجاد شد', 'باتشکر');
        return redirect()->route('admin.attributes.groups.index');
    }

    public function attribute_group_edit(AttributeGroup $group)
    {
        return view('admin.attributes.groups.edit', compact('group'));
    }

    public function attribute_group_update(Request $request, AttributeGroup $group)
    {
        $request->validate([
            'name' => 'required|unique:attribute_group,name,' . $group->id,
            'priority' => 'required',
        ]);
        $group->update([
            'name' => $request->name,
            'priority' => $request->priority,
        ]);

        alert()->success('ویژگی مورد نظر ویرایش شد', 'باتشکر');
        return redirect()->route('admin.attributes.groups.index');
    }

    public function attribute_group_remove(Request $request)
    {
        $attr_group_id = $request->attr_group_id;
        //check attr in use
        $attributes = Attribute::where('group_id', $attr_group_id)->get();
        if (sizeof($attributes) > 0) {
            $msg = 'کالاهای زیر مربوط به این گروه بندی هستند.ابتدا باید گروه بندی را از تمامی مشخصه های فنی زیر حذف نمایید.';
            $items = [];
            foreach ($attributes as $attribute) {
                $item['name'] = $attribute->name;
                $item['link'] = route('admin.attributes.edit', ['attribute' => $attribute->id]);
                array_push($items, $item);
            }
            return response()->json([0, $msg, $items]);
        }
        $attribute_group = AttributeGroup::find($attr_group_id);
        $attribute_group->delete();
        $msg = 'گروه بندی مورد نظر با موفقیت حذف شد';
        return response()->json([1, $msg]);
    }
}
