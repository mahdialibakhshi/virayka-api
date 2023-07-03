@extends('admin.layouts.admin')

@section('title')
    Edit labels
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

        <div class="col-12 mb-4 p-4 bg-white">
            <div class="mb-4 text-center text-md-right">
                <h5 class="font-weight-bold">تنظیمات محدودیت سفارش</h5>
            </div>
            <hr>
            @include('admin.sections.errors')
            <form action="{{ route('admin.orders.limit.update',['id'=>$limit->id]) }}" method="POST"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-row">
                    <div class="form-group col-md-4 col-12">
                        <label title="مشخص کنید افراد حقیقی حداکثر چند سفارش از هر محصول میتوانند ثبت کنند؟" for="count">محدودیت تعداد سفارش اعضاء حقیقی</label>
                        <input title="مشخص کنید افراد حقیقی حداکثر چند سفارش از هر محصول میتوانند ثبت کنند؟" type="number" class="form-control form-control-sm" name="count" value="{{ $limit->count }}">
                    </div>
                    <div class="form-group col-md-4 col-12">
                        <label title="مشخص کنید چند روز پس از سفارش سقف کالایی، کاربر میتواند مجددا سفارس ثبت کند؟" for="count">فاصله زمانی بین رفع محدودیت ها برای افراد حقیقی(روز)</label>
                        <input type="number" title="مشخص کنید چند روز پس از سفارش سقف کالایی، کاربر میتواند مجددا سفارس ثبت کند؟" class="form-control form-control-sm" name="day" value="{{ $limit->day }}">
                    </div>
                    <div class="form-group col-md-4 col-12">
                        <label for="count">وضعیت</label>
                        <select name="is_active" class="form-control form-control-sm">
                            <option {{ $limit->is_active==1 ? 'selected' : '' }} value="1">فعال</option>
                            <option {{ $limit->is_active==0 ? 'selected' : '' }} value="0">غیرفعال</option>
                        </select>
                    </div>
                </div>
                <button class="btn btn-outline-primary mt-5" type="submit">ثبت</button>
                <a href="{{ route('admin.labels.index') }}" class="btn btn-dark mt-5 mr-3">بازگشت</a>
            </form>
        </div>

    </div>

@endsection
