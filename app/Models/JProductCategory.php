<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JProductCategory extends Model
{
    use HasFactory;
    protected $table='bhayb_jshopping_products_to_categories';
    protected $guarded=[];
}
