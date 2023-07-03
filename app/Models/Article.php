<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "articles";
    protected $guarded = [];

    public function Category()
    {
        return $this->belongsTo(ArticleCategoriy::class,'category_id','id');
    }
}
