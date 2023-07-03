<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductAttrVariation extends Model
{
    use HasFactory,SoftDeletes;
    protected $table="product_attr_variation";
    protected $guarded=[];

    public function Product(){
        return $this->belongsTo(Product::class,'product_id','id');
    }
    public function Attribute(){
        return $this->belongsTo(Attribute::class,'attr_id','id');
    }
    public function AttributeValue(){
        return $this->belongsTo(AttributeValues::class,'attr_value','id');
    }
    public function Color(){
        return $this->belongsTo(AttributeValues::class,'color_attr_value','id');
    }

    public function category_name($product_id){
        $product=Product::where('id',$product_id)->first();
            $product_categories=$product->category_id;
            $product_categories=json_decode($product_categories);
            if (is_array($product_categories)){
                $category_name='';
                foreach ($product_categories as $key=>$category){
                    $category=Category::where('id',$category)->first()->name;
                    if ($key==0){
                        $category_name=$category;
                    }else{
                        $category_name=$category.'/'.$category_name;
                    }
                }
                $product['category_name']=$category_name;
            }else{
                if ($product_categories!=null){
                    $category_name=Category::where('id',$product_categories)->first()->name;
                }else{
                    $category_name='-';
                }
                $product['category_name']=$category_name;
            }
            return $category_name;
    }

}
