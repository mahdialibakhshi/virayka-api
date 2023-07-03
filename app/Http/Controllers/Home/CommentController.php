<?php

namespace App\Http\Controllers\Home;

use App\Models\Comment;
use App\Models\CommentIndex;
use App\Models\Product;
use App\Models\ProductRate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{

    public function create(){
        $user=auth()->user();
        return view('home.users_profile.comment_create',compact('user'));
    }

    public function store_index(Request $request){
        if (Auth::check()){
            $request->validate([
                'title'=>'required|string|max:20',
                'description'=>'required|string|max:400',
            ]);
            CommentIndex::create([
                'user_id'=>\auth()->id(),
                'title'=>$request->title,
                'description'=>$request->description,
            ]);
            alert()->success('نظر شما با موفقیت ثبت شد');
            return redirect()->route('home.comments.users_profile.index');
        }else{
            return redirect()->route('home.index');
        }
    }

    public function edit(CommentIndex $comment){
        $user=auth()->user();
        return view('home.users_profile.comment_edit',compact('comment','user'));
    }

    public function update_index(Request $request,CommentIndex $comment){
        $request->validate([
            'title'=>'required|string|max:20',
            'description'=>'required|string|max:400',
        ]);
        $comment->update([
            'title'=>$request->title,
            'published'=>0,
            'description'=>$request->description,
        ]);
        alert()->success('نظر شما با موفقیت ویرایش شد');
        return redirect()->route('home.comments.users_profile.index');
    }

    public function store(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'text' => 'required|min:5|max:7000',
            'rate' => 'required|digits_between:0,5'
        ]);

        if ($validator->fails()) {
            return redirect()->to(url()->previous() . '#comments')->withErrors($validator);
        }

        if (auth()->check()) {
            try {
                DB::beginTransaction();
                Comment::create([
                    'user_id' => auth()->id(),
                    'product_id' => $product->id,
                    'text' => $request->text
                ]);
                if ($product->rates()->where('user_id', auth()->id())->exists()) {
                    $productRate = $product->rates()->where('user_id', auth()->id())->first();
                    $productRate->update([
                        'rate' => $request->rate
                    ]);
                } else {
                    ProductRate::create([
                        'user_id' => auth()->id(),
                        'product_id' => $product->id,
                        'rate' => $request->rate
                    ]);
                }

                DB::commit();
            } catch (\Exception $ex) {
                DB::rollBack();
                alert()->error('مشکل در ویرایش محصول', $ex->getMessage())->persistent('ok');
                return redirect()->back();
            }

            alert()->success('نظر ارزشمند شما با موفقیت برای این محصول ثبت شد', 'باتشکر');
            return redirect()->back();
        } else {
            alert()->warning('برای ثبت نظر نیاز هست در ابتدا وارد سایت شوید', 'دقت کنید')->autoclos('3000');
            return redirect()->back();
        }
    }

    public function usersProfileIndex()
    {
        $user=User::find(auth()->id());
        $comments=Comment::where('user_id',auth()->id())->get();
        return view('home.users_profile.comments', compact('user','comments'));
    }

    public function delete(CommentIndex $comment){
        $comment->delete();
        alert()->success('نظر شما با موفقیت حذف شد');
        return redirect()->route('home.comments.users_profile.index');
    }
}
