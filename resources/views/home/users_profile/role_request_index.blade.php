@extends('home.users_profile.layout')

@section('title')
    تغییر کاربری
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('home/css/profile_panel.css') }}">

    <style>
        .nice-select {
            width: 100%;
        }

    </style>
@endsection

@section('script')
    <script>
        // Show File Name
        $('#image').change(function () {
            //get the file name
            var fileName = $(this).val();
            //replace the "Choose a file" label
            $(this).next('.custom-file-label').html(fileName);
        });
        $('input[name="company_type"]').click(function (){
            radio_btn_checked();
        });
        $(document).ready(function (){
            radio_btn_checked();
        });
        function radio_btn_checked(){
            let company_type=$('input[name="company_type"]:checked').attr('data-company-type');
            $('.company_type').addClass('d-none');
            $('.company_type_'+company_type).removeClass('d-none');
        }
    </script>
@endsection

@section('main_content')
    <div class="col-lg-9 col-md-8 order-2">
        <div class="tab-content" id="myaccountContent">
            <div class="myaccount-content">
                @if($user->name==null or $user->national_code==null)
                    <div class="alert alert-info text-center">
                        برای فعال شدن منو ها از قسمت <a class="ht-btn mx-2"
                                                        href="{{ route('home.users_profile.index') }}">پروفایل</a>
                        اطلاعات خود را تکمیل نمایید
                    </div>
                @else
                    <h3> درخواست اکانت همکار </h3>
                    @if($user->role_request_status==1)
                        <div class="alert alert-info text-center">
                            درخواست شما در حال بررسی است
                        </div>
                    @else
                        @if($user->role_request_status==2)
                            <div class="alert alert-danger text-center">
                                متاسفانه درخواست شما برای تغییر کاربری رد شده است.با پشتیبان تماس
                                بگیرید
                            </div>
                        @endif


                        <div class="account-details-form">
                            <form action="{{ route('home.profile.role_request.store') }}"
                                  method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row mt-3">
                                    @error('company_type')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                    <div class="col-lg-6 col-12">
                                        <div class="d-flex align-items-center justify-content-between company_label mb-2">
                                            <label for="company_type" class="required d-block w-100 h-100">
                                                فروشگاه
                                            </label>
                                            <input {{ old('company_type')==1 ? 'checked' : '' }}  type="radio" value="1" id="company_type" name="company_type" data-company-type="1" class="radio_btn">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <div class="d-flex align-items-center justify-content-between company_label mb-2">
                                            <label for="company_type_2" class="required d-block w-100 h-100">
                                                شرکت
                                            </label>
                                            <input {{ old('company_type')==2 ? 'checked' : '' }} type="radio" value="2" id="company_type_2" data-company-type="2" name="company_type" class="radio_btn">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <div class="single-input-item mb-2">
                                            <label for="company_name" class="required">
                                                نام موسسه/شرکت *
                                            </label>
                                            <input id="company_name" name="company_name"
                                                   class="form-control form-control-sm">
                                            @error('company_name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <div class="single-input-item mb-2">
                                            <label for="economic_code" class="required">
                                                کد اقتصادی *
                                            </label>
                                            <input id="economic_code" name="economic_code"
                                                   class="form-control form-control-sm">
                                            @error('economic_code')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <div class="single-input-item mb-2">
                                            <label for="naghsh_code" class="required">
                                                کد نقش *
                                            </label>
                                            <input id="naghsh_code" name="naghsh_code"
                                                   class="form-control form-control-sm">
                                            @error('naghsh_code')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-6 company_type company_type_1 mb-2">
                                        <label for="image_atach_1">جواز کسب *</label>
                                        <input type="file" name="image_atach_1" class="form-control"
                                               id="image_atach_1">
                                        @error('image_atach_1')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-lg-6 company_type company_type_1 mb-2">
                                        <label for="image_atach_2">تصویر کارت ملی صاحب جواز *</label>
                                        <input type="file" name="image_atach_2" class="form-control"
                                               id="image_atach_2">
                                        @error('image_atach_2')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-lg-6 company_type company_type_2 d-none mb-2">
                                        <label for="image_atach_3">آگهی آخرین تغییرات *</label>
                                        <input type="file" name="image_atach_3" class="form-control"
                                               id="image_atach_3">
                                        @error('image_atach_3')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-lg-6 company_type company_type_2 d-none mb-2">
                                        <label for="image_atach_4">اساس نامه *</label>
                                        <input type="file" name="image_atach_4" class="form-control"
                                               id="image_atach_4">
                                        @error('image_atach_4')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-lg-6 company_type company_type_2 d-none mb-2">
                                        <label for="image_atach_5">تصویر کارت ملی مدیر عامل *</label>
                                        <input type="file" name="image_atach_5" class="form-control"
                                               id="image_atach_5">
                                        @error('image_atach_5')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-lg-6 company_type company_type_2 d-none mb-2">
                                        <label for="image_atach_6">روزنامه رسمی کشور *</label>
                                        <input type="file" name="image_atach_6" class="form-control"
                                               id="image_atach_6">
                                        @error('image_atach_6')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="single-input-item mt-3">
                                    <button type="submit" class="btn btn-main-masai"> ثبت درخواست
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif
                @endif
            </div>

        </div>
    </div>
@endsection
