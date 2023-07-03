<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::latest()->paginate(20);
        return view('admin.tags.index' , compact('tags'));
    }

    public function create()
    {
        return view('admin.tags.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:tags,name'
        ]);

        Tag::create([
            'name' => $request->name
        ]);

        alert()->success('تگ مورد نظر ایجاد شد', 'باتشکر');
        return redirect()->route('admin.tags.index');
    }

    public function show(Tag $tag)
    {
        return view('admin.tags.show' , compact('tag'));
    }

    public function edit(Tag $tag)
    {
        return view('admin.tags.edit' , compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $request->validate([
            'name' => 'required|unique:tags,name,'.$tag->id
        ]);

        $tag->update([
            'name' => $request->name
        ]);

        alert()->success('تگ مورد نظر ویرایش شد', 'باتشکر');
        return redirect()->route('admin.tags.index');
    }

    public function destroy($id)
    {
        //
    }
}
