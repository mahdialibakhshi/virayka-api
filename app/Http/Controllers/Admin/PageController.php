<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::paginate();
        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:pages,title',
            'image' => 'nullable|mimes:jpg,jpeg,png,svg',
        ]);
        if ($request->has('image')) {
            $fileNameImage = generateFileName($request->image->getClientOriginalName());
            $request->image->move(public_path(env('BANNER_PAGES_UPLOAD_PATH')), $fileNameImage);
        } else {
            $fileNameImage = null;
        }
        Page::create([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'banner_is_active' => $request->banner_is_active,
            'image' => $fileNameImage,
        ]);
        alert()->success('صفحه جدید با موفقیت ایجاد شد', 'باتشکر');
        return redirect()->route('admin.pages.index');
    }

    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $request->validate([
            'title' => 'required|unique:pages,title,' . $page->id,
            'image' => 'nullable|mimes:jpg,jpeg,png,svg',
        ]);
        if ($request->has('image')) {
            $path=public_path(env('BANNER_PAGES_UPLOAD_PATH')).$page->image;
            unlink_image_helper_function($path);
            $fileNameImage = generateFileName($request->image->getClientOriginalName());
            $request->image->move(public_path(env('BANNER_PAGES_UPLOAD_PATH')), $fileNameImage);
        } else {
            $fileNameImage = $page->image;
        }
        $page->update([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'banner_is_active' => $request->banner_is_active,
            'image' => $fileNameImage,
        ]);
        alert()->success('صفحه با موفقیت ویرایش شد', 'باتشکر');
        return redirect()->route('admin.pages.index');
    }

    public function destroy(Request $request)
    {
        Page::where('id', $request->id)->delete();
        alert()->success('صفحه با موفقیت حذف شد', 'باتشکر');
        return redirect()->back();
    }

}
