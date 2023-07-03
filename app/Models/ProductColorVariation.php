<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductColorVariation extends Model
{
    use HasFactory,SoftDeletes;
    protected $table="product_color_variation";
    protected $guarded=[];

    public function Color(){
        return $this->belongsTo(AttributeValues::class,'attr_value','id');
    }
}
