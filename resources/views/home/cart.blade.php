@extends('home.layouts.index')

@section('title')
   سبد خرید
@endsection

@section('description')

@endsection

@section('keywords')

@endsection

@section('style')
    <style>
        tr > td {
            text-align: center !important;
        }

        .number-input {
            border: none !important;
        }
        .fa-times{
            position: absolute;
            top: 0;
        }
    </style>
@endsection

@section('script')
    <script>
        function remove_cart(cart_id) {
            $.ajax({
                url: "{{ route('home.cart.remove') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    cart_id: cart_id,
                },
                dataType: "json",
                type: 'POST',
                beforeSend: function () {

                },
                success: function (msg) {
                    console.log(msg);
                    if (msg) {
                        if (msg[0] == 1) {
                            let message = msg[1];
                            swal({
                                title: 'باتشکر',
                                text: message,
                                icon: 'success',
                                timer: 1500,
                            })
                            setTimeout(function () {
                                window.location.reload();
                            }, 1500)
                        }
                        if (msg[0] == 0) {
                            let message = msg[1];
                            swal({
                                title: 'error',
                                text: message,
                                icon: 'error',
                                buttons: 'ok',
                            })
                        }
                    }
                },
            })
        }

        function remove_carts() {
            $.ajax({
                url: "{{ route('home.carts.remove') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                },
                dataType: "json",
                type: 'POST',
                beforeSend: function () {

                },
                success: function (msg) {
                    console.log(msg);
                    if (msg) {
                        if (msg[0] == 1) {
                            let message = msg[1];
                            swal({
                                title: 'باتشکر',
                                text: message,
                                icon: 'success',
                                timer: 1500,
                            })
                            setTimeout(function () {
                                window.location.href = "{{ route('home.index') }}";
                            }, 1500)
                        }
                        if (msg[0] == 0) {
                            let message = msg[1];
                            swal({
                                title: 'error',
                                text: message,
                                icon: 'error',
                                buttons: 'ok',
                            })
                        }
                    }
                },
            })
        }

        function updateQuantity(cart_id, tag) {
            let quantity = $(tag).val();
            $.ajax({
                url: "{{ route('home.cart.update') }}",
                type: "POST",
                dataType: "json",
                data: {
                    quantity: quantity,
                    cart_id: cart_id,
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    if (response[0] === 1) {
                        window.location.href = "{{ route('home.cart') }}";
                    }
                    if (response[0] === 0) {
                        swal({
                            title: " دقت کنید",
                            text: "تعداد وارد شده بیش از موجودی کالا است",
                            icon: "error",
                        });

                        $(tag).val(response[1]);
                    }
                }
            });
        }

        function checkCart() {
            $.ajax({
                url: "{{ route('home.cart.checkCartAjax') }}",
                type: "POST",
                dataType: "json",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    if (response[0] == 1) {
                        window.location.href = "{{ route('home.checkout') }}";
                    }
                    if (response[0] == 0) {
                        swal({
                            title: 'خطا',
                            text: response[1],
                            icon: 'error',
                            timer: '4000'
                        })
                        setTimeout(function () {
                            window.location.reload();
                        }, 4000)
                    }
                }
            });
        }

    </script>
@endsection

@section('content')
    <!-- main -->
    <main class="cart-page default space-top-30">
        <div class="container">
            @if(count($carts)>0)
            <div class="row">
                <div class="col-12 text-center">
                    <ul class="order-steps">
                        <li>
                            <a href="{{ route('home.cart') }}" class="active">
                                <span>سبدخرید</span>
                            </a>
                        </li>
                        <li  class="active">
                            <a href="shopping-payment.html">
                                <span>روش ارسال</span>
                            </a>
                        </li>
                        <li >
                            <a href="successful-payment.html" >
                                <span>روش پرداخت</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="cart_content col-xl-12 col-lg-12 col-md-12">
                    <header class="card-header">
                        <h3 class="card-title"><span>سبد خرید شما</span></h3>
                    </header>
                    <div class="table-responsive default">
                        <table class="table table-striped">
                            <thead>
                            <tr class="text-center">
                                <th class="product-name"><span>تصویر</span></th>
                                <th class="product-name"><span>عنوان</span></th>
                                <th>اقلام افزوده</th>
                                <th class="product-price"><span>قیمت</span></th>
                                <th class="product-quantity"><span>تعداد</span></th>
                                <th class="product-subtotal"><span>جمع</span></th>
                            </tr>
                            </thead>
                            <tbody>
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
                                <tr class="cart_item">
                                    <td class="product-thumbnail">
                                        <div class="position-relative">
                                            <a href="{{ route('home.product',['alias'=>$cart->Product->alias]) }}">
                                                <figure>
                                                    <img
                                                        src="{{ imageExist(env('PRODUCT_IMAGES_THUMBNAIL_UPLOAD_PATH'),$cart->Product->primary_image) }}"
                                                        alt="product"
                                                        width="300" height="338">
                                                </figure>
                                            </a>
                                            <i onclick="remove_cart({{ $cart->id }})"
                                               class="fas fa-times"></i>
                                        </div>
                                    </td>
                                    <td class="product-name">
                                        <a href="{{ route('home.product',['alias'=>$cart->Product->alias]) }}">
                                            {{ $cart->Product->name }}
                                            <br>
                                            {{ isset($cart->AttributeValues->name) ? $cart->AttributeValues->name : '' }}
                                            <br>
                                            {{ isset($cart->Color->name) ? $cart->Color->name : '' }}
                                            <br>
                                        </a>
                                    </td>
                                    <td class="product-name">
                                        <a href="product-default.html">
                                            @if($cart->option_ids!=null)
                                                @if(product_price_for_user_normal($cart->product_id,$cart->product_attr_variation_id)[1]!=0)
                                                    <br>
                                                @endif
                                                @foreach($cart->option_ids as $option)
                                                    <br>{{ \App\Models\ProductOption::where('id',$option)->first()->VariationValue->name }}
                                                @endforeach
                                            @else
                                                -
                                            @endif
                                        </a>
                                    </td>
                                    <td class="product-price">
                                    <span class="amount">
                                        {{ number_format(product_price_for_user_normal($cart->product_id,$cart->product_attr_variation_id)[2]) }} تومان
                                        @if(product_price_for_user_normal($cart->product_id,$cart->product_attr_variation_id)[1]!=0)
                                            <br>
                                            <span class="input-error-validation">
                                           تخفیف {{ number_format(product_price_for_user_normal($cart->product_id,$cart->product_attr_variation_id)[1]) }}%
                                        </span>
                                        @endif
                                        @if($cart->option_ids!=null)
                                            @foreach($cart->option_ids as $option)
                                                <br>
                                                + {{ number_format(\App\Models\ProductOption::where('id',$option)->first()->price).' تومان ' }}
                                            @endforeach
                                        @endif
                                    </span>
                                    </td>
                                    <td>
                                        <input class="text-center number-input"
                                               onchange="updateQuantity({{ $cart->id }},this)"
                                               id="quantity_{{  $cart->id }}" type="number" min="1" max="100000"
                                               value="{{ $cart->quantity }}">
                                    </td>
                                    <td class="product-subtotal">
                                    <span class="amount">
                                        {{ number_format(calculateCartProductPrice(product_price_for_user_normal($cart->product_id,$cart->product_attr_variation_id)[2],$cart->option_ids)*$cart->quantity) }}
                                          تومان
                                    </span>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="cart-page-content col-xl-12 col-lg-12 col-md-12">
                    <div class="row cart_details">

                        <div class="cart-page-content col-xl-8 col-lg-7 col-md-7 ">
                            <button type="button" onclick="remove_carts()" class="btn btn-second-masai">خالی کردن سبد خرید</button>
                            <div class="text_details">
                                <p>
                                    ارسال رایگان برای سفارش‌های بالای 1 میلیون و 400 هزار تومان
                                </p>
                                <p>
                                    افزودن کالا به سبد خرید به معنی رزرو آن نیست با توجه به محدودیت موجودی سبد خود را
                                    ثبت و خرید را تکمیل کنید.
                                </p>
                            </div>
                        </div>
                        <div class="cart-page-aside col-xl-4 col-lg-5 col-md-5 divider_details">
                                <div class="cart-summary mb-4">
                                    <h3 class="cart-title text-uppercase text-center mb-0">خلاصه سبد خرید </h3>
                                    <hr>
                                    <div class="cart-subtotal d-flex align-items-center justify-content-between">
                                        <label class="ls-25">کل سبد خرید </label>
                                        <span>{{ number_format(calculateCartPrice()['original_price']) }} تومان</span>
                                    </div>
                                    <hr>
                                    <div class="order-total d-flex justify-content-between align-items-center">
                                        <label>تخفیف</label>
                                        <span
                                            class="ls-50">{{ number_format(calculateCartPrice()['total_sale']) }} تومان</span>
                                    </div>
                                    <hr>
                                    <div class="order-total d-flex justify-content-between align-items-center">
                                        <label>مبلغ کد تخفیف</label>
                                        <span
                                            class="ls-50">{{ number_format( session()->get('coupon.amount') ) }} تومان</span>
                                    </div>
                                    <hr>
                                    <div class="order-total d-flex justify-content-between align-items-center">
                                        <label>جمع کل</label>
                                        <span class="ls-50">{{ number_format(calculateCartPrice()['sale_price']- session()->get('coupon.amount')) }} تومان</span>
                                    </div>
                                    <button onclick="checkCart()"
                                            class="btn btn-block btn-dark btn-icon-right btn-rounded  btn-checkout mt-3">
                                        تایید و ادامه
                                        <i class="w-icon-long-arrow-left"></i></button>
                                </div>
                        </div>
                    </div>
                </div>

            </div>
            @else
            <div class="row">
                <div class="col-12 text-center">
                    سبد خرید شما خالی می باشد
                </div>
            </div>
            @endif
        </div>

    </main>
    <!-- main -->
@endsection
