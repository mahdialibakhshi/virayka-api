@extends('home.layouts.index')

@section('title')
    انتخاب آدرس
@endsection

@section('description')

@endsection

@section('keywords')

@endsection

@section('script')
    <script>
        $(document).ready(function () {
            $('#alopeyk_location').val('');
            $('#dayNameInput').val('');
            $('#send_time_select').val('');
            $('select').removeClass('nice-select');
            $('select').show();
            $('div .nice-select').remove();
            // add delivery price to payment box
            add_price_to_payment_box();
        })
        $('#address-select').change(function () {
            $('#address-input').val($(this).val());
        });
        $('.province-select').change(function () {

            var provinceID = $(this).val();

            if (provinceID) {
                $.ajax({
                    type: "GET",
                    url: "{{ url('/get-province-cities-list') }}?province_id=" + provinceID,
                    success: function (res) {
                        if (res) {
                            $(".city-select").empty();
                            $.each(res, function (key, city) {
                                $(".city-select").append('<option value="' + city.id + '">' +
                                    city.name + '</option>');
                            });
                            $(".city-select .list").show();
                        } else {
                            $(".city-select").empty();
                        }
                    }
                });
            } else {
                $(".city-select").empty();
            }
        });
        $('#createNewAddress').click(function () {
            $('#collapseAddAddress').slideToggle();
        })


        function add_price_to_payment_box() {
            let delivery_method_selected = $('#selectDeliveryItems').find('.km-active');
            let price = delivery_method_selected.find('.priceIn').text();
            let send_method = delivery_method_selected.find('.priceIn').find('.send_method').val();
            price = price.replaceAll(',', '');
            price = price.replaceAll(' ', '');
            price = price.replaceAll('تومان', '');
            if (number_format(price) == 0) {
                $('#calculate_delivery_price').text('پس کرایه');
                price = 0;
            } else {
                $('#calculate_delivery_price').text(number_format(parseInt(price)) + ' تومان ');
            }
            let total_payment = {{ calculateCartPrice()['sale_price']- session()->get('coupon.amount') }};
            total_payment = parseInt(total_payment) + parseInt(price);
            $('#total_payment').text(number_format(total_payment) + ' تومان ');
            if (send_method == 1) {
                let total_payment = {{ calculateCartPrice()['sale_price']- session()->get('coupon.amount') }};
                $('#calculate_delivery_price').text('تیپاکس(پس کرایه)');
                $('#total_payment').text(number_format(total_payment) + ' تومان ')
            }
            if (send_method == 5) {
                let total_payment = {{ calculateCartPrice()['sale_price']- session()->get('coupon.amount') }};
                $('#calculate_delivery_price').text('تحویل حضوری');
                $('#total_payment').text(number_format(total_payment) + ' تومان ')
            }
        }

        function selectAddress(tag, address_id) {
            $('.km-address-style').removeClass('km-active');
            $(tag).addClass('km-active');
            $('#address_selected_id').val(address_id);
            $.ajax({
                url: "{{ route('home.checkout_calculate_delivery') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    address_id: address_id,
                },
                type: "POST",
                dataType: "json",
                success: function (msg) {
                    if (msg) {
                        if (msg[0] == 1) {
                            $('#selectDeliveryItems').html(msg[1]);
                            $('#delivery_method_selected_id').html(msg[2]);
                        }
                        if (msg[3]) {
                            $('#delivery_time').removeClass('d-none');
                        } else {
                            $('#delivery_time').addClass('d-none');
                        }
                    }
                }
            })
        }

        function selectDeliveryMethod(tag, delivery_selected_id) {
            $('.km-delivery-type-style').removeClass('km-active');
            $(tag).addClass('km-active');
            $('#delivery_method_selected_id').val(delivery_selected_id);
            if (delivery_selected_id == 3) {
                $('#MapModal').modal('show');
                //show Map
                var map_token = '{{ \App\Models\AlopeykConfig::first()->neshan_token }}';
                setTimeout(function () {
                    var location = $('[name="alopeyk_location"]').val();
                    if (location.length > 0) {
                        var center = [location.split('-')[0], location.split('-')[1]];
                    } else {
                        var center = [35.699739, 51.338097];
                    }
                    var map = new L.Map('map', {
                        key: map_token,
                        maptype: 'dreamy',
                        poi: true,
                        traffic: false,
                        center: center,
                        zoom: 14
                    });
                    var marker;
                    if (location.length > 0) {
                        location = location.split('-');
                        console.log(location);
                        marker = new L.Marker([location[0], location[1]]).addTo(map); // set
                    }
                    map.on('click', function (e) {
                        if (marker) { // check
                            map.removeLayer(marker); // remove
                        }
                        marker = new L.Marker([e.latlng.lat, e.latlng.lng]).addTo(map); // set
                        $('[name="alopeyk_location"]').val(e.latlng.lat + '-' + e.latlng.lng);
                        console.log(marker.getLatLng());
                    });
                }, 1000);
            }
            $.ajax({
                url: "{{ route('home.select_delivery_method') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    delivery_selected_id: delivery_selected_id,
                },
                type: "POST",
                dataType: "json",
                success: function (msg) {
                    if (msg) {
                        if (msg[0] == 1) {
                            if (msg[1]) {
                                $('#delivery_time').removeClass('d-none');
                            } else {
                                $('#delivery_time').addClass('d-none');
                            }
                        }
                    }
                }
            })
            add_price_to_payment_box();
        }

        function getAlopeykPrice() {
            let alopeyk_location = $('#alopeyk_location').val();
            $.ajax({
                url: "{{ route('home.calculateAloPeykPrice') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    alopeyk_location: alopeyk_location,
                },
                type: "POST",
                dataType: "json",
                success: function (msg) {
                    if (msg) {
                        if (msg[0] == 0) {
                            swal({
                                title: 'دقت کنید',
                                text: msg[1],
                                icon: 'warning',
                                timer: 3000,
                            })
                        }
                        if (msg[0] == 1) {
                            if (msg[1]) {
                                $('#MapModal').modal('hide');
                                $('.alopeyk_price').text(msg[1]);
                            } else {
                                $('#delivery_time').addClass('d-none');
                            }
                            add_price_to_payment_box();
                        }
                    }
                }
            })
        }

        function selectDayName(tag, day) {
            $('.km-item').removeClass('km-active');
            $(tag).addClass('km-active');
            $('#dayNameInput').val(day);
        }

        function selectSendTime(tag, time) {
            $('.hours').removeClass('km-active');
            $(tag).addClass('km-active');
            $('#send_time_select').val(time);
        }

        function submitCheckOut(e, tag) {
            e.preventDefault();
            let address_id = $('#address_selected_id').val();
            let delivery_method_selected_id = $('#delivery_method_selected_id').val();
            let dayNameInput = $('#dayNameInput').val();
            let send_time_select = $('#send_time_select').val();
            let alopeyk_location = $('#alopeyk_location').val();
            $.ajax({
                url: "{{ route('home.checkoutSaveStep1') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    address_id: address_id,
                    delivery_method_selected_id: delivery_method_selected_id,
                    dayNameInput: dayNameInput,
                    send_time_select: send_time_select,
                    alopeyk_location: alopeyk_location,
                },
                type: "POST",
                dataType: "json",
                success: function (msg) {
                    if (msg) {
                        if (msg[0] == 0) {
                            swal({
                                title: 'دقت کنید',
                                text: msg[1],
                                icon: 'warning',
                                timer: 3000,
                            })
                        }
                        if (msg[0] == 1) {
                            swal({
                                text: msg[1],
                                icon: 'success',
                                timer: 1500,
                            })
                            setTimeout(function () {
                                location.href = "{{ route('home.checkout.preview') }}"
                            }, 1500)
                        }
                        if (msg[0] == 2) {
                            swal({
                                text: msg[1],
                                icon: 'warning',
                                timer: 3000,
                            });
                            $('#MapModal').modal('show');
                        }
                        if (msg[0] == 3) {
                            $('#modal_address').text(msg[1]);
                            $('#modal_address_id').val(address_id);
                            $('#PostalCodeModal').modal('show');
                        }
                    }
                }
            })
        }

        function AddPostalCodeToAddress() {
            $('#error_postalCode').hide();
            let address_id = $('#modal_address_id').val();
            let modal_postalCode = $('#modal_postalCode').val();
            $.ajax({
                url: "{{ route('home.AddPostalCodeToAddress') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    address_id: address_id,
                    modal_postalCode: modal_postalCode,
                },
                type: "POST",
                dataType: "json",
                success: function (msg) {
                    if (msg[0] === 0) {
                        $('#error_postalCode').show();
                        $('#error_postalCode').text(msg[1]);
                    }
                    if (msg[0] === 1) {
                        $('#PostalCodeModal').modal('hide');
                        swal({
                            text: msg[1],
                            icon: 'success',
                            timer: 1500,
                        })
                        setTimeout(function () {
                            location.href = "{{ route('home.checkout.preview') }}"
                        }, 1500)
                    }
                }
            })
        }

    </script>
    {{--    //login script--}}
    <script>
        $('.loginWithSMS').click(function () {
            $('.DefaultLogin').hide();
            $('.SMSLoginBox').show();
        })
        $('.SwitchToDefaultLogin').click(function () {
            $('.SMSLoginBox').hide();
            $('.DefaultLogin').show();
        })

        $('#checkOTPForm').hide();
        $('#resendOTPButton').hide();

        //ready to Send Code Ajax
        let login_token;
        $('#loginOTPForm').submit(function (event) {
            var cellphone = $('#cellphoneInput').val();
            console.log(cellphone);
            event.preventDefault();
            $.post("{{ url('/smsLogin') }}", {
                '_token': "{{ csrf_token() }}",
                'cellphone': cellphone
            }, function (response, status) {
                console.log(response, status);
                login_token = response.login_token;
                swal({
                    icon: 'success',
                    text: 'رمز یکبار مصرف برای شما ارسال شد',
                    timer: 2000
                });
                $('#loginOTPForm').fadeOut();
                $('#checkOTPForm').fadeIn();
                timer();

            }).fail(function (response) {
                console.log(response.responseJSON);
                $('#cellphoneInput').addClass('mb-1');
                $('#cellphoneInputError').fadeIn()
                $('#cellphoneInputErrorText').html(response.responseJSON.errors.cellphone[0])
            })
        })
        //ready to check Code and login Ajax
        $('#checkOTPForm').submit(function (event) {
            let otp = $('#checkOTPInput').val();
            event.preventDefault();
            $.post("{{ url('/check-otp') }}", {
                '_token': "{{ csrf_token() }}",
                'otp': otp,
                'login_token': login_token
            }, function (response, status) {
                swal({
                    icon: 'success',
                    text: 'ورود با موفقیت انجام شد',
                    timer: 2000
                });
                $(location).attr('href', '{{ route('home.checkout') }}');

            }).fail(function (response) {
                // console.log(response.responseJSON);
                $('#checkOTPInput').addClass('mb-1');
                $('#checkOTPInputError').fadeIn()
                $('#checkOTPInputErrorText').html(response.responseJSON.errors.otp[0])
            })
        });

        //resend Code
        $('#resendOTPButton').click(function (event) {
            event.preventDefault();
            $.post("{{ url('/resend-otp') }}", {
                '_token': "{{ csrf_token() }}",
                'login_token': login_token
            }, function (response, status) {
                console.log(response, status);
                login_token = response.login_token;
                swal({
                    icon: 'success',
                    text: 'رمز یکبار مصرف برای شما ارسال شد',
                    timer: 2000
                });
                $('#resendOTPButton').fadeOut();
                timer();
                $('#resendOTPTime').fadeIn();
                $('#resendCodeDiv').fadeIn();

            }).fail(function (response) {
                console.log(response.responseJSON);
                swal({
                    icon: 'error',
                    text: 'مشکل در ازسال مجدد رمز یکبار مصرف.دوباره تلاش کنید',
                    timer: 2000
                });
            })
        })


        //timer for resend Code
        function timer() {
            let time = "2:01";
            let interval = setInterval(function () {
                let countdown = time.split(':');
                let minutes = parseInt(countdown[0], 10);
                let seconds = parseInt(countdown[1], 10);
                --seconds;
                minutes = (seconds < 0) ? --minutes : minutes;
                if (minutes < 0) {
                    clearInterval(interval);
                    $('#resendOTPTime').hide();
                    $('#resendCodeDiv').hide();
                    $('#resendOTPButton').fadeIn();
                }
                ;
                seconds = (seconds < 0) ? 59 : seconds;
                seconds = (seconds < 10) ? '0' + seconds : seconds;
                //minutes = (minutes < 10) ?  minutes : minutes;
                $('#resendOTPTime').html(minutes + ':' + seconds);
                time = minutes + ':' + seconds;
            }, 1000);
        }


    </script>

@endsection

@section('style')
    <style>
        .show {
            display: block;
        }

        .alert_delivery_price {
            display: none;
        }

        #delivery_price {
            display: none;
        }

        .m-0 {
            margin: 0 !important;
        }

        .cursor-pointer {
            cursor: pointer !important;
        }

        .nice-select {
            width: 100%;
        }

        .deActive {
            background-color: #e6e6e6;
        }

        #collapseAddAddress {
            display: none;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('home/css/checkout.css') }}">
@endsection

@section('content')
<!-- main-shopping -->
@auth
    <section class="p-5">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12 text-center">
                    <ul class="order-steps">
                        <li>
                            <a href="{{ route('home.cart') }}"  class="active">
                                <span>سبدخرید</span>
                            </a>
                        </li>
                        <li  class="active">
                            <a class="active active2">
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
            </div>
            <div class="row my-3">
                <div class="col-12">
                    <div class="km-title-style-theme">
                        <h1 class="km-title">انتخاب آدرس تحویل سفارش</h1>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-lg-8">
                    <div class="row">
                        <div class="col-12">
                            <div class="justDesktop">
                                <div class="km-box-style2 after-clear d-lg-flex flex-wrap">
                                    @foreach($addresses as $key=>$address)
                                        <div onclick="selectAddress(this,{{ $address->id }})"
                                             class="km-address-style m-1 {{ $key==0 ? 'km-active' : '' }}">
                                            @if($key==0)
                                                <div class="km-active-btn km-btn km-theme-3 defaultActive">پیش فرض
                                                </div>
                                            @endif
                                            <div class="km-title">
                                                <div class="firstTitle">
                                                    <div class="title">عنوان:</div>
                                                    <div class="title2">{{ $address->title }}</div>
                                                </div>
                                            </div>
                                            <div class="km-title">
                                                <div class="firstTitle">
                                                    <div class="title">گیرنده:</div>
                                                    <div class="title2">{{ $address->User->name }}</div>
                                                </div>
                                            </div>
                                            <div class="km-details after-clear">
                                                <div class="km-title">
                                                    <div class="firstTitle">
                                                        <div class="title">
                                                            استان / شهر :
                                                        </div>
                                                        <div class="title2 address">
                                                            {{ province_name($address->province_id).' / '.city_name($address->city_id) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="km-title">
                                                    <div class="firstTitle">
                                                        <div class="title">
                                                            شماره تماس :
                                                        </div>
                                                        <div class="title2">{{ $address->cellphone }}</div>
                                                    </div>
                                                </div>
                                                <div class="km-title">
                                                    <div class="firstTitle">
                                                        <div class="title">
                                                            آدرس :
                                                        </div>
                                                        <div class="title2 address">
                                                            {{ $address->address }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="km-title">
                                                    <div class="firstTitle">
                                                        <div class="title">
                                                            کد پستی :
                                                        </div>
                                                        <div class="title2 address">
                                                            {{ $address->postal_code==null ? '-' : $address->postal_code }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    <input id="address_selected_id" type="hidden" name="address_selected_id"
                                           value="{{ $address_id }}">
                                    <div class="km-address-style-add km-address-style m-1">
                                        <button id="createNewAddress"
                                                class="btn btn-second-masai btn-sm" type="submit">
                                            <i class="fa fa-plus"></i>
                                            ایجاد آدرس جدید
                                        </button>
                                    </div>
                                </div>
                                <div class="km-box-style2 d-flex flex-wrap after-clear m-1 mb-3">
                                    <div id="collapseAddAddress" class="collapse collapse-address-create-content"
                                         style="{{ count($errors->addressStore) > 0 ? 'display:block' : '' }}">

                                        <form action="{{ route('home.addresses.store') }}" method="POST">
                                            @csrf
                                            <div class="row">
                                                @if($user->name==null)
                                                    <div class="tax-select col-lg-6 col-md-6 mb-3">
                                                        <label>
                                                            نام و نام‌خانوادگی *
                                                        </label>
                                                        <input class="form-control" type="text" name="name"
                                                               value="{{ old('name') }}">
                                                        @error('name', 'addressStore')
                                                        <p class="input-error-validation">
                                                            <strong>{{ $message }}</strong>
                                                        </p>
                                                        @enderror
                                                    </div>
                                                @endif
                                                <div class="tax-select col-lg-6 col-md-6 mb-3">
                                                    <label>
                                                        عنوان آدرس*(منزل,محل کار و ...)
                                                    </label>
                                                    <input class="form-control" type="text" name="title"
                                                           value="{{ old('title') }}">
                                                    @error('title', 'addressStore')
                                                    <p class="input-error-validation">
                                                        <strong>{{ $message }}</strong>
                                                    </p>
                                                    @enderror
                                                </div>
                                                <div class="tax-select col-lg-6 col-md-6 mb-3">
                                                    <label>
                                                        موبایل *
                                                    </label>
                                                    <input class="form-control" type="text" name="cellphone"
                                                           value="{{ auth()->user()->cellphone }}">
                                                    @error('cellphone', 'addressStore')
                                                    <p class="input-error-validation">
                                                        <strong>{{ $message }}</strong>
                                                    </p>
                                                    @enderror
                                                </div>
                                                <div class="tax-select col-lg-6 col-md-6 mb-3">
                                                    <label>
                                                        استان *
                                                    </label>
                                                    <select class="form-control email s-email s-wid province-select"
                                                            name="province_id">
                                                        <option value="" selected>انتخاب کنید
                                                        </option>
                                                        @foreach ($provinces as $province)
                                                            <option
                                                                value="{{ $province->id }}">{{ $province->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('province_id', 'addressStore')
                                                    <p class="input-error-validation">
                                                        <strong>{{ $message }}</strong>
                                                    </p>
                                                    @enderror
                                                </div>
                                                <div class="tax-select col-lg-6 col-md-6 mb-3">
                                                    <label>
                                                        شهر *
                                                    </label>
                                                    <select class="form-control email s-email s-wid city-select"
                                                            name="city_id">
                                                        <option value="" selected>انتخاب کنید
                                                        </option>
                                                    </select>
                                                    @error('city_id', 'addressStore')
                                                    <p class="input-error-validation">
                                                        <strong>{{ $message }}</strong>
                                                    </p>
                                                    @enderror
                                                </div>
                                                <div class="tax-select col-lg-6 col-md-6 mb-3">
                                                    <label>
                                                        شماره ثابت
                                                    </label>
                                                    <input class="form-control" type="text" name="tel"
                                                           value="{{ old('tel') }}">
                                                    @error('tel', 'addressStore')
                                                    <p class="input-error-validation">
                                                        <strong>{{ $message }}</strong>
                                                    </p>
                                                    @enderror
                                                </div>
                                                <div class="tax-select col-lg-6 col-md-6 mb-3">
                                                    <label>
                                                        کد پستی
                                                    </label>
                                                    <input class="form-control" type="text" name="postal_code"
                                                           value="{{ old('postal_code') }}">
                                                    @error('postal_code', 'addressStore')
                                                    <p class="input-error-validation">
                                                        <strong>{{ $message }}</strong>
                                                    </p>
                                                    @enderror
                                                </div>

                                                <div class="tax-select col-md-12">
                                                    <label>
                                                        نشانی *
                                                    </label>
                                                    <textarea class="form-control" type="text"
                                                              name="address">{{ old('address') }}</textarea>
                                                    @error('address', 'addressStore')
                                                    <p class="input-error-validation">
                                                        <strong>{{ $message }}</strong>
                                                    </p>
                                                    @enderror
                                                </div>

                                                <div class=" col-lg-12 col-md-12 mt-3">

                                                    <button class="btn btn-second-masai btn-outline btn-rounded mt-2"
                                                            type="submit"> ثبت آدرس
                                                    </button>
                                                </div>


                                            </div>

                                        </form>

                                    </div>
                                </div>

                            </div>
                            @if($address_id!=null)
                                <div class="km-box-style2  after-clear">
                                    <div class="kmAddressListContainer" id="kmAddressListContainer">
                                        <div class="km-title-style-new">
                                            <h3 class="km-title">انتخاب شیوه ارسال</h3>
                                        </div>
                                        <div class="km-delivery-type-list">
                                            <input id="delivery_method_selected_id" name="shippingoption"
                                                   type="hidden"
                                                   value="{{ $deliveryMethod[0]->id }}">
                                            <div id="selectDeliveryItems"
                                                 class="d-flex flex-wrap flex-x-spaceBetween">
                                                @foreach($deliveryMethod as $key=>$item)
                                                        <?php
                                                        if ($item->exist_service == true) {
                                                            $onclick = 'onclick="selectDeliveryMethod(this,' . $item->id . ')"';
                                                        } else {
                                                            $onclick = '';
                                                        }
                                                        ?>
                                                    <div
                                                            <?php echo $onclick ?> class="d-md-flex justify-content-between km-delivery-type-style {{ $item->exist_service==true ? '' : 'deActive' }} {{ $key==0 ? 'km-active' : '' }}">
                                                        <input checked="" class="km-value-control none km-active"
                                                               type="radio" value="">
                                                        <div class="d-md-flex flex-y-center">
                                                            <div class="km-img">
                                                                <img alt="پیک موتوری"
                                                                     src="{{ imageExist(env('DELIVERY_METHOD_ICON'),$item->image) }}">
                                                            </div>
                                                            <div class="km-content">
                                                                <div class="km-title">{{ $item->name }}</div>
                                                                <div class="km-description">
                                                                    {{ $item->description }}
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="price">
                                                            <div class="title">هزینه:</div>
                                                            <div
                                                                class="priceIn {{ $item->id==3 ? 'alopeyk_price' : '' }}">
                                                                @if($item->id==4)
                                                                    <input type="hidden" class="send_method"
                                                                           value="1">
                                                                    پس کرایه
                                                                @elseif($item->id==3)
                                                                    انتخاب کنید
                                                                @elseif($item->id==5)
                                                                    <input type="hidden" class="send_method"
                                                                           value="5">
                                                                    0
                                                                @else
                                                                    @if($item->price_for_post==0)
                                                                        پس کرایه
                                                                    @else
                                                                        {{$item->price_for_post==null ? '-' :number_format($item->price_for_post).' تومان ' }}
                                                                    @endif
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <br><br>
                                        </div>
                                    </div>
                                    <div id="delivery_time"
                                         class="km-delivery-time-container {{ $selected_time ? '' : 'd-none' }}"
                                         style="">
                                        <div class="km-title-style-theme">
                                            <h1 class="km-title">انتخاب زمان ارسال</h1>
                                        </div>
                                        <div class="km-delivery-time km-box-style2">
                                            <div class="km-delivery-time-tab">
                                                @foreach($date as $item)
                                                    @if(!in_array(verta($item)->format('l'),$holidaysName))
                                                        <div
                                                            onclick="selectDayName(this,'{{ verta($item)->format('l-d-F') }}')"
                                                            data-val="{{ $item }}"
                                                            class="km-item {{ in_array(verta($item)->format('l'),$holidaysName)  ? '' : 'text-success' }}">
                                                            <div
                                                                class="km-day-name">{{ verta($item)->format('l-d-F') }}</div>
                                                        </div>
                                                    @else
                                                        <div data-val="{{ $item }}"
                                                             class="km-item {{ in_array(verta($item)->format('l'),$holidaysName)  ? '' : 'text-success' }}">
                                                            <div
                                                                class="km-day-name">{{ verta($item)->format('l-d-F') }}</div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                                <input type="hidden" id="dayNameInput">
                                            </div>
                                            <div class="km-delivery-time-contents">
                                                <div class="km-item km-active" km-id="0">
                                                    <div class="km-cart-detail-select">
                                                        @foreach($sent_times as $key=>$item)
                                                            <div onclick="selectSendTime(this,'{{ $item }}')"
                                                                 data-val="{{ $item }}"
                                                                 class="km-detail hours">
                                                                <div
                                                                    class="pretty p-icon p-smooth p-round p-bigger">
                                                                    <input type="radio" name="shippingDate"
                                                                           checked=""
                                                                           value="1400/12/09 12:00" 1400="" 12=""
                                                                           09="">
                                                                    <div class="state p-success-o ">
                                                                        <i class="icon fal "></i>
                                                                        <label>ساعت {{ $item }}</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                        <input type="hidden" id="send_time_select"
                                                               value="{{ $item }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-12">
                                    <div class="form-group col-12">

                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="row">
                        <div class="col-12 mb-20 km-box-style2">
                            <div class="cart-summary">
                                <form class="coupon" action="{{ route('home.coupons.check') }}" method="post">
                                    @csrf
                                    <h5 class="title coupon-title font-weight-bold text-uppercase">
                                        کد تخفیف :
                                    </h5>
                                    <input type="text" class="form-control mb-4" name="couponCode"
                                           placeholder="کد تخفیف را وارد کنید..."/>
                                    @error('couponCode')
                                    <p class="input-error-validation">
                                        <strong>{{ $message }}</strong>
                                    </p>
                                    @enderror
                                    <button class="btn btn-second-masai btn-outline btn-rounded">اعمال کد</button>
                                </form>
                            </div>
                        </div>
                        <div class="col-12 sticky-sidebar-wrapper km-box-style2">
                            <div class="sticky-sidebar">
                                <div class="cart-summary mb-4">
                                    <h3 class="cart-title text-uppercase">خلاصه سبد خرید </h3>
                                    <div class="cart-subtotal d-flex align-items-center justify-content-between">
                                        <label class="ls-25">کل سبد خرید </label>
                                        <span>{{ number_format(calculateCartPrice()['original_price']) }} تومان</span>
                                    </div>
                                    <hr>
                                    <div class="order-total d-flex justify-content-between align-items-center">
                                        <label>تخفیف</label>
                                        <span class="ls-50">{{ number_format(calculateCartPrice()['total_sale']) }} تومان</span>
                                    </div>
                                    <hr>
                                    <div class="order-total d-flex justify-content-between align-items-center">
                                        <label>مبلغ کد تخفیف</label>
                                        <span class="ls-50">{{ number_format( session()->get('coupon.amount') ) }} تومان</span>
                                    </div>
                                    <hr>
                                    <div class="order-total d-flex justify-content-between align-items-center">
                                        <label>هزینه ارسال</label>
                                        <span id="calculate_delivery_price" class="ls-50">-</span>
                                    </div>
                                    <hr>
                                    <div class="order-total d-flex justify-content-between align-items-center">
                                        <label>جمع کل</label>
                                        <span id="total_payment" class="ls-50">{{ number_format(calculateCartPrice()['sale_price']- session()->get('coupon.amount')) }} تومان</span>
                                    </div>
                                    <a href="{{ route('home.cart') }}"
                                       class="btn btn-second-masai btn-outline btn-rounded mt-2">سبد خرید</a>
                                    <a onclick="submitCheckOut(event,this)"
                                       class="btn btn-second-masai btn-outline btn-rounded mt-2">پرداخت</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@else
    <!-- START SECTION SHOP DETAIL -->
    <section class="p-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 mb-4 mb-md-0 DefaultLogin">
                    <div class="heading_s2">
                        <h3 class="text-center">ورود</h3>
                    </div>
                    <form method="post" class="login_form" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group">
                            <a href="{{ route('provider.login',['provider'=>'google']) }}"
                               class="btn btn-main-masai w-100 my-3">ورود
                                با حساب گوگل</a>
                            <button type="button" class="btn btn-main-masai w-100 loginWithSMS">ورود پیامکی</button>
                        </div>
                    </form>
                </div>
                <div class="col-lg-6 col-md-12 col-xl-5 col-12 SMSLoginBox">
                    <div class="contact-form-wrapper">
                        <!-- Start Contact Form -->
                        <div class="axil-contact-form contact-form-style-1 text-right">
                            <div>
                                <a href="{{ route('home.index') }}"><img
                                        src="{{ asset
                                            ('/home/images/Login/Login2.png')}}"
                                        alt=""></a>
                            </div>
                            <form id="loginOTPForm">
                                @csrf
                                <div class="form-group">
                                    <input
                                        class="form-control"
                                        id="cellphoneInput"
                                        type="text"
                                        placeholder="شماره همراه خود را وارد کنید">
                                    <span class="focus-border"></span>

                                    <div id="cellphoneInputError" class="input-error-validation">
                                        <strong id="cellphoneInputErrorText"></strong>
                                    </div>

                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-main-masai w-100 my-3">
                                        <span class="button-text">ورود / ثبت نام با شماره همراه</span>
                                    </button>
                                </div>
                                <a href="{{ route('provider.login',['provider'=>'google']) }}"
                                   class="btn btn-main-masai w-100">ورود
                                    / ثبت نام با حساب گوگل</a>
                            </form>
                            <form id="checkOTPForm">
                                @csrf
                                <div class="form-group">
                                    <input class="form-control"
                                           id="checkOTPInput"
                                           type="text"
                                           placeholder="رمز یکبار مصرف">
                                    <span class="focus-border"></span>

                                    <div id="checkOTPInputError" class="input-error-validation">
                                        <strong id="checkOTPInputErrorText"></strong>
                                    </div>

                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-main-masai w-100 my-3">
                                        <span class="button-text">ورود</span>
                                    </button>
                                    <button id="resendOTPButton" type="submit"
                                            class="btn btn-main-masai w-100 my-3">
                                        <span class="button-text">ارسال مجدد</span>
                                    </button>
                                    <div class="d-flex justify-content-between p-3 align-content-center">
                                        <span id="resendCodeDiv">ارسال مجدد کد </span>
                                        <span id="resendOTPTime"></span>
                                    </div>
                                </div>
                            </form>
                            {{--                                    {!! app('captcha')->render(); !!}--}}
                        </div>
                        <!-- End Contact Form -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END SECTION SHOP DETAIL -->
@endauth

<!-- Modal -->
<div class="modal fade" id="MapModal" tabindex="-1" aria-labelledby="MapModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="MapModalLabel">آدرس تحویل کالا را با دقت انتخاب نمایید</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="map"
                     style="width: 100%; height: 450px; background: rgb(244, 243, 239) none repeat scroll 0% 0%; border: 2px solid rgb(170, 170, 170); position: relative;"
                     class="leaflet-container leaflet-touch leaflet-fade-anim leaflet-grab leaflet-touch-drag leaflet-touch-zoom"
                     tabindex="0"></div>
                <input id="alopeyk_location" type="hidden" class="inputbox wide" name="alopeyk_location"
                       value=""/>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                <button onclick="getAlopeykPrice()" type="button" class="btn btn-primary">ذخیره</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="PostalCodeModal" tabindex="-1" aria-labelledby="PostalCodeModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger text-center">در روش ارسال با پست پیشتاز وارد کردن کد پستی الزامی
                    است
                </div>
                <p id="modal_address"></p>
                <label class="mb-2">کد پستی:</label>
                <input class="form-control form-control-sm" id="modal_postalCode" value="">
                <input type="hidden" id="modal_address_id" value="">
                <span class="input-error-validation" id="error_postalCode"></span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                <button onclick="AddPostalCodeToAddress()" type="button" class="btn btn-primary">ذخیره</button>
            </div>
        </div>
    </div>
</div>

<!-- main-shopping -->
@endsection
