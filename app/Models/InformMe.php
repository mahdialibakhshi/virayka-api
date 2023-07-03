<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InformMe extends Model
{
    use HasFactory,SoftDeletes;
    protected $table='inform_me';
    protected $guarded=[];

    public function Product(){
        return $this->belongsTo(Product::class);
    }
}
