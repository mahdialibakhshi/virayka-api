<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlopeykConfig;
use App\Models\DeliveryConfig;
use App\Models\DeliveryMethod;
use App\Models\DeliveryMethodAmount;
use App\Models\PaymentMethods;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeliveryMethodController extends Controller
{
//==================================================== methods =======================================================

    public function index()
    {
        $deliveryMethods = DeliveryMethod::all();
        $delivery_config = DeliveryConfig::first();
        if (!empty($delivery_config->sent_times)) {
            $sent_times = explode(',', $delivery_config->sent_times);
        } else {
            $sent_times = [];
        }

        return view('admin.deliveryMethods.index', compact('deliveryMethods', 'delivery_config','sent_times'));
    }

    public function changeStatus(DeliveryMethod $method, $status)
    {
        $newStatus = $status == 1 ? 0 : 1;
        $method->update([
            'is_active' => $newStatus,
        ]);
        alert()->success('وضعیت  با موفقیت تغییر کرد', 'باتشکر');
        return redirect()->back();
    }

    public function edit($method)
    {
        if ($method == 'post') {
            $DeliveryMethodAmount = DeliveryMethodAmount::where('method_id', 1)->get();
            return view('admin.deliveryMethods.post.index', compact('DeliveryMethodAmount'));
        }
        if ($method == 'peyk') {
            $DeliveryMethodAmount = DeliveryMethodAmount::where('method_id', 2)->paginate(20);
            return view('admin.deliveryMethods.peyk.index', compact('DeliveryMethodAmount'));
        }
        if ($method == 'َAlopeyk') {
            $alopeykConfig=AlopeykConfig::first();
            return view('admin.deliveryMethods.AloPeyk.index',compact('alopeykConfig'));
        }
        if ($method == 'tipox') {
            return view('admin.deliveryMethods.tipox');
        }

    }

    public function create($method, Request $request)
    {
        if ($method == 'post') {
            $provinces = Province::all();
            return view('admin.deliveryMethods.post.create', compact('provinces'));
        }
        if ($method == 'peyk') {
            $provinces = Province::all();
            return view('admin.deliveryMethods.peyk.create', compact('provinces'));
        }
    }

    public function info(Request $request)
    {
        $method = DeliveryMethod::where('id', $request->methodId)->first();
        return response()->json($method);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'nullable|max:300',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['error', $error]);
        }
        $method = DeliveryMethod::where('id', $request->methodId)->first();
        $method->update([
            'description' => $request->description,
        ]);
        return response()->json(['ok']);

    }

//==================================================== post method =======================================================

    public function PostAdd($method, Request $request)
    {
        if ($method == 'post') {
            $method_id = DeliveryMethod::where('slug', $method)->first()->id;
            $request->validate([
                'province_id' => 'required|integer',
                'price' => 'required|string|max:10',
            ]);
            DeliveryMethodAmount::create([
                'method_id' => $method_id,
                'province_id' => $request->province_id,
                'price' => $request->price,
            ]);
            alert()->success('تعرفه پستی جدید ایجاد شد', 'باتشکر');
            return redirect()->route('admin.delivery_method.edit', ['method' => 'post']);

        }
    }

    public function PostEdit(DeliveryMethodAmount $id)
    {
        $provinces = Province::all();
        return view('admin.deliveryMethods.peyk.edit', compact('id', 'provinces'));
    }

    public function PostUpdate(Request $request)
    {
        $row = DeliveryMethodAmount::where('id', $request->rowId)->first();
        $price = floatval(preg_replace('/[^\d.]/', '', $request->price));
        $row->update([
            'price' => $price,
        ]);
        return response()->json('ok');
    }

//==================================================== peyk method =======================================================
    public function PeykAdd($method, Request $request)
    {
        $method_id = DeliveryMethod::where('slug', $method)->first()->id;
        $request->validate([
            'province_id' => 'required|integer',
            'price' => 'required|string|max:10',
        ]);
        DeliveryMethodAmount::create([
            'method_id' => $method_id,
            'province_id' => $request->province_id,
            'price' => $request->price,
        ]);
        alert()->success('تعرفه پستی جدید ایجاد شد', 'باتشکر');
        return redirect()->route('admin.delivery_method.edit', ['method' => 'peyk']);
    }

    public function PeykEdit(DeliveryMethodAmount $id)
    {
        $provinces = Province::all();
        return view('admin.deliveryMethods.peyk.edit', compact('id', 'provinces'));
    }

    public function PeykUpdate(DeliveryMethodAmount $id, Request $request)
    {
        $request->validate([
            'province_id' => 'required|integer',
            'price' => 'required|string|max:10',
        ]);
        $id->update([
            'province_id' => $request->province_id,
            'price' => $request->price,
        ]);
        alert()->success('تعرفه پستی جدید ویرایش شد', 'باتشکر');
        return redirect()->route('admin.delivery_method.edit', ['method' => 'peyk']);
    }

    public function delete(Request $request)
    {
        $Item = $request->Item;
        $deliveryAmount = DeliveryMethodAmount::where('id', $Item)->first();
        $deliveryAmount->delete();
        alert()->success('گزینه ی مورد نظر با موفقیت حذف شد', 'باتشکر');
        return redirect()->back();
    }
//==================================================== Alopeyk method =======================================================
    public function AlopeykUpdate(Request $request)
    {
        $request->validate([
            'alopeyk_token'=>'required|string',
            'neshan_token'=>'required|string',
            'anbar_address'=>'required|string',
            'cellphone'=>'required|string',
            'name'=>'required|string',
            'alopeyk_location'=>'required|string',

        ]);

        $alopeykConfig=AlopeykConfig::first();
        $alopeykConfig->update([
            'alopeyk_token'=>$request->alopeyk_token,
            'neshan_token'=>$request->neshan_token,
            'anbar_address'=>$request->anbar_address,
            'cellphone'=>$request->cellphone,
            'alopeyk_location'=>$request->alopeyk_location,
            'name'=>$request->name,
        ]);
        alert()->success('تغییرات مورد نظر با موفقیت انجام شد', 'باتشکر');
        return redirect()->back();

    }
//==================================================== method config =======================================================
    public function config(Request $request)
    {
        $request->validate([
            'sent_times'=>'required',
            'shipping'=>'required',
            'holidays'=>'required',
        ]);
        $holidays = join(',', $request->holidays);
        $methods_id = join(',', $request->shipping);
        $sent_times = join(',', $request->sent_times);
        $free_delivery_for = join(',', $request->free_delivery_for);
        $order_send_after = $request->order_send_after;
        $days_count = $request->days_count;
        $free_delivery = $request->free_delivery;
        $delivery_config = DeliveryConfig::first();
        $delivery_config->update([
            'holidays' => $holidays,
            'methods_id' => $methods_id,
            'sent_times' => $sent_times,
            'order_send_after' => $order_send_after,
            'days_count' => $days_count,
            'free_delivery_for' => $free_delivery_for,
            'free_delivery' => $free_delivery,
        ]);
        alert()->success('تغییرات مورد نظر با موفقیت انجام شد', 'باتشکر');
        return redirect()->back();
    }
}
