@extends('admin.layouts.admin')

@section('title')
    edit attribute Group
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
                <h5 class="font-weight-bold">ویرایش گروه بندی</h5>
            </div>
            <hr>

            @include('admin.sections.errors')

            <form action="{{ route('admin.attributes.group.update',['group'=>$group->id]) }}" method="POST">
                @csrf
                @method('put')

                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="name">نام</label>
                        <input class="form-control" id="name" name="name" type="text" value="{{ $group->name }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="name">اولویت نمایش</label>
                    <input class="form-control" id="priority" name="priority" type="number" value="{{ $group->priority }}">
                </div>
                <button class="btn btn-outline-primary mt-5" type="submit">ثبت</button>
                <a href="{{ route('admin.attributes.groups.index') }}" class="btn btn-dark mt-5 mr-3">بازگشت</a>
            </form>
        </div>

    </div>

@endsection
