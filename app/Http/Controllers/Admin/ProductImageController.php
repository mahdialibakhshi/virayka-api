<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\URL;
use Image;

class ProductImageController extends Controller
{
    public function upload($primaryImage, $images)
    {
        $env=env('PRODUCT_IMAGES_THUMBNAIL_UPLOAD_PATH');
        $fileNamePrimaryImage = generateFileName($primaryImage->getClientOriginalName());
        parent::createThumbnail(400,$primaryImage,$env,$fileNamePrimaryImage);
        $primaryImage->move(public_path(env('PRODUCT_IMAGES_UPLOAD_PATH')), $fileNamePrimaryImage);
        $fileNameImages = [];
        if ($images!=null){
            foreach ($images as $image) {
                $fileNameImage = generateFileName($image->getClientOriginalName());
                parent::createThumbnail(400,$image,$env,$fileNameImage);
                $image->move(public_path(env('PRODUCT_IMAGES_UPLOAD_PATH')), $fileNameImage);
                array_push($fileNameImages,  $fileNameImage);
            }
        }
        return ['fileNamePrimaryImage' => $fileNamePrimaryImage, 'fileNameImages' => $fileNameImages];

    }

    public function edit(Product $product)
    {
        $previous_url = URL::previous();
        $previous_url_explode = explode('?page', $previous_url);
        if (count($previous_url_explode) > 1 or $previous_url == route('admin.products.index')) {
            $pre_url = $previous_url;
            session()->put('pre_url', $pre_url);
        } else {
            $pre_url = session()->get('pre_url');
        }
        return view('admin.products.edit_images', compact('product','pre_url'));
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'image_id' => 'required|exists:product_images,id'
        ]);
        //delete image
        $productImage=ProductImage::where('id',$request->image_id)->first();
        if (!empty($productImage)){
            $path=public_path(env('PRODUCT_IMAGES_UPLOAD_PATH').$productImage->image);
            if (file_exists($path) and !is_dir($path)){
                unlink($path);
            }
            $path_thumbnail=public_path(env('PRODUCT_IMAGES_THUMBNAIL_UPLOAD_PATH').$productImage->image);
            if (file_exists($path_thumbnail) and !is_dir($path_thumbnail)){
                unlink($path_thumbnail);
            }
        }
        ProductImage::destroy($request->image_id);

        alert()->success('تصویر محصول مورد نظر حدف شد', 'باتشکر');
        return redirect()->back();
    }

    public function setPrimary(Request $request, Product $product)
    {
        $request->validate([
            'image_id' => 'required|exists:product_images,id'
        ]);

        $productImage = ProductImage::findOrFail($request->image_id);
        $product->update([
            'primary_image' => $productImage->image
        ]);
        alert()->success('ویرایش تصویر اصلی محصول با موفقیت انجام شد', 'باتشکر');
        return redirect()->back();
    }
    public function set_as_second_image(Request $request){
        $image_id=$request->image_id;
        try {
            DB::beginTransaction();
            $image=ProductImage::where('id',$image_id)->first();
            $set_as_second_image=$image->set_as_second_image;
            $new_set_as_second_image=$set_as_second_image==1?0:1;
            $image->update([
                'set_as_second_image'=>$new_set_as_second_image,
            ]);
            if ($new_set_as_second_image==1){
                $button='<button onclick="set_as_second_image('.$image->id.')"
                                            id="set_as_second_image_'.$image->id.'"
                                            class="btn btn-success btn-sm mb-3"
                                            type="button">
                                        انتخاب به عنوان تصویر دوم
                                    </button>';
            }else{
                $button='<button onclick="set_as_second_image('.$image->id.')"
                                            id="set_as_second_image_'.$image->id.'"
                                            class="btn btn-dark text-gray-500 btn-sm mb-3"
                                            type="button">
                                        انتخاب به عنوان تصویر دوم
                                    </button>';
            }
            DB::commit();
            return response()->json([1,$button]);
        }catch (\Exception $exception){
            DB::rollBack();
            return response()->json([0,$exception->getMessage()]);
        }
    }

    public function add(Request $request, Product $product)
    {
        $env=env('PRODUCT_IMAGES_THUMBNAIL_UPLOAD_PATH');
        $request->validate([
            'primary_image' => 'nullable|mimes:jpg,jpeg,png,svg',
            'images.*' => 'nullable|mimes:jpg,jpeg,png,svg',
        ]);

        if ($request->primary_image == null && $request->images == null) {
            return redirect()->back()->withErrors(['msg' => 'تصویر یا تصاویر محصول الزامی هست']);
        }

        try {
            DB::beginTransaction();

            if ($request->has('primary_image')) {

                $fileNamePrimaryImage = generateFileName($request->primary_image->getClientOriginalName());
                parent::createThumbnail(400,$request->primary_image,$env,$fileNamePrimaryImage);
                $request->primary_image->move(public_path(env('PRODUCT_IMAGES_UPLOAD_PATH')), $fileNamePrimaryImage);

                $product->update([
                    'primary_image' => $fileNamePrimaryImage
                ]);
            }

            if ($request->has('images')) {

                foreach ($request->images as $image) {
                    $fileNameImage = generateFileName($image->getClientOriginalName());
                    parent::createThumbnail(400,$image,$env,$fileNameImage);
                    $image->move(public_path(env('PRODUCT_IMAGES_UPLOAD_PATH')), $fileNameImage);

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image' => $fileNameImage
                    ]);
                }
            }

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            alert()->error('مشکل در ایجاد محصول', $ex->getMessage())->persistent('حله');
            return redirect()->back();
        }

        alert()->success('ویرایش تصویر اصلی محصول با موفقیت انجام شد', 'باتشکر');
        return redirect()->back();
    }
    //label images
    public function labelImageController($Image)
    {
        $fileNameImage = generateFileName($Image->getClientOriginalName());

        $Image->move(public_path(env('LABEL_IMAGES_UPLOAD_PATH')), $fileNameImage);

        return $fileNameImage;
    }
    //Articles images
    public function ArticlesImageController($Image)
    {
        $env=env('ARTICLES_IMAGES_THUMBNAIL_UPLOAD_PATH');
        $fileNameImage = generateFileName($Image->getClientOriginalName());
        $img = Image::make($Image->path());
        $img->resize(500, 'auto', function ($const) {
            $const->aspectRatio();
        })->save($env.$fileNameImage);
        $Image->move(public_path(env('ARTICLES_IMAGES_UPLOAD_PATH')), $fileNameImage);

        return $fileNameImage;
    }
    //category image
    public function categoryImageUpload($Image)
    {
        $fileNameImage = generateFileName($Image->getClientOriginalName());

        $Image->move(public_path(env('CATEGORY_IMAGES_UPLOAD_PATH')), $fileNameImage);

        return $fileNameImage;
    }
    //logo image
    public function logoUpload($Image)
    {
        $fileNameImage = generateFileName($Image->getClientOriginalName());

        $Image->move(public_path(env('LOGO_UPLOAD_PATH')), $fileNameImage);

        return $fileNameImage;
    }
    //logo image
    public function AttributeUpload($Image)
    {
        $fileNameImage = generateFileName($Image->getClientOriginalName());

        $Image->move(public_path(env('ATTR_UPLOAD_PATH')), $fileNameImage);

        return $fileNameImage;
    }

}
