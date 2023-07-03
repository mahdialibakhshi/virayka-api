@extends('admin.layouts.admin')

@section('title')
    create attributes
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
                <h5 class="font-weight-bold">مشخصه ی فنی جدید</h5>
            </div>
            <hr>

            @include('admin.sections.errors')

            <form action="{{ route('admin.attributes.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-4">
                        <label for="name">نام</label>
                        <input class="form-control" id="name" name="name" type="text" {{ old('name') }}>
                    </div>
                    <div class="col-lg-4">
                        <label for="name">انتخاب گروه بندی</label>
                        <select class="form-control" id="group_id" name="group_id">
                            <option value="">بدون گروه بندی</option>
                            @foreach($attr_groups as $group)
                                <option value="{{ $group->id }}">{{ $group->name.' (ID:'.$group->id.')' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <label for="image"> انتخاب تصویر </label>
                        <div class="custom-file">
                            <input type="file" name="image" class="custom-file-input" id="image">
                            <label class="custom-file-label" for="image"> انتخاب فایل </label>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12 mt-3">
                        <div class="alert alert-danger d-flex justify-content-between">
                            <label for="limit_select" class="m-0">محدودیت انتخاب در اقلام همراه</label>
                            <input type="checkbox" id="limit_select" name="limit_select" value="1" checked>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12 mt-3">
                        <div class="alert alert-warning d-flex justify-content-between">
                            <label for="is_dependence" class="m-0">وابسته به رنگ</label>
                            <input type="checkbox" id="is_dependence" name="is_dependence" value="1">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12 mt-3">
                        <div class="alert alert-info d-flex justify-content-between">
                            <label for="is_filter" class="m-0">قرار گرفتن در بخش فیلتر</label>
                            <input type="checkbox" id="is_filter" name="is_filter" value="1">
                        </div>
                    </div>
                </div>
                <button class="btn btn-outline-primary mt-5" type="submit">ثبت</button>
                <a href="{{ route('admin.attributes.index') }}" class="btn btn-dark mt-5 mr-3">بازگشت</a>
            </form>
        </div>

    </div>

@endsection
