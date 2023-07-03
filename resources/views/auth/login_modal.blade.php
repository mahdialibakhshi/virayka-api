
    <style>
        .SMSLoginBox {
            display: block;
        }

        .text-right {
            text-align: right !important;
        }

        .mr-0 {
            margin-right: 0 !important;
        }
        #login_modal{
            top:30% !important
        }
        .input-error-validation {
            font-size: 9pt;
            color: red;
        }

        #checkOTPForm {
            padding: 20px;
        }

        #resendOTPTime {
            border: 2px solid #eee;
            padding: 5px;
            border-radius: 50%;
        }

        .DefaultLogin {
            display: none;
        }
    </style>
{{--javaScript--}}

    <!-- START SECTION SHOP DETAIL -->
    <!-- Modal -->
    <div class="modal fade" id="login_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content p-2">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
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
                            <button type="submit" class="btn btn-block btn-primary mt-2">
                                <span class="button-text">ورود / ثبت نام با شماره همراه</span>
                            </button>
                        </div>
{{--                        <a href="{{ route('provider.login',['provider'=>'google']) }}" class="btn btn-block btn-primary mt-2">ورود--}}
{{--                            / ثبت نام با حساب گوگل</a>--}}
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
                            <button type="submit" class="btn btn-block btn-primary mt-2">
                                <span class="button-text">ورود</span>
                            </button>
                            <button id="resendOTPButton" type="submit"
                                    class="btn btn-block btn-primary mt-2">
                                <span class="button-text">ارسال مجدد</span>
                            </button>
                            <div class="d-flex justify-content-between p-3 align-content-center">
                                <span id="resendCodeDiv">ارسال مجدد کد </span>
                                <span id="resendOTPTime"></span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END SECTION SHOP DETAIL -->

