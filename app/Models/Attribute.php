<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attribute extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "attributes";
    protected $guarded = [];

    public function categories()
    {
        return $this->belongsToMany(Category::class , 'attribute_category');
    }
    public function Group()
    {
        return $this->belongsTo(AttributeGroup::class , 'group_id','id');
    }
    public function AttributeValues()
    {
        return $this->hasMany(AttributeValues::class , 'attribute_id','id');
    }
}
