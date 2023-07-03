@extends('home.users_profile.layout')

@section('title')
    صفحه ای سفارشات
@endsection

@section('script')
    <script>
        function showDetail(order_id) {
            let modal = $('#ordersDetails-' + order_id);
            modal.modal('show');
        }
    </script>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('home/css/profile_panel.css') }}">

    <style>
        .img-thumbnail{
            width: 40px !important;
            height: auto !important;
            margin-right: 10px;
        }
        .cursor-pointer {
            cursor: pointer;
        }

        td {
            font-size: 10pt;
            padding: 0;
            vertical-align: middle;
        }

        th {
            text-align: center;
        }

        @media (min-width: 576px) {
            .modal-dialog {
                max-width: 1000px !important;
                margin: 1.75rem auto;
            }
        }
    </style>
@endsection

@section('main_content')
    <div class="col-lg-9 col-md-8 order-2">
        <div class="tab-content" id="myaccountContent">
            <div class="myaccount-content">
                <h3>کیف پول من</h3>
                @if($user->name==null or $user->national_code==null)
                    <div class="alert alert-info text-center">
                        برای فعال شدن منو ها از قسمت <a class="ht-btn mx-2"
                                                        href="{{ route('home.users_profile.index') }}">پروفایل</a>
                        اطلاعات خود را تکمیل نمایید
                    </div>
                @else
                    <div class="row">

                        <div class="col-xl-12 col-md-12 mb-4 p-4 bg-white">
                            <div class="row">
                                <div class="col-12 mb-5">
                                    <p>موجودی کیف پول : {{ number_format($wallet->amount) }}
                                        تومان</p>
                                </div>
                                <div class="col-12">
                                    <form method="post"
                                          action="{{ route('home.payment.charge_wallet') }}">
                                        @csrf
                                        <div class="position-relative text-center mb-4">
                                            <input onkeyup="NumberFormat(this)" id="amount"
                                                   type="text"
                                                   name="amount"
                                                   class="form-control form-control-sm"
                                                   value="0"
                                                   placeholder="مقدار دلخواه خود را وارد نمایید">
                                            <div class="d-flex align-center justify-content-start mt-4">
                                                @foreach($PaymentMethods as $item)

                                                    <input id="{{ $item->name }}"
                                                           class="input-radio" type="radio"
                                                           value="{{ $item->name }}"
                                                           checked="checked"
                                                           name="payment_method">
                                                    @if(file_exists(public_path(env('LOGO_UPLOAD_PATH').$item->image))
     and !is_dir(public_path(env('LOGO_UPLOAD_PATH').$item->image)))
                                                        <img class="img-thumbnail"
                                                             src="{{ asset(env('LOGO_UPLOAD_PATH').$item->image) }}">
                                                    @else
                                                        <img class="img-thumbnail"
                                                             src="{{ asset('admin/images/no_image.jpg') }}">
                                                    @endif
                                                    <label class="ml-3 mr-3"
                                                           for="{{ $item->name }}">{{ $item->description }}</label>
                                                @endforeach
                                            </div>
                                            <div class="input-button">
                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-primary">افزایش
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <hr>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped text-center">

                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>مبلغ (تومان)</th>
                                        <th>تغییرات (تومان)</th>
                                        <th>نوع</th>
                                        <th>تاریخ</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($wallet_history as $key => $item)
                                        <tr>
                                            <th>
                                                {{ $wallet_history->firstItem() + $key }}
                                            </th>
                                            <th>
                                                {{ number_format($item->previous_amount) }}
                                            </th>
                                            <th class="d-flex justify-content-center">
                                <span style="width: 200px;display: block">
                                    {{ number_format($item->amount) }}
                                </span>
                                                @if($item->increase_type==1)
                                                    <i class="text-success fa fa-arrow-up"></i>
                                                @else
                                                    <i class="text-danger fa fa-arrow-down"></i>
                                                @endif
                                            </th>
                                            <th>
                                                {{ $item->Type->description }}
                                            </th>
                                            <th>
                                                {{ verta($item->created_at)->format('Y-m-d H:i') }}
                                            </th>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-center mt-5">
                                {{ $wallet_history->render() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
