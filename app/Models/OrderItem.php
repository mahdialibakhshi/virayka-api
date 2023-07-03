<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "order_items";
    protected $guarded = [];

    public function Product(){
        return $this->belongsTo(Product::class)->withTrashed();
    }
    public function AttributeValues(){
        return $this->belongsTo(AttributeValues::class,'variation_id','id');
    }
    public function Color(){
        return $this->belongsTo(AttributeValues::class,'color_id','id');
    }
}
