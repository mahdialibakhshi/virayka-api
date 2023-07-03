@extends('admin.layouts.admin')

@section('title')
    edit brands
@endsection

@section('script')
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script>
        // Show File Name
        $('#image').change(function () {
            //get the file name
            var fileName = $(this).val();
            //replace the "Choose a file" label
            $(this).next('.custom-file-label').html(fileName);
        });
        // Show File Name
        $('#banner').change(function () {
            //get the file name
            var fileName = $(this).val();
            //replace the "Choose a file" label
            $(this).next('.custom-file-label').html(fileName);
        });
        CKEDITOR.replace('description', {
            language: 'fa',
            filebrowserUploadUrl: "{{route('upload', ['_token' => csrf_token() ])}}",
            filebrowserUploadMethod: 'form'
        });
    </script>
@endsection

@section('content')

    <!-- Content Row -->
    <div class="row">

        <div class="col-xl-12 col-md-12 mb-4 p-4 bg-white">
            <div class="mb-4 text-center text-md-right">
                <h5 class="font-weight-bold">ویرایش برند {{ $brand->name }}</h5>
            </div>
{{--            <hr>--}}
{{--            <div class="text-center">--}}
{{--                <img src="{{ imageExist(env('BRAND_UPLOAD_PATH'),$brand->image) }}">--}}
{{--            </div>--}}
{{--            <hr>--}}
{{--            <div class="text-center">--}}
{{--                <img src="{{ imageExist(env('BRAND_UPLOAD_PATH'),$brand->banner) }}">--}}
{{--            </div>--}}
{{--            <hr>--}}
            @include('admin.sections.errors')
            <form action="{{ route('admin.brands.update' , ['brand' => $brand->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="name">نام</label>
                        <input class="form-control" id="name" name="name" type="text" value="{{ $brand->name }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="primary_image"> انتخاب تصویر </label>
                        <div class="custom-file">
                            <input type="file" name="image" class="custom-file-input" id="image">
                            <label class="custom-file-label" for="image"> انتخاب فایل </label>
                        </div>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="primary_image"> انتخاب بنر بالای صفحه </label>
                        <div class="custom-file">
                            <input type="file" name="banner" class="custom-file-input" id="banner">
                            <label class="custom-file-label" for="image"> انتخاب فایل </label>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="description">توضیحات</label>
                        <textarea class="form-control" id="description"
                                  name="description">{{ $brand->description }}</textarea>
                    </div>
                </div>

                <button class="btn btn-outline-primary mt-5" type="submit">ویرایش</button>
                <a href="{{ route('admin.brands.index') }}" class="btn btn-dark mt-5 mr-3">بازگشت</a>
            </form>
        </div>

    </div>

@endsection
