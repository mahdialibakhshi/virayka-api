<?php

namespace App\Http\Controllers\Admin;

use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CouponController extends Controller
{

    public function index()
    {
        $coupons = Coupon::latest()->paginate(20);
        return view('admin.coupons.index', compact('coupons'));
    }


    public function create()
    {
        return view('admin.coupons.create');
    }


    public function store(Request $request)
    {

        $request->validate([
            'code' => 'required|unique:coupons,code',
            'type' => 'required',
            'amount' => 'required_if:type,=,amount',
            'percentage' => 'required_if:type,=,percentage',
            'max_percentage_amount' => 'required_if:type,=,percentage',
            'expired_at' => 'required',
            'times' => 'required',
        ]);

        Coupon::create([
            'code' => $request->code,
            'type' => $request->type,
            'amount' => $request->amount,
            'percentage' => $request->percentage,
            'max_percentage_amount' => $request->max_percentage_amount,
            'user_id' => $request->user_id,
            'times' => $request->times,
            'expired_at' => convertShamsiToGregorianDate($request->expired_at)
        ]);

        alert()->success('کوپن مورد نظر ایجاد شد', 'باتشکر');
        return redirect()->route('admin.coupons.index');
    }


    public function show(Coupon $coupon)
    {
        return view('admin.coupons.show', compact('coupon'));
    }


    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function remove(Request $request){
        $coupon_id=$request->coupon_id;
        $coupon=Coupon::findOrFail($coupon_id);
        $coupon->delete();
        $msg='کوپن مورد نظر با موفقیت حذف شد';
        return response()->json([1,$msg]);
    }
}
