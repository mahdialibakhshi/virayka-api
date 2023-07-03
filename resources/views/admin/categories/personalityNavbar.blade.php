@extends('admin.layouts.admin')

@section('title')
    شخصی سازی دسته‌بندی
@endsection

@section('style')
    <style>
        input{
            cursor: pointer;
        }
    </style>
@endsection

@section('script')
    <script>
        $('#submitBtn').click(function (){
            $('form').submit();
        })
    </script>
@endsection

@section('content')

    <!-- Content Row -->
    <div class="row">

        <div class="col-xl-12 col-md-12 mb-4 p-4 bg-white">
            <div class="d-flex flex-column text-center flex-md-row justify-content-md-between mb-4">
                <h5 class="font-weight-bold mb-3 mb-md-0">شخصی سازی دسته‌بندی</h5>
                <div class="d-flex">
                    <button id="submitBtn" type="button" class="btn btn-sm btn-outline-success ml-3">
                        ثبت تغییرات
                    </button>
                    <a class="btn btn-sm btn-outline-dark ml-3" href="{{ route('admin.categories.index') }}">
                        بازگشت
                        <i class="fa fa-arrow-left"></i>
                    </a>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.category.personalityNavbar.update') }}">
                @csrf
            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>نام</th>
                        <th>جداکننده</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($categories as $key => $category)
                        <tr>
                            <th>
                                {{ $category->id }}
                            </th>
                            <th>
                                {{ $category->name }}
                            </th>
                            <th>
                               <input {{ $category->full_height ? 'checked' : '' }} type="checkbox" name="ids[]" value="{{ $category->id }}">
                            </th>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            </form>
        </div>
    </div>

    @include('admin.categories.modal')
    @include('admin.categories.alertModal')
@endsection
