@extends('admin.layouts.admin')

@section('title')
    create coupon
@endsection

@section('style')

@endsection

@section('script')

@endsection
@section('content')

    <!-- Content Row -->
    <div class="row">

        <div class="col-xl-12 col-md-12 mb-4 p-4 bg-white">
            <div class="mb-4 text-center text-md-right">
                <h5 class="font-weight-bold">ایجاد کوپن</h5>
            </div>
            <hr>

            @include('admin.sections.errors')

            <form action="{{ route('admin.coupons.store') }}" method="POST">
                @csrf

                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="name">نام</label>
                        <input disabled class="form-control" value="{{ $coupon->name }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="code">کد</label>
                        <input disabled class="form-control" value="{{ $coupon->code }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="type">نوع</label>
                        <input disabled class="form-control" value="{{ $coupon->type }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="amount">مبلغ (تومان)</label>
                        <input disabled class="form-control" value="{{ $coupon->amount }}">
                    </div>
                    <div id="percentageParent" class="form-group col-md-3">
                        <label for="percentage">درصد</label>
                        <input disabled class="form-control" value="{{ $coupon->percentage }}">
                    </div>
                    <div id="max_percentage_amount_parent" class="form-group col-md-3">
                        <label for="max_percentage_amount">حداکثر مبلغ برای نوع درصدی (تومان)</label>
                        <input disabled class="form-control"  value="{{ $coupon->max_percentage_amount }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="max_percentage_amount">دفعات قابل استفاده</label>
                        <input disabled class="form-control"   value="{{ $coupon->times }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label> تاریخ انقضا  </label>
                        <input disabled class="form-control"   value="{{ verta($coupon->expired_at)->format('Y-d-m') }}">
                    </div>

                    <div class="form-group col-md-12">
                        <label for="description"> کاربر </label>
                        <input disabled class="form-control" id="user_id" name="user_id" type="text" value="{{ $coupon->user_id }}">

                    </div>
                </div>

                <a href="{{ route('admin.coupons.index') }}" class="btn btn-dark mt-5 mr-3">بازگشت</a>
            </form>
        </div>

    </div>

@endsection
