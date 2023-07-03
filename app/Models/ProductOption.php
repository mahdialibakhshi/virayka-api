<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductOption extends Model
{
    use HasFactory,SoftDeletes;
    protected $table="product_options";
    protected $guarded=[];

    public function VariationName(){
        return $this->belongsTo(Attribute::class,'attribute_id','id');
    }
    public function VariationValue(){
        return $this->belongsTo(AttributeValues::class,'value','id');
    }
    public function Product(){
        return $this->belongsTo(Product::class,'product_id','id');
    }
}
