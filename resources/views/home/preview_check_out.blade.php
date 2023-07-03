@extends('home.layouts.index')

@section('title')
    پیش نمایش خرید
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

        .img-thumbnail {
            width: 50px !important;
            height: auto !important;
        }

        .align-center {
            align-items: center !important;
        }

        h5 {
            margin-bottom: 0 !important;
        }

    </style>
@endsection

@section('script')
    <script>
        function WalletUsage() {
            let use_wallet = 0;
            if ($('#wallet_input').is(':checked')) {
                use_wallet = $('#wallet_input').val();
            }
            $.ajax({
                url: "{{ route('home.checkout.WalletUsage') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    use_wallet: use_wallet,
                },
                method: "post",
                dataType: "json",
                beforeSend: function () {

                },
                success: function (msg) {
                    if (msg[0] == 1) {
                        $('#summery_cart').html(msg[1]);
                    }
                }
            })
        }

        $('#paymentBtn').click(function () {
            check_limit_order();
        })

        $(document).ready(function () {
            let use_wallet = "{{ session()->get('use_wallet') }}";
            if (use_wallet == 1) {
                WalletUsage()
            }
        })

        function check_limit_order() {
            $.ajax({
                url: "{{ route('home.checkout.check_limit') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                },
                method: "post",
                dataType: "json",
                beforeSend: function () {

                },
                success: function (msg) {
                    let status = msg['status'];
                    if (status == 0) {
                        swal({
                            title: 'دقت کنید',
                            icon: 'warning',
                            text: msg['message'],
                        });
                        setTimeout(function () {
                            window.location.href = msg['redirect'];
                        }, 3000);
                    }
                    if (status == 1) {
                        check_national_code();
                    }
                }
            })
        }

        function check_national_code() {
            let national_code_modal = $('#national_code_modal');
            $.ajax({
                url: "{{ route('home.checkout.check_national_code') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    user_id: "{{ $address->User->id }}",
                },
                method: "post",
                dataType: "json",
                beforeSend: function () {

                },
                success: function (msg) {
                    let status = msg['status'];
                    console.log(msg);
                    if (status == 0) {
                        swal({
                            title: 'دقت کنید',
                            icon: 'warning',
                            text: msg['message'],
                        });
                        setTimeout(function () {
                            window.location.href = msg['redirect'];
                        }, 3000);
                    }
                    if (status == 2) {
                        national_code_modal.modal('show');
                    }
                    if (status == 1) {
                        check_accept_shop_roles();
                    }
                }
            })
        }

        function check_accept_shop_roles() {
            let role_is_checked=$('#accept_roles').prop('checked');
            if(role_is_checked){
                swal({
                    text: 'در حال هدایت به درگاه بانک...',
                });
                setTimeout(function () {
                    $('#payment_form').submit();
                }, 3000);
            }else {
                alert('پذیرش شرایط و قوانین فروشگاه الزامی است');
            }
        }

        function add_national_code() {
            let national_code = $('#national_code').val();
            $.ajax({
                url: "{{ route('home.checkout.add_national_code') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    national_code: national_code,
                    user_id: "{{ $address->User->id }}",
                },
                method: "post",
                dataType: "json",
                beforeSend: function () {

                },
                success: function (msg) {
                    if (msg[0] == 1) {
                        let national_code_modal = $('#national_code_modal');
                        national_code_modal.modal('hide');
                        swal({
                            title: 'با تشکر',
                            icon: 'success',
                            text: 'کد ملی شما با موفقیت ثبت شد',
                        });
                        setTimeout(function () {
                            check_accept_shop_roles();
                        }, 3000);

                    }
                },
                error: function (response) {
                    $.each(response.responseJSON.errors, function (i, v) {
                        let p = `<p class="input-error-validation">${v[0]}</p>`;
                        $('#national_code_error').html(p);
                    })
                }
            })
        }
    </script>
@endsection

@section('content')

    <!-- Start of Main -->
    <main class="main cart">
        <!-- Start of PageContent -->
        <div class="page-content mt-3">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center mb-5">
                        <ul class="order-steps">
                            <li>
                                <a href="{{ route('home.cart') }}"  class="active">
                                    <span>سبدخرید</span>
                                </a>
                            </li>
                            <li  class="active">
                                <a href="{{ route('home.checkout') }}" class="active active2">
                                    <span>روش ارسال</span>
                                </a>
                            </li>
                            <li  class="active">
                                <a class="active active2">
                                    <span>روش پرداخت</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row gutter-lg mb-10">
                    <div class="col-lg-8 pr-lg-4 mb-6">
                        <div class="row km-box-style2 base_border mb-3">
                            <div class="col-12">
                                <table class="shop-table cart-table table-striped text-center">
                                    <thead>
                                    <tr>
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
                                        <tr>
                                            <td class="product-thumbnail">
                                                <div class="p-relative">
                                                    <a href="{{ route('home.product',['alias'=>$cart->Product->alias]) }}">
                                                        <figure class="m-0">
                                                            <img
                                                                src="{{ imageExist(env('PRODUCT_IMAGES_THUMBNAIL_UPLOAD_PATH'),$cart->Product->primary_image) }}"
                                                                alt="product"
                                                                width="300">
                                                        </figure>
                                                    </a>
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
                                            <td class="text-center">
                                                {{ $cart->quantity }}
                                            </td>
                                            <td class="product-subtotal">
                                    <span class="amount">
                                        {{ number_format(calculateCartProductPrice(product_price_for_user_normal($cart->product_id,$cart->product_attr_variation_id)[2],$cart->option_ids)*$cart->quantity) }}
                                          تومان </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @if($amount!=0)
                            <div class="row km-box-style2 base_border">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-center">
                                        <h5 class="title coupon-title font-weight-bold text-uppercase">استفاده از کیف
                                            پول
                                        </h5>
                                        <div class="d-flex align-center">
                                <span>
                                    موجودی کیف پول : {{ number_format($amount) }} تومان
                                </span>
                                            <input id="wallet_input"
                                                   {{ session()->get('use_wallet')==1 ? 'checked' : '' }} onchange="WalletUsage()"
                                                   class="ml-3" type="checkbox" value="1"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="row km-box-style2 base_border">
                            <div class="col-12">
                                <div class="card-body cart-summary-wrap bg-white">
                                    <h4>اطلاعات گیرنده:</h4>
                                    <hr>
                                    <div class="d-md-flex justify-content-between mb-1">
                                <span class="receive_information">
                                            نام و نام‌خانوادگی :
                                            <span>{{ $address->User->name }}</span>
                                </span>
                                        <span class="receive_information">
                                            شماره همراه :
                                            <span>{{ $address->cellphone }}</span>
                                </span>
                                    </div>
                                    <div class="d-md-flex justify-content-between mb-1">
                                <span class="receive_information">
                                        شماره ثابت :
                                        <span>{{ $address->tell==null ? '-' : $address->tell }}</span>
                                    </span>
                                        <span class="receive_information">
                                        کد پستی :
                                        <span>{{ $address->postal_code==null ? '-' : $address->postal_code }}</span>
                                    </span>
                                    </div>
                                    <div class="d-md-flex justify-content-between mb-1">
                                <span class="receive_information">
                                            روش ارسال : {{ $delivery_method->name.' / '.$delivery_method->description }}
                                        </span>
                                        @if($delivery_method->id!=5)
                                            <span class="receive_information">
زمان و تاریخ دریافت کالا : {{ $set_time ?  $delivery_day.' / ساعت :'.$delivery_time : '-' }}
                                        </span>
                                        @endif
                                    </div>
                                    <div class="d-md-flex justify-content-between mb-1">
                                <span class="receive_information">
                                آدرس : {{ province_name($address->province_id).' / '.city_name($address->city_id).' - '.$address->address }}
                                </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="row">
                            <div class="col-12">
                                <div id="summery_cart" class="sticky-sidebar km-box-style2 base_border">
                                    <div class="cart-summary mb-4">
                                        <h3 class="cart-title text-uppercase text-center">خلاصه سبد خرید </h3>
                                        <hr>
                                        <div class="cart-subtotal d-flex align-items-center justify-content-between">
                                            <label class="ls-25">کل سبد خرید </label>
                                            <span>{{ number_format(summery_cart()['original_price']) }} تومان </span>
                                        </div>
                                         <hr>
                                        <div class="order-total d-flex justify-content-between align-items-center">
                                            <label>تخفیف</label>
                                            <span
                                                class="ls-50">{{ number_format(summery_cart()['total_sale']) }} تومان </span>
                                        </div>
                                         <hr>
                                        <div class="order-total d-flex justify-content-between align-items-center">
                                            <label>مبلغ کد تخفیف</label>
                                            <span class="ls-50">{{ number_format(summery_cart()['coupon_amount']) }} تومان </span>
                                        </div>
                                         <hr>
                                        <div class="order-total d-flex justify-content-between align-items-center">
                                            <label>هزینه ارسال</label>
                                            <span>{{ intval(summery_cart()['delivery_price'])==0 ? summery_cart()['delivery_price'] : number_format(summery_cart()['delivery_price']).' تومان ' }}</span>
                                        </div>
                                        <hr class="divider-black">
                                        <div
                                            class="order-total text-black d-flex justify-content-between align-items-center">
                                            <label>مبلغ قابل پرداخت</label>
                                            <span
                                                class="ls-50">{{ number_format(summery_cart()['payment']) }} تومان </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="cart-summary km-box-style2 base_border mt-3">
                                    <div class="cart-summary-wrap ">
                                        <form id="payment_form" action="{{ route('home.payment') }}" method="POST">
                                            @csrf
                                            <h4>درگاه پرداخت</h4>
                                            <hr>
                                            @foreach($PaymentMethods as $item)
                                                <div
                                                    class="pay-top sin-payment d-flex justify-content-between align-center mb-3">
                                                    <div class="d-flex align-center">
                                                        @if(file_exists(public_path(env('LOGO_UPLOAD_PATH').$item->image))
                         and !is_dir(public_path(env('LOGO_UPLOAD_PATH').$item->image)))
                                                            <img class="img-thumbnail"
                                                                 src="{{ asset(env('LOGO_UPLOAD_PATH').$item->image) }}">
                                                        @else
                                                            <img class="img-thumbnail"
                                                                 src="{{ asset('admin/images/no_image.jpg') }}">
                                                        @endif
                                                        <label class="ml-3"
                                                               for="{{ $item->name }}">{{ $item->description }}</label>
                                                    </div>
                                                    <div class="d-flex justify-content-between align-center">
                                                        <input id="{{ $item->name }}" class="input-radio" type="radio"
                                                               value="{{ $item->name }}"
                                                               checked="checked" name="payment_method">
                                                    </div>
                                                </div>
                                                <hr>
                                            @endforeach
                                            <input type="hidden" id="address-input" name="address_id"
                                                   value="{{ $address->id }}">
                                        </form>
                                    </div>
                                    <div class="pay-top sin-payment d-flex justify-content-between align-center alert alert-info mb-3 ">
                                        <div class="d-flex align-center">
                                            <span>
                                                <a href="page/3" target="_blank" class="text-danger">شرایط و قوانین</a>
                                                فروشگاه را میپذیرم
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-between align-center">
                                            <input id="accept_roles" class="input-radio" type="checkbox"
                                                   value="1" name="accept_roles">
                                        </div>
                                    </div>
                                    <div class="cart-summary-button d-flex justify-content-between">
                                        <a href="{{ route('home.checkout') }}" class="btn btn-second-masai btn-rounded">
                                            ثبت آدرس</a>
                                        <button id="paymentBtn" type="button" class="btn btn-second-masai btn-rounded">
                                            پرداخت
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- End of Main -->
    {{--    //national_code_modal--}}
    <div class="modal fade" id="national_code_modal" tabindex="-1" role="dialog" aria-labelledby="add_product_attribute"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="error" id="modal_error"></div>
                    <div class="mt-2">
                        <label class="mt-2 mb-2">کد ملی خود را وارد نمایید :</label>
                        <input type="text" name="national_code" id="national_code"
                               class="form-control form-control-sm mt-3">
                    </div>
                    <div id="national_code_error">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">بستن</button>
                    <button type="submit" class="btn btn-second-masai btn-rounded ml-3" onclick="add_national_code()">افزودن
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
