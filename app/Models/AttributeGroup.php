<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttributeGroup extends Model
{
    use HasFactory,SoftDeletes;
    protected $table="attribute_group";
    protected $guarded=[];
    public function Attribute(){
        return $this->belongsTo(Attribute::class,'attribute_id','id');
    }
}
