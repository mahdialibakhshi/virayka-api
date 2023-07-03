<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductAttribute extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "product_attributes";
    protected $guarded = [];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function attributeValues($value,$attribute_id){
        return AttributeValues::where('id',$value)->where('attribute_id',$attribute_id)->first();
//        return $this->belongsTo(AttributeValues::class,'value','id');
    }
}
