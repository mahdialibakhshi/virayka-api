@extends('home.layouts.index')

@section('title')
    پروفایل کاربری
@endsection

@section('description')

@endsection

@section('keywords')

@endsection

@section('style')
    @yield('sub_style')
@endsection

@section('script')

@endsection

@section('content')
    <!-- main -->
    <main class="profile-user-page default space-top-30">
        <div class="container">
            <div class="row">
                @yield('main_content')
                @include('home.users_profile.side_bar')
            </div>
        </div>
    </main>
    <!-- main -->
@endsection
