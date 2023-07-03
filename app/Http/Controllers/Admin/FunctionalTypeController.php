<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FunctionalTypes;
use Illuminate\Http\Request;

class FunctionalTypeController extends Controller
{
    public function index()
    {
        $types = FunctionalTypes::latest()->paginate(20);
        return view('admin.functionalType.index', compact('types'));
    }

    public function create()
    {
        return view('admin.functionalType.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:functional_types,title',
            'image' => 'nullable|mimes:jpg,jpeg,png,svg',
        ]);

        if ($request->has('image')) {
            $fileNameImage = generateFileName($request->image->getClientOriginalName());
            $request->image->move(public_path(env('FUNCTIONAL_TYPE_UPLOAD_PATH')), $fileNameImage);
        } else {
            $fileNameImage = null;
        }

        if ($request->has('banner_image')) {
            $bannerName = generateFileName($request->banner_image->getClientOriginalName());
            $request->banner_image->move(public_path(env('FUNCTIONAL_TYPE_UPLOAD_PATH')), $bannerName);
        } else {
            $bannerName = null;
        }
        FunctionalTypes::create([
            'title' => $request->title,
            'image' => $fileNameImage,
            'banner_image' => $bannerName,
            'priority' => $request->priority,
        ]);

        alert()->success('عملکرد مورد نظر ایجاد شد', 'باتشکر');
        return redirect()->route('admin.functionalType.index');
    }

    public function edit(FunctionalTypes $functionalType)
    {
        return view('admin.functionalType.edit',compact('functionalType'));
    }

    public function update(Request $request,FunctionalTypes $functionalType)
    {
        $request->validate([
            'title' => 'required|unique:functional_types,title,'.$functionalType->id,
            'image' => 'nullable|mimes:jpg,jpeg,png,svg',
        ]);

        if ($request->has('image')) {
            //unlink
            $path=public_path(env('FUNCTIONAL_TYPE_UPLOAD_PATH').$functionalType->image);
            unlink_image_helper_function($path);
            $fileNameImage = generateFileName($request->image->getClientOriginalName());
            $request->image->move(public_path(env('FUNCTIONAL_TYPE_UPLOAD_PATH')), $fileNameImage);
        } else {
            $fileNameImage = $functionalType->image;
        }

        if ($request->has('banner_image')) {
            //unlink
            $path=public_path(env('FUNCTIONAL_TYPE_UPLOAD_PATH').$functionalType->banner_image);
            unlink_image_helper_function($path);
            $bannerName = generateFileName($request->banner_image->getClientOriginalName());
            $request->banner_image->move(public_path(env('FUNCTIONAL_TYPE_UPLOAD_PATH')), $bannerName);
        } else {
            $bannerName = $functionalType->banner_image;
        }

        $functionalType->update([
            'title' => $request->title,
            'image' => $fileNameImage,
            'banner_image' => $bannerName,
            'priority' => $request->priority,
        ]);

        alert()->success('عملکرد مورد نظر ویرایش شد', 'باتشکر');
        return redirect()->route('admin.functionalType.index');
    }

    public function remove(Request $request){
        $type=FunctionalTypes::find($request->id);
        $products=$type->products;
        if (count($products)>0){
                $msg='کالاهای زیر مربوط به این عملکرد هستند.ابتدا باید کالاهای زیر را حذف کنید و یا ویژگی مربوطه را غیر فعال نمایید';
                $items=[];
                foreach ($products as $product){
                    $item['name']=$product->name;
                    $item['link']=route('admin.products.edit',['product'=>$product->id]);
                    array_push($items,$item);
                }
                return response()->json([0,$msg,$items]);
        }
        //unlink
        $path=public_path(env('FUNCTIONAL_TYPE_UPLOAD_PATH').$type->image);
        unlink_image_helper_function($path);
        $path=public_path(env('FUNCTIONAL_TYPE_UPLOAD_PATH').$type->banner_image);
        unlink_image_helper_function($path);
        $type->delete();
        return response([1,'مورد انتخابی با موفقیت حذف شد']);

    }

}
