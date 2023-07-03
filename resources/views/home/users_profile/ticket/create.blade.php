@extends('home.users_profile.layout')

@section('title')
    صفحه ای سفارشات
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('home/css/profile_panel.css') }}">

    <style>
        .active{
            color: #0B94F7;
        }
        .UserImage{
            width: 100px;
            height: 100px;
            border-radius: 50%;
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
                    <div class="row">
                        <div class="col-xl-12 col-md-12 mb-1 p-4 bg-white">
                            @include('admin.sections.errors')
                            <div class="row">
                                <form
                                    action="{{ route('home.ticket.store') }}"
                                    method="POST"
                                    enctype="multipart/form-data"
                                >
                                    @csrf
                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            <label class="text-right">عنوان</label>
                                            <input class="form-control" type="text" name="title" id="title" value="{{ old('title') }}">
                                            <span class="focus-border"></span>
                                        </div>

                                        <div class="form-group col-sm-12">
                                            <label class="text-right">توضیحات</label>
                                            <textarea class="form-control" rows="5" type="text"  id="description" name="description">{{ old('description') }}</textarea>
                                            <span class="focus-border"></span>
                                        </div>
                                        <div class="col-sm-12 mt-3">
                                            <div class="input-group mb-3">
                                                <div class="custom-file">
                                                    <input type="file" class="form-control custom-file-input" name="file" id="file">
                                                    <label class="custom-file-label" for="file">Choose file</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <button type="submit" class="btn btn-main-masai">ارسال</button>
                                            <a href="{{ route('home.ticket.index') }}" class="btn btn-main-masai">
                                                بازگشت
                                            </a>
                                        </div>

                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection




