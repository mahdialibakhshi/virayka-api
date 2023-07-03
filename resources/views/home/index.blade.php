@extends('home.layouts.index')

@section('title')
    {{ $setting->title }}
@endsection

@section('description')

@endsection

@section('keywords')

@endsection

@section('style')
<style>
    @media screen and (max-width:867px){
    #offercarousel > .row {
        height:606px !important;
    }
    #offercarousel{
        height:656px !important;
    }
    .timer-title{
        margin-top:25px !important;
    }
    div.price{
         margin-top:25px !important;
    }
    .imgboxofer > a > img{
        margin-bottom:40px;
    }
    
}

@media screen and (max-width:420px){
    #offercarousel .countdown-timer{
        display:flex !important;
        justify-content: space-around !important;
    }
}
.active > .yellow-p{
    color:yellow !important;
}
</style>
@endsection

@section('script')
    <script>
        function ActiveNav(product_id, tag) {
            $('.carousel_nav_item').removeClass('active');
            $(tag).addClass('active');
            $('.carousel_item_div').removeClass('active');
            $('.carousel_item_' + product_id).addClass('active');
        }
    </script>
@endsection

@section('content')
    <main class="main default">
        @if(count($sliders)>0)
            <div class="container-fluid p-0">
                <div class="slider_main owl-carousel owl-theme">
                    @foreach($sliders as $slider)
                        <div class="item">
                            <a href="category-search.html">
                                <img src="{{ imageExist( env('SLIDER_IMAGES_UPLOAD_PATH'),$slider->image ) }}"
                                     class="img-fluid imgslider" alt="">
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        <div class="container space-top-50 ">
            @if(count($brands)>0)
                <div class="row">
                    <div class="col-12">
                        <div class="widget widget-product card border_all">
                            <div class="product-carousel owl-carousel owl-theme text-center">
                                @foreach($brands as $brand)
                                    <div class="item borderitem">
                                        <a href="{{ route('home.products.brand',['brand'=>$brand->id]) }}">
                                            <img src="{{ imageExist(env('BRAND_UPLOAD_PATH'),$brand->image) }}"
                                                 alt="{{ $brand->name }}">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="row align-items-center mt-4">
                @if(count($product_has_sale)>0)
                    <div class="col-12 col-lg-9">
                        <section id="offercarousel" class="carousel slide carousel-fade card border_all"
                                 data-ride="carousel">
                            <span class="product_has_sale_title">
                                Time Base Offer فروش زمان دار
                            </span>
                            <div class="row m-0">
                                <div class="carousel-inner p-0 col-12 col-lg-12">
                                    <ol class="carousel-indicators pr-0 d-flex flex-column ">
                                        @foreach($product_has_sale as $key=>$product)
                                            <li onclick="ActiveNav({{ $product->id }},this)"
                                                class="carousel_nav_item {{ $key==0 ? 'active' : '' }}"
                                                data-target="#offercarousel_{{ $product->id }}"
                                                data-slide-to="{{ $key }}">
                                                <p class="text-center yellow-p">
                                                    {{ $key+1 }}
                                                </p>
                                                <span>
                                                    {{ $product->name }}
                                                </span>
                                            </li>
                                        @endforeach
                                    </ol>
                                    @foreach($product_has_sale as $key=>$product)
                                        <div
                                            class="carousel-item   carousel_item_div carousel_item_{{ $product->id }} {{ $key==0 ? 'active' : '' }} {{ $product->quantity==0 ? 'finished' : '' }}">
                                            <div class="row m-0" style="padding: 0 37px">
                                                <div class="right-col col-12 col-lg-5 d-flex imgboxofer">
                                                    <a class="w-100 text-center"
                                                       href="{{ route('home.product',['alias'=>$product->alias]) }}">
                                                        @if(product_price_for_user_normal($product->id)[1]!=0)
                                                            <span
                                                                class="discount_badge d-flex justify-content-center align-center">
                                                    {{ number_format(product_price_for_user_normal($product->id)[1]).'%' }}
                                                <br>
                                                    OFF
                                                </span>
                                                        @endif
                                                        <img
                                                            src="{{ imageExist(env('PRODUCT_IMAGES_THUMBNAIL_UPLOAD_PATH'),$product->primary_image) }}"
                                                            class="img-fluid"
                                                            alt="">
                                                    </a>
                                                </div>
                                                <div class="left-col col-12 col-lg-7">
                                                    <h2 class="product-title ">
                                                        <a href="{{ route('home.product',['alias'=>$product->alias]) }}"> {{ $product->name }} </a>
                                                    </h2>
                                                    @if(product_price_for_user_normal($product->id)[2]==0)
                                                        <div
                                                            class="price d-flex justify-content-around align-center">
                                                            <ins><span>اتمام موجودی</span></ins>
                                                        </div>
                                                    @else
                                                        <div
                                                            class="row price d-flex justify-content-around align-center mt-3 mb-4">
                                                            <div
                                                                class="col-6 product_has_sale_previous_price text-center">
                                                                @if(product_price_for_user_normal($product->id)[1]!=0)
                                                                    <span>{{ number_format(product_price_for_user_normal($product->id)[0]) }}
                                                                    <span>ت</span>
                                                                </span>
                                                                @endif
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="text-center product_has_sale_price">
                                                                <span>{{ number_format(product_price_for_user_normal($product->id)[2]) }}
                                                                    <span>ت</span>
                                                                </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <div id="product_attrubute_info" class="row mb-4 mt-2">
                                                        @foreach($product->product_attributes_original() as $product_attributes_original_items)
                                                            @include('home.sections.product_attributes')
                                                        @endforeach
                                                    </div>
                                                    @if(\Carbon\Carbon::now()> $product->DateOnSaleFrom and \Carbon\Carbon::now()< $product->DateOnSaleTo)
                                                        <div class="row align-center">
                                                            <div class="col-12 col-lg-3 timer-title text-center">
                                                                <p>زمان باقی مانده </p>
                                                            </div>
                                                            <div class="col-12 col-lg-9 text-center">
                                                                <div class="countdown-timer" countdown
                                                                     data-date="{{ \Carbon\Carbon::parse($product->DateOnSaleTo)->format('m d Y') }} 00:00:00">
                                                                    <ul class="text_countdown">
                                                                        <li data-days class="number_countdown">0</li>
                                                                        <li>روز</li>
                                                                    </ul>
                                                                    <ul class="text_countdown">
                                                                        <li data-hours class="number_countdown">0</li>
                                                                        <li>ساعت</li>
                                                                    </ul>
                                                                    <ul class="text_countdown">
                                                                        <li data-minutes class="number_countdown">0</li>
                                                                        <li>دقیقه</li>
                                                                    </ul>
                                                                    <ul class="text_countdown">
                                                                        <li data-seconds class="number_countdown">0</li>
                                                                        <li>ثانیه</li>
                                                                    </ul>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    @endif

                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </section>
                    </div>
                @endif
                    <div class="col-12 col-lg-3">
                    <div class="widget-bid-s widget">
                        <div id="bid-s" class="owl-carousel owl-theme">
                            @foreach($products_has_sale as $product)
                                <div class="item">
                                    <a href="{{ route('home.product',['alias'=>$product->alias]) }}">
                                        <img
                                            src="{{ imageExist(env('PRODUCT_IMAGES_THUMBNAIL_UPLOAD_PATH'),$product->primary_image) }}"
                                            class="w-100" alt="">
                                    </a>
                                    <h3 class="product-title">
                                        <a href="{{ route('home.product',['alias'=>$product->alias]) }}">
                                            {{ $product->name }}
                                        </a>
                                    </h3>
                                    @if(product_price_for_user_normal($product->id)[2]==0)
                                        <div class="price d-flex justify-content-around align-center">
                                            <ins><span>اتمام موجودی</span></ins>
                                        </div>
                                    @else
                                        <div class="price d-flex justify-content-around align-center">
                                            {{--                                            @if(product_price_for_user_normal($product->id)[1]!=0)--}}
                                            {{--                                                <del><span>{{ number_format(product_price_for_user_normal($product->id)[0]) }}<span>تومان</span></span>--}}
                                            {{--                                                </del>--}}
                                            {{--                                            @endif--}}
                                            @if(product_price_for_user_normal($product->id)[1]!=0)
                                                <span class="discount_badge d-flex justify-content-center align-center">
                                                    {{ number_format(product_price_for_user_normal($product->id)[1]).'%' }}
                                                <br>
                                                    OFF
                                                </span>
                                            @endif

                                            {{--                                            <ins><span>{{ number_format(product_price_for_user_normal($product->id)[2]) }}<span>تومان</span></span>--}}
                                            {{--                                            </ins>--}}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <div id="progressBar">
                            <div class="slide-progress"></div>
                        </div>
                    </div>

                </div>
            </div>
            @if(count($types)>0)
                <div class="row">
                    <div class="col-12">
                        <div class="widget widget-product card">
                            <div class="product-type owl-carousel owl-theme text-center">
                                @foreach($types as $type)
                                    <a href="{{ route('home.products.type',['type'=>$type->id]) }}">
                                        <div class="space-5">
                                            <img alt="{{ $type->title }}"
                                                 src="{{ imageExist(env('FUNCTIONAL_TYPE_UPLOAD_PATH'),$type->image) }}"
                                                 class="minilogo_w">
                                            <b class="d-block title-3 light-black mt-3 mb-3">{{ $type->title }}</b>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if(count($products_special_sale)>0)
                <div class="row">
                    <div class="col-12">
                        <div style="padding-top:0 !important" class="widget widget-product card border_all bglight">
                            <header class="card-header m-0">
                                <p class="special_sale_title text-center">
                                    special offer فروش ویژه

                                </p>
                            </header>
                            <div class="special-product-carousel owl-carousel owl-theme" data-option="{
items: 4,
                                    }">
                                @foreach($products_special_sale as $product)
                                    @include('home.sections.product_box')
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-12">
                    <div class="row banner-ads">
                        <div class="col-12">
                            <div class="row">
                                @foreach($banners as $banner)
                                    @if($banner->position==1)
                                        <div class="col-6 col-lg-3">
                                            <div class="widget-banner card border_all">
                                                <a href="{{ $banner->button_link }}" target="_blank">
                                                    <img class="img-fluid"
                                                         src="{{ imageExist(env('BANNER_IMAGES_UPLOAD_PATH'),$banner->image) }}"
                                                         alt="">
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    @if(\Carbon\Carbon::now()<$setting->expire_amazing_product)
                        <div class="row">
                            <div class="col-12">
                                <div class="widget widget-product card border_all bglight pad_time_prod"
                                     id="shegeft_1">
                                    <header class="card-header">
                                        <h3 class="card-title">
                                            <span>
                                                <img src="/home/img/shegeft_1.png"/>
                                            </span>
                                        </h3>
                                        <div class="countdown-timer" countdown
                                             data-date="{{ \Carbon\Carbon::parse($setting->expire_amazing_product)->month .' '.\Carbon\Carbon::parse($setting->expire_amazing_product)->day.' '. \Carbon\Carbon::parse($setting->expire_amazing_product)->year }} 00:00:00">
                                            <ul class="text_countdown">
                                                <li data-days class="number_countdown">0</li>
                                                <li>روز</li>
                                            </ul>
                                            <ul class="text_countdown">
                                                <li data-hours class="number_countdown">0</li>
                                                <li>ساعت</li>
                                            </ul>
                                            <ul class="text_countdown">
                                                <li data-minutes class="number_countdown">0</li>
                                                <li>دقیقه</li>
                                            </ul>
                                            <ul class="text_countdown">
                                                <li data-seconds class="number_countdown">0</li>
                                                <li>ثانیه</li>
                                            </ul>
                                        </div>
                                    </header>
                                    <div class="product-carousel owl-carousel owl-theme">
                                        @foreach($products_amazing_sale as $product)
                                            @include('home.sections.product_box')
                                        @endforeach
                                    </div>
                                    <a href="#" class="view_more">مشاهده بیشتر</a>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="row banner-ads">
                        <div class="col-12">
                            <div class="row">
                                @foreach($banners as $banner)
                                    @if($banner->position==2)
                                        <div class="col-12 col-lg-6">
                                            <div class="widget-banner card border_all">
                                                <a href="{{ $banner->button_link }}" target="_blank">
                                                    <img class="img-fluid"
                                                         src="{{ imageExist(env('BANNER_IMAGES_UPLOAD_PATH'),$banner->image) }}"
                                                         alt="">
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row banner-ads">
                <div class="col-12">
                    <div class="row">
                        @foreach($banners as $banner)
                            @if($banner->position==3)
                                <div class="col-12">
                                    <div class="widget widget-banner card border_all">
                                        <a href="{{ $banner->button_link }}" target="_blank">
                                            <img class="img-fluid"
                                                 src="{{ imageExist(env('BANNER_IMAGES_UPLOAD_PATH'),$banner->image) }}"
                                                 alt="">
                                        </a>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
                @if(count($products_hit)>0)
                    <div class="row">
                        <div class="col-12">
                            <div class="widget widget-product card border_all bglight">
                                <header>
                                    <p class="best_sale_title text-center">
                                        BEST SELLER پرفروش ترین ها
                                    </p>
                                </header>
                                <div class="newest-product-carousel owl-carousel owl-theme">
                                    @foreach($products_hit as $product)
                                        @include('home.sections.product_box')
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if(count($products_new)>0)
                    <div class="row">
                        <div class="col-12">
                            <div class="widget widget-product card border_all bglight">
                                <header>
                                    <p class="newest_sale_title text-center">
                                        NEWEST جدیدترین ها
                                    </p>
                                </header>
                                <div class="newest-product-carousel owl-carousel owl-theme">
                                    @foreach($products_new as $product)
                                        @include('home.sections.product_box')
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
        </div>


        {{--                @include('home.sections.best_categories')--}}

    </main>
@endsection
