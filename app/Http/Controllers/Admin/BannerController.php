<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{

    public function index()
    {
        $banners = Banner::paginate(20);
        return view('admin.banners.index', compact('banners'));
    }

//    public function create()
//    {
//        return view('admin.banners.create');
//    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|mimes:jpg,jpeg,png,svg|max:2048',
        ]);

        $fileNameImage = generateFileName($request->image->getClientOriginalName());
        $request->image->move(public_path(env('BANNER_IMAGES_UPLOAD_PATH')), $fileNameImage);

        Banner::create([
            'image' => $fileNameImage,
            'thumbnail' => null,
            'title' => $request->title,
            'text' => $request->text,
            'button_link' => $request->button_link,
            'button_text' => $request->button_text,
        ]);

        alert()->success('بنر مورد نظر ایجاد شد', 'باتشکر');
        return redirect()->route('admin.banners.index');
    }

    public function show($id)
    {
        //
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }


    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'image' => 'nullable|mimes:jpg,jpeg,png,svg|max:2048',
        ]);

        if ($request->has('image')) {
            $fileNameImage = generateFileName($request->image->getClientOriginalName());
            $request->image->move(public_path(env('BANNER_IMAGES_UPLOAD_PATH')), $fileNameImage);
            $path=public_path(env('BANNER_IMAGES_UPLOAD_PATH').$banner->image);
            if (file_exists($path) and !is_dir($path)){
                unlink($path);
            }
        }
        if ($request->has('thumbnail')) {
            $fileNameImage_thumbnail = generateFileName($request->thumbnail->getClientOriginalName());
            $request->thumbnail->move(public_path(env('BANNER_IMAGES_UPLOAD_PATH')), $fileNameImage_thumbnail);
            $path=public_path(env('BANNER_IMAGES_UPLOAD_PATH').$banner->thumbnail);
            if (file_exists($path) and !is_dir($path)){
                unlink($path);
            }
        }
        $banner->update([
            'image' => $request->has('image') ? $fileNameImage : $banner->image,
            'thumbnail' => $request->has('thumbnail') ? $fileNameImage_thumbnail : $banner->thumbnail,
            'title' => $request->title,
            'text' => $request->text,
            'button_link' => $request->button_link,
            'button_text' => $request->button_text,
            'position' => $request->position,
        ]);

        alert()->success('بنر مورد نظر ویرایش شد', 'باتشکر');
        return redirect()->route('admin.banners.index');
    }

    public function destroy(Banner $banner)
    {
        $path=public_path(env('BANNER_IMAGES_UPLOAD_PATH').$banner->image);
        if (file_exists($path) and !is_dir($path)){
            unlink($path);
        }
        $banner->delete();

        alert()->success('بنر مورد نظر حذف شد', 'باتشکر');
        return redirect()->route('admin.banners.index');
    }
}
