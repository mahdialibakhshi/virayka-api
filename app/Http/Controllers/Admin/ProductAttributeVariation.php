<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValues;
use App\Models\Product;
use App\Models\ProductAttrVariation;
use App\Models\ProductColorVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class ProductAttributeVariation extends Controller
{
    public function edit(Product $product)
    {
        $previous_url=URL::previous();
        $previous_url_explode=explode('?page',$previous_url);
        if (count($previous_url_explode)>1 or $previous_url==route('admin.products.index')){
            $pre_url=$previous_url;
            session()->put('pre_url',$pre_url);
        }else{
            $pre_url=session()->get('pre_url');
        }
        $attributes = Attribute::where('is_dependence', 1)->latest()->get();
        $attribute_values = AttributeValues::all();
        $attr_color=Attribute::where('name','رنگ')->first();
        $colors = AttributeValues::where('attribute_id', $attr_color->id)->orderBy('priority_show','asc')->orderBy('updated_at','desc')->get();
        $product_variation_colors = ProductColorVariation::where('product_id', $product->id)->get();
        $product_attr_variations = ProductAttrVariation::where('product_id', $product->id)->latest()->get()->chunk(count($product_variation_colors));
        $attrs = [];
        foreach ($product_attr_variations as $key => $product_attr_variation) {
            foreach ($product_attr_variation as $item) {
                $attrs[$key]['attr_id_name'] = $item->Attribute->name;
                $attrs[$key]['attr_id'] = $item->Attribute->attr_id;
                $attrs[$key]['attr_value_name'] = $item->AttributeValue->name;
                $attrs[$key]['attr_value'] = $item->attr_value;
            }
        }
        return view('admin.products.variations.attributes.edit', compact('colors',
            'product',
            'product_variation_colors',
            'attributes',
            'product_attr_variations',
            'attrs',
            'attribute_values',
            'pre_url',
        ));
    }

    public function update(Request $request){
        $ids=$request->ids;
        try {
            DB::beginTransaction();
            foreach ($ids as $id){
                $quantity=$request->quantity[$id];
                $price=$request->price_[$id];
                $sale_price=$request->sale_price_[$id];
                $percent_sale_price=$request->percent_sale_price_[$id];
                $product_attribute_variation=ProductAttrVariation::where('id',$id)->first();
                //remove price separator
                $price=str_replace(',','',$price);
                $sale_price=str_replace(',','',$sale_price);

                $product_attribute_variation->update([
                    'quantity'=>$quantity,
                    'price'=>$price,
                    'sale_price'=>$sale_price,
                    'percent_sale_price'=>$percent_sale_price,
                ]);
            }
			//update main product
            $product_attr_variations=ProductAttrVariation::where('product_id',$request->product_id)->get();
            $cheapest_attr_variation_exists=ProductAttrVariation::where('product_id',$request->product_id)->where('quantity','>',0)->orderby('price','asc')->exists();
            if($cheapest_attr_variation_exists){
                $variation_has_sale_exists=ProductAttrVariation::where('product_id',$request->product_id)->where('sale_price','>',0)->orderby('sale_price','asc')->exists();
                if ($variation_has_sale_exists){
                    $cheapest_attr_variation=ProductAttrVariation::where('product_id',$request->product_id)->where('sale_price','>',0)->orderby('sale_price','asc')->first();
                }else{
                    $cheapest_attr_variation=ProductAttrVariation::where('product_id',$request->product_id)->where('quantity','>',0)->orderby('price','asc')->first();
                }
                
            }else{
                $cheapest_attr_variation=ProductAttrVariation::where('product_id',$request->product_id)->orderby('price','asc')->first();
            }
            $quantity=0;
            foreach ($product_attr_variations as $item){
                $quantity=$quantity+$item->quantity;
            }
            $product=Product::where('id',$request->product_id)->first();
            $previous_quantity=$product->quantity;
            $new_quantity=$quantity;
            $product->update([
                'quantity'=>$quantity,
                'price'=>$cheapest_attr_variation->price,
                'sale_price'=>$cheapest_attr_variation->sale_price,
                'percent_sale_price'=>$cheapest_attr_variation->percent_sale_price,
                ]);
            $this->send_sms_to_user_if_product_exist($previous_quantity,$new_quantity,$product->id);
            DB::commit();
            alert()->success('ویرایش اطلاعات با موفقیت انجام شد','با تشکر');
            if ($request->update_type==='update'){
                return redirect()->back();
            }
            if ($request->update_type==='update_and_close'){
                return redirect()->to($request->previous_rout);
            }
        }catch (\Exception $exception){
            DB::rollBack();
            alert()->error($exception->getMessage())->persistent('ok');
            return redirect()->back();
        }
    }

    public function save_colors(Request $request, Product $product)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'image' => 'nullable|mimes:jpg,jpeg,png,svg',
                'attr_value' => 'required',
            ]);
            $product_variation_color = ProductColorVariation::where('product_id', $product->id)->where('attr_value', $request->attr_value)->first();
            //update product color variation
            if ($product_variation_color != null) {
                if ($request->has('image')) {
                    $fileNameImage = generateFileName($request->image->getClientOriginalName());
                    $request->image->move(public_path(env('PRODUCT_VARIATION_COLOR_UPLOAD_PATH')), $fileNameImage);
                    $path = public_path(env('PRODUCT_VARIATION_COLOR_UPLOAD_PATH') . $product_variation_color->image);
                    unlink_image_helper_function($path);
                } else {
                    $fileNameImage = $product_variation_color->image;
                }
                $product_variation_color->update([
                    'image'=>$fileNameImage,
                ]);
                DB::commit();
                alert()->success('رنگ بندی مورد نظر با موفقیت ویرایش شد.', 'ویرایش رنگ بندی')->persistent('متوجه شدم');
                return redirect()->back();
            }
            //create new product variation
            if ($request->has('image')) {
                $fileNameImage = generateFileName($request->image->getClientOriginalName());
                $request->image->move(public_path(env('PRODUCT_VARIATION_COLOR_UPLOAD_PATH')), $fileNameImage);
            } else {
                $fileNameImage = null;
            }
            //create new color
            ProductColorVariation::create([
                'product_id' => $product->id,
                'attr_id' => 14,
                'attr_value' => $request->attr_value,
                'image' => $fileNameImage,
            ]);
            //reWrite product attr variations
            $attr_values = [];
            $product_variation_colors = ProductColorVariation::all();
            $product_attr_variations = ProductAttrVariation::where('product_id', $product->id)->get();
            foreach ($product_attr_variations as $key => $item) {
                array_push($attr_values,$item->attr_value);
            }
            $attr_values=array_unique($attr_values);
            $new_product_attr_variations=[];
            $exist_colors=[];
            $new_color=$request->attr_value;
            foreach ($attr_values as $attr_value){
                $product_attr_variations=ProductAttrVariation::where('product_id', $product->id)->where('attr_value',$attr_value)->get();
                foreach ($product_attr_variations as $product_attr_variation){
                    array_push($exist_colors,$product_attr_variation->color_attr_value);
                }
                foreach ($product_attr_variations as $product_attr_variation){
                    $new=[
                        'product_id'=>$product_attr_variation->product_id,
                        'attr_id'=>$product_attr_variation->attr_id,
                        'attr_value'=>$product_attr_variation->attr_value,
                        'color_attr_id'=>$product_attr_variation->color_attr_id,
                        'color_attr_value'=>$product_attr_variation->color_attr_value,
                        'quantity'=>$product_attr_variation->quantity,
                        'price'=>$product_attr_variation->price,
                        'percent_sale_price'=>$product_attr_variation->percent_sale_price,
                    ];
                    array_push($new_product_attr_variations,$new);
                }
                if (!in_array($new_color,$exist_colors)){
                    $new=[
                        'product_id'=>$product_attr_variations[0]->product_id,
                        'attr_id'=>$product_attr_variations[0]->attr_id,
                        'attr_value'=>$product_attr_variations[0]->attr_value,
                        'color_attr_id'=>$product_attr_variations[0]->color_attr_id,
                        'color_attr_value'=>$new_color,
                        'quantity'=>0,
                        'price'=>0,
                        'percent_sale_price'=>0,
                    ];
                    array_push($new_product_attr_variations,$new);
                }

            }
            $all_product_attr_variations=ProductAttrVariation::where('product_id',$product->id)->get();
            foreach ($all_product_attr_variations as $item) {
                $item->delete();
            }

            foreach ($new_product_attr_variations as $new_product_attr_variation){
                ProductAttrVariation::create([
                    'product_id' => $product->id,
                    'attr_id' => $new_product_attr_variation['attr_id'],
                    'attr_value' => $new_product_attr_variation['attr_value'],
                    'color_attr_id' => $new_product_attr_variation['color_attr_id'],
                    'color_attr_value' => $new_product_attr_variation['color_attr_value'],
                    'quantity' => $new_product_attr_variation['quantity'],
                    'price' => $new_product_attr_variation['price'],
                    'percent_sale_price' => $new_product_attr_variation['percent_sale_price'],
                ]);
            }
            DB::commit();
            alert()->success('رنگ بندی مورد نظر با موفقیت اضافه شد', 'باتشکر')->persistent('ok');
            return redirect()->back();
        } catch (\Exception $exception) {
            DB::rollBack();
            alert()->error($exception->getMessage(), 'خطا')->persistent('ok');
            return redirect()->back();
        }

    }

    public function color_remove(Request $request)
    {
        $product_id=$request->product_id;
        try {
            DB::beginTransaction();
            $product_variation_color = ProductColorVariation::where('id', $request->id)->first();
            $path = public_path(env('PRODUCT_VARIATION_COLOR_UPLOAD_PATH') . $product_variation_color->image);
            unlink_image_helper_function($path);
            $product_attr_variations = ProductAttrVariation::where('product_id',$product_id)->where('color_attr_value', $product_variation_color->attr_value)->get();
            foreach ($product_attr_variations as $product_attr_variation) {
                $product_attr_variation->delete();
            }
            $product_variation_color->delete();
            DB::commit();
            $msg = 'عملیات حذف با موفقیت انجام شد';
            return response()->json([1, $msg]);

        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([0, $exception->getMessage()]);
        }
    }

    public function add_product(Request $request, Product $product)
    {
        $request->validate([
            'attribute_id' => 'required',
            'attribute_value' => 'required',
        ]);
        $exists_product = ProductAttrVariation::where('product_id', $product->id)
            ->where('attr_id', $request->attribute_id)
            ->where('attr_value', $request->attribute_value)
            ->exists();
        if ($exists_product) {
            alert()->warning('این ویژگی از قبل اضافه شده است', 'دقت کنید')->persistent('ok');
            return redirect()->back();
        }
        //get all colors
        $product_variation_colors = ProductColorVariation::where('product_id', $product->id)->get();
        if (count($product_variation_colors) == 0) {
            alert()->warning('ابتدا باید مقدار رنگ را اضافه کنید', 'دقت کنید')->persistent('متوجه شدم');
            return redirect()->back();
        }
        foreach ($product_variation_colors as $item) {
            ProductAttrVariation::create([
                'product_id' => $product->id,
                'attr_id' => $request->attribute_id,
                'attr_value' => $request->attribute_value,
                'color_attr_id' => $item->attr_id,
                'color_attr_value' => $item->attr_value,
            ]);
        }
        alert()->success('وابستگی جدید اضافه شد', 'با تشکر');
        return redirect()->back();
    }

    public function attr_remove(Request $request)
    {
        try {
            DB::beginTransaction();
            $attr_value = $request->attr_value;
            $product_id = $request->product_id;
            $products = ProductAttrVariation::where('attr_value', $attr_value)->where('product_id',$product_id)->get();
            foreach ($products as $product) {
                $product->delete();
            }
            DB::commit();
            $msg = 'عملیات حذف با موفقیت انجام شد';
            return response()->json([1, $msg]);

        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([0, $exception->getMessage()]);
        }
    }

}
