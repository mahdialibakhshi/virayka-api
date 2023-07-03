<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gift;
use Illuminate\Http\Request;

class GiftController extends Controller
{
    public function index(){
        $gifts = Gift::latest()->paginate(20);
        return view('admin.gift.index', compact('gifts'));
    }
    public function create(){
        return view('admin.gift.create');
    }
    public function store(Request $request){
        $request->validate([
            'transaction'=>'required',
            'gift'=>'required',
        ]);
        $transaction=str_replace(',','',$request->transaction);
        $gift=str_replace(',','',$request->gift);
        Gift::create([
            'transaction'=>$transaction,
            'gift'=>$gift,
        ]);
        alert()->success('هدیه مورد نظر ایجاد شد', 'باتشکر');
        return redirect()->route('admin.gift.index');
    }

    public function edit(Gift $gift){
        return view('admin.gift.edit',compact('gift'));
    }

    public function update(Request $request,Gift $gift){
        $request->validate([
            'transaction'=>'required',
            'gift'=>'required',
        ]);
        $transaction=str_replace(',','',$request->transaction);
        $gift_amount=str_replace(',','',$request->gift);
        $gift->update([
            'transaction'=>$transaction,
            'gift'=>$gift_amount,
        ]);
        alert()->success('هدیه مورد نظر ویرایش شد', 'باتشکر');
        return redirect()->route('admin.gift.index');
    }

    public function remove(Request $request){
        $gift_id=$request->gift_id;
        $gift=Gift::where('id',$gift_id)->first();
        $gift->delete();
        $msg='هدیه با موفقیت حذف شد';
        return response()->json([1,$msg]);
    }
}
