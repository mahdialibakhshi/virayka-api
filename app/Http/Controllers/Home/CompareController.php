<?php

namespace App\Http\Controllers\Home;

use App\Models\Attribute;
use App\Models\AttributeValues;
use App\Models\Product;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use SebastianBergmann\Diff\Exception;

class CompareController extends Controller
{
    public function add(Request $request)
    {
        $productId=$request->productId;
        if (session()->has('compareProducts')) {
            if (in_array($productId, session()->get('compareProducts'))) {
                return response()->json(['exist']);
            }
            $count=count(session()->get('compareProducts'));
            if ($count>3){
                return response()->json(['full']);
            }
            session()->push('compareProducts', $productId);
        } else {
            session()->put('compareProducts', [$productId]);
        }

      return response()->json(['ok']);
    }
    public function get(){
        $productsInCart='';
        $ids=session()->get('compareProducts');
        $products = Product::findOrFail($ids);
        foreach ($products as $product){
            $productsInCart = $productsInCart.'<div class="product product-cart">
                                        <div class="product-detail">
                                            <td class="product-name">
                                                <a href="' . route('home.product', ['alias' => $product->alias]) . '">
                                                    ' . $product->name . '
                                                </a>
                                            </td>
                                        </div>
                                        <figure class="product-media">
                                            <a href="product-default.html">
                                                <img
                                                    src="' . imageExist(env('PRODUCT_IMAGES_THUMBNAIL_UPLOAD_PATH'), $product->primary_image) . '"
                                                    alt="product"
                                                    height="84"
                                                    width="94"/>
                                            </a>
                                        </figure>
                                        <button onclick="compare_side_bar('.$product->id.')" class="btn btn-link btn-close" aria-label="button">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>';

        }
        return response()->json(['ok',count(session()->get('compareProducts')),$productsInCart]);
    }

    public function index()
    {
        if (!session()->has('compareProducts')) {
            alert()->warning('در ابتدا باید محصولی برای مقایسه اضافه کنید', 'دقت کنید');
            return redirect()->back();
        }

        $ids=session()->get('compareProducts');
        $product_ids=[];
        $products = Product::findOrFail($ids);
        foreach ($products as $product) {
            array_push($product_ids, $product->id);
        }
        $attribute_values=ProductAttribute::whereIn('product_id',$product_ids)->get();
        $attribute_ids=[];
        foreach ($attribute_values as $attribute_value){
            array_push($attribute_ids, $attribute_value->attribute_id);
        }
        $attribute_ids=array_unique($attribute_ids);
        $attributes=Attribute::whereIn('id',$attribute_ids)->get();
        return view('home.compare', compact('products','attributes'));
    }

    public function remove($productId)
    {
        if (session()->has('compareProducts')) {
            foreach (session()->get('compareProducts') as $key => $item) {
                if ($item == $productId) {
                    session()->pull('compareProducts.' . $key);
                }
            }
            if (session()->get('compareProducts') == []) {
                session()->forget('compareProducts');
                alert()->success('محصول از لیست مقایسه باموفقیت حذف شد', 'با تشکر');
                return redirect()->route('home.index');
            }
            alert()->success('محصول از لیست مقایسه باموفقیت حذف شد', 'با تشکر');
            return redirect()->route('home.compare');
        }

        alert()->warning('در ابتدا باید محصولی برای مقایسه اضافه کنید', 'دقت کنید');
        return redirect()->back();
    }
    public function remove_sideBar(Request $request)
    {
        try {
            DB::beginTransaction();
            if (session()->has('compareProducts')) {
                foreach (session()->get('compareProducts') as $key => $item) {
                    if ($item == $request->product_id) {
                        session()->pull('compareProducts.' . $key);
                    }
                }
                DB::commit();
                return response()->json([1]);
            }
        }catch (\Exception $exception){
            DB::rollBack();
            return response()->json([0,$exception->getMessage()]);
        }


    }
}
