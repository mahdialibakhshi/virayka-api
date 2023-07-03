<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use HasFactory;
    protected $table="cart";
    protected $guarded=[];

    public function Product(){
        return $this->belongsTo(Product::class);
    }
    public function AttributeValues(){
        return $this->belongsTo(AttributeValues::class,'variation_id','id');
    }
    public function Color(){
        return $this->belongsTo(AttributeValues::class,'color_id','id');
    }
}
