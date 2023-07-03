@extends('admin.layouts.admin')

@section('title')
    پیک فروشگاه - ویرایش استان
@endsection

@section('content')

    <!-- Content Row -->
    <div class="row">

        <div class="col-xl-12 col-md-12 mb-4 p-4 bg-white">
            <div class="mb-4 text-center text-md-right">
                <h5 class="font-weight-bold">
                    پیک فروشگاه - ویرایش استان
                </h5>
            </div>
            <hr>
            @include('admin.sections.errors')
            <form action="{{ route('admin.delivery_method.peyk.update',['id'=>$id->id]) }}" method="POST">
                @csrf
                @method('put')
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="is_active">استان</label>
                        <select class="form-control" id="province_id" name="province_id">
                            @foreach($provinces as $item)
                                <option {{ $id->province_id==$item->id ? 'selected' : ''  }} value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="name">قیمت</label>
                        <input class="form-control" id="price" name="price" type="text" value="{{ $id->price }}" >
                    </div>
                </div>

                <button class="btn btn-outline-primary mt-5" type="submit">ثبت</button>
                <a href="{{ route('admin.delivery_method.edit',['method'=>'Peyk']) }}" class="btn btn-dark mt-5 mr-3">بازگشت</a>
            </form>
        </div>

    </div>

@endsection
