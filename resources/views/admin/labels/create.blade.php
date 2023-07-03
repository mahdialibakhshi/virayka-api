@extends('admin.layouts.admin')

@section('title')
    create labels
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

        <div class="col-md-4 mb-4 p-4 bg-white">
            <div class="mb-4 text-center text-md-right">
                <h5 class="font-weight-bold">ایجاد برچسب</h5>
            </div>
            <hr>
            @include('admin.sections.errors')
            <form action="{{ route('admin.labels.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-row">
                    <div class="form-group col-md-10">
                        <label for="name">نام</label>
                        <input class="form-control" id="name" name="name" type="text" value="{{ old('name') }}">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="color">رنگ</label>
                        <input class="form-control" id="color" name="color" type="color" value="{{ old('color') }}">
                    </div>
                </div>

                <button class="btn btn-outline-primary mt-5" type="submit">ثبت</button>
                <a href="{{ route('admin.labels.index') }}" class="btn btn-dark mt-5 mr-3">بازگشت</a>
            </form>
        </div>

    </div>

@endsection
