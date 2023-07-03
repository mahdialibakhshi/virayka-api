@extends('admin.layouts.admin')

@section('title')
    create functionalType
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
        // Show File Name
        $('#banner_image').change(function () {
            //get the file name
            var fileName = $(this).val();
            //replace the "Choose a file" label
            $(this).next('.custom-file-label').html(fileName);
        });
    </script>
@endsection

@section('style')
    <style>
        .img-thumbnail{
            width: 200px;
        }
    </style>
@endsection

@section('content')

    <!-- Content Row -->
    <div class="row">

        <div class="col-xl-12 col-md-12 mb-4 p-4 bg-white">
            <div class="mb-4 text-center text-md-right">
                <h5 class="font-weight-bold">عملکرد جدید</h5>
            </div>
            <hr>
            <div class="row">
                <div class="col-12 text-center">
                    <img class="img-thumbnail" src="{{ imageExist(env('FUNCTIONAL_TYPE_UPLOAD_PATH'),$functionalType->image) }}">
                </div>
                <div class="col-12">
                    <hr>
                </div>
            </div>
            @include('admin.sections.errors')

            <form action="{{ route('admin.functionalType.update',['functionalType'=>$functionalType->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="title">عنوان</label>
                        <input class="form-control" id="title" name="title" type="text" value="{{ $functionalType->title }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="priority">اولویت نمایش</label>
                        <input class="form-control" id="priority" name="priority" type="number" value="{{ $functionalType->priority }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="image"> انتخاب تصویر </label>
                        <div class="custom-file">
                            <input type="file" name="image" class="custom-file-input" id="image">
                            <label class="custom-file-label" for="image"> انتخاب فایل </label>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="banner_image"> انتخاب بنر </label>
                        <div class="custom-file">
                            <input type="file" name="banner_image" class="custom-file-input" id="banner_image">
                            <label class="custom-file-label" for="image"> انتخاب فایل </label>
                        </div>
                    </div>
                </div>

                <button class="btn btn-outline-primary mt-5" type="submit">ثبت</button>
                <a href="{{ route('admin.functionalType.index') }}" class="btn btn-dark mt-5 mr-3">بازگشت</a>
            </form>
        </div>

    </div>

@endsection
