<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JUser extends Model
{
    use HasFactory;
    protected $table='bhayb_users';
    protected $guarded=[];
}
