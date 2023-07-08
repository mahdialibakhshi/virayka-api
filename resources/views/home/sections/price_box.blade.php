
<div class="d-flex">
    @if(isset($product_variation))
        @if(product_price_for_user_normal($request->product_id)[1]!=0)
            <div class="d-flex off-box justify-content-end align-items-center mb-3">

                                                    <span
                                                        class="discount flex-column">
                                                        <span>% {{ number_format(product_price_for_user_normal($request->product_id, $product_variation->id)[1]) }}</span>

                                                     <span>
                                                              OFF
                                                        </span>
                                                    </span>

            </div>
        @endif
    @else
        @if(product_price_for_user_normal($product->id)[1]!=0)
            <div class="d-flex off-box justify-content-end align-items-center mb-3">

                                                    <span
                                                        class="discount flex-column">
                                                        <span>% {{ number_format(product_price_for_user_normal($product->id)[1]) }}</span>
 <span>
                                                              OFF
                                                        </span>
                                                    </span>


            </div>
        @endif
    @endif
    @if(isset($product_variation))
        <div class="single-product-price">
            @if (product_price_for_user_normal($request->product_id, $product_variation->id)[1] != 0)
                <p class="regular-price oldPrice">
                <span><del
                        class="previous_product_price_span">{{ number_format(product_price_for_user_normal($request->product_id, $product_variation->id)[0]) }}</del> ت</span>
                    <input class="previous_product_price" type="hidden"
                           value="{{ product_price_for_user_normal($request->product_id, $product_variation->id)[0] }}">
                </p>
            @endif
            <div style="background-color: red;
    padding: 5px 30px;
    color: #fff;
    font-weight: 800;
}">
                <p class="price new-price">
                <span><span
                        class="product_final_price_span">{{ number_format(product_price_for_user_normal($request->product_id, $product_variation->id)[2]) }}</span> ت</span>
                    <input class="product_final_price" type="hidden"
                           value="{{ product_price_for_user_normal($request->product_id, $product_variation->id)[2] }}">
                </p>
                <input type="hidden" id="" value="">
            </div>
        </div>
    @else
        <div class="single-product-price">
            @if (product_price_for_user_normal($product->id)[1] != 0)
                <p class="regular-price oldPrice">
                <span class="del-box">
                    <span style="color: #000000"
                          class="previous_product_price_span">{{ number_format(product_price_for_user_normal($product->id)[0]) }}ت</span> </span>
                    <input class="previous_product_price" type="hidden"
                           value="{{ product_price_for_user_normal($product->id)[0] }}">
                </p>
            @endif
            <div style="background-color: red;
    padding: 5px 30px;
    color: #fff;
    font-weight: 800;
}">
                <p class="price new-price">
                <span><span
                        class="product_final_price_span">{{ number_format(product_price_for_user_normal($product->id)[2]) }}</span>ت</span>
                    <input class="product_final_price" type="hidden"
                           value="{{ product_price_for_user_normal($product->id)[2] }}">
                </p>
                <input type="hidden" id="" value="">
            </div>
        </div>
    @endif


</div>

{{--<div id="quantityBox">--}}
{{--        <span>تعداد موجود در انبار :<span id="span_quantity">--}}
{{--                @if(isset($product_variation))--}}
{{--                    {{ $product_variation->quantity }}--}}
{{--                @else--}}
{{--                    {{ $product->quantity }}--}}
{{--                @endif--}}
{{--            </span> عدد </span>--}}
{{--</div>--}}
{{--<div class="fix-bottom product-sticky-content sticky-content">--}}
{{--    <label>تعداد:</label>--}}
{{--    <div class="product-form d-flex justify-content-between align-center">--}}
{{--        <div class="product-qty-form with-label">--}}
{{--            <div>--}}
{{--                <input id="quantity" class="quantity form-control" type="number"--}}
{{--                       min="1"--}}
{{--                       max="10000000" value="1">--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        @if($product->quantity==0)--}}
{{--            <button onclick="informMe({{ $product->id }})"--}}
{{--                    title="موجود شد به من اطلاع بده"--}}
{{--                    type="button" class="btn btn-primary btn-cart">--}}
{{--                <i class="fas fa-bell"></i>--}}
{{--                <span>موجود شد به من اطلاع بده</span>--}}
{{--            </button>--}}
{{--        @else--}}
{{--            <div class="d-flex">--}}

{{--                                                        <span id="addToCartBtn" class="search_prod_btn">--}}
{{--                                                            <i class="fa fa fa-cart-arrow-down search_icon_cart"--}}
{{--                                                               aria-hidden="true"></i>--}}
{{--                                                        </span>--}}
{{--            </div>--}}
{{--        @endif--}}
{{--    </div>--}}
{{--</div>--}}

<div style="max-width:200px !important; text-align: center" class="product-qty-form with-label">
    <label class="font-weight-bold">تعداد:</label>
    <i style="margin-right: 15px;margin-left:10px;cursor: pointer" onclick="change_quantity(1)" class="fa fa-plus"></i>

    <input   style="width: 36px" readonly class="text-center quantity-input number-input"
             id="quantity"
             min="1" max="100000"
             value="1">
    <i style="margin-right: 10px;cursor: pointer" onclick="change_quantity(0)" class="fa fa-minus"></i>
</div>
<div class="mt-3" id="addToCartBtn" style="width: 100%">
    <span  >افزودن به سبد </span>
</div>



