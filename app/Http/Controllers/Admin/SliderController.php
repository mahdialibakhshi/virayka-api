<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::orderby('priority','asc')->paginate(20);
        return view('admin.sliders.index', compact('sliders'));
    }

    public function create()
    {
        return view('admin.sliders.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|mimes:jpg,jpeg,png,svg|max:10048',
         	'priority' => 'required|integer',
          ]);

        $fileNameImage = generateFileName($request->image->getClientOriginalName());
        $request->image->move(public_path(env('SLIDER_IMAGES_UPLOAD_PATH')), $fileNameImage);

        Slider::create([
            'image' => $fileNameImage,
            'thumbnail' => null,
            'title' => $request->title,
            'text' => $request->text,
            'button_link' => $request->button_link,
            'priority' => $request->priority,
            'is_active' => $request->is_active,
        ]);

        alert()->success('بنر مورد نظر ایجاد شد', 'باتشکر');
        return redirect()->route('admin.sliders.index');
    }

    public function show($id)
    {
        //
    }

    public function edit(Slider $slider)
    {
        return view('admin.sliders.edit', compact('slider'));
    }


    public function update(Request $request, Slider $slider)
    {
        $request->validate([
            'image' => 'nullable|mimes:jpg,jpeg,png,svg|max:10048',
          	'priority' => 'required|integer',
        ]);

        if ($request->has('image')) {
            $fileNameImage = generateFileName($request->image->getClientOriginalName());
            $request->image->move(public_path(env('SLIDER_IMAGES_UPLOAD_PATH')), $fileNameImage);
            $path=public_path(env('SLIDER_IMAGES_UPLOAD_PATH').$slider->image);
            if (file_exists($path) and !is_dir($path)){
                unlink($path);
            }
        }
        if ($request->has('thumbnail')) {
            $fileNameImage_thumbnail = generateFileName($request->thumbnail->getClientOriginalName());
            $request->thumbnail->move(public_path(env('slider_IMAGES_UPLOAD_PATH')), $fileNameImage_thumbnail);
            $path=public_path(env('SLIDER_IMAGES_UPLOAD_PATH').$slider->thumbnail);
            if (file_exists($path) and !is_dir($path)){
                unlink($path);
            }
        }
        $slider->update([
            'image' => $request->has('image') ? $fileNameImage : $slider->image,
            'thumbnail' => $request->has('thumbnail') ? $fileNameImage_thumbnail : $slider->thumbnail,
            'title' => $request->title,
            'text' => $request->text,
            'button_link' => $request->button_link,
            'priority' => $request->priority,
            'is_active' => $request->is_active,
        ]);

        alert()->success('بنر مورد نظر ویرایش شد', 'باتشکر');
        return redirect()->route('admin.sliders.index');
    }

    public function destroy(Slider $slider)
    {
        $path=public_path(env('SLIDER_IMAGES_UPLOAD_PATH').$slider->image);
        if (file_exists($path) and !is_dir($path)){
            unlink($path);
        }
        $slider->delete();

        alert()->success('بنر مورد نظر حذف شد', 'باتشکر');
        return redirect()->route('admin.sliders.index');
    }
}
