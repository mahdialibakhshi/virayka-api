<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory , SoftDeletes;

    protected $table = "products";
    protected $guarded = [];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */

    public function getIsActiveAttribute($is_active)
    {
        return $is_active ? 'فعال' : 'غیرفعال' ;
    }
    public function getSpecialSaleAttribute($specialSale)
    {
        return $specialSale ? 'فعال' : 'غیرفعال' ;
    }
    public function getSetAsNewAttribute($Set_as_new)
    {
        return $Set_as_new ? 'فعال' : 'غیرفعال' ;
    }
    public function getAmazingSaleAttribute($amazing_sale)
    {
        return $amazing_sale ? 'فعال' : 'غیرفعال' ;
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tag');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class,'brand_id','id');
    }

    public function Categories(){
        return $this->belongsToMany(Category::class);
    }

    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function ProductAttributeVariation($product_id)
    {
        return ProductAttrVariation::where('product_id',$product_id)->distinct()->get();

    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function rates()
    {
        return $this->hasMany(ProductRate::class);
    }

    public function approvedComments()
    {
        return $this->hasMany(Comment::class)->where('approved' , 1);
    }

    public function checkUserWishlist($userId): bool
    {
        return $this->hasMany(Wishlist::class)->where('user_id' , $userId)->exists();
    }
    public function Label()
    {
        return $this->belongsTo(Label::class,'label','id');
    }
    public function functionalTypes(){
        return $this->belongsToMany(FunctionalTypes::class,
            'functional_product',
            'product_id',
            'type_id',
            'id',
            'id');
    }

    public function product_attributes_original(){
        $product_id=$this->id;
        $product_attributes_original_count = ProductAttribute::where('product_id', $product_id)->where('is_active',1)->orderby('priority','ASC')->count();
        $chunk=ceil($product_attributes_original_count/2);
        return ProductAttribute::where('product_id', $product_id)->where('is_active',1)->orderby('priority','ASC')->get()->chunk($chunk);
    }
    public function options()
    {
        return $this->hasMany(ProductOption::class);
    }
}
