<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $offers = Offer::latest()->paginate(20);
        return view('admin.offers.index', compact('offers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.offers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'bg_image' => 'nullable|mimes:jpg,jpeg,png,svg|max:1024',
            'product_image' => 'nullable|mimes:jpg,jpeg,png,svg|max:1024',
            'button_link' => 'required',
            'title' => 'nullable|string|max:100',
            'type'=>'required'
        ]);

        if ($request->has('product_image')){
        $fileNameImage = generateFileName($request->product_image->getClientOriginalName());
         $env=env('BANNER_IMAGES_UPLOAD_PATH');
        parent::createThumbnail(400,$request->bg_image,$env,$fileNameImage);
        }else{
            $fileNameImage=null;
        }
        if ($request->has('bg_image')){
            $env=env('BANNER_IMAGES_UPLOAD_PATH');
            $fileNameBGImage = generateFileName($request->bg_image->getClientOriginalName());
            $request->bg_image->move(public_path(env('BANNER_IMAGES_UPLOAD_PATH')), $fileNameBGImage);
        }else{
            $fileNameBGImage=null;
        }

        Offer::create([
            'bg_image' => $fileNameBGImage,
            'product_image' => $fileNameImage,
            'button_link' => $request->button_link,
            'bg_color' => $request->bg_color,
            'title' => $request->title,
            'type'=>$request->type,
        ]);

        alert()->success('موردی پیشنهادی جدید ایجاد شد', 'باتشکر');
        return redirect()->route('admin.offers.index');
    }


    public function edit(Offer $offer)
    {
        return view('admin.offers.edit', compact('offer'));
    }

    public function update(Request $request, Offer $offer)
    {
        $request->validate([
            'bg_image' => 'nullable|mimes:jpg,jpeg,png,svg|max:1024',
            'product_image' => 'nullable|mimes:jpg,jpeg,png,svg|max:1024',
            'button_link' => 'required',
            'title' => 'nullable|string|max:100',
            'type'=>'required'
        ]);
        if ($request->has('product_image')) {
            $fileNameImage = generateFileName($request->product_image->getClientOriginalName());
            $env=env('BANNER_IMAGES_UPLOAD_PATH');
            parent::createThumbnail(400,$request->bg_image,$env,$fileNameImage);
            $path=public_path(env('BANNER_IMAGES_UPLOAD_PATH').$offer->product_image);
            if (file_exists($path) and !is_dir($path)){
                unlink($path);
            }
        }
        if ($request->has('bg_image')) {
            $fileNameBGImage = generateFileName($request->bg_image->getClientOriginalName());
            $request->bg_image->move(public_path(env('BANNER_IMAGES_UPLOAD_PATH')), $fileNameBGImage);
            $path=public_path(env('BANNER_IMAGES_UPLOAD_PATH').$offer->bg_image);
            if (file_exists($path) and !is_dir($path)){
                unlink($path);
            }
        }
        $offer->update([
            'product_image' => $request->has('product_image') ? $fileNameImage : $offer->product_image,
            'bg_image' => $request->has('bg_image') ? $fileNameBGImage : $offer->bg_image,
            'button_link' => $request->button_link,
            'bg_color' => $request->bg_color,
            'title' => $request->title,
            'type'=>$request->type,
        ]);
        alert()->success('گزینه مورد نظر ویرایش شد', 'باتشکر');
        return redirect()->route('admin.offers.index');
    }

    public function destroy(Offer $offer)
    {
        $path=public_path(env('BANNER_IMAGES_UPLOAD_PATH').$offer->product_image);
        if (file_exists($path) and !is_dir($path)){
            unlink($path);
        }
        $path=public_path(env('BANNER_IMAGES_UPLOAD_PATH').$offer->bg_image);
        if (file_exists($path) and !is_dir($path)){
            unlink($path);
        }
        $offer->delete();
        alert()->success('گزینه مورد نظر حذف شد', 'باتشکر');
        return redirect()->route('admin.offers.index');
    }
}
