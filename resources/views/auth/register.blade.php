@extends('home.layouts.index')
{{--//title--}}
@section('title')
    افراسنتر - ثبت نام
@endsection
{{--//description--}}
@section('description')
    {{--توضیحات متا--}}
@endsection
{{--//style--}}
@section('myCss')
    <style>
        .SMSLoginBox{
            display: none;
        }
        .text-right{
            text-align: right !important;
        }
        .mr-0{
            margin-right: 0 !important;
        }
        #loginOTPForm{
            border: 2px solid #eee;
            padding: 20px;
        }
        .input-error-validation{
            font-size: 9pt;
            color: red;
        }
        #checkOTPForm{
            border: 2px solid #eee;
            padding: 20px;
        }
        #resendOTPTime{
            border: 2px solid #eee;
            padding: 5px;
            border-radius: 50%;
        }
    </style>
@endsection
{{--javaScript--}}
@section('MyJs')
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
                login_token=response.login_token;
                swal({
                    icon : 'success',
                    text : 'رمز یکبار مصرف برای شما ارسال شد',
                    timer : 2000
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
            let otp=$('#checkOTPInput').val();
            event.preventDefault();
            $.post("{{ url('/check-otp') }}", {
                '_token': "{{ csrf_token() }}",
                'otp': otp,
                'login_token': login_token
            }, function (response, status) {
                swal({
                    icon : 'success',
                    text : 'ثبت نام با موفقیت انجام شد',
                    timer : 2000
                });
                $(location).attr('href','{{ route('home.redirects') }}');

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
                login_token=response.login_token;
                swal({
                    icon : 'success',
                    text : 'رمز یکبار مصرف برای شما ارسال شد',
                    timer : 2000
                });
                $('#resendOTPButton').fadeOut();
                timer();
                $('#resendOTPTime').fadeIn();
                $('#resendCodeDiv').fadeIn();


            }).fail(function (response) {
                console.log(response.responseJSON);
                swal({
                    icon : 'error',
                    text : 'مشکل در ازسال مجدد رمز یکبار مصرف.دوباره تلاش کنید',
                    timer : 2000
                });
            })
        })


        //timer for resend Code
        function timer() {
            let time = "1:01";
            let interval = setInterval(function() {
                let countdown = time.split(':');
                let minutes = parseInt(countdown[0], 10);
                let seconds = parseInt(countdown[1], 10);
                --seconds;
                minutes = (seconds < 0) ? --minutes : minutes;
                if (minutes < 0) {
                    clearInterval(interval);
                    $('#resendOTPTime').hide();
                    $('#resendOTPButton').fadeIn();
                    $('#resendCodeDiv').hide();

                };
                seconds = (seconds < 0) ? 59 : seconds;
                seconds = (seconds < 10) ? '0' + seconds : seconds;
                //minutes = (minutes < 10) ?  minutes : minutes;
                $('#resendOTPTime').html(minutes + ':' + seconds);
                time = minutes + ':' + seconds;
            }, 1000);
        }




    </script>
@endsection
@section('content')
    <!-- START SECTION BANNER -->
    <section class="bg_light_yellow breadcrumb_section background_bg bg_fixed bg_size_contain" data-img-src="assets/images/breadcrumb_bg.png">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-sm-12 text-center">
                    <div class="page-title">
                        <h1>ثبت نام</h1>
                    </div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a href="#">صفحه اصلی</a></li>
                            <li class="breadcrumb-item"><a href="#">فروشگاه</a></li>
                            <li class="breadcrumb-item active" aria-current="page">ثبت نام</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    <!-- END SECTION BANNER -->

    <!-- START SECTION SHOP DETAIL -->
    <section>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 DefaultLogin">
                    <div class="heading_s2">
                        <h3>ثبت نام</h3>
                    </div>
                    <form action="{{ route('register') }}"
                          method="POST" class="login_form ">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>نام و نام خانوادگی<span class="required">*</span></label>
                                    <input required class="form-control" type="text" name="name" value="{{ old('name') }}">
                                    @error('name')
                                    <div class="input-error-validation">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>ایمیل<span class="required">*</span></label>
                                    <input required class="form-control" type="email" name="email" value="{{ old('email') }}">
                                    @error('email')
                                    <div class="input-error-validation">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>رمز عبور <span class="required">*</span></label>
                                    <input class="form-control" required type="password" name="password">
                                    @error('password')
                                    <div class="input-error-validation">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>تکرار رمز عبور<span class="required">*</span></label>
                                    <input class="form-control" type="password" name="password_confirmation">
                                    @error('password_confirmation')
                                    <div class="input-error-validation">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-default btn-block" name="login" value="Register">ثبت نام</button>
                            <a href="{{ route('provider.login',['provider'=>'google']) }}" class="btn btn-default btn-block mr-0">ثبت نام با حساب گوگل</a>
                            <button  type="button" class="btn btn-default btn-block mr-0 loginWithSMS">ثبت نام پیامکی</button>
                        </div>
                        <div class="login_footer">
                            حساب کاربری دارید؟
                            <a href="{{ route('login') }}">  وارد شوید </a>
                        </div>
                    </form>
                </div>
                <div class="col-lg-6 col-md-12 col-xl-5 col-12 SMSLoginBox">
                    <div class="contact-form-wrapper">
                        <!-- Start Contact Form -->
                        <div class="axil-contact-form contact-form-style-1 text-right">
                            <div class="heading_s2">
                                <h3 class="text-right">ثبت نام با شماره همراه</h3>
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
                                    <button type="submit" class="btn btn-default btn-block">
                                        <span class="button-text">ارسال کد</span>
                                    </button>
                                    <button type="button"
                                            class="btn btn-default btn-block mr-0 SwitchToDefaultLogin">
                                        <span class="button-text">ورود با سایر شیوه ها</span>
                                    </button>
                                </div>
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
                                    <button id="resendOTPButton" type="submit" class="btn btn-default btn-block mt-3 mr-0">
                                        <span class="button-text">ارسال مجدد</span>
                                    </button>
                                    <button type="submit" class="btn btn-default btn-block mt-3 mr-0">
                                        <span class="button-text">ورود</span>
                                    </button>
                                    <button type="button"
                                            class="btn btn-default btn-block SwitchToDefaultLogin mr-0">
                                        <span class="button-text">ورود با سایر شیوه ها</span>
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

@endsection
