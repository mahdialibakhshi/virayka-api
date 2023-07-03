<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = "orders";
    protected $guarded = [];

    public function getActiveSmsAttribute($is_active)
    {
        return $is_active ? 'فعال' : 'غیرفعال' ;
    }

    public function getStatusAttribute($status)
    {
        switch($status){
            case '0' :
                $status = 'نامشخص';
                break;
            case '1' :
                $status = 'پرداخت شده';
            case '2' :
                $status = 'پرداخت اعتباری';
                break;
        }
        return $status;
    }

    public function getPaymentTypeAttribute($paymentType)
    {
        switch($paymentType){
            case '1' :
                $paymentType = 'اینترنتی';
                break;
            case '2' :
                $paymentType = 'پرداخت اعتباری';
                break;
        }
        return $paymentType;
    }

    public function getPaymentStatusAttribute($paymentStatus)
    {
        switch($paymentStatus){
            case 0 :
                $paymentStatus = 'ناموفق';
                break;
            case 1 :
                $paymentStatus = 'موفق';
                break;
            case 2 :
                $paymentStatus = '-';
                break;
        }
        return $paymentStatus;
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class)->withTrashed();
    }

    public function address()
    {
        return $this->belongsTo(UserAddress::class)->withTrashed();
    }

    public function DeliveryMethod()
    {
        return $this->belongsTo(DeliveryMethod::class,'delivery_method','id');
    }

    public function DeliveryMethodStatus()
    {
        return $this->belongsTo(OrderStatus::class,'delivery_status','id');
    }

    public function Transaction(){
        return $this->belongsTo(Transaction::class,'id','order_id');
    }

}
