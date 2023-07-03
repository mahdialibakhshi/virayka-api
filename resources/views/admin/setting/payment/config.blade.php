@extends('admin.layouts.admin')

@section('title')
    index paymentMethods
@endsection

@section('content')

    <!-- Content Row -->
    <div class="row">

        <div class="col-xl-12 col-md-12 mb-4 p-4 bg-white">
            <div class="d-flex flex-column text-center flex-md-row justify-content-md-between mb-4">
                <h5 class="font-weight-bold mb-3 mb-md-0">
                    تنظیمات ( {{ $payment->description }} )
                </h5>
            </div>


        </div>

    </div>
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4 p-4 bg-white">
            <div class="text-center">
                <img class="img-thumbnail" src="">

            </div>
            @include('admin.sections.errors')
            <form action="{{ route('admin.paymentMethods.edit',['payment'=>$payment->id]) }}" method="POST"
                  enctype="multipart/form-data">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="name">merchantCode</label>
                        <input class="form-control" id="merchantID" name="merchantID" type="text"
                               value="{{ $payment->merchantID }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="name">کد ترمینال</label>
                        <input class="form-control" id="terminalId" name="terminalId" type="text"
                               value="{{ $payment->terminalId }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="name">نام کاربری</label>
                        <input class="form-control" id="userName" name="userName" type="text"
                               value="{{ $payment->userName }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="name">رمز عبور</label>
                        <input class="form-control" id="userPassword" name="userPassword" type="text"
                               value="{{ $payment->userPassword }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="name">نماد</label>
                        <input class="form-control" id="image" name="image" type="file">
                    </div>
                </div>
                <button class="btn btn-outline-primary mt-5" type="submit">ثبت</button>
                <a href="{{ route('admin.paymentMethods') }}" class="btn btn-outline-dark mt-5" type="button">بازگشت</a>
            </form>
        </div>
    </div>

@endsection
