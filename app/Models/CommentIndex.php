<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommentIndex extends Model
{
    use HasFactory,SoftDeletes;
    protected $table="comment_index";
    protected $guarded=[];

    public function getPublishedAttribute($approved)
    {
        return $approved ? 'تایید شده' : 'تایید نشده' ;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
