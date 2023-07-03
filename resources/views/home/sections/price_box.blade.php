<div id="quantityBox">
        <span>تعداد موجود در انبار :<span id="span_quantity">
                @if(isset($product_variation))
                    {{ $product_variation->quantity }}
                @else
                    {{ $product->quantity }}
                @endif
            </span> عدد </span>
</div>
@if(isset($product_variation))
@if(product_price_for_user_normal($request->product_id)[1]!=0)
    <div class="d-flex justify-content-end align-items-center mb-3">
                                                    <span
                                                        class="discount">{{ number_format(product_price_for_user_normal($request->product_id, $product_variation->id)[1]).' % ' }}</span>
    </div>
@endif
@else
    @if(product_price_for_user_normal($product->id)[1]!=0)
    <div class="d-flex justify-content-end align-items-center mb-3">
                                                    <span
                                                        class="discount">{{ number_format(product_price_for_user_normal($product->id)[1]).' % ' }}</span>
    </div>
    @endif
@endif
@if(isset($product_variation))
    <div class="single-product-price">
        @if (product_price_for_user_normal($request->product_id, $product_variation->id)[1] != 0)
            <p class="regular-price oldPrice">
                <span>قیمت :<del
                        class="previous_product_price_span">{{ number_format(product_price_for_user_normal($request->product_id, $product_variation->id)[0]) }}</del> تومان</span>
                <input class="previous_product_price" type="hidden"
                       value="{{ product_price_for_user_normal($request->product_id, $product_variation->id)[0] }}">
            </p>
        @endif
        <div class="btn btn-block btn-primary">
            <p class="price new-price">
                <span>قیمت :<span
                        class="product_final_price_span">{{ number_format(product_price_for_user_normal($request->product_id, $product_variation->id)[2]) }}</span> تومان</span>
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
                <span>قیمت :<del
                        class="previous_product_price_span">{{ number_format(product_price_for_user_normal($product->id)[0]) }}</del> تومان</span>
                <input class="previous_product_price" type="hidden"
                       value="{{ product_price_for_user_normal($product->id)[0] }}">
            </p>
        @endif
        <div class="btn btn-block btn-primary">
            <p class="price new-price">
                <span>قیمت :<span
                        class="product_final_price_span">{{ number_format(product_price_for_user_normal($product->id)[2]) }}</span> تومان</span>
                <input class="product_final_price" type="hidden"
                       value="{{ product_price_for_user_normal($product->id)[2] }}">
            </p>
            <input type="hidden" id="" value="">
        </div>
    </div>
@endif
