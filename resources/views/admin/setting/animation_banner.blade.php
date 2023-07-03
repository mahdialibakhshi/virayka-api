@extends('admin.layouts.admin')

@section('title')
    ویرایش بنر متحرک
@endsection

@section('style')
    <style>
        .img-thumbnail{
            max-width: 200px;
            height: auto;
        }
        p{
            padding: 10px;
        }
    </style>
@endsection

@section('content')

    <!-- Content Row -->
    <div class="row">

        <div class="col-xl-12 col-md-12 mb-4 p-4 bg-white">
            <div class="mb-4 text-center text-md-right">
                <h5 class="font-weight-bold">ویرایش بنر متحرک صفحه اصلی</h5>
            </div>
            <hr>
            @include('admin.sections.errors')
            <form action="{{ route('admin.animation_banner.update') }}" method="POST" enctype="multipart/form-data">
                @method('put')
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="black_text">متن سیاه</label>
                        <input class="form-control" id="black_text" name="black_text" type="text" value="{{ $animation_banner->black_text }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="red_text">متن قرمز</label>
                        <input class="form-control" id="red_text" name="red_text" type="text" value="{{ $animation_banner->red_text }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="animation_text">متن متحرک</label>
                        <input class="form-control" id="animation_text" name="animation_text" type="text" value="{{ $animation_banner->animation_text }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="btn_text">متن دکمه</label>
                        <input class="form-control" id="btn_text" name="btn_text" type="text" value="{{ $animation_banner->btn_text }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="btn_text">لینک دکمه</label>
                        <input class="form-control" id="btn_link" name="btn_link" type="text" value="{{ $animation_banner->btn_link }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="is_active">نمایش متن</label>
                        <select class="form-control" name="is_active">
                            <option value="1" {{ $animation_banner->is_active==1?'selected':'' }}>فعال</option>
                            <option value="2" {{ $animation_banner->is_active==2?'selected':'' }}>غیر فعال</option>
                        </select>
                    </div>
                </div>

                <button class="btn btn-outline-primary mt-5" type="submit">ثبت</button>
            </form>
        </div>

    </div>

@endsection
