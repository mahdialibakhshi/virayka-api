<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FunctionalTypes extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function products(){
        return $this->belongsToMany(Product::class,
            'functional_product',
            'type_id',
            'product_id',
            'id',
        'id');
    }
}
