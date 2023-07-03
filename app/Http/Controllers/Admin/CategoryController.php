<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Attribute;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{

    public function index()
    {

        $categories = Category::where('parent_id','!=',13)->latest()->paginate(20);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {

        $parentCategories = Category::where('parent_id',0)->get();
        $attributes = Attribute::all();

        return view('admin.categories.create', compact('parentCategories', 'attributes'));
    }

    public function store(Request $request)
    {
        $category_already_exists = Category::where('name', $request->name)->where('parent_id', $request->parent_id)->exists();
        if ($category_already_exists) {
            return redirect()->back()->withErrors(['msg' => 'این دسته بندی از قبل ساخته شده است']);
        }
        $request->validate([
            'primary_image' => 'nullable|mimes:jpg,jpeg,png,svg',
            'banner_image' => 'nullable|mimes:jpg,jpeg,png,svg',
            'header_image' => 'nullable|mimes:jpg,jpeg,png,svg',
            'description' => 'nullable|string|max:60000',
        ]);
        try {
            DB::beginTransaction();
            if ($request->has('primary_image')) {
                $productImageController = new ProductImageController();
                $Image = $productImageController->categoryImageUpload($request->primary_image);
            } else {
                $Image = null;
            }
            if ($request->has('banner_image')) {
                $Banner_image = $productImageController->categoryImageUpload($request->banner_image);
            } else {
                $Banner_image = null;
            }
            if ($request->has('header_image')) {
                $header_image_image = $productImageController->categoryImageUpload($request->header_image);
            } else {
                $header_image_image = null;
            }
            Category::create([
                'name' => $request->name,
                'text' => $request->text,
                'icon' => $request->icon,
                'parent_id' => $request->parent_id,
                'image' => $Image,
                'banner_image' => $Banner_image,
                'header_image' => $header_image_image,
                'description' => $request->description,
                'priority' => $request->priority,
            ]);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            alert()->error('مشکل در ایجاد دسته بندی', $ex->getMessage())->persistent('حله');
            return redirect()->back();
        }

        alert()->success('دسته بندی مورد نظر ایجاد شد', 'باتشکر');
        return redirect()->route('admin.categories.index');
    }


    public function show(Category $category)
    {
        return view('admin.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        $parentCategories = Category::where('parent_id',0)->get();
        $attributes = Attribute::all();

        return view('admin.categories.edit', compact('category', 'parentCategories', 'attributes'));
    }

    public function update(Request $request, Category $category)
    {
        $categories_already_exists = Category::where('name', $request->name)->where('parent_id', $request->parent_id)->first();
        if (isset($categories_already_exists) and $category->id!=$categories_already_exists->id) {
            return redirect()->back()->withErrors(['msg' => 'این نام و والد  برای دسته بندی دیگری هم در حال استفاده است.لطفا نام دیگری انتخاب کنید']);
        }
        $request->validate([
            'parent_id' => 'required',
            'primary_image' => 'nullable|mimes:jpg,jpeg,png,svg',
            'banner_image' => 'nullable|mimes:jpg,jpeg,png,svg',
            'description' => 'nullable|string|max:60000',
        ]);

        try {
            $Image = $category->image;
            if ($request->has('primary_image')) {
                $path = public_path(env('CATEGORY_IMAGES_UPLOAD_PATH') . $Image);
                if (file_exists($path) and !is_dir($path)) {
                    unlink($path);
                }
                $productImageController = new ProductImageController();
                $Image = $productImageController->categoryImageUpload($request->primary_image);
            }
            if ($request->has('banner_image')) {
                $path2 = public_path(env('CATEGORY_IMAGES_UPLOAD_PATH') . $request->banner_image);
                if (file_exists($path2) and !is_dir($path2)) {
                    unlink($path2);
                }
                $productImageController = new ProductImageController();
                $Banner_image = $productImageController->categoryImageUpload($request->banner_image);
            } else {
                $Banner_image = $category->banner_image;
            }
            if ($request->has('header_image')) {
                $path2 = public_path(env('CATEGORY_IMAGES_UPLOAD_PATH') . $request->header_image);
                if (file_exists($path2) and !is_dir($path2)) {
                    unlink($path2);
                }
                $productImageController = new ProductImageController();
                $header_image = $productImageController->categoryImageUpload($request->header_image);
            } else {
                $header_image = $category->header_image;
            }
            DB::beginTransaction();
            $category->update([
                'name' => $request->name,
                'text' => $request->text,
                'icon' => $request->icon,
                'parent_id' => $request->parent_id,
                'is_active' => $request->is_active,
                'image' => $Image,
                'banner_image' => $Banner_image,
                'header_image' => $header_image,
                'description' => $request->description,
                'priority' => $request->priority,
            ]);

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            alert()->error('مشکل در ویرایش دسته بندی', $ex->getMessage())->persistent('حله');
            return redirect()->back();
        }

        alert()->success('دسته بندی مورد نظر ویرایش شد', 'باتشکر');
        return redirect()->route('admin.categories.index');
    }


    public function destroy($id)
    {
        //
    }

    public function getCategoryAttributes(Category $category)
    {
        $attributes = $category->attributes()->wherePivot('is_variation' ,0)->get();
        $variation = $category->attributes()->wherePivot('is_variation' ,1)->first();
        return ['attrubtes' => $attributes , 'variation' => $variation];
    }

    public function remove(Request $request){
        $category_id=$request->category_id;
        $category=Category::where('parent_id',$category_id)->get();
        $products=Product::where(function ($query) use ($category_id) {
            $query->where('category_id', 'LIKE', "%" . json_encode($category_id) . "%")
                ->orWhere('main_category_id', 'LIKE', "%" . json_encode($category_id) . "%")
                ->orWhere('category_id', $category_id)
                ->orWhere('main_category_id', $category_id);
        })->where('is_active', 1)->distinct()->get();
        if (sizeof($category)>0){
            $items=[];
            foreach ($category as $cat){
                $item['name']=$cat->name;
                $item['link']=route('admin.categories.show',['category'=>$cat->id]);
                array_push($items,$item);
            }
            $msg='دسته‌بندی‌های زیر مربوط به این دسته‌بندی هستند.ابتدا باید دسته‌بندی‌های زیر را حذف کنید.';

            return response()->json([0,$msg,$items]);
        }
        if (sizeof($products)){
            $msg='کالاهای زیر مربوط به این دسته‌بندی هستند.ابتدا باید کالاهای زیر را حذف کنید.';
            $items=[];
            foreach ($products as $product){
                $item['name']=$product->name;
                $item['link']=route('admin.products.show',['product'=>$product->id]);
                array_push($items,$item);
            }
            return response()->json([0,$msg,$items]);
        }
        $category=Category::find($category_id);
        $category->delete();
        $msg='دسته‌بندی با موفقیت حذف شد';
        return response()->json([1,$msg]);
    }

    public function showOnIndex(Request $request){
        $category_id=$request->category_id;
        $category=Category::where('id',$category_id)->first();
        $showOnIndex=$category->showOnIndex;
        if ($showOnIndex==0){
            $newShowOnIndex=1;
        }
        if ($showOnIndex==1){
            $newShowOnIndex=0;
        }
        $category->update([
            'showOnIndex'=>$newShowOnIndex,
        ]);
        return \response()->json([1,$newShowOnIndex]);
    }

    public function personalityNavbar(){
        $parent_ids=[];
        $parent_categories=Category::where('parent_id',0)->get();
        foreach ($parent_categories as $parent_category){
            array_push($parent_ids,$parent_category->id);
        }
        $categories=Category::whereIn('parent_id',$parent_ids)->get();
        return view('admin.categories.personalityNavbar',compact('categories'));
    }

    public function personalityNavbar_update(Request $request){
        $ids=$request->ids;
        $parent_ids=[];
        $parent_categories=Category::where('parent_id',0)->get();
        foreach ($parent_categories as $parent_category){
            array_push($parent_ids,$parent_category->id);
        }
        $categories=Category::whereIn('parent_id',$parent_ids)->get();
        foreach ($categories as $category){
            $category->update([
                'full_height'=>0
            ]);
        }
        if (!empty($ids)){
            foreach ($ids as $id){
                $category=Category::where('id',$id)->first();
                $category->update([
                    'full_height'=>1
                ]);
            }
        }
        alert()->success('تغییرات مورد نظر با موفقیت اعمال شد','باتشکر');
        return redirect()->back();
    }

    public function get(Request $request){
        try {
            DB::beginTransaction();
            $name=$request->name;
            $categories=Category::where('name','LIKE','%'.$name.'%')->get();
            $html='';
            foreach ($categories as $category){
                if ($category->showOnIndex==1){
                    $show_index_btn='btn-success text-white';
                    $show_index_text='فعال';
                }else{
                    $show_index_btn='text-dark';
                    $show_index_text='غیر فعال';
                }
                if ($category->getRawOriginal('is_active')==1){$text_success='text-success';}else{$text_success='';}
                if ($category->parent_id == 0){$parent='بدون والد';}else{$parent=$category->parent->name;}
                $html=$html.'<tr>
                            <th>
                               -
                            </th>
                            <th>
                                '.$category->name.'
                            </th>
                             <th>
                                '.$category->priority.'
                            </th>
                            <th>
                                <img class="img-thumbnail"
                                     src="'.imageExist(env('CATEGORY_IMAGES_UPLOAD_PATH'),$category->image).'">
                            </th>
                            <th>
                                <img class="img-thumbnail"
                                     src="'.imageExist(env('CATEGORY_IMAGES_UPLOAD_PATH'),$category->banner_image).'">
                            </th>
                            <th>
                                <img class="img-thumbnail"
                                     src="'.imageExist(env('CATEGORY_IMAGES_UPLOAD_PATH'),$category->header_image).'">
                            </th>
                            <th>
                                '.$parent.'
                            </th>

                            <th>
                                    <span
                                        class="'.$text_success.'">
                                        '.$category->is_active.'
                                    </span>
                            </th>
                            <th>
                                <a title="نمایش دسته‌بندی در صفحه اصلی" id="category_'.$category->id.'" onclick="showOnIndex('.$category->id.')"
                                   class="btn btn-sm '.$show_index_btn.'">
                                    '.$show_index_text.'
                                </a>
                            </th>
                            <th>
                                <a title="مشاهده" class="btn btn-sm btn-success"
                                   href="'.route('admin.categories.show', ['category' => $category->id]).'"><i
                                        class="fa fa-eye"></i></a>
                                <a title="ویرایش" class="btn btn-sm btn-info mr-3"
                                   href="'.route('admin.categories.edit', ['category' => $category->id]).'"><i
                                        class="fa fa-edit"></i></a>
                                <button title="حذف" type="button" onclick="RemoveModal('.$category->id.')"
                                        class="btn btn-sm btn-danger mr-3"
                                        href=""><i class="fa fa-trash"></i></button>
                            </th>
                        </tr>';
            }
            DB::commit();
            return response()->json([1,$html]);
        }catch (\Exception $exception){
            DB::rollBack();
            return response()->json([0,$exception->getMessage()]);
        }

    }


}
