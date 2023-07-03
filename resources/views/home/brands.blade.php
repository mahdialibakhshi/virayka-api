@extends('home.layouts.index')

@section('title')
    برند ها | {{ $setting->name }}
@endsection

@section('description')

@endsection

@section('keywords')

@endsection

@section('style')
    <style>
        .borderitem{
            border: 1px solid #61bec3 !important;
            padding: 20px !important;
            margin-bottom: 30px !important;
        }
    </style>
@endsection

@section('script')

@endsection

@section('content')
    <main class="main default space-top-10">
        <div class="container space-top-50 ">
            <div class="row">
                @foreach($brands as $brand)
                    <div class="col-12 col-md-4 col-lg-3">
                        <div class="item borderitem">
                            <a href="{{ route('home.products.brand',['brand'=>$brand->id]) }}">
                                <img src="{{ imageExist(env('BRAND_UPLOAD_PATH'),$brand->image) }}"
                                     alt="{{ $brand->name }}">
                                <div class="text-center">{{ $brand->name
 }}</div>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </main>
@endsection
