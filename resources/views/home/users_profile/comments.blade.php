@extends('home.users_profile.layout')

@section('title')
    صفحه ای پروفایل
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('home/css/profile_panel.css') }}">

    <style>
        li {
            list-style: none !important;
        }

        #myaccountContent {
            margin-top: 0 !important;
        }
        td{
            vertical-align: middle;
        }
    </style>
@endsection

@section('main_content')
    <div class="col-lg-9 col-md-8 order-2">
        @if($user->name==null or $user->national_code==null)
            <div class="alert alert-info text-center">
                برای فعال شدن منو ها از قسمت <a class="ht-btn mx-2" href="{{ route('home.users_profile.index') }}">پروفایل</a> اطلاعات خود را تکمیل نمایید
            </div>
        @else
            <div class="tab-content" id="myaccountContent">

                <div class="myaccount-content">
                    <h3>
                        دیدگاه
                    </h3>
                    {{--                                            @if(empty($comment_index))--}}
                    {{--                                                <div class="col-12 d-flex justify-content-end mb-3">--}}
                    {{--                                                    <a href="{{ route('home.comment.create') }}"--}}
                    {{--                                                       class="btn btn-sm btn-success">جدید</a>--}}
                    {{--                                                </div>--}}
                    {{--                                            @endif--}}
                    <div class="review-wrapper">
                        @if (empty($comments))
                            <div class="alert alert-danger text-center">
                                تاکنون دیدگاهی ثبت نکرده اید
                            </div>
                        @else
                            @if (!empty($comments))
                                <table class="table table-bordered text-center mb-5">
                                    <thead class="thead-light">
                                    <tr>
                                        <th>محصول</th>
                                        <th>متن</th>
                                        <th>وضعیت</th>
                                        <th>تاریخ</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($comments as $comment)
                                        <tr>
                                            <td class="product-thumbnail">
                                                <a href="{{ route('home.product' , ['alias' => $comment->product->alias]) }}">
                                                    <img width="100" src="{{ asset(env('PRODUCT_IMAGES_UPLOAD_PATH') . $comment->product->primary_image) }}"
                                                         alt="">
                                                </a>
                                            </td>
                                            <td> {{ $comment->text }} </td>
                                            <td>{{ $comment->approved }}</td>
                                            <td>{{ verta($comment->created_at)->format('%d %B,Y') }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                        @endif
                    </div>
                </div>

            </div>
        @endif
    </div>
@endsection
