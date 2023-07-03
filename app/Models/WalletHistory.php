<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WalletHistory extends Model
{
    use HasFactory,SoftDeletes;
    protected $table="wallet_history";
    protected $guarded=[];
    public function Type(){
        return $this->belongsTo(WalletType::class,'type','id');
    }
}
