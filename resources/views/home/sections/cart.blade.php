@if(count($carts)>0)
<ul class="m_cart-list">
    @foreach($carts as $cart)
        @php
            $product_attr_variation=\App\Models\ProductAttrVariation::where('product_id',$cart->product_id)
           ->where('attr_value',$cart->variation_id)
           ->where('color_attr_value',$cart->color_id)->first();
              if (isset($product_attr_variation)){
                $product_attr_variation_id=$product_attr_variation->id;
            }else{
                $product_attr_variation_id=null;
            }
        @endphp
        <li class="m_cart_li1">
            <span class="m_cart-item position-absolute">
                <i onclick="cart_side_bar({{ $cart->id }})" class="fa fa-times" aria-hidden="true"></i>
            </span>
            <a href="{{ route('home.product',['alias'=>$cart->Product->alias]) }}" class="m_cart-item">



                <div class="m_cart-item-content">
                    <div class="m_cart-item-image">
                        <img src="{{ imageExist(env('PRODUCT_IMAGES_THUMBNAIL_UPLOAD_PATH'),$cart->Product->primary_image) }}"/>
                    </div>
                    <div class="m_cart-item-details">
                        <div class="m_cart-item-title">
                            {{ $cart->Product->name }}
                        </div>
                        <div class="m_cart-item-params">
                            <div class="m_cart-item-props">
                                <span>تعداد : {{ $cart->quantity }}</span>
                                <br>
                                <span>رنگ: {{ isset($cart->Color->name) ? $cart->Color->name : '' }}</span>
                                <br>
                                <span>{{ isset($cart->AttributeValues->name) ? $cart->AttributeValues->name : '' }}</span>
                                @unless (calculateCartProductPrice(product_price_for_user_normal($cart->product_id,$product_attr_variation_id)[1], json_decode($cart->option_ids))==0)
                                <br>
                                <span>
                                     <del class="products_old_price">
                                                        {{ number_format(calculateCartProductPrice(product_price_for_user_normal($cart->product_id,$product_attr_variation_id)[0], json_decode($cart->option_ids))) }}
                                                    </del>

                                </span>
                                @endunless
                                <br>
                                <span>
                                    {{ number_format(calculateCartProductPrice(product_price_for_user_normal($cart->product_id,$product_attr_variation_id)[2],$cart->option_ids)) }}
                                          تومان
                                </span>
                            </div>
                        </div>

                    </div>
                </div>
            </a>
        </li>
    @endforeach
</ul>
<div class="m_cart-header">
    <div class="m_cart-total">
        <span>مجموع سبد:</span>
        <span>{{ number_format(calculateCartPrice()['sale_price']- session()->get('coupon.amount')) }}</span>
        <span> تومان</span>
    </div>
</div>
<div class="btn_cart">
    <a href="{{ route('home.cart') }}" class="btn btn_sabad">مشاهده سبد</a>
    <a href="{{ route('home.checkout') }}" class="btn btn_pardakht btn-main-masai">پرداخت</a>
</div>
@endif
