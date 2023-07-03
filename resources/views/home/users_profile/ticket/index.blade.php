@extends('home.users_profile.layout')

@section('title')
    صفحه‌ی تیکت ها
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('home/css/profile_panel.css') }}">

    <style>
        .active {
            color: #0B94F7;
        }

        .UserImage {
            width: 100px;
            height: 100px;
            border-radius: 50%;
        }

        li {
            list-style: none !important;
        }

        #myaccountContent {
            margin-top: 0 !important;
            text-align: right !important;
        }

        .float-right {
            float: right !important;
        }

        .show {
            display: block;
        }

        table {
            font-size: 14px;
        }
    </style>
@endsection

@section('main_content')
    <div class="col-lg-9 col-md-8 order-2">
        @if($user->name==null)
            <div class="alert alert-info text-center">
                برای فعال شدن منو ها از قسمت <a class="ht-btn mx-2" href="{{ route('home.users_profile.index') }}">پروفایل</a> اطلاعات خود را تکمیل نمایید
            </div>
        @else
            <div class="tab-content" id="myaccountContent">
                <div class="myaccount-content">
                    <h3>تیکت‌های من</h3>
                    <div class="row mb-3">
                        <div class="col-12 d-flex justify-content-end">
                            <a href="{{ route('home.ticket.create') }}"
                               class="btn btn-second-masai btn-sm text-decoration-none text-white">جدید</a>
                        </div>
                    </div>
                    @if(count($tickets)>0)
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-bordered">
                                    <thead class="thead-light">
                                    <tr>
                                        <th class="text-center">ردیف</th>
                                        <th class="text-center">عنوان</th>
                                        <th class="text-center">وضعیت</th>
                                        <th class="text-center">مشاهده</th>
                                        <th class="text-center">پاسخ</th>
                                        <th class="text-center">شماره تیکت</th>
                                        <th class="text-center">تاریخ</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($tickets as $key=>$ticket)
                                        <tr>
                                            <td class="text-center">{{ $tickets->firstItem()+$key }}</td>
                                            <td class="text-center">{{ $ticket->title }}</td>
                                            <td class="text-center">{{ $ticket->Status->title }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('home.ticket.show',['ticket'=>$ticket->id]) }}"
                                                   class="btn btn-sm btn-info text-white">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                @if($ticket->status_id==3 )
                                                    <a href="{{ route('home.ticket.show',['ticket'=>$ticket->id]) }}"
                                                       class="btn btn-sm btn-danger text-white">
                                                        <i class="fa fa-envelope"></i>
                                                    </a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                {{ $ticket->id }}
                                            </td>
                                            <td class="text-center">{{ verta($ticket->created_at)->format('d - %B - Y') }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="row justify-content-center">
                                    {{ $tickets->render() }}
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-info text-center" style="background-color: #CFEBED !important;color: black">
                                    تا کنون تیکتی ارسال نکرده اید
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        @endif
    </div>
@endsection
