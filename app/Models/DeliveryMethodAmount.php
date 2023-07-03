<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryMethodAmount extends Model
{
    use HasFactory,SoftDeletes;
    protected $table="delivery_method_amount";
    protected $guarded=[];

    public function Province(){
        return $this->belongsTo(Province::class,'province_id','id');
    }
}
