@extends('admin.layouts.admin')

@section('title')
    show attributes
@endsection

@section('content')

    <!-- Content Row -->
    <div class="row">

        <div class="col-xl-12 col-md-12 mb-4 p-4 bg-white">
            <div class="mb-4 text-center text-md-right">
                <h5 class="font-weight-bold">مشخصه فنی : {{ $attribute->name }}</h5>
            </div>
            <hr>

            <div class="row">
                <div class="form-group col-md-3">
                    <label>نام</label>
                    <input class="form-control" type="text" value="{{ $attribute->name }}" disabled>
                </div>
                <div class="form-group col-md-3">
                    <label>تاریخ ایجاد</label>
                    <input class="form-control" type="text" value="{{ verta($attribute->created_at) }}" disabled>
                </div>

                <div class="form-group col-md-3">
                    <label>محدودیت انتخاب</label>
                    <input class="form-control" type="text" value="{{  $attribute->name==1 ? 'دارد' : 'ندارد' }}" disabled>
                </div>

            </div>

            <a href="{{ route('admin.attributes.index') }}" class="btn btn-dark mt-5">بازگشت</a>

        </div>

    </div>

@endsection
