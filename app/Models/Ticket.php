<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory,SoftDeletes;
    protected $table="ticket";
    protected $guarded=[];

    public function Category()
    {
        return $this->belongsTo(Category::class,'category_id','id');
    }
    public function Status()
    {
        return $this->belongsTo(Status::class,'status_id','id');
    }
    public function User()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
