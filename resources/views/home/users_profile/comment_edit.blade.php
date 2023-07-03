@extends('home.users_profile.layout')

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

@section('main_content')
    <div class="col-lg-9 col-md-8 order-2">
        @include('home.sections.errors')
        <div class="tab-content" id="myaccountContent">
            <div class="myaccount-content">
                @if($user->name==null or $user->national_code==null)
                    <div class="alert alert-info text-center">
                        برای فعال شدن منو ها از قسمت <a class="ht-btn mx-2" href="{{ route('home.users_profile.index') }}">پروفایل</a> اطلاعات خود را تکمیل نمایید
                    </div>
                @else
                    <div class="alert alert-info text-center">
                        نظر کلی خود را در مورد عملکرد تیم اوسیانو وارد نمایید.این نظر در صفحه اصلی سایت نمایش داده خواهد شد
                    </div>
                    <div class="account-details-form">
                        <form action="{{ route('home.comment_index.update',['comment'=>$comment->id]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div class="row">

                                <div class="col-lg-4">
                                    <div class="single-input-item">
                                        <label for="first-name" class="required">
                                            عنوان
                                        </label>
                                        <input name="title"  value="{{ $comment->title }}" />
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="single-input-item">
                                        <label for="first-name" class="required">
                                            توضیحات
                                        </label>
                                        <textarea name="description">{{ $comment->description }}</textarea>
                                    </div>
                                </div>

                            </div>
                            <div class="single-input-item">
                                <button type="submit" class="check-btn sqr-btn "> ویرایش </button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection
