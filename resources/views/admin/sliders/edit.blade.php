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
                <h5 class="font-weight-bold">ویرایش اسلایدر : {{ $slider->image }}</h5>
            </div>
            <hr>
            @include('admin.sections.errors')
            <form action="{{ route('admin.sliders.update' , ['slider' => $slider]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row justify-content-center mb-3">
                    <div class="col-md-4">
                        <div class="card">
                            <img class="card-img-top" src="{{ url( env('SLIDER_IMAGES_UPLOAD_PATH').$slider->image ) }}" alt="">
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="primary_image"> انتخاب تصویر </label>
                        <div class="custom-file">
                            <input type="file" name="image" class="custom-file-input" id="slider_image">
                            <label class="custom-file-label" for="slider_image"> انتخاب فایل </label>
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="title">عنوان</label>
                        <input class="form-control" id="title" name="title" type="text" value="{{ $slider->title }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="priority">اولویت نمایش</label>
                        <input class="form-control" id="priority" name="priority" type="number" value="{{ $slider->priority }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="is_active">وضعیت</label>
                        <select class="form-control" id="is_active" name="is_active" >
                            <option {{ $slider->is_active==1 ? 'selected' : '' }} value="1">فعال</option>
                            <option {{ $slider->is_active==0 ? 'selected' : '' }} value="0">غیر فعال</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="button_link">لینک دکمه</label>
                        <input class="form-control" id="button_link" name="button_link" type="text" value="{{ $slider->button_link }}">
                    </div>
                    <div class="form-group col-md-12">
                        <label for="text">متن</label>
                        <input class="form-control" id="text" name="text" type="text" value="{{ $slider->text }}">
                    </div>
                </div>
                <button class="btn btn-outline-primary mt-5" type="submit">ثبت</button>
                <a href="{{ route('admin.sliders.index') }}" class="btn btn-dark mt-5 mr-3">بازگشت</a>
            </form>
        </div>

    </div>

@endsection
