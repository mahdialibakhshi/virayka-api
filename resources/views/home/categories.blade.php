@extends('home.layouts.index')

@section('title')
    دسته بندی محصولات
@endsection

@section('description')
@endsection

@section('keywords')
@endsection

@section('style')
@endsection

@section('script')
    <script>
        function activeTab(tab_id, tag) {
            $('.tab-pane').removeClass('active show')
            $('#' + tab_id).addClass('active show');
            $('.tabItem').removeClass('active');
            $(tag).addClass('active');
        }
    </script>
@endsection

@section('content')
    <!-- Page Banner Section Start -->
    <div class="page-banner-section section bg_image--3">
        <div class="container">
            <div class="row">
                <div class="col">

                    <div class="page-banner text-center">
                        <h2>دسته‌بندی‌ها</h2>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- Page Banner Section End -->
    <!--Categories section start-->
    <div
        class="categories-section section  pt-70 pt-lg-50 pt-md-40 pt-sm-30 pt-xs-20 pb-95 pb-lg-75 pb-md-70 pb-sm-60 pb-xs-50">
        <div class="container">
            <div class="row">
                <!-- Categories Action End -->
            @foreach($categories as $item)
                <!-- Single Categories Item Start -->
                    <div class="col-md-3 col-sm-6 mt-30">
                        <div class="single-categories-item">
                            <div class="cate-icon">
                                <img src="{{ asset(env('CATEGORY_IMAGES_UPLOAD_PATH').$item->image) }}" alt="">
                            </div>
                            <div class="cate-content">
                                <a href="{{ route('home.product_categories',['category'=>$item->id]) }}">{{ $item->name }}</a>
                            </div>
                        </div>
                    </div>
                    <!-- Single Categories Item Start -->
                @endforeach
            </div>
            <div class="row mt-3">
                <div class="d-flex justify-content-center">
                    {{ $categories->render() }}
                </div>
            </div>
        </div>
    </div>
    <!--Categories section end-->
@endsection
