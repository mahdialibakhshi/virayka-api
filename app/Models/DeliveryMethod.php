<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryMethod extends Model
{
    use HasFactory,SoftDeletes;
    protected $table="delivery_method";
    protected $guarded=[];

    public function getIsActiveAttribute($is_active){
        return $is_active==1 ? 'فعال' : 'غیرفعال' ;
    }

}
