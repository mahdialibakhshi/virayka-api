@extends('admin.layouts.admin')

@section('title')
    create slider
@endsection

@section('script')
<script>
    // Show File Name
    $('#slider_image').change(function() {
        //get the file name
        var fileName = $(this).val();
        //replace the "Choose a file" label
        $(this).next('.custom-file-label').html(fileName);
    });
    // Show File Name
    $('#thumbnail').change(function() {
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
                <h5 class="font-weight-bold">ایجاد اسلایدر</h5>
            </div>
            <hr>
            @include('admin.sections.errors')
            <form action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="primary_image"> انتخاب تصویر<span class="mr-3" style="font-size: 9pt;color: red">(ابعاد پیشنهادی :1800*900 پیکسل)</span></label>
                        <div class="custom-file">
                            <input type="file" name="image" class="custom-file-input" id="slider_image">
                            <label class="custom-file-label" for="slider_image"> انتخاب فایل </label>
                        </div>
                    </div>
{{--                    <div class="form-group col-md-6">--}}
{{--                        <label for="thumbnail">image thumbnail<span class="mr-3" style="font-size: 9pt;color: red">(ابعاد پیشنهادی :1800*900 پیکسل)</span></label>--}}
{{--                        <div class="custom-file">--}}
{{--                            <input type="file" name="thumbnail" class="custom-file-input" id="thumbnail">--}}
{{--                            <label class="custom-file-label" for="thumbnail"> انتخاب فایل </label>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <div class="form-group col-md-6">
                        <label for="title">عنوان</label>
                        <input class="form-control" id="title" name="title" type="text" value="{{ old('title') }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="priority">اولویت نمایش</label>
                        <input class="form-control" id="priority" name="priority" type="number" value="1">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="is_active">وضعیت</label>
                        <select class="form-control" id="is_active" name="is_active" >
                            <option  value="1">فعال</option>
                            <option  value="0">غیر فعال</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="button_link">لینک دکمه</label>
                        <input class="form-control" id="button_link" name="button_link" type="text" value="{{ old('button_link') }}">
                    </div>
                    <div class="form-group col-12">
                        <label for="text">متن</label>
                        <textarea class="form-control" id="text" name="text" type="text">{{ old('text') }}</textarea>
                    </div>
                </div>

                <button class="btn btn-outline-primary mt-5" type="submit">ثبت</button>
                <a href="{{ route('admin.sliders.index') }}" class="btn btn-dark mt-5 mr-3">بازگشت</a>
            </form>
        </div>

    </div>

@endsection
