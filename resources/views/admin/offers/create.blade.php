@extends('admin.layouts.admin')

@section('title')
    create Offers
@endsection

@section('script')
    <script>
        // Show File Name
        $('#product_image').change(function () {
            //get the file name
            var fileName = $(this).val();
            //replace the "Choose a file" label
            $(this).next('.custom-file-label').html(fileName);
        });
        $('#bg_image').change(function () {
            //get the file name
            var fileName = $(this).val();
            //replace the "Choose a file" label
            $(this).next('.custom-file-label').html(fileName);
        });
    </script>
@endsection
@section('content')

    <!-- Content Row -->
    <div class="row">

        <div class="col-xl-12 col-md-12 mb-4 p-4 bg-white">
            <div class="mb-4 text-center text-md-right">
                <h5 class="font-weight-bold">جدید</h5>
            </div>
            <hr>
            @include('admin.sections.errors')
            <form action="{{ route('admin.offers.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="primary_image"> تصویر پس‌زمینه<span class="mr-3" style="font-size: 9pt">(1200px*600px)</span> </label>
                        <div class="custom-file">
                            <input type="file" name="bg_image" class="custom-file-input" id="bg_image">
                            <label class="custom-file-label" for="bg_image"> انتخاب فایل </label>
                        </div>
                    </div>
                    <div class="form-group col-md-1">
                        <label for="button_link">رنگ پس‌زمینه</label>
                        <input class="form-control" id="bg_color" name="bg_color" type="color"
                               value="{{ old('button_link') }}">
                    </div>
                </div>
                <hr>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="primary_image"> تصویر محصول </label>
                        <div class="custom-file">
                            <input type="file" name="product_image" class="custom-file-input" id="product_image">
                            <label class="custom-file-label" for="product_image"> انتخاب فایل </label>
                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="button_link">عنوان</label>
                        <input class="form-control" id="title" name="title" type="text"
                               value="{{ old('button_link') }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="button_link">لینک دکمه</label>
                        <input class="form-control" id="button_link" name="button_link" type="text"
                               value="{{ old('button_link') }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="button_link">نوع نمایش</label>
                        <select class="form-control" name="type">
                            <option value="">انتخاب کنید</option>
                            <option value="2">2 ستونه</option>
                            <option value="3">3 ستونه</option>
                        </select>
                    </div>
                </div>
                <button class="btn btn-outline-primary mt-5" type="submit">ثبت</button>
                <a href="{{ route('admin.offers.index') }}" class="btn btn-dark mt-5 mr-3">بازگشت</a>
            </form>
        </div>

    </div>

@endsection
