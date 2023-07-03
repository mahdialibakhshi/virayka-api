@extends('admin.layouts.admin')

{{-- ===========  meta Title  =================== --}}
@section('title')
    ایجاد صفحه
@endsection
{{-- ===========  My Css Style  =================== --}}
@section('style')
    <style>
        .modal-header .close {
            margin: -1rem;
        }

        .modal-body > p, .form-group {
            padding: 0.25rem 0.5rem;
        }

        .noneDisplay {
            display: none !important;
        }

        .redBorder {
            border: 1px solid red;
        }

    </style>
@endsection
{{-- ===========  My JavaScript  =================== --}}

@section('script')
    <script>

        // Show File Name
        $('#image').change(function () {
            //get the file name
            var fileName = $(this).val();
            //replace the "Choose a file" label
            $(this).next('.custom-file-label').html(fileName);
        });

    </script>
    {{--    //ckEditor--}}
    <script src="{{ asset('admin/fullCKEditor/ckeditor/ckeditor.js') }}"></script>
    <script>
        CKEDITOR.replace('description', {
            language: 'fa',
            filebrowserUploadUrl: "{{route('upload', ['_token' => csrf_token() ])}}",
            filebrowserUploadMethod: 'form'
        });
    </script>
@endsection
{{-- ===========      CONTENT      =================== --}}
@section('content')

    <!-- Content Row -->
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4 p-4 bg-white">
            <div class="mb-4 text-center text-md-right">
                <h5 class="font-weight-bold">ایجاد صفحه</h5>
            </div>
            <hr>

            @include('admin.sections.errors')

            <form action="{{ route('admin.pages.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="name">عنوان</label>
                        <input class="form-control" id="title" name="title" type="text" value="{{ old('title') }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="name">اولویت</label>
                        <input class="form-control" id="priority" name="priority" type="number" value="1">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="is_active">وضعیت نمایش بنر بالای صفحه</label>
                        <select class="form-control" id="banner_is_active" name="banner_is_active">
                            <option value="1" selected>فعال</option>
                            <option value="0">غیرفعال</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="image"> تصویر بنر </label>
                        <div class="custom-file">
                            <input type="file" name="image" class="custom-file-input" id="image">
                            <label class="custom-file-label" for="image"> انتخاب فایل </label>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="description">توضیحات</label>
                        <textarea class="form-control" id="description"
                                  name="description">{{ old('description') }}</textarea>
                    </div>
                    <button class="btn btn-outline-primary mt-5" type="submit">ثبت</button>
                    <a href="{{ route('admin.pages.index') }}" class="btn btn-dark mt-5 mr-3">بازگشت</a>
                </div>
            </form>
        </div>
    </div>

@endsection
