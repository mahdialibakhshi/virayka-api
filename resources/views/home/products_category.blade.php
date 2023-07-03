@extends('home.layouts.index')

@section('title')
    سورین همراه | sorinhamrah
@endsection

@section('description')

@endsection

@section('keywords')

@endsection

@section('style')
    <style>
        img {
            height: auto !important;
        }

        .noUi-base {
            left: -12px;
        }
        .quantityMargin{
            margin: 5px 0 ;
        }
    </style>
@endsection

@section('script')
    <script>
        {{--var total = {{ $products->lastPage() }};--}}
        var current_page = 1;
        $('.btn-clear-filter').click(function () {
            window.location.reload();
        })
        // Price slider Active
        // ----------------------------------*/
        $(document).ready(function () {
            slider.noUiSlider.updateOptions({
                start: [{{ $min_price }}, {{ $max_price }}],
                range: {
                    min: {{ $min_price }},
                    max: {{ $max_price }}
                }
            }, true);
        })
        slider.noUiSlider.change(function () {
            filter_products();
        });

        function filter_products() {
            let attribute_values = [];
            let page = 1;
            let sort = $('#sort_products').val();
            let has_quantity = $('#has_quantity input:checked').val();
            let min_price = $('.noUi-handle-lower').find('.noUi-tooltip').text();
            min_price = min_price.replaceAll(',', '');
            min_price = min_price.replaceAll('تومان', '');
            min_price = min_price.replaceAll(' ', '');

            let max_price = $('.noUi-handle-upper').find('.noUi-tooltip').text();
            max_price = max_price.replaceAll(',', '');
            max_price = max_price.replaceAll('تومان', '');
            max_price = max_price.replaceAll(' ', '');
            let brands = [];
            $.each($('#brand_content input:checked'), function () {
                brands.push($(this).val());
            });
            $.each($('.attr_filter_ids:checked'), function () {
                attribute_values.push($(this).val());
            });
            $.ajax({
                url: "{{ route('home.product_categories',['category'=>$category->id]) }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    attribute_values: attribute_values,
                    sort: sort,
                    min_price: min_price,
                    max_price: max_price,
                    page: page,
                    brands: brands,
                    has_quantity: has_quantity,
                },
                dataType: "json",
                type: 'get',
                beforeSend: function () {

                },
                success: function (msg) {
                    if (msg[0] === 1) {
                        $('#products').html(msg[1]);
                        total = msg[2];
                        current_page = 1;

                    }
                }
            })

            // $('#orderby').val(sort);

            // $('#filter_products').submit();
        }

        $('.open_page_description').click(function () {
            let is_open = $(this).attr('data-open');
            let button_html = '';
            if (is_open === 'no') {
                button_html = `نمایش کمتر
                    <i class="fa fa-angle-up ml-3"></i>`;
                $(this).parent().removeClass('page_description');
                $(this).parent().addClass('page_description2');
                $(this).attr('data-open', 'yes');
            } else {
                button_html = `نمایش بیشتر
                    <i class="fa fa-angle-down ml-3"></i>`;
                $(this).parent().removeClass('page_description2');
                $(this).parent().addClass('page_description');
                $(this).attr('data-open', 'no');
            }
            $(this).html(button_html);
        })

        function slideToggleChildren(attr_id, tag) {
            $('#children_attr_' + attr_id).slideToggle();
            $(tag).find('i').toggleClass('active-arrow');
        }
    </script>
@endsection

@section('content')
    <!-- main -->
    <main class="search-page default space-top-30">
        <div class="container">
            <div class="row">
                @if(count($related_categories)>0)
                    <div class="col-12 hidden-xs">
                        <div class="brand-slider card border_all ">
                            <header class="card-header">
                                <h3 class="card-title"><span>دسته بندی های مرتبط</span></h3>
                            </header>
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        @foreach($related_categories as $category)
                                            <div class="col-6 col-md-2 contact-bigicon">

                                                <a href="{{ route('home.product_categories',['category'=>$category->id]) }}"
                                                   target="_blank">
                                                    <img class="img-responsive imgpad"
                                                         src="{{ imageExist(env('CATEGORY_IMAGES_UPLOAD_PATH'),$category->header_image) }}"
                                                         alt="{{ $category->name }}"/>
                                                    <b class="title-3 light-black">{{ $category->name }}</b>
                                                </a>

                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="col-12 hidden-xs">
                    <header class="card-header">
                        <h3 class="card-title"><span>{{ $category->name }}</span></h3>
                    </header>
                </div>
                <aside class="sidebar-page col-12 col-sm-12 col-md-4 col-lg-3 ">
                    <div class="box">
                        <header class="card-header">
                            <h3 class="card-title"><span class="space-right-10">مرتب سازی براساس</span></h3>
                        </header>
                        <div class="box-content">
                            <div class="collapse show mb-3">
                                    <select onchange="filter_products()" id="sort_products"
                                            class="form-control form-control-sm">
                                        <option value="0" {{ $sort==0 ? 'selected' : '' }}>پیش فرض</option>
                                        <option value="1" {{ $sort==1 ? 'selected' : '' }}>جدید ترین</option>
                                        <option value="2" {{ $sort==2 ? 'selected' : '' }}>قدیمی ترین</option>
                                        <option value="3" {{ $sort==3 ? 'selected' : '' }}>قیمت ،نزولی</option>
                                        <option value="4" {{ $sort==4 ? 'selected' : '' }}>قیمت ،صعودی</option>
                                        <option value="5" {{ $sort==5 ? 'selected' : '' }}>محبوب ترین ها</option>
                                    </select>
                            </div>
                        </div>
                    </div>

                    @foreach($attributes as $attribute)
                        <div class="box">
                            <header class="card-header">
                                <h3 class="card-title"><span class="space-right-10">{{ $attribute->name }}</span></h3>
                            </header>
                            <div class="box-content">
                                <div class="collapse show">
                                    @foreach($attribute->AttributeValues()->orderby('priority_show','asc')->whereIn('id',$all_attribute_value_exists_ids)->get() as $attrValue)
                                        <div class="form-account-agree">
                                            <label class="checkbox-form checkbox-primary">
                                                <input autocomplete="off" id="attr_filter_{{ $attrValue->id }}"
                                                       onclick="filter_products()"
                                                       type="checkbox" name="attr_filter_ids[]"
                                                       class="attr_filter_ids"
                                                       value="{{ $attrValue->id }}">
                                                <span class="checkbox-check"></span>
                                            </label>
                                            <label
                                                for="attr_filter_{{ $attrValue->id }}"> {{ $attrValue->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @if(count($brands)>0)
                        <div class="box">
                            <header class="card-header">
                                <h3 class="card-title"><span class="space-right-10">برند ها</span></h3>
                            </header>
                            <div class="box-content">
                                <div id="brand_content" class="collapse show">
                                    @foreach($brands as $brand)
                                        <div class="form-account-agree">
                                            <label class="checkbox-form checkbox-primary">
                                                <input id="brand_{{ $brand->id }}"
                                                       onclick="filter_products()" type="checkbox"
                                                       value="{{ $brand->id }}">
                                                <span class="checkbox-check"></span>
                                            </label>
                                            <label for="brand_{{ $brand->id }}"> {{ $brand->name }}</label>

                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                    @unless($min_price==0 and $max_price==0)
                        <div class="box ">
                            <header class="card-header">
                                <h3 class="card-title"><span class="space-right-10">قیمت</span></h3>
                            </header>
                            <div class="box-content space-40 space-right-25 space-left-25">
                                <div id="slider">
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="box">
                        <div class="box-content">
                            <div class="form-account-agree quantityMargin" id="has_quantity">
                                <label class="checkbox-form checkbox-primary">
                                    <input
                                           onclick="filter_products()" type="checkbox"
                                           value="1">
                                    <span class="checkbox-check"></span>
                                </label>
                                <label for="has_quantity">موجود در انبار</label>
                            </div>
                        </div>
                    </div>
                    <div>
                        <button type="button" onclick="filter_products()" class="btn btn-warning btn-block">Filter
                        </button>
                    </div>
                </aside>
                <div class="col-12 col-sm-12 col-md-8 col-lg-9">

                    <div class="listing default ">
                        <div class="tab-content default text-center">
                            <div class="tab-pane active" id="suggestion" role="tabpanel" aria-expanded="true">
                                <div class="row listing-items" id="products">
                                    @include('home.sections.product_box_2')
                                </div>
                            </div>
                            <div class="tab-pane" id="most-visited" role="tabpanel" aria-expanded="false">
                                <div class="row listing-items">
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 list_search_p ">
                                        <div class="product-box">
                                            <div class="product-seller-details product-seller-details-item-grid">
                                                    <span class="search_prod_icon">
                                                        <i class="fa fa-search search_icon_search"
                                                           aria-hidden="true"></i>
                                                        <i class="fa fa-heart search_icon_like" aria-hidden="true"></i>
                                                    </span>


                                                <span class="search_prod_btn">
                                                        <i class="fa fa fa-cart-arrow-down search_icon_cart"
                                                           aria-hidden="true"></i>
                                                    </span>
                                            </div>
                                            <a class="product-box-img" href="single-product.html">
                                                <img src="/home/img/product_img/p_21.jpg" alt="">
                                                <ul>
                                                    <li class="color_pro"
                                                        style="background-color: #ff6a00;top: 7px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #278e3c;top: 20px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #d500ff;top: 33px;"></li>
                                                </ul>
                                            </a>
                                            <div class="product-box-content">
                                                <div class="product-box-content-row">
                                                    <div class="product_title">
                                                        <a href="#">
                                                            اپل مدل Iphone 14 Pro Max
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="product-box-row product_price_search">
                                                    <div class="price">
                                                        <del><span>85,156,000<span>تومان</span></span></del>
                                                        <span class="discount_badge">5%</span>
                                                        <ins><span>75,255,000<span>تومان</span></span></ins>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 list_search_p ">
                                        <div class="product-box">
                                            <div class="product-seller-details product-seller-details-item-grid">
                                                    <span class="search_prod_icon">
                                                        <i class="fa fa-search search_icon_search"
                                                           aria-hidden="true"></i>
                                                        <i class="fa fa-heart search_icon_like" aria-hidden="true"></i>
                                                    </span>


                                                <span class="search_prod_btn">
                                                        <i class="fa fa fa-cart-arrow-down search_icon_cart"
                                                           aria-hidden="true"></i>
                                                    </span>
                                            </div>
                                            <a class="product-box-img" href="single-product.html">
                                                <img src="/home/img/product_img/p_20.jpg" alt="">
                                                <ul>
                                                    <li class="color_pro"
                                                        style="background-color: #ff6a00;top: 7px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #278e3c;top: 20px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #d500ff;top: 33px;"></li>
                                                </ul>
                                            </a>
                                            <div class="product-box-content">
                                                <div class="product-box-content-row">
                                                    <div class="product_title">
                                                        <a href="#">
                                                            گوشی موبایل ردمی نوت 15 شیائومی
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="product-box-row product_price_search">
                                                    <div class="price">
                                                        <del><span>35,156,000<span>تومان</span></span></del>
                                                        <span class="discount_badge">10%</span>
                                                        <ins><span>25,255,000<span>تومان</span></span></ins>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 list_search_p ">
                                        <div class="product-box">
                                            <div class="product-seller-details product-seller-details-item-grid">
                                                    <span class="search_prod_icon">
                                                        <i class="fa fa-search search_icon_search"
                                                           aria-hidden="true"></i>
                                                        <i class="fa fa-heart search_icon_like" aria-hidden="true"></i>
                                                    </span>


                                                <span class="search_prod_btn">
                                                        <i class="fa fa fa-cart-arrow-down search_icon_cart"
                                                           aria-hidden="true"></i>
                                                    </span>
                                            </div>
                                            <a class="product-box-img" href="single-product.html">
                                                <img src="/home/img/product_img/p_15.jpg" alt="">
                                                <ul>
                                                    <li class="color_pro"
                                                        style="background-color: #37d3c0;top: 7px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #000;top: 20px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #93d337;top: 33px;"></li>
                                                </ul>
                                            </a>
                                            <div class="product-box-content">
                                                <div class="product-box-content-row">
                                                    <div class="product_title">
                                                        <a href="#">
                                                            گوشی موبایل سامسونگ گلکسی A32
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="product-box-row product_price_search">
                                                    <div class="price">

                                                        <ins><span>16,255,000<span>تومان</span></span></ins>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 list_search_p ">
                                        <div class="product-box">
                                            <div class="product-seller-details product-seller-details-item-grid">
                                                    <span class="search_prod_icon">
                                                        <i class="fa fa-search search_icon_search"
                                                           aria-hidden="true"></i>
                                                        <i class="fa fa-heart search_icon_like" aria-hidden="true"></i>
                                                    </span>


                                                <span class="search_prod_btn">
                                                        <i class="fa fa fa-cart-arrow-down search_icon_cart"
                                                           aria-hidden="true"></i>
                                                    </span>
                                            </div>
                                            <a class="product-box-img" href="single-product.html">
                                                <img src="/home/img/product_img/p_19.jpg" alt="">
                                                <ul>
                                                    <li class="color_pro" style="background-color: #000;top: 7px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #f00;top: 20px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #0f0;top: 33px;"></li>
                                                </ul>
                                            </a>
                                            <div class="product-box-content">
                                                <div class="product-box-content-row">
                                                    <div class="product_title">
                                                        <a href="#">
                                                            گوشی موبایل ردمی نوت 11SE شیائومی
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="product-box-row product_price_search">
                                                    <div class="price">
                                                        <del><span>72,156,000<span>تومان</span></span></del>
                                                        <span class="discount_badge">2%</span>
                                                        <ins><span>69,255,000<span>تومان</span></span></ins>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 list_search_p ">
                                        <div class="product-box">
                                            <div class="product-seller-details product-seller-details-item-grid">
                                                    <span class="search_prod_icon">
                                                        <i class="fa fa-search search_icon_search"
                                                           aria-hidden="true"></i>
                                                        <i class="fa fa-heart search_icon_like" aria-hidden="true"></i>
                                                    </span>


                                                <span class="search_prod_btn">
                                                        <i class="fa fa fa-cart-arrow-down search_icon_cart"
                                                           aria-hidden="true"></i>
                                                    </span>
                                            </div>
                                            <a class="product-box-img" href="single-product.html">
                                                <img src="/home/img/product_img/p_17.jpg" alt="">
                                                <ul>
                                                    <li class="color_pro" style="background-color: #000;top: 7px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #f00;top: 20px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #0f0;top: 33px;"></li>
                                                </ul>
                                            </a>
                                            <div class="product-box-content">
                                                <div class="product-box-content-row">
                                                    <div class="product_title">
                                                        <a href="#">
                                                            گوشی موبایل سامسونگ مدل گلکسی A23
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="product-box-row product_price_search">
                                                    <div class="price">

                                                        <ins><span>32,255,000<span>تومان</span></span></ins>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 list_search_p ">
                                        <div class="product-box">
                                            <div class="product-seller-details product-seller-details-item-grid">
                                                    <span class="search_prod_icon">
                                                        <i class="fa fa-search search_icon_search"
                                                           aria-hidden="true"></i>
                                                        <i class="fa fa-heart search_icon_like" aria-hidden="true"></i>
                                                    </span>


                                                <span class="search_prod_btn">
                                                        <i class="fa fa fa-cart-arrow-down search_icon_cart"
                                                           aria-hidden="true"></i>
                                                    </span>
                                            </div>
                                            <a class="product-box-img" href="single-product.html">
                                                <img src="/home/img/product_img/p_9.jpg" alt="">
                                                <ul>
                                                    <li class="color_pro"
                                                        style="background-color: #794cc3;top: 7px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #18bd71;top: 20px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #d500ff;top: 33px;"></li>
                                                </ul>
                                            </a>
                                            <div class="product-box-content">
                                                <div class="product-box-content-row">
                                                    <div class="product_title">
                                                        <a href="#">
                                                            گوشی موبایل شیائومی مدل Poco X4 Pro 5G
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="product-box-row product_price_search">
                                                    <div class="price">

                                                        <ins><span>15,255,000<span>تومان</span></span></ins>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 list_search_p ">
                                        <div class="product-box">
                                            <div class="product-seller-details product-seller-details-item-grid">
                                                    <span class="search_prod_icon">
                                                        <i class="fa fa-search search_icon_search"
                                                           aria-hidden="true"></i>
                                                        <i class="fa fa-heart search_icon_like" aria-hidden="true"></i>
                                                    </span>


                                                <span class="search_prod_btn">
                                                        <i class="fa fa fa-cart-arrow-down search_icon_cart"
                                                           aria-hidden="true"></i>
                                                    </span>
                                            </div>
                                            <a class="product-box-img" href="single-product.html">
                                                <img src="/home/img/product_img/p_22.jpg" alt="">
                                                <ul>
                                                    <li class="color_pro"
                                                        style="background-color: #ff0075;top: 7px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #1aea44;top: 20px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #70367c;top: 33px;"></li>
                                                </ul>
                                            </a>
                                            <div class="product-box-content">
                                                <div class="product-box-content-row">
                                                    <div class="product_title">
                                                        <a href="#">
                                                            آیفون 12 پرو مکس اپل
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="product-box-row product_price_search">
                                                    <div class="price">
                                                        <del><span>65,156,000<span>تومان</span></span></del>
                                                        <span class="discount_badge">10%</span>
                                                        <ins><span>62,200,000<span>تومان</span></span></ins>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 list_search_p ">
                                        <div class="product-box">
                                            <div class="product-seller-details product-seller-details-item-grid">
                                                    <span class="search_prod_icon">
                                                        <i class="fa fa-search search_icon_search"
                                                           aria-hidden="true"></i>
                                                        <i class="fa fa-heart search_icon_like" aria-hidden="true"></i>
                                                    </span>


                                                <span class="search_prod_btn">
                                                        <i class="fa fa fa-cart-arrow-down search_icon_cart"
                                                           aria-hidden="true"></i>
                                                    </span>
                                            </div>
                                            <a class="product-box-img" href="single-product.html">
                                                <img src="/home/img/product_img/p_23.jpg" alt="">
                                                <ul>
                                                    <li class="color_pro"
                                                        style="background-color: #272082;top: 7px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #8e278c;top: 20px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #ff004e;top: 33px;"></li>
                                                </ul>
                                            </a>
                                            <div class="product-box-content">
                                                <div class="product-box-content-row">
                                                    <div class="product_title">
                                                        <a href="#">
                                                            گلکسی اس 21 اولترا سامسونگ

                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="product-box-row product_price_search">
                                                    <div class="price">
                                                        <del><span>65,156,000<span>تومان</span></span></del>
                                                        <span class="discount_badge">10%</span>
                                                        <ins><span>62,200,000<span>تومان</span></span></ins>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 list_search_p ">
                                        <div class="product-box">
                                            <div class="product-seller-details product-seller-details-item-grid">
                                                    <span class="search_prod_icon">
                                                        <i class="fa fa-search search_icon_search"
                                                           aria-hidden="true"></i>
                                                        <i class="fa fa-heart search_icon_like" aria-hidden="true"></i>
                                                    </span>


                                                <span class="search_prod_btn">
                                                        <i class="fa fa fa-cart-arrow-down search_icon_cart"
                                                           aria-hidden="true"></i>
                                                    </span>
                                            </div>
                                            <a class="product-box-img" href="single-product.html">
                                                <img src="/home/img/product_img/p_7.jpg" alt="">
                                                <ul>
                                                    <li class="color_pro"
                                                        style="background-color: #2fabd3;top: 7px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #4e1dac;top: 20px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #ff0075;top: 33px;"></li>
                                                </ul>
                                            </a>
                                            <div class="product-box-content">
                                                <div class="product-box-content-row">
                                                    <div class="product_title">
                                                        <a href="#">
                                                            گوشی موبایل اپل مدل Iphone 13 Pro Max
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="product-box-row product_price_search">
                                                    <div class="price">
                                                        <del><span>72,156,000<span>تومان</span></span></del>
                                                        <span class="discount_badge">2%</span>
                                                        <ins><span>69,255,000<span>تومان</span></span></ins>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="delivery" role="tabpanel" aria-expanded="false">
                                <div class="row listing-items">
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 list_search_p ">
                                        <div class="product-box">
                                            <div class="product-seller-details product-seller-details-item-grid">
                                                    <span class="search_prod_icon">
                                                        <i class="fa fa-search search_icon_search"
                                                           aria-hidden="true"></i>
                                                        <i class="fa fa-heart search_icon_like" aria-hidden="true"></i>
                                                    </span>


                                                <span class="search_prod_btn">
                                                        <i class="fa fa fa-cart-arrow-down search_icon_cart"
                                                           aria-hidden="true"></i>
                                                    </span>
                                            </div>
                                            <a class="product-box-img" href="single-product.html">
                                                <img src="/home/img/product_img/p_19.jpg" alt="">
                                                <ul>
                                                    <li class="color_pro" style="background-color: #000;top: 7px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #f00;top: 20px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #0f0;top: 33px;"></li>
                                                </ul>
                                            </a>
                                            <div class="product-box-content">
                                                <div class="product-box-content-row">
                                                    <div class="product_title">
                                                        <a href="#">
                                                            گوشی موبایل ردمی نوت 11SE شیائومی
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="product-box-row product_price_search">
                                                    <div class="price">
                                                        <del><span>72,156,000<span>تومان</span></span></del>
                                                        <span class="discount_badge">2%</span>
                                                        <ins><span>69,255,000<span>تومان</span></span></ins>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 list_search_p ">
                                        <div class="product-box">
                                            <div class="product-seller-details product-seller-details-item-grid">
                                                    <span class="search_prod_icon">
                                                        <i class="fa fa-search search_icon_search"
                                                           aria-hidden="true"></i>
                                                        <i class="fa fa-heart search_icon_like" aria-hidden="true"></i>
                                                    </span>


                                                <span class="search_prod_btn">
                                                        <i class="fa fa fa-cart-arrow-down search_icon_cart"
                                                           aria-hidden="true"></i>
                                                    </span>
                                            </div>
                                            <a class="product-box-img" href="single-product.html">
                                                <img src="/home/img/product_img/p_21.jpg" alt="">
                                                <ul>
                                                    <li class="color_pro"
                                                        style="background-color: #ff6a00;top: 7px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #278e3c;top: 20px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #d500ff;top: 33px;"></li>
                                                </ul>
                                            </a>
                                            <div class="product-box-content">
                                                <div class="product-box-content-row">
                                                    <div class="product_title">
                                                        <a href="#">
                                                            اپل مدل Iphone 14 Pro Max
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="product-box-row product_price_search">
                                                    <div class="price">
                                                        <del><span>85,156,000<span>تومان</span></span></del>
                                                        <span class="discount_badge">5%</span>
                                                        <ins><span>75,255,000<span>تومان</span></span></ins>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 list_search_p ">
                                        <div class="product-box">
                                            <div class="product-seller-details product-seller-details-item-grid">
                                                    <span class="search_prod_icon">
                                                        <i class="fa fa-search search_icon_search"
                                                           aria-hidden="true"></i>
                                                        <i class="fa fa-heart search_icon_like" aria-hidden="true"></i>
                                                    </span>


                                                <span class="search_prod_btn">
                                                        <i class="fa fa fa-cart-arrow-down search_icon_cart"
                                                           aria-hidden="true"></i>
                                                    </span>
                                            </div>
                                            <a class="product-box-img" href="single-product.html">
                                                <img src="/home/img/product_img/p_9.jpg" alt="">
                                                <ul>
                                                    <li class="color_pro"
                                                        style="background-color: #794cc3;top: 7px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #18bd71;top: 20px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #d500ff;top: 33px;"></li>
                                                </ul>
                                            </a>
                                            <div class="product-box-content">
                                                <div class="product-box-content-row">
                                                    <div class="product_title">
                                                        <a href="#">
                                                            گوشی موبایل شیائومی مدل Poco X4 Pro 5G
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="product-box-row product_price_search">
                                                    <div class="price">

                                                        <ins><span>15,255,000<span>تومان</span></span></ins>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 list_search_p ">
                                        <div class="product-box">
                                            <div class="product-seller-details product-seller-details-item-grid">
                                                    <span class="search_prod_icon">
                                                        <i class="fa fa-search search_icon_search"
                                                           aria-hidden="true"></i>
                                                        <i class="fa fa-heart search_icon_like" aria-hidden="true"></i>
                                                    </span>


                                                <span class="search_prod_btn">
                                                        <i class="fa fa fa-cart-arrow-down search_icon_cart"
                                                           aria-hidden="true"></i>
                                                    </span>
                                            </div>
                                            <a class="product-box-img" href="single-product.html">
                                                <img src="/home/img/product_img/p_7.jpg" alt="">
                                                <ul>
                                                    <li class="color_pro"
                                                        style="background-color: #2fabd3;top: 7px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #4e1dac;top: 20px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #ff0075;top: 33px;"></li>
                                                </ul>
                                            </a>
                                            <div class="product-box-content">
                                                <div class="product-box-content-row">
                                                    <div class="product_title">
                                                        <a href="#">
                                                            گوشی موبایل اپل مدل Iphone 13 Pro Max
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="product-box-row product_price_search">
                                                    <div class="price">
                                                        <del><span>72,156,000<span>تومان</span></span></del>
                                                        <span class="discount_badge">2%</span>
                                                        <ins><span>69,255,000<span>تومان</span></span></ins>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 list_search_p ">
                                        <div class="product-box">
                                            <div class="product-seller-details product-seller-details-item-grid">
                                                    <span class="search_prod_icon">
                                                        <i class="fa fa-search search_icon_search"
                                                           aria-hidden="true"></i>
                                                        <i class="fa fa-heart search_icon_like" aria-hidden="true"></i>
                                                    </span>


                                                <span class="search_prod_btn">
                                                        <i class="fa fa fa-cart-arrow-down search_icon_cart"
                                                           aria-hidden="true"></i>
                                                    </span>
                                            </div>
                                            <a class="product-box-img" href="single-product.html">
                                                <img src="/home/img/product_img/p_17.jpg" alt="">
                                                <ul>
                                                    <li class="color_pro" style="background-color: #000;top: 7px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #f00;top: 20px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #0f0;top: 33px;"></li>
                                                </ul>
                                            </a>
                                            <div class="product-box-content">
                                                <div class="product-box-content-row">
                                                    <div class="product_title">
                                                        <a href="#">
                                                            گوشی موبایل سامسونگ مدل گلکسی A23
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="product-box-row product_price_search">
                                                    <div class="price">

                                                        <ins><span>32,255,000<span>تومان</span></span></ins>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 list_search_p ">
                                        <div class="product-box">
                                            <div class="product-seller-details product-seller-details-item-grid">
                                                    <span class="search_prod_icon">
                                                        <i class="fa fa-search search_icon_search"
                                                           aria-hidden="true"></i>
                                                        <i class="fa fa-heart search_icon_like" aria-hidden="true"></i>
                                                    </span>


                                                <span class="search_prod_btn">
                                                        <i class="fa fa fa-cart-arrow-down search_icon_cart"
                                                           aria-hidden="true"></i>
                                                    </span>
                                            </div>
                                            <a class="product-box-img" href="single-product.html">
                                                <img src="/home/img/product_img/p_15.jpg" alt="">
                                                <ul>
                                                    <li class="color_pro"
                                                        style="background-color: #37d3c0;top: 7px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #000;top: 20px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #93d337;top: 33px;"></li>
                                                </ul>
                                            </a>
                                            <div class="product-box-content">
                                                <div class="product-box-content-row">
                                                    <div class="product_title">
                                                        <a href="#">
                                                            گوشی موبایل سامسونگ گلکسی A32
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="product-box-row product_price_search">
                                                    <div class="price">

                                                        <ins><span>16,255,000<span>تومان</span></span></ins>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 list_search_p ">
                                        <div class="product-box">
                                            <div class="product-seller-details product-seller-details-item-grid">
                                                    <span class="search_prod_icon">
                                                        <i class="fa fa-search search_icon_search"
                                                           aria-hidden="true"></i>
                                                        <i class="fa fa-heart search_icon_like" aria-hidden="true"></i>
                                                    </span>


                                                <span class="search_prod_btn">
                                                        <i class="fa fa fa-cart-arrow-down search_icon_cart"
                                                           aria-hidden="true"></i>
                                                    </span>
                                            </div>
                                            <a class="product-box-img" href="single-product.html">
                                                <img src="/home/img/product_img/p_20.jpg" alt="">
                                                <ul>
                                                    <li class="color_pro"
                                                        style="background-color: #ff6a00;top: 7px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #278e3c;top: 20px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #d500ff;top: 33px;"></li>
                                                </ul>
                                            </a>
                                            <div class="product-box-content">
                                                <div class="product-box-content-row">
                                                    <div class="product_title">
                                                        <a href="#">
                                                            گوشی موبایل ردمی نوت 15 شیائومی
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="product-box-row product_price_search">
                                                    <div class="price">
                                                        <del><span>35,156,000<span>تومان</span></span></del>
                                                        <span class="discount_badge">10%</span>
                                                        <ins><span>25,255,000<span>تومان</span></span></ins>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 list_search_p ">
                                        <div class="product-box">
                                            <div class="product-seller-details product-seller-details-item-grid">
                                                    <span class="search_prod_icon">
                                                        <i class="fa fa-search search_icon_search"
                                                           aria-hidden="true"></i>
                                                        <i class="fa fa-heart search_icon_like" aria-hidden="true"></i>
                                                    </span>


                                                <span class="search_prod_btn">
                                                        <i class="fa fa fa-cart-arrow-down search_icon_cart"
                                                           aria-hidden="true"></i>
                                                    </span>
                                            </div>
                                            <a class="product-box-img" href="single-product.html">
                                                <img src="/home/img/product_img/p_23.jpg" alt="">
                                                <ul>
                                                    <li class="color_pro"
                                                        style="background-color: #272082;top: 7px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #8e278c;top: 20px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #ff004e;top: 33px;"></li>
                                                </ul>
                                            </a>
                                            <div class="product-box-content">
                                                <div class="product-box-content-row">
                                                    <div class="product_title">
                                                        <a href="#">
                                                            گلکسی اس 21 اولترا سامسونگ

                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="product-box-row product_price_search">
                                                    <div class="price">
                                                        <del><span>65,156,000<span>تومان</span></span></del>
                                                        <span class="discount_badge">10%</span>
                                                        <ins><span>62,200,000<span>تومان</span></span></ins>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 list_search_p ">
                                        <div class="product-box">
                                            <div class="product-seller-details product-seller-details-item-grid">
                                                    <span class="search_prod_icon">
                                                        <i class="fa fa-search search_icon_search"
                                                           aria-hidden="true"></i>
                                                        <i class="fa fa-heart search_icon_like" aria-hidden="true"></i>
                                                    </span>


                                                <span class="search_prod_btn">
                                                        <i class="fa fa fa-cart-arrow-down search_icon_cart"
                                                           aria-hidden="true"></i>
                                                    </span>
                                            </div>
                                            <a class="product-box-img" href="single-product.html">
                                                <img src="/home/img/product_img/p_22.jpg" alt="">
                                                <ul>
                                                    <li class="color_pro"
                                                        style="background-color: #ff0075;top: 7px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #1aea44;top: 20px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #70367c;top: 33px;"></li>
                                                </ul>
                                            </a>
                                            <div class="product-box-content">
                                                <div class="product-box-content-row">
                                                    <div class="product_title">
                                                        <a href="#">
                                                            آیفون 12 پرو مکس اپل
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="product-box-row product_price_search">
                                                    <div class="price">
                                                        <del><span>65,156,000<span>تومان</span></span></del>
                                                        <span class="discount_badge">10%</span>
                                                        <ins><span>62,200,000<span>تومان</span></span></ins>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="tab-pane" id="most-seller" role="tabpanel" aria-expanded="false">
                                <div class="row listing-items">
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 list_search_p ">
                                        <div class="product-box">
                                            <div class="product-seller-details product-seller-details-item-grid">
                                                    <span class="search_prod_icon">
                                                        <i class="fa fa-search search_icon_search"
                                                           aria-hidden="true"></i>
                                                        <i class="fa fa-heart search_icon_like" aria-hidden="true"></i>
                                                    </span>


                                                <span class="search_prod_btn">
                                                        <i class="fa fa fa-cart-arrow-down search_icon_cart"
                                                           aria-hidden="true"></i>
                                                    </span>
                                            </div>
                                            <a class="product-box-img" href="single-product.html">
                                                <img src="/home/img/product_img/p_9.jpg" alt="">
                                                <ul>
                                                    <li class="color_pro"
                                                        style="background-color: #794cc3;top: 7px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #18bd71;top: 20px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #d500ff;top: 33px;"></li>
                                                </ul>
                                            </a>
                                            <div class="product-box-content">
                                                <div class="product-box-content-row">
                                                    <div class="product_title">
                                                        <a href="#">
                                                            گوشی موبایل شیائومی مدل Poco X4 Pro 5G
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="product-box-row product_price_search">
                                                    <div class="price">

                                                        <ins><span>15,255,000<span>تومان</span></span></ins>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 list_search_p ">
                                        <div class="product-box">
                                            <div class="product-seller-details product-seller-details-item-grid">
                                                    <span class="search_prod_icon">
                                                        <i class="fa fa-search search_icon_search"
                                                           aria-hidden="true"></i>
                                                        <i class="fa fa-heart search_icon_like" aria-hidden="true"></i>
                                                    </span>


                                                <span class="search_prod_btn">
                                                        <i class="fa fa fa-cart-arrow-down search_icon_cart"
                                                           aria-hidden="true"></i>
                                                    </span>
                                            </div>
                                            <a class="product-box-img" href="single-product.html">
                                                <img src="/home/img/product_img/p_7.jpg" alt="">
                                                <ul>
                                                    <li class="color_pro"
                                                        style="background-color: #2fabd3;top: 7px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #4e1dac;top: 20px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #ff0075;top: 33px;"></li>
                                                </ul>
                                            </a>
                                            <div class="product-box-content">
                                                <div class="product-box-content-row">
                                                    <div class="product_title">
                                                        <a href="#">
                                                            گوشی موبایل اپل مدل Iphone 13 Pro Max
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="product-box-row product_price_search">
                                                    <div class="price">
                                                        <del><span>72,156,000<span>تومان</span></span></del>
                                                        <span class="discount_badge">2%</span>
                                                        <ins><span>69,255,000<span>تومان</span></span></ins>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 list_search_p ">
                                        <div class="product-box">
                                            <div class="product-seller-details product-seller-details-item-grid">
                                                    <span class="search_prod_icon">
                                                        <i class="fa fa-search search_icon_search"
                                                           aria-hidden="true"></i>
                                                        <i class="fa fa-heart search_icon_like" aria-hidden="true"></i>
                                                    </span>


                                                <span class="search_prod_btn">
                                                        <i class="fa fa fa-cart-arrow-down search_icon_cart"
                                                           aria-hidden="true"></i>
                                                    </span>
                                            </div>
                                            <a class="product-box-img" href="single-product.html">
                                                <img src="/home/img/product_img/p_17.jpg" alt="">
                                                <ul>
                                                    <li class="color_pro" style="background-color: #000;top: 7px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #f00;top: 20px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #0f0;top: 33px;"></li>
                                                </ul>
                                            </a>
                                            <div class="product-box-content">
                                                <div class="product-box-content-row">
                                                    <div class="product_title">
                                                        <a href="#">
                                                            گوشی موبایل سامسونگ مدل گلکسی A23
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="product-box-row product_price_search">
                                                    <div class="price">

                                                        <ins><span>32,255,000<span>تومان</span></span></ins>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 list_search_p ">
                                        <div class="product-box">
                                            <div class="product-seller-details product-seller-details-item-grid">
                                                    <span class="search_prod_icon">
                                                        <i class="fa fa-search search_icon_search"
                                                           aria-hidden="true"></i>
                                                        <i class="fa fa-heart search_icon_like" aria-hidden="true"></i>
                                                    </span>


                                                <span class="search_prod_btn">
                                                        <i class="fa fa fa-cart-arrow-down search_icon_cart"
                                                           aria-hidden="true"></i>
                                                    </span>
                                            </div>
                                            <a class="product-box-img" href="single-product.html">
                                                <img src="/home/img/product_img/p_23.jpg" alt="">
                                                <ul>
                                                    <li class="color_pro"
                                                        style="background-color: #272082;top: 7px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #8e278c;top: 20px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #ff004e;top: 33px;"></li>
                                                </ul>
                                            </a>
                                            <div class="product-box-content">
                                                <div class="product-box-content-row">
                                                    <div class="product_title">
                                                        <a href="#">
                                                            گلکسی اس 21 اولترا سامسونگ

                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="product-box-row product_price_search">
                                                    <div class="price">
                                                        <del><span>65,156,000<span>تومان</span></span></del>
                                                        <span class="discount_badge">10%</span>
                                                        <ins><span>62,200,000<span>تومان</span></span></ins>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 list_search_p ">
                                        <div class="product-box">
                                            <div class="product-seller-details product-seller-details-item-grid">
                                                    <span class="search_prod_icon">
                                                        <i class="fa fa-search search_icon_search"
                                                           aria-hidden="true"></i>
                                                        <i class="fa fa-heart search_icon_like" aria-hidden="true"></i>
                                                    </span>


                                                <span class="search_prod_btn">
                                                        <i class="fa fa fa-cart-arrow-down search_icon_cart"
                                                           aria-hidden="true"></i>
                                                    </span>
                                            </div>
                                            <a class="product-box-img" href="single-product.html">
                                                <img src="/home/img/product_img/p_22.jpg" alt="">
                                                <ul>
                                                    <li class="color_pro"
                                                        style="background-color: #ff0075;top: 7px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #1aea44;top: 20px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #70367c;top: 33px;"></li>
                                                </ul>
                                            </a>
                                            <div class="product-box-content">
                                                <div class="product-box-content-row">
                                                    <div class="product_title">
                                                        <a href="#">
                                                            آیفون 12 پرو مکس اپل
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="product-box-row product_price_search">
                                                    <div class="price">
                                                        <del><span>65,156,000<span>تومان</span></span></del>
                                                        <span class="discount_badge">10%</span>
                                                        <ins><span>62,200,000<span>تومان</span></span></ins>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 list_search_p ">
                                        <div class="product-box">
                                            <div class="product-seller-details product-seller-details-item-grid">
                                                    <span class="search_prod_icon">
                                                        <i class="fa fa-search search_icon_search"
                                                           aria-hidden="true"></i>
                                                        <i class="fa fa-heart search_icon_like" aria-hidden="true"></i>
                                                    </span>


                                                <span class="search_prod_btn">
                                                        <i class="fa fa fa-cart-arrow-down search_icon_cart"
                                                           aria-hidden="true"></i>
                                                    </span>
                                            </div>
                                            <a class="product-box-img" href="single-product.html">
                                                <img src="/home/img/product_img/p_19.jpg" alt="">
                                                <ul>
                                                    <li class="color_pro" style="background-color: #000;top: 7px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #f00;top: 20px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #0f0;top: 33px;"></li>
                                                </ul>
                                            </a>
                                            <div class="product-box-content">
                                                <div class="product-box-content-row">
                                                    <div class="product_title">
                                                        <a href="#">
                                                            گوشی موبایل ردمی نوت 11SE شیائومی
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="product-box-row product_price_search">
                                                    <div class="price">
                                                        <del><span>72,156,000<span>تومان</span></span></del>
                                                        <span class="discount_badge">2%</span>
                                                        <ins><span>69,255,000<span>تومان</span></span></ins>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 list_search_p ">
                                        <div class="product-box">
                                            <div class="product-seller-details product-seller-details-item-grid">
                                                    <span class="search_prod_icon">
                                                        <i class="fa fa-search search_icon_search"
                                                           aria-hidden="true"></i>
                                                        <i class="fa fa-heart search_icon_like" aria-hidden="true"></i>
                                                    </span>


                                                <span class="search_prod_btn">
                                                        <i class="fa fa fa-cart-arrow-down search_icon_cart"
                                                           aria-hidden="true"></i>
                                                    </span>
                                            </div>
                                            <a class="product-box-img" href="single-product.html">
                                                <img src="/home/img/product_img/p_21.jpg" alt="">
                                                <ul>
                                                    <li class="color_pro"
                                                        style="background-color: #ff6a00;top: 7px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #278e3c;top: 20px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #d500ff;top: 33px;"></li>
                                                </ul>
                                            </a>
                                            <div class="product-box-content">
                                                <div class="product-box-content-row">
                                                    <div class="product_title">
                                                        <a href="#">
                                                            اپل مدل Iphone 14 Pro Max
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="product-box-row product_price_search">
                                                    <div class="price">
                                                        <del><span>85,156,000<span>تومان</span></span></del>
                                                        <span class="discount_badge">5%</span>
                                                        <ins><span>75,255,000<span>تومان</span></span></ins>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 list_search_p ">
                                        <div class="product-box">
                                            <div class="product-seller-details product-seller-details-item-grid">
                                                    <span class="search_prod_icon">
                                                        <i class="fa fa-search search_icon_search"
                                                           aria-hidden="true"></i>
                                                        <i class="fa fa-heart search_icon_like" aria-hidden="true"></i>
                                                    </span>


                                                <span class="search_prod_btn">
                                                        <i class="fa fa fa-cart-arrow-down search_icon_cart"
                                                           aria-hidden="true"></i>
                                                    </span>
                                            </div>
                                            <a class="product-box-img" href="single-product.html">
                                                <img src="/home/img/product_img/p_15.jpg" alt="">
                                                <ul>
                                                    <li class="color_pro"
                                                        style="background-color: #37d3c0;top: 7px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #000;top: 20px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #93d337;top: 33px;"></li>
                                                </ul>
                                            </a>
                                            <div class="product-box-content">
                                                <div class="product-box-content-row">
                                                    <div class="product_title">
                                                        <a href="#">
                                                            گوشی موبایل سامسونگ گلکسی A32
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="product-box-row product_price_search">
                                                    <div class="price">

                                                        <ins><span>16,255,000<span>تومان</span></span></ins>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 list_search_p ">
                                        <div class="product-box">
                                            <div class="product-seller-details product-seller-details-item-grid">
                                                    <span class="search_prod_icon">
                                                        <i class="fa fa-search search_icon_search"
                                                           aria-hidden="true"></i>
                                                        <i class="fa fa-heart search_icon_like" aria-hidden="true"></i>
                                                    </span>


                                                <span class="search_prod_btn">
                                                        <i class="fa fa fa-cart-arrow-down search_icon_cart"
                                                           aria-hidden="true"></i>
                                                    </span>
                                            </div>
                                            <a class="product-box-img" href="single-product.html">
                                                <img src="/home/img/product_img/p_20.jpg" alt="">
                                                <ul>
                                                    <li class="color_pro"
                                                        style="background-color: #ff6a00;top: 7px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #278e3c;top: 20px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #d500ff;top: 33px;"></li>
                                                </ul>
                                            </a>
                                            <div class="product-box-content">
                                                <div class="product-box-content-row">
                                                    <div class="product_title">
                                                        <a href="#">
                                                            گوشی موبایل ردمی نوت 15 شیائومی
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="product-box-row product_price_search">
                                                    <div class="price">
                                                        <del><span>35,156,000<span>تومان</span></span></del>
                                                        <span class="discount_badge">10%</span>
                                                        <ins><span>25,255,000<span>تومان</span></span></ins>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                            <div class="tab-pane" id="price" role="tabpanel" aria-expanded="false">
                                <div class="row listing-items">

                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 list_search_p ">
                                        <div class="product-box">
                                            <div class="product-seller-details product-seller-details-item-grid">
                                                    <span class="search_prod_icon">
                                                        <i class="fa fa-search search_icon_search"
                                                           aria-hidden="true"></i>
                                                        <i class="fa fa-heart search_icon_like" aria-hidden="true"></i>
                                                    </span>


                                                <span class="search_prod_btn">
                                                        <i class="fa fa fa-cart-arrow-down search_icon_cart"
                                                           aria-hidden="true"></i>
                                                    </span>
                                            </div>
                                            <a class="product-box-img" href="single-product.html">
                                                <img src="/home/img/product_img/p_22.jpg" alt="">
                                                <ul>
                                                    <li class="color_pro"
                                                        style="background-color: #ff0075;top: 7px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #1aea44;top: 20px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #70367c;top: 33px;"></li>
                                                </ul>
                                            </a>
                                            <div class="product-box-content">
                                                <div class="product-box-content-row">
                                                    <div class="product_title">
                                                        <a href="#">
                                                            آیفون 12 پرو مکس اپل
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="product-box-row product_price_search">
                                                    <div class="price">
                                                        <del><span>65,156,000<span>تومان</span></span></del>
                                                        <span class="discount_badge">10%</span>
                                                        <ins><span>62,200,000<span>تومان</span></span></ins>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 list_search_p ">
                                        <div class="product-box">
                                            <div class="product-seller-details product-seller-details-item-grid">
                                                    <span class="search_prod_icon">
                                                        <i class="fa fa-search search_icon_search"
                                                           aria-hidden="true"></i>
                                                        <i class="fa fa-heart search_icon_like" aria-hidden="true"></i>
                                                    </span>


                                                <span class="search_prod_btn">
                                                        <i class="fa fa fa-cart-arrow-down search_icon_cart"
                                                           aria-hidden="true"></i>
                                                    </span>
                                            </div>
                                            <a class="product-box-img" href="single-product.html">
                                                <img src="/home/img/product_img/p_19.jpg" alt="">
                                                <ul>
                                                    <li class="color_pro" style="background-color: #000;top: 7px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #f00;top: 20px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #0f0;top: 33px;"></li>
                                                </ul>
                                            </a>
                                            <div class="product-box-content">
                                                <div class="product-box-content-row">
                                                    <div class="product_title">
                                                        <a href="#">
                                                            گوشی موبایل ردمی نوت 11SE شیائومی
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="product-box-row product_price_search">
                                                    <div class="price">
                                                        <del><span>72,156,000<span>تومان</span></span></del>
                                                        <span class="discount_badge">2%</span>
                                                        <ins><span>69,255,000<span>تومان</span></span></ins>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 list_search_p ">
                                        <div class="product-box">
                                            <div class="product-seller-details product-seller-details-item-grid">
                                                    <span class="search_prod_icon">
                                                        <i class="fa fa-search search_icon_search"
                                                           aria-hidden="true"></i>
                                                        <i class="fa fa-heart search_icon_like" aria-hidden="true"></i>
                                                    </span>


                                                <span class="search_prod_btn">
                                                        <i class="fa fa fa-cart-arrow-down search_icon_cart"
                                                           aria-hidden="true"></i>
                                                    </span>
                                            </div>
                                            <a class="product-box-img" href="single-product.html">
                                                <img src="/home/img/product_img/p_21.jpg" alt="">
                                                <ul>
                                                    <li class="color_pro"
                                                        style="background-color: #ff6a00;top: 7px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #278e3c;top: 20px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #d500ff;top: 33px;"></li>
                                                </ul>
                                            </a>
                                            <div class="product-box-content">
                                                <div class="product-box-content-row">
                                                    <div class="product_title">
                                                        <a href="#">
                                                            اپل مدل Iphone 14 Pro Max
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="product-box-row product_price_search">
                                                    <div class="price">
                                                        <del><span>85,156,000<span>تومان</span></span></del>
                                                        <span class="discount_badge">5%</span>
                                                        <ins><span>75,255,000<span>تومان</span></span></ins>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 list_search_p ">
                                        <div class="product-box">
                                            <div class="product-seller-details product-seller-details-item-grid">
                                                    <span class="search_prod_icon">
                                                        <i class="fa fa-search search_icon_search"
                                                           aria-hidden="true"></i>
                                                        <i class="fa fa-heart search_icon_like" aria-hidden="true"></i>
                                                    </span>


                                                <span class="search_prod_btn">
                                                        <i class="fa fa fa-cart-arrow-down search_icon_cart"
                                                           aria-hidden="true"></i>
                                                    </span>
                                            </div>
                                            <a class="product-box-img" href="single-product.html">
                                                <img src="/home/img/product_img/p_9.jpg" alt="">
                                                <ul>
                                                    <li class="color_pro"
                                                        style="background-color: #794cc3;top: 7px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #18bd71;top: 20px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #d500ff;top: 33px;"></li>
                                                </ul>
                                            </a>
                                            <div class="product-box-content">
                                                <div class="product-box-content-row">
                                                    <div class="product_title">
                                                        <a href="#">
                                                            گوشی موبایل شیائومی مدل Poco X4 Pro 5G
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="product-box-row product_price_search">
                                                    <div class="price">

                                                        <ins><span>15,255,000<span>تومان</span></span></ins>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 list_search_p ">
                                        <div class="product-box">
                                            <div class="product-seller-details product-seller-details-item-grid">
                                                    <span class="search_prod_icon">
                                                        <i class="fa fa-search search_icon_search"
                                                           aria-hidden="true"></i>
                                                        <i class="fa fa-heart search_icon_like" aria-hidden="true"></i>
                                                    </span>


                                                <span class="search_prod_btn">
                                                        <i class="fa fa fa-cart-arrow-down search_icon_cart"
                                                           aria-hidden="true"></i>
                                                    </span>
                                            </div>
                                            <a class="product-box-img" href="single-product.html">
                                                <img src="/home/img/product_img/p_7.jpg" alt="">
                                                <ul>
                                                    <li class="color_pro"
                                                        style="background-color: #2fabd3;top: 7px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #4e1dac;top: 20px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #ff0075;top: 33px;"></li>
                                                </ul>
                                            </a>
                                            <div class="product-box-content">
                                                <div class="product-box-content-row">
                                                    <div class="product_title">
                                                        <a href="#">
                                                            گوشی موبایل اپل مدل Iphone 13 Pro Max
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="product-box-row product_price_search">
                                                    <div class="price">
                                                        <del><span>72,156,000<span>تومان</span></span></del>
                                                        <span class="discount_badge">2%</span>
                                                        <ins><span>69,255,000<span>تومان</span></span></ins>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 list_search_p ">
                                        <div class="product-box">
                                            <div class="product-seller-details product-seller-details-item-grid">
                                                    <span class="search_prod_icon">
                                                        <i class="fa fa-search search_icon_search"
                                                           aria-hidden="true"></i>
                                                        <i class="fa fa-heart search_icon_like" aria-hidden="true"></i>
                                                    </span>


                                                <span class="search_prod_btn">
                                                        <i class="fa fa fa-cart-arrow-down search_icon_cart"
                                                           aria-hidden="true"></i>
                                                    </span>
                                            </div>
                                            <a class="product-box-img" href="single-product.html">
                                                <img src="/home/img/product_img/p_17.jpg" alt="">
                                                <ul>
                                                    <li class="color_pro" style="background-color: #000;top: 7px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #f00;top: 20px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #0f0;top: 33px;"></li>
                                                </ul>
                                            </a>
                                            <div class="product-box-content">
                                                <div class="product-box-content-row">
                                                    <div class="product_title">
                                                        <a href="#">
                                                            گوشی موبایل سامسونگ مدل گلکسی A23
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="product-box-row product_price_search">
                                                    <div class="price">

                                                        <ins><span>32,255,000<span>تومان</span></span></ins>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 list_search_p ">
                                        <div class="product-box">
                                            <div class="product-seller-details product-seller-details-item-grid">
                                                    <span class="search_prod_icon">
                                                        <i class="fa fa-search search_icon_search"
                                                           aria-hidden="true"></i>
                                                        <i class="fa fa-heart search_icon_like" aria-hidden="true"></i>
                                                    </span>


                                                <span class="search_prod_btn">
                                                        <i class="fa fa fa-cart-arrow-down search_icon_cart"
                                                           aria-hidden="true"></i>
                                                    </span>
                                            </div>
                                            <a class="product-box-img" href="single-product.html">
                                                <img src="/home/img/product_img/p_23.jpg" alt="">
                                                <ul>
                                                    <li class="color_pro"
                                                        style="background-color: #272082;top: 7px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #8e278c;top: 20px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #ff004e;top: 33px;"></li>
                                                </ul>
                                            </a>
                                            <div class="product-box-content">
                                                <div class="product-box-content-row">
                                                    <div class="product_title">
                                                        <a href="#">
                                                            گلکسی اس 21 اولترا سامسونگ

                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="product-box-row product_price_search">
                                                    <div class="price">
                                                        <del><span>65,156,000<span>تومان</span></span></del>
                                                        <span class="discount_badge">10%</span>
                                                        <ins><span>62,200,000<span>تومان</span></span></ins>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 list_search_p ">
                                        <div class="product-box">
                                            <div class="product-seller-details product-seller-details-item-grid">
                                                    <span class="search_prod_icon">
                                                        <i class="fa fa-search search_icon_search"
                                                           aria-hidden="true"></i>
                                                        <i class="fa fa-heart search_icon_like" aria-hidden="true"></i>
                                                    </span>


                                                <span class="search_prod_btn">
                                                        <i class="fa fa fa-cart-arrow-down search_icon_cart"
                                                           aria-hidden="true"></i>
                                                    </span>
                                            </div>
                                            <a class="product-box-img" href="single-product.html">
                                                <img src="/home/img/product_img/p_15.jpg" alt="">
                                                <ul>
                                                    <li class="color_pro"
                                                        style="background-color: #37d3c0;top: 7px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #000;top: 20px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #93d337;top: 33px;"></li>
                                                </ul>
                                            </a>
                                            <div class="product-box-content">
                                                <div class="product-box-content-row">
                                                    <div class="product_title">
                                                        <a href="#">
                                                            گوشی موبایل سامسونگ گلکسی A32
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="product-box-row product_price_search">
                                                    <div class="price">

                                                        <ins><span>16,255,000<span>تومان</span></span></ins>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 list_search_p ">
                                        <div class="product-box">
                                            <div class="product-seller-details product-seller-details-item-grid">
                                                    <span class="search_prod_icon">
                                                        <i class="fa fa-search search_icon_search"
                                                           aria-hidden="true"></i>
                                                        <i class="fa fa-heart search_icon_like" aria-hidden="true"></i>
                                                    </span>


                                                <span class="search_prod_btn">
                                                        <i class="fa fa fa-cart-arrow-down search_icon_cart"
                                                           aria-hidden="true"></i>
                                                    </span>
                                            </div>
                                            <a class="product-box-img" href="single-product.html">
                                                <img src="/home/img/product_img/p_20.jpg" alt="">
                                                <ul>
                                                    <li class="color_pro"
                                                        style="background-color: #ff6a00;top: 7px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #278e3c;top: 20px;"></li>
                                                    <li class="color_pro"
                                                        style="background-color: #d500ff;top: 33px;"></li>
                                                </ul>
                                            </a>
                                            <div class="product-box-content">
                                                <div class="product-box-content-row">
                                                    <div class="product_title">
                                                        <a href="#">
                                                            گوشی موبایل ردمی نوت 15 شیائومی
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="product-box-row product_price_search">
                                                    <div class="price">
                                                        <del><span>35,156,000<span>تومان</span></span></del>
                                                        <span class="discount_badge">10%</span>
                                                        <ins><span>25,255,000<span>تومان</span></span></ins>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- main -->
@endsection
