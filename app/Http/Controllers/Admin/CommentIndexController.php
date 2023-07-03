<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommentIndex;
use Illuminate\Http\Request;

class CommentIndexController extends Controller
{
    public function index(){
        $comments=CommentIndex::paginate(20);
        return view('admin.setting.comments.index', compact('comments'));
    }

    public function show(CommentIndex $comment){
        return view('admin.setting.comments.show', compact('comment'));
    }

    public function changeApprove(CommentIndex $comment)
    {
        if ($comment->getRawOriginal('published')) {
            $comment->update([
                'published' => 0
            ]);
        } else {
            $comment->update([
                'published' => 1
            ]);
        }

        alert()->success('وضعیت کامنت مورد نظر تغییر کرد', 'باتشکر');
        return redirect()->route('admin.Comment_index');
    }

    public function delete(CommentIndex $comment){
        $comment->delete();
        alert()->success('عملیات حذف باموفقیت انجام شد');
        return redirect()->back();
    }
}
