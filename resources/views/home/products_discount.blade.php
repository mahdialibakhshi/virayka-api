@extends('home.layouts.index')

@section('title')
    محصولات داغ
@endsection

@section('description')

@endsection

@section('keywords')

@endsection

@section('style')

@endsection

@section('script')

@endsection

@section('content')
    <main class="main">
        <!-- Start of Page Content -->
        <div class="page-content mb-10">
            <div class="container">
                <!-- Start of Shop Banner -->
                <div class="shop-default-banner banner d-flex align-items-center mb-5 br-xs"
                     style="background-image: url({{ asset('home/images/shop/banner1.jpg') }}); background-color: #FFC74E;">
                    <div class="banner-content">
                        <h4 class="banner-subtitle font-weight-bold">مجموعه لوازم جانبی </h4>
                        <h3 class="banner-title text-white text-uppercase font-weight-bolder ls-normal">ساعت مچی
                            هوشمند</h3>
                        <a href="shop-banner-sidebar.html" class="btn btn-dark btn-rounded btn-icon-right">اکنون کشف
                            کنید<i class="w-icon-long-arrow-left"></i></a>
                    </div>
                </div>
                <!-- End of Shop Banner -->
                <div class="shop-content">
                    <!-- Start of Shop Main Content -->
                    <div class="main-content">
                        <div class="product-wrapper row cols-lg-5 cols-md-4 cols-sm-3 cols-2">
                            @foreach($products as $product)
                                <div class="product-wrap">
                                    @include('home.sections.product_box')
                                </div>
                            @endforeach
                        </div>

                        <div class="toolbox toolbox-pagination d-flex justify-content-between">
                            <p class="showing-info mb-2 mb-sm-0">
                                نمایش <span>{{ $products->firstItem() }}-{{ $products->lastItem() }} از {{ $products_count }}</span>محصولات
                            </p>
                            {{ $products->render() }}
                        </div>
                    </div>
                    <!-- End of Shop Main Content -->
                </div>
                <!-- End of Shop Content -->

            </div>
        </div>
        <!-- End of Page Content -->
    </main>
@endsection
