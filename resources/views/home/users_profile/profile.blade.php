@extends('home.users_profile.layout')

@section('title')
    پروفایل کاربری
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('home/css/profile_panel.css') }}">

    <style>
        .nice-select{
            width: 100%;
        }
        .position-relative{
            position: relative;
        }
        #close_alert{
            position: absolute;
            cursor: pointer;
            top: 5px;
            left: 5px;
        }
        .my-2{
            margin: 2rem 0;
        }
    </style>
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
        $('#close_alert').click(function (){
            $(this).parent().slideUp();
        })
    </script>
@endsection

@section('main_content')
    <div class="col-lg-9 col-md-8 order-2 profile_card">
        @include('home.sections.errors')
        <div class="tab-content" id="myaccountContent">
            <div class="myaccount-content">
                @if($user->role==4)
                    <div class="my-2 alert alert-info text-center position-relative">
                        <i title="بستن" id="close_alert" class="fa fa-times-circle"></i>
                        برای درخواست اکانت همکار از منوهای ناحیه کاربری بر روی <a href="{{ route('home.profile.role_request.index') }}" class="btn btn-primary">درخواست اکانت همکار</a> کلیک کنید
                    </div>
                @endif
                @if($user->name==null or $user->national_code==null)
                    <div class="alert alert-info text-center">
                        برای فعال شدن منو ها اطلاعات خود را تکمیل نمایید
                    </div>
                @else
                    <h3 class="mt-2"> پروفایل </h3>
                @endif
                    <hr class="mb-4">
                <div class="account-details-form">
                    <form action="{{ route('home.userUpdateInfo') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="single-input-item">
                                    <label for="first-name" class="required">
                                        نام و نام خانوادگی*
                                    </label>
                                    <input  class="form-control form-control-sm" type="text" id="name" name="name" value="{{ $user->name }}" />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="single-input-item">
                                    <label for="first-name" class="required">
                                        جنسیت
                                    </label>
                                    <select name="jensiyat" class="form-control ">
                                        <option {{ $user->jensiyat==0 ? 'selected' : '' }} value="">
                                            انتخاب کنید
                                        </option>
                                        <option {{ $user->jensiyat==1 ? 'selected' : '' }} value="1">
                                            مرد
                                        </option>
                                        <option {{ $user->jensiyat==2 ? 'selected' : '' }} value="2">
                                            زن
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="single-input-item">
                                    <label for="first-name" class="required">
                                        ایمیل
                                    </label>
                                    <input  class="form-control form-control-sm" type="email" id="email" name="email"  value="{{ $user->email }}" />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="single-input-item">
                                    <label for="national_code" class="required">
                                        کد ملی *
                                    </label>
                                    <input  class="form-control form-control-sm" id="national_code" name="national_code"  value="{{ $user->national_code }}" />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="single-input-item">
                                    <label for="first-name" class="required">
                                        شماره همراه
                                    </label>
                                    <input  class="form-control form-control-sm" disabled  value="{{ $user->cellphone }}" />
                                </div>
                            </div>
                            <div class="form-group col-12">
                                <label for="primary_image">تصویر پروفایل</label>
                                <input type="file" name="avatar" class="form-control" id="profile_image">
                            </div>
                        </div>
                        <div class="single-input-item mt-3">
                            @if($user->name==null)
                                <button type="submit" class="btn btn-main-masai"> تکمیل ثبت نام </button>
                            @else
                                <button type="submit" class="btn btn-main-masai"> ثبت تغییرات </button>
                            @endif
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
