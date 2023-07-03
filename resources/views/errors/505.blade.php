@extends('home.layouts.index')

@section('title')
    ERROR 500
@endsection

@section('description')

@endsection

@section('keywords')

@endsection

@section('style')
    <style>

    </style>
@endsection

@section('script')

@endsection

@section('content')
<!-- main -->
<main class="page-404">
    <div class="container text-center">
        <div class="flex-container">
            <div class="text-center">
                <h1>
                    <span class="fade-in" id="digit1">0</span>
                    <span class="fade-in" id="digit2">0</span>
                    <span class="fade-in" id="digit3">5</span>
                </h1>
                <h6 class="fadeIn">گویا خطایی رخ داده است!</h6>
                <a href="{{ route('home.index') }}" class="btn btn-main-masai">صفحه نخست</a>
            </div>
        </div>
    </div>
</main>
<!-- main -->
@endsection
