<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable,SoftDeletes,HasRoles,HasPermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'is_active',
        'email',
        'password',
        'cellphone',
        'tel',
        'otp',
        'login_token',
        'provider_name',
        'avatar',
        'jensiyat',
        'email_verified_at',
        'role_request',
        'role_image',
        'role',
        'image_atach_1',
        'image_atach_2',
        'image_atach_3',
        'image_atach_4',
        'image_atach_5',
        'image_atach_6',
        'role_description',
        'company_type',
        'company_name',
        'economic_code',
        'naghsh_code',
        'role_request_status',
        'national_code'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function rates()
    {
        return $this->hasMany(ProductRate::class);
    }
    public function Role()
    {
        return $this->belongsTo(Roles::class,'role','id');
    }
    public function RoleRequest()
    {
        return $this->belongsTo(Roles::class,'role_request','id');
    }
    public function Addresses()
    {
        return $this->hasMany(UserAddress::class,'user_id');
    }

}
