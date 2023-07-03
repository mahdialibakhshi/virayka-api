@extends('home.users_profile.layout')

@section('title')
    صفحه ی سفارشات
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
        @if($user->name==null or $user->national_code==null)
            <div class="alert alert-info text-center">
                برای فعال شدن منو ها از قسمت <a class="ht-btn mx-2"
                                                href="{{ route('home.users_profile.index') }}">پروفایل</a>
                اطلاعات خود را تکمیل نمایید
            </div>
        @else
            <div class="tab-content" id="myaccountContent">

                <div class="myaccount-content">
                    <div class="myaccount-table table-responsive text-center">
                        <h3>سفارشات</h3>
                        @if ($orders->isEmpty())
                            <div class="alert alert-danger">
                                لیست سفارشات شما خالی می باشد
                            </div>
                        @else
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                <tr>
                                    <th> شماره سفارش</th>
                                    <th>شماره تراکنش بانک</th>
                                    <th> تاریخ</th>
                                    <th>نوع پرداخت</th>
                                    <th>وضعیت تراکنش</th>
                                    <th> جمع کل</th>
                                    <th> عملیات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($orders as $order)
                                    <tr class="{{ $order->getRawOriginal('status')==0 ? 'text-danger' : '' }}">
                                        <td>{{ $setting->productCode.'-'.$order->order_number }}</td>
                                        <td>{{ $order->Transaction->ref_id }}</td>
                                        <td> {{ verta($order->created_at)->format('%d %B، %Y') }}
                                        </td>
                                        <td>{{ $order->payment_type }}</td>
                                        <td>{{ $order->payment_status }}</td>
                                        <td>
                                            {{ number_format($order->paying_amount) }}
                                            تومان
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#ordersDetails-{{ $order->id }}">
                                                جزئیات
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                                @endforeach
                            </table>
                        @endif
                    </div>
                </div>

            </div>
        @endif
    </div>
@endsection
