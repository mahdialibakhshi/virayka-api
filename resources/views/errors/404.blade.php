@extends('home.layouts.index')

@section('title')
    ERROR 404
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
                    <span class="fade-in" id="digit1">4</span>
                    <span class="fade-in" id="digit2">0</span>
                    <span class="fade-in" id="digit3">4</span>
                </h1>
                <h6 class="fadeIn">صفحه‌ای که دنبال آن بودید پیدا نشد!</h6>
                <a href="{{ route('home.index') }}" class="btn btn-main-masai">صفحه نخست</a>
            </div>
        </div>
    </div>
</main>
<!-- main -->

@endsection
