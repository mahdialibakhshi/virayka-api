<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnimationBanner;
use App\Models\PaymentMethods;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{



    public function edit(Setting $setting,$amazing_sale=null)
    {
        return view('admin.setting.edit',compact('setting','amazing_sale'));
    }


    public function update(Request $request,Setting $setting)
    {
        if ($request->has('name')){
            $request->validate([
                'name' => 'nullable|string|max:50',
                'email' => 'nullable|email',
                'address' => 'nullable|string|max:500',
                'shomare_sabt' => 'nullable|string|max:50',
                'tel' => 'required',
                'cellphone' => 'required|iran_mobile',
                'whatsapp' => 'required',
                'workTime' => 'nullable',
                'EconomicCode' => 'nullable',
                'postalCode' => 'nullable|iran_postal_code',
                'productCode' => 'nullable',
                'delivery_order_numbers' => 'required|string',
                'message' => 'nullable|string',
                'instagram' => 'nullable|string',
                'telegram' => 'nullable|string',
                'image' => 'nullable|image',
                'special_page_banner' => 'nullable|image',
                'newest_page_banner' => 'nullable|image',
            ]);

            $Image=$setting->image;
            if ($request->has('image')){
                $productImageController = new ProductImageController();
                $Image = $productImageController->logoUpload($request->image);
                $path=public_path(env('LOGO_UPLOAD_PATH').$setting->image);
                if (file_exists($path) & !is_dir($path)){
                    unlink($path);
                }
            }
            $special_page_banner=$setting->special_page_banner;
            if ($request->has('special_page_banner')){
                $special_page_banner = generateFileName($request->special_page_banner->getClientOriginalName());
                $request->special_page_banner->move(public_path(env('BANNER_PAGES_UPLOAD_PATH')), $special_page_banner);
                $path=public_path(env('BANNER_PAGES_UPLOAD_PATH').$setting->special_page_banner);
                if (file_exists($path) & !is_dir($path)){
                    unlink($path);
                }
            }
            $newest_page_banner=$setting->newest_page_banner;
            if ($request->has('newest_page_banner')){
                $newest_page_banner = generateFileName($request->newest_page_banner->getClientOriginalName());
                $request->newest_page_banner->move(public_path(env('BANNER_PAGES_UPLOAD_PATH')), $newest_page_banner);
                $path=public_path(env('BANNER_PAGES_UPLOAD_PATH').$setting->newest_page_banner);
                if (file_exists($path) & !is_dir($path)){
                    unlink($path);
                }
            }
            $favicon=$setting->favicon;
            if ($request->has('favicon')){
                $favicon = generateFileName($request->favicon->getClientOriginalName());
                $request->favicon->move(public_path(env('LOGO_UPLOAD_PATH')), $favicon);
                $path=public_path(env('LOGO_UPLOAD_PATH').$setting->favicon);
                if (file_exists($path) & !is_dir($path)){
                    unlink($path);
                }
            }
            $top_page_banner=$setting->top_page_banner;
            if ($request->has('top_page_banner')){
                $top_page_banner = generateFileName($request->top_page_banner->getClientOriginalName());
                $request->top_page_banner->move(public_path(env('BANNER_PAGES_UPLOAD_PATH')), $top_page_banner);
                $path=public_path(env('BANNER_PAGES_UPLOAD_PATH').$setting->top_page_banner);
                if (file_exists($path) & !is_dir($path)){
                    unlink($path);
                }
            }
            $setting->update([
                'name'=>$request->name,
                'email'=>$request->email,
                'address'=>$request->address,
                'shomare_sabt'=>$request->shomare_sabt,
                'postalCode'=>$request->postalCode,
                'productCode'=>$request->productCode,
                'tel'=>$request->tel,
                'tel2'=>$request->tel2,
                'tel3'=>$request->tel3,
                'tel4'=>$request->tel4,
                'image'=>$Image,
                'newest_page_banner'=>$newest_page_banner,
                'special_page_banner'=>$special_page_banner,
                'top_page_banner'=>$top_page_banner,
                'favicon'=>$favicon,
                'cellphone'=>$request->cellphone,
                'whatsapp'=>$request->whatsapp,
                'workTime'=>$request->workTime,
                'instagram'=>$request->instagram,
                'telegram'=>$request->telegram,
                'EconomicCode'=>$request->EconomicCode,
                'message'=>$request->message,
                'product_message'=>$request->product_message,
                'top_page_banner_active'=>$request->top_page_banner_active,
                'delivery_order_numbers'=>$request->delivery_order_numbers,
                'about_us'=>$request->about_us,
            ]);
        }else{
            $expire_amazing_product=null;
            $expire_amazing_product=convertShamsiToGregorianDate($request->date_on_sale_from);
            $setting->update([
               'expire_amazing_product' =>$expire_amazing_product
            ]);
        }


        alert()->success('اطلاعات با موفقیت ویرایش شد', 'باتشکر');
        return redirect()->back();
    }

    public function priority_show_active(Request $request){
        $setting=Setting::first();
        $sort=$request->sort;
        $setting->update([
            'product_sort'=>$sort
        ]);
        return response()->json([1,'ok']);
    }

    public function animation_banner_edit(){
        $animation_banner=AnimationBanner::first();
        return view('admin.setting.animation_banner',compact('animation_banner'));
    }

    public function animation_banner_update(Request $request){
        $animation_banner=AnimationBanner::first();
        $animation_banner->update([
            'black_text'=>$request->black_text,
            'red_text'=>$request->red_text,
            'animation_text'=>$request->animation_text,
            'btn_text'=>$request->btn_text,
            'btn_link'=>$request->btn_link,
            'is_active'=>$request->is_active,
        ]);
        alert()->success('اطلاعات با موفقیت ویرایش شد', 'باتشکر');
        return redirect()->back();
    }



}
