@extends('admin.layouts.admin')

@section('title')
    افزودن استان
@endsection

@section('content')

    <!-- Content Row -->
    <div class="row">

        <div class="col-xl-12 col-md-12 mb-4 p-4 bg-white">
            <div class="mb-4 text-center text-md-right">
                <h5 class="font-weight-bold">
                    پست پیشتاز - افزودن استان
                </h5>
            </div>
            <hr>
            @include('admin.sections.errors')
            <form action="{{ route('admin.delivery_method.post.add',['method'=>'post']) }}" method="POST">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="is_active">استان</label>
                        <select class="form-control" id="province_id" name="province_id">
                            <option value="" selected>انتخاب کنید</option>
                            @foreach($provinces as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="name">قیمت</label>
                        <input class="form-control" id="price" name="price" type="text" value="{{ old('price') }}" >
                    </div>
                </div>

                <button class="btn btn-outline-primary mt-5" type="submit">ثبت</button>
                <a href="{{ route('admin.delivery_method.edit',['method'=>'post']) }}" class="btn btn-dark mt-5 mr-3">بازگشت</a>
            </form>
        </div>

    </div>

@endsection
