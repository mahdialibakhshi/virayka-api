@extends('admin.layouts.admin')

@section('title')
    edit gift
@endsection
@section('script')

@endsection


@section('content')
    <!-- Content Row -->
    <div class="row">
        <div class="col-md-4 mb-4 p-4 bg-white">
            <div class="mb-4 text-center text-md-right">
                <h5 class="font-weight-bold">ویرایش هدیه</h5>
            </div>
            <hr>
            @include('admin.sections.errors')
            <form action="{{ route('admin.gift.update',['gift'=>$gift->id]) }}" method="POST" enctype="multipart/form-data">
                @method('put')
                @csrf
                <div class="form-row">
                    <div class="form-group col-12">
                        <label for="name">حداقل تراکنش(تومان)</label>
                        <input class="form-control" id="transaction" name="transaction" value="{{ number_format($gift->transaction) }}" onkeyup="NumberFormat(this)">
                    </div>
                    <div class="form-group col-md-12">
                        <label for="color">مبلغ هدیه(تومان)</label>
                        <input class="form-control" id="gift" name="gift" value="{{ number_format($gift->gift) }}" onkeyup="NumberFormat(this)">
                    </div>
                </div>
                <button class="btn btn-outline-primary mt-5" type="submit">ثبت</button>
                <a href="{{ route('admin.gift.index') }}" class="btn btn-dark mt-5 mr-3">بازگشت</a>
            </form>
        </div>

    </div>

@endsection
