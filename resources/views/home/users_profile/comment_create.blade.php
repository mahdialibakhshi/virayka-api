@extends('home.layouts.index')

@section('title')
    صفحه ای پروفایل
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('home/css/profile_panel.css') }}">

@endsection

@section('script')
    <script>
        // Show File Name
        $('#profile_image').change(function() {
            //get the file name
            var fileName = $(this).val();
            //replace the "Choose a file" label
            $(this).next('.custom-file-label').html(fileName);
        });
    </script>
@endsection

@section('content')

    <div class="page-banner-section section bg_image--3">
        <div class="container">
            <div class="row">
                <div class="col">

                    <div class="page-banner text-center">
                        <ul class="page-breadcrumb">
                            <li><a href="{{ route('home.index') }}">خانه</a></li>
                            <li>ثبت دیدگاه جدید</li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- my account wrapper start -->
    <div class="my-account-wrapper my-5">
        <div class="container card">
            <div class="row card-body">
                <div class="col-lg-12">
                    <!-- My Account Page Start -->
                    <div class="myaccount-page-wrapper">
                        <!-- My Account Tab Menu Start -->
                        <div class="row text-right" style="direction: rtl;">
                            <div class="col-lg-3 col-md-4">
                                @include('home.sections.profile_sidebar')
                            </div>
                            <!-- My Account Tab Menu End -->
                            <!-- My Account Tab Content Start -->
                            <div class="col-lg-9 col-md-8">
                                @include('home.sections.errors')
                                <div class="tab-content" id="myaccountContent">
                                    <div class="myaccount-content">
                                        @if($user->name==null or $user->national_code==null)
                                            <div class="alert alert-info text-center">
                                                برای فعال شدن منو ها از قسمت <a class="ht-btn mx-2" href="{{ route('home.users_profile.index') }}">پروفایل</a> اطلاعات خود را تکمیل نمایید
                                            </div>
                                        @else
                                            <div class="alert alert-info text-center">
                                                نظر کلی خود را در مورد عملکرد تیم افراسنتر وارد نمایید.این نظر در صفحه اصلی سایت نمایش داده خواهد شد
                                            </div>
                                            <div class="account-details-form">
                                                <form action="{{ route('home.comment_index.store') }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="row">

                                                        <div class="col-lg-4">
                                                            <div class="single-input-item">
                                                                <label for="first-name" class="required">
                                                                    عنوان
                                                                </label>
                                                                <input name="title"  value="{{ old('title') }}" />
                                                            </div>
                                                        </div>
                                                        <div class="col-12 mt-2">
                                                            <div class="single-input-item">
                                                                <label for="first-name" class="required my-1">
                                                                    توضیحات
                                                                </label>
                                                                <textarea class="form-control" name="description"></textarea>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="single-input-item">
                                                        <button type="submit" class="btn btn-main-masai mt-3"> ثبت </button>
                                                    </div>
                                                </form>
                                            </div>
                                        @endif
                                    </div>

                                </div>
                            </div> <!-- My Account Tab Content End -->
                        </div>
                    </div> <!-- My Account Page End -->
                </div>
            </div>
        </div>
    </div>
    <!-- my account wrapper end -->
@endsection
