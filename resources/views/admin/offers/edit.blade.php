@extends('admin.layouts.admin')

@section('title')
    create offer
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
                <h5 class="font-weight-bold">ویرایش پیشنهاد ویژه : </h5>
            </div>
            <hr>
            @include('admin.sections.errors')
            <form action="{{ route('admin.offers.update' , ['offer' => $offer]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row justify-content-center mb-3">
                    @if(!$offer->product_image==null)
                    <div class="col-md-4">
                        <div class="card">
                            <img class="card-img-top" src="{{ url( env('BANNER_IMAGES_UPLOAD_PATH').$offer->product_image ) }}" alt="">
                        </div>
                        <p class="text-center p-2">تصویر محصول</p>
                    </div>
                    @endif
                    @if(!$offer->bg_image==null)
                    <div class="col-md-4">
                        <div class="card">
                            <img class="card-img-top" src="{{ url( env('BANNER_IMAGES_UPLOAD_PATH').$offer->bg_image ) }}" alt="">
                        </div>
                        <p class="text-center p-2">تصویر پس زمینه</p>
                    </div>
                    @endif
                </div>

                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="primary_image"> تصویر پس‌زمینه </label>
                        <div class="custom-file">
                            <input type="file" name="bg_image" class="custom-file-input" id="bg_image">
                            <label class="custom-file-label" for="bg_image"> انتخاب فایل </label>
                        </div>
                    </div>
                    <div class="form-group col-md-1">
                        <label for="button_link">رنگ پس‌زمینه</label>
                        <input class="form-control" id="bg_color" name="bg_color" type="color"
                               value="{{ $offer->bg_color }}">
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
                               value="{{ $offer->title }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="button_link">لینک دکمه</label>
                        <input class="form-control" id="button_link" name="button_link" type="text"
                               value="{{ $offer->button_link }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="button_link">نوع نمایش</label>
                        <select class="form-control" name="type">
                            <option value="">انتخاب کنید</option>
                            <option {{ $offer->type==2 ? 'selected' : '' }} value="2">2 ستونه</option>
                            <option {{ $offer->type==3 ? 'selected' : '' }} value="3">3 ستونه</option>
                        </select>
                    </div>
                </div>
                <button class="btn btn-outline-primary mt-5" type="submit">ثبت</button>
                <a href="{{ route('admin.offers.index') }}" class="btn btn-dark mt-5 mr-3">بازگشت</a>
            </form>
        </div>

    </div>

@endsection
