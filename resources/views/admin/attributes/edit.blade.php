@extends('admin.layouts.admin')

@section('title')
    edit attributes
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
    </script>
@endsection

@section('content')

    <!-- Content Row -->
    <div class="row">

        <div class="col-xl-12 col-md-12 mb-4 p-4 bg-white">
            <div class="mb-4 text-center text-md-right">
                <h5 class="font-weight-bold">ویرایش مشخصه ی فنی {{ $attribute->name }}</h5>
            </div>
            <hr>

            <div class="row">
                <div class="col-12">
                    <img class="img-thumbnail" src="{{ imageExist(env('ATTR_UPLOAD_PATH'),$attribute->image) }}">
                </div>
                <div class="col-12">
                    <hr>
                </div>
            </div>

            @include('admin.sections.errors')

            <form
                action="{{ route('admin.attributes.update' , ['attribute' => $attribute->id]) }}"
                method="POST"
                enctype="multipart/form-data"
            >
                @csrf
                @method('put')
                <div class="row">
                    <div class="col-md-3">
                        <label for="name">نام</label>
                        <input class="form-control" id="name" name="name" type="text" value="{{ $attribute->name }}">
                    </div>
                    <div class="col-md-3">
                        <label for="name">اولویت نمایش</label>
                        <input class="form-control" id="priority" name="priority" type="number" value="{{ $attribute->priority }}">
                    </div>
                    <div class="col-md-3">
                        <label for="name">انتخاب گروه بندی</label>
                        <select class="form-control" id="group_id" name="group_id">
                            <option value="">بدون گروه بندی</option>
                            @foreach($attr_groups as $group)
                                <option {{ $group->id==$attribute->group_id ? 'selected' : '' }} value="{{ $group->id }}">{{ $group->name.' (ID:'.$group->id.')' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="image"> ویرایش تصویر </label>
                        <div class="custom-file">
                            <input type="file" name="image" class="custom-file-input" id="image">
                            <label class="custom-file-label" for="image"> انتخاب فایل </label>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12 mt-3">
                        <div class="alert alert-danger d-flex justify-content-between">
                            <label for="limit_select" class="m-0">محدودیت انتخاب در اقلام همراه</label>
                            <input {{ $attribute->limit_select==1 ? 'checked' : '' }} type="checkbox" id="limit_select" name="limit_select" value="1">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12 mt-3">
                        <div class="alert alert-warning d-flex justify-content-between">
                            <label for="is_dependence" class="m-0">وابسته به رنگ</label>
                            <input {{ $attribute->is_dependence==1 ? 'checked' : '' }} type="checkbox" id="is_dependence" name="is_dependence" value="1">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12 mt-3">
                        <div class="alert alert-info d-flex justify-content-between">
                            <label for="is_filter" class="m-0">قرار گرفتن در بخش فیلتر</label>
                            <input {{ $attribute->is_filter==1 ? 'checked' : '' }} type="checkbox" id="is_filter" name="is_filter" value="1">
                        </div>
                    </div>
                </div>
                <button class="btn btn-outline-primary mt-5" type="submit">ویرایش</button>
                <a href="{{ route('admin.attributes.index') }}" class="btn btn-dark mt-5 mr-3">بازگشت</a>
            </form>
        </div>

    </div>

@endsection
