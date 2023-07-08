@extends('home.layouts.index')

@section('title')
    {{ $product->name }} | {{ $setting->name }}
@endsection

@section('description')

@endsection

@section('keywords')

@endsection

@section('style')
    <style>
        .search_prod_icon .search_icon_like {
            cursor: pointer;
            width: 33px;
            height: 33px;
        }

        .product-title {
            font-weight: 900;
            font-size: 15px !important;
            padding-right: 17px;
            color: #000 !important;
            border-top: 4px solid #efefef;
            border-bottom: 4px solid #efefef;
            margin-bottom: 20px;
        }

        input[type='radio'] {
            display: none;
        }

        .product_color > img {
           border: 3px solid #fff;
            outline: 1px solid #7C7C7C;
        }

        #product_attr_variations_categories > label> .ActiveBorder {
            background-color: #7DCACE;
            color: white;
        }
        label >.ActiveBorder > img{
            border: 4px solid var(--yellow) !important;
            outline: none !important;
        }

        .color_base {
            color: #7DCACE;
        }
        .img-variations{
            width: 30px !important;
            height: 30px !important;
        }

        .btn:hover, .btn:active, .btn:focus {
            color: white !important;
            border-color: #7DCACE !important;
            background-color: #7DCACE !important;
        }

        .btn:hover .color_base {
            color: white !important;
        }

        .variationItem {
            width: 142px;
            height: auto;
            display: flex;
            justify-content: center;
            text-align: center;
        }

        .display-none {
            display: none;
        }

        .old_price {
            font-size: 17px;
        }

        .margin-auto {
            margin: 5px auto;
        }

        .discount {
            display: flex;
            width: 71px;
            color: white;
            text-align: center;
            background: #7DCACE;
            vertical-align: middle;
            justify-content: center;
            align-items: center;
            padding: 5px;
            font-family: arial;
            font-size: 20px;
        }

        .product_productInfoRightSide__04W6P > div {
            display: flex;
            flex-wrap: wrap;
        }

        .product_technical__qvJms {
            width: auto;
            min-height: 312px;
            margin-left: 2%;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: baseline;
            margin-top: 30px;
        }

        .product_technical__qvJms > header {
            font-weight: 700;
            color: #666;
            font-size: 16px;
            margin-bottom: 10px !important;
        }

        .brand-image{
            border: 1px solid #cccccc;
        }

        .product_technical__qvJms > ul {
            width: 100%;
        }

        .product_productInfoRightSide__04W6P > div > * {
            margin-bottom: 10px;
        }

        .product_technical__qvJms > ul > li:first-of-type {
            border-top: 1px solid #eee;
        }

        .product_technical__qvJms > ul > li {
            min-height: 40px;
            display: flex;
            border-bottom: 1px solid #eee;
        }

        .product_technical__qvJms > ul > li > span {
            width: 68px;
            min-height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #d3e3f2;
        }

        .product_technical__qvJms > ul > li > h3 {
            width: calc(100% - 68px);
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            padding: 4px 8px 4px 0;
            font-size: 14px;
            color: #1e3b58;
            font-weight: 400;
        }

        h3 {
            margin: 0 !important;
        }

        .property-org {

            text-align: right;
            color: #7f7d7d;
            padding-right: 18px;
            padding-top: 20px;
            margin-bottom: 7px;
        }

        .arrow_icon {
            width: 20px;
            height: auto;
        }

        .product_final_price_span {
            font-size: 17px;
        }

        .color_base {
            color: white;
            font-weight: normal;
            padding: 5px;
        }

        .old_price {
            padding: 8px;
        }

        .oldPrice {
            color: red !important;
            padding: 8px;
        }

        .list-group-item {
            border-bottom: 1px solid #e2e2e2 !important;
            border-top: 1px solid #e2e2e2 !important;
            border-top-left-radius: 0 !important;
            border-top-right-radius: 0 !important;
            padding: 10px 20px !important;

        }

        .new-price {
            margin: 0 !important;
        }
        .w-icon-heart {
            font-size: 25px;
        }

        .w-icon-compare {
            font-size: 25px;
        }

        .search_icon_like {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .search_prod_icon .search_icon_like::before {
            top: 0;
            position: relative;
            right: 0;
        }

        .search_prod_icon, .search_prod_btn {
            position: relative;
            margin-right: 20px;
        }
        .variationItem{
            background-color: #f6f6f6;
            /* padding: 1px 24px; */
            width: 180px;
            margin-right: 14px;
            color: #8f8c8c;
            padding-top: 5px;
        }
        .product_final_price_span{
margin-left: 4px;
        }
        .previous_product_price_span{
            margin-left: 4px;
            font-weight: 800;
        }
        .regular-price{
            background-color: #f6f6f6;
            padding: 3px 40px;
font-size: 17px;
            font-weight: 800;
        }
        .discount{
            height: 83px;
            margin-left: 6px;
            background-color: red;
            font-weight: 800;
        }
        .del-box::after{
            content: ' ';
            display: block;
            height: 3px;
            width: 94px;
            top: 13px;
            left: 3px;
            position: absolute;
            background-color: red;
        }
        .del-box{
            position: relative;
        }
        .price > span{
            font-weight: 800;
        }
        .variationSelected{
            box-shadow: 0px 3px 4px -2px;
        }
        .product-qty-form > i{
            background-color: var(--yellow);
            width: 19px;
            padding: 4px;
            font-size: 10px;
            height: 19px;
            color: #fff;
        }
        #addToCartBtn{
            text-align: center;  background-color: var(--yellow);
            color: red;
            padding: 7px 20px;
            font-weight: bold;}

        #quantity{
            width: 36px;
            background: #F6F6F7;
            outline: none;
            border: none;
            height: 30px;
            font-weight: 900;
            color: #8f8c8c !important;
        }
    </style>
@endsection

@section('script')
    <script>

        $(document).ready(function () {
            $('.product_color').find('input').prop('checked', false);
            let product_colors = {{ count($product_colors) }};
            let product_variation = {{ count($product_attr_variations_categories) }};
            if (product_colors == 1) {
                $("input[name='product_color']").trigger('click');
            }
            if (product_variation == 1) {
                $("input[name='product_attr_variation_categories']").trigger('click');
            }
        })
        function change_quantity(type){
            let quantity = $('#quantity').val();
            if (type==1) {
                quantity = parseInt(quantity) + 1;
            }else {
                quantity = parseInt(quantity) -1;
            }
            if (quantity==0){
                quantity=1;
                $('#quantity').val(quantity);
            }else {
                $('#quantity').val(quantity);
            }


        }
        function getProductColors(attr_value, product_id, tag) {
            $('.colors').removeClass('ActiveBorder');
            $('.product_color').removeClass('ActiveBorder');
            $('.variations').removeClass('deActive');
            $(tag).parents('span').addClass('ActiveBorder');
            let color_id = null;
            // if ($("input[name='product_color']").is(':checked')) {
            //     color_id = $("input[name='product_color']:checked").val();
            // }
            $.ajax({
                url: "{{ route('home.getProductColors') }}",
                data: {
                    attr_value: attr_value,
                    product_id: product_id,
                    color_id: color_id,
                    _token: "{{ csrf_token() }}"
                },
                dataType: "json",
                method: "post",
                success: function (msg) {
                    if (msg[0] == 1) {
                        let array = msg[1];
                        $("input[name='product_color']").attr('disabled', false);
                        $.each(array, function (color_id, quantity) {
                            if (quantity == 0) {
                                $('#product_color_' + color_id).attr('disabled', true);
                                $('#product_color_' + color_id).parents('span').addClass('deActive');
                                $('#product_color_' + color_id).removeAttr('onclick');
                            }
                            if (quantity != 0) {
                                $('#product_color_' + color_id).attr('disabled', false);
                                $('#product_color_' + color_id).attr('onclick', 'getAttributeVariation(' + color_id + ',' + product_id + ',this)');

                            }
                        })
                        if (msg[2] != false) {
                            $('#price_info').html(msg[2]);
                            extraPrice();
                        }
                        if (msg[4] != 0) {
                        }
                    }
                }
            })
        }

        function getAttributeVariation(color_id, product_id, tag) {
            $('.variations').removeClass('ActiveBorder');
            $('.colors').removeClass('deActive');
            $(tag).parents('span').addClass('ActiveBorder');
            let attr_value = null;
            if ($("input[name='product_attr_variation_categories']").is(':checked')) {
                attr_value = $("input[name='product_attr_variation_categories']:checked").val();
            }
            $.ajax({
                url: "{{ route('home.getAttributeVariation') }}",
                data: {
                    attr_value: attr_value,
                    color_id: color_id,
                    product_id: product_id,
                    _token: "{{ csrf_token() }}"
                },
                dataType: "json",
                method: "post",
                success: function (msg) {
                    if (msg[0] == 1) {
                        let image = msg[3];
                        $("input[name='product_attr_variation_categories']").attr('disabled', false);
                        // $.each(array, function (attr_value, quantity) {
                        //     if (quantity == 0) {
                        //         $('#product_attr_variation_categories_' + attr_value).attr('disabled', true);
                        //         $('#product_attr_variation_categories_' + attr_value).parents('span').addClass('deActive');
                        //         $('#product_attr_variation_categories_' + attr_value).next('label').removeAttr('onclick');
                        //     }
                        //     if (quantity != 0) {
                        //         $('#product_attr_variation_categories_' + attr_value).attr('disabled', false);
                        //         $('#product_attr_variation_categories_' + attr_value).next('label').attr('onclick', 'getProductColors(' + attr_value + ',' + product_id + ',this)');
                        //     }
                        // })
                        if (image != null) {
                            $('.swiper-slide-active').find('.product-image').find('img').attr('src', image);
                        }
                        if (msg[2] != false) {
                            $('#price_info').html(msg[2]);
                            extraPrice();
                        }
                    }
                }
            })
        }

        $('.btn-filter-clear').click(function () {
            // getAllProductVariations();
            // getAllProductColors();
            // let alert_message = `<div class="alert alert-danger text-center">انتخاب رنگ و مدل الزامی است</div>`;
            // $('#priceBox').html(alert_message);
            // $('#quantityBox').html('');
            window.location.reload();
        })

        function getAllProductVariations() {
            $.ajax({
                url: "{{ route('home.getAllProductVariations') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    product_id: "{{ $product->id }}",
                },
                dataType: "json",
                method: "post",
                success: function (msg) {
                    $('#product_attr_variations_categories').html('');
                    if (msg[0] == 1) {
                        $('#product_attr_variations_categories').html(msg[1]);
                    }
                }
            })
        }

        function getAllProductColors() {
            $.ajax({
                url: "{{ route('home.getAllProductColors') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    product_id: "{{ $product->id }}",
                },
                dataType: "json",
                method: "post",
                success: function (msg) {
                    $('#getAllProductColors').html('');
                    if (msg[0] == 1) {
                        $('#getAllProductColors').html(msg[1]);
                    }
                }
            })
        }

        function check_product_variation_selected() {
            let product_variation = {{ count($product_attr_variations_categories) }};
            let product_colors = {{ count($product_colors) }};
            let add_to_cart_continue = 1;
            let color_id = null;
            let variation_id = null;
            if (product_colors > 0) {
                if ($("input[name='product_color']").is(':checked')) {
                    color_id = $("input[name='product_color']:checked").val();
                } else {
                    let message = 'انتخاب رنگ الزامی است';
                    swal({
                        title: 'دقت کنید',
                        text: message,
                        icon: 'warning',
                        timer: 3000,
                    });
                    add_to_cart_continue = 0;
                }
            }
            if (product_variation > 0) {
                if ($("input[name='product_attr_variation_categories']").is(':checked')) {
                    variation_id = $("input[name='product_attr_variation_categories']:checked").val();
                } else {
                    let message = 'انتخاب مدل الزامی است';
                    swal({
                        title: 'دقت کنید',
                        text: message,
                        icon: 'warning',
                        timer: 3000,
                    });
                    add_to_cart_continue = 0;
                }
            }
            return [add_to_cart_continue, color_id, variation_id];
        }

        function check_product_option_selected() {
            <?php
            $option_group = [];
            foreach ($product_options_attributes as $product_options_attribute) {
                $attribute = \App\Models\Attribute::where('id', $product_options_attribute)->first();
                $option_group[$product_options_attribute] = $attribute;
            }
            ?>
            let option_group =@json($option_group);
            let add_to_cart_continue = 1;
            $.each(option_group, function (i, val) {
                if (val.limit_select == 1) {
                    let value = $('#option_group_' + i).val();
                    if (value == '') {
                        let message = 'انتخاب ' + val.name + ' الزامی است';
                        swal({
                            title: 'دقت کنید',
                            text: message,
                            icon: 'warning',
                            timer: 3000,
                        });
                        add_to_cart_continue = 0;
                    }
                }
            })
            return add_to_cart_continue;
        }

        //show extra  attr with price when select new attribute
        function extraPrice(product_options_attribute, tag, attribute) {
            if (attribute == 1) {
                $('.option_variation_radio').removeClass('variationSelected');
                $(tag).parent().addClass('variationSelected');
            }
            if (attribute == 0) {
                $('.option_variation_checkbox').removeClass('variationSelected');
            }
            $('#option_group_' + product_options_attribute).val(product_options_attribute);
            var attr = [];
            $.each($("input[data-id='extra_option']:checked"), function () {
                attr.push($(this).val());
                $(this).parent().addClass('variationSelected');
            });
            $('#product_option').val(attr);
            $.ajax({
                url: "{{ route('home.variation.getPrice') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    attr_ids: attr,
                },
                dataType: 'json',
                method: 'post',
                success: function (msg) {
                    if (msg[0] == 1) {
                        //add price
                        let product_price = $('.product_final_price').val();
                        let attr_price = msg[1];
                        let final_price = parseInt(attr_price) + parseInt(product_price);
                        $('.product_final_price_span').text(number_format(final_price));
                        //add span
                        let titles = msg[2];
                        let titles_span = '';
                        $.each(titles, function (i, title) {
                            titles_span = titles_span + `<span class="variationItem" style="font-size: 9pt">${title}</span>`;
                        });
                        // $('.extra_attr').html(titles_span);
                    }
                }
            })
        }

        // $(document).ready(function () {
        //     extraPrice();
        // })

        $('#addToCartBtn').click(function () {
            //check color and variation selected
            check_product_variation_selected();
            //check product_option selected
            check_product_option_selected();
            if (check_product_option_selected() == 1 & check_product_variation_selected()[0] == 1) {
                var option_ids = [];
                $.each($("input[data-id='extra_option']:checked"), function () {
                    option_ids.push($(this).val());
                });
                let product_id = "{{ $product->id }}";
                let product_has_option = {{ count($product_options) }};
                let quantity = $('#quantity').val();
                let variation_id = check_product_variation_selected()[2];
                let color_id = check_product_variation_selected()[1];
                let product_has_variation = {{ count($product_attr_variations_categories) }};
                let product_has_color = {{ count($product_colors) }};
                let is_single_page = 1;
                AddToCart(product_id, quantity, is_single_page, product_has_variation, variation_id, product_has_color, color_id, product_has_option, option_ids);
            }
        })

        function number_format(number, decimals, dec_point, thousands_sep) {
            // *     example: number_format(1234.56, 2, ',', ' ');
            // *     return: '1 234,56'
            number = (number + '').replace(',', '').replace(' ', '');
            var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                s = '',
                toFixedFix = function (n, prec) {
                    var k = Math.pow(10, prec);
                    return '' + Math.round(n * k) / k;
                };
            // Fix for IE parseFloat(0.55).toFixed(0) = 0;
            s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
            }
            return s.join(dec);
        }

        function open_tab_group(tag, attr_group_id) {
            $('#attr_group_table_' + attr_group_id).slideToggle(500);
            $(tag).find('.left_icon').toggleClass('d-none');
            $(tag).find('.down_icon').toggleClass('d-none');
        }
    </script>
@endsection

@section('content')
    <!-- main -->
    <main class="single-product default">
        <div class="container">
            <div class="row mt-3">
                <div class="col-12">
                    <article class="product">
                        <div class="row product_main_details">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="product-gallery default">
                                            <img class="main_img_gallery"
                                                 src="{{ imageExist(env('PRODUCT_IMAGES_UPLOAD_PATH'),$product->primary_image) }}"/>
                                            <section class="testimonial py-3" id="testimonial">
                                                <div class="container">
                                                    <div class="row gallery">
                                                        @foreach($AllProductImages as $image)
                                                            <div class="col-4 col-md-3 pd">
                                                                <a href="{{ imageExist(env('PRODUCT_IMAGES_UPLOAD_PATH'),$image) }}"
                                                                   rel="prettyPhoto[gallery1]">
                                                                    <img
                                                                        src="{{ imageExist(env('PRODUCT_IMAGES_UPLOAD_PATH'),$image) }}"
                                                                        class="img-thumb" alt="نمایشگر همیشه روشن"/>
                                                                </a>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </section>
                                        </div>
                                        <!-- Modal Core -->
                                        <div class="modal-share modal fade" id="myModal" tabindex="-1" role="dialog"
                                             aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-hidden="true">&times;
                                                        </button>
                                                        <h4 class="modal-title" id="myModalLabel">به اشتراک گذاشتن</h4>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <form class="default">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <p>
                                                                        برای کپی آدرس در کادر زیر دابل کلیک کنید
                                                                    </p>
                                                                    <p class="right-side-header shareurlvalue"
                                                                       title="کپی بعد دوبار کلیک" id="text"
                                                                       onclick="copyElementText(this.id)">
                                                                        http://www.mysite.com/single-product.html</p>

                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Modal Core -->
                                    </div>
                                    @if(\Carbon\Carbon::now()> $product->DateOnSaleFrom and \Carbon\Carbon::now()< $product->DateOnSaleTo)
                                        <div class="col-12">
                                            <div
                                                class="d-flex align-center justify-content-center mt-5 border-base p-3">
                                                <p>زمان باقی مانده </p>
                                                <div class="countdown-timer" countdown=""
                                                     data-date="{{ \Carbon\Carbon::parse($product->DateOnSaleTo)->format('m d Y') }} 00:00:00">
                                                    <ul class="text_countdown">
                                                        <li data-days="" class="number_countdown">35</li>
                                                        <li>روز</li>
                                                    </ul>
                                                    <ul class="text_countdown">
                                                        <li data-hours="" class="number_countdown">8</li>
                                                        <li>ساعت</li>
                                                    </ul>
                                                    <ul class="text_countdown">
                                                        <li data-minutes="" class="number_countdown">1</li>
                                                        <li>دقیقه</li>
                                                    </ul>
                                                    <ul class="text_countdown">
                                                        <li data-seconds="" class="number_countdown">35</li>
                                                        <li>ثانیه</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 ">

                                <div style="margin-bottom: 50px" class="row mb-5 mt-2">
                                    <div class="col-4"></div>
                                    <div class="col-4 text-center">
                                        @if($product->brand !=null)
                                            <a href="#">
                                                <img width="110"
                                                     height="110" src="{{imageExist(env('BRAND_UPLOAD_PATH'),$product->brand->image)}}" class="img-fluid brand-image">
                                            </a>
                                        @endif
                                    </div>
                                    <div style="    padding-top: 31px;
    padding-left: 44px;" class="col-4 text-left">
                                        <i class="w-icon-compare mr-2"></i>

                                                            @include('home.sections.wishlist')


                                    </div>
                                </div>
                                <div>
                                    {!! $product->shortDescription !!}
                                </div>
                                <h2 class="product-title ">
                                    {{ $product->name }}
                                </h2>
                                <p class="property-org mt-3">ویژگی های اصلی</p>
                                <div class="row">
                                    <div class="col-12">
                                        <ul class="list-group">
                                            @foreach($product->product_attributes_original() as $product_attributes_original_items)

                                                @include('home.sections.product_attributes')

                                            @endforeach
                                        </ul>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 product_main_pr">
                                        @if(count($product_attr_variations_categories)>0)
                                            <div id="product_attr_variations_categories"
                                                 class="{{ $product_attr_variations_categories[0]->AttributeValue->id==217 ? 'd-none' : 'd-flex' }} flex-wrap"
                                                 style="margin: 10px 0 !important;">
                                                @foreach($product_attr_variations_categories as $item)
                                                    <label
                                                        for="product_attr_variation_categories_{{ $item->AttributeValue->id }}">
                                    <span class="product_color colors">
                                     <input onclick="getProductColors({{ $item->attr_value }},{{ $product->id }},this)"
                                            type="radio" name="product_attr_variation_categories"
                                            id="product_attr_variation_categories_{{ $item->attr_value }}"
                                            value="{{ $item->attr_value }}">

                                        {{ $item->AttributeValue->name }}
                                    </span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        @endif

                                        {{--                            //colors--}}
                                        @if(count($product_colors)>0)
                                            <div id="getAllProductColors"
                                                 class="flex-wrap {{ $product_colors[0]->Color->id==346 ? 'd-none' : 'd-flex' }}"
                                                 style="margin: 10px 0 !important;">
                                                @foreach($product_colors as $key => $product_color)
                                                    <label for="product_color_{{ $product_color->Color->id }}">
                                    <span class="product_color variations">
                                        <img class="img-variations"
                                             src="{{ imageExist(env('ATTR_UPLOAD_PATH'),$product_color->Color->image) }}">
                                     <input
                                         onclick="getAttributeVariation({{ $product_color->Color->id }},{{ $product->id }},this)"
                                         type="radio" name="product_color"
                                         id="product_color_{{ $product_color->Color->id }}"
                                         value="{{ $product_color->Color->id }}"
                                     >
                                         <input value="{{ $product_color->Color->name }}" type="hidden" id="color_name_{{$key}}">

                                    </span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        @endif

                                    <div  class="row">
                                        @if(count($product_options)>0)
                                            <div class="mb-5 col-xl-6">
                                                @foreach($product_options_attributes as $product_options_attribute)
                                                    <div class="d-flex">
                                                        <div class="control-label mb-3"
                                                             style="margin-top: 20px;padding-top: 20px; font-size: 15px; font-weight: bold;">{{ \App\Models\Attribute::where('id',$product_options_attribute)->first()->name }}
                                                            :
                                                        </div>
                                                        <ul style="margin-top: 2.1rem" class="  ">
                                                                <?php
                                                                $i = 0;
                                                                ?>
                                                            @foreach($product_options as $key=>$product_option)
                                                                @if($product_options_attribute==$product_option->attribute_id)
                                                                        <?php
                                                                        $i++;
                                                                        $attribute = \App\Models\Attribute::where('id', $product_options_attribute)->first()->limit_select;
                                                                        if ($attribute == 1) {
                                                                            $class = 'option_variation_radio';
                                                                        } else {
                                                                            $class = 'option_variation_checkbox';
                                                                        }
                                                                        ?>
                                                                    <span class="variationItem mb-2 {{ $class }}">
                                                                <input
                                                                    onclick="extraPrice({{ $product_options_attribute }},this,{{ $attribute }})"
                                                                    data-id="extra_option"
                                                                    id="attr_{{ $product_option->id }}"
                                                                    name="attrs_{{ $product_options_attribute }}"
                                                                    type="{{ $attribute==1 ? 'radio' : 'checkbox'  }}"
                                                                    value="{{ $product_option->id }}"
                                                                        {{--                                                                {{ ($attribute==1 and $i==1) ? 'checked' : '' }}--}}
                                                                >
                                                        <input type="hidden"
                                                               id="option_group_{{ $product_options_attribute }}"
                                                               value="{{ ($attribute==1 and $i==1) ? $product_options_attribute : '' }}"
                                                        >
                                                                <label
                                                                    style="display: flex;align-items:center;justify-content: center;width: 100%;height: 100%;"
                                                                    for="attr_{{ $product_option->id }}">{{ $product_option->VariationValue->name }}</label>
                                                       </span>
                                                                @endif
                                                            @endforeach
                                                        </ul>
                                                    </div>

                                                @endforeach
                                            </div>
                                        @endif
                                        <div style="{{count($product_options)>0 ? ' ' : 'margin-right:auto;margin-left:auto;'}}"  id="price_info col-xl-6">
                                            @include('home.sections.price_box')
                                        </div>
                                    </div>
                                        @if(count($product_attr_variations_categories)>1 and count($product_colors)>1)
                                            <div class="d-flex flex-wrap" style="margin: 10px 0 !important;">
                                                <button class="btn btn-primary btn-filter-clear">
                                                    <span>پاکسازی فیلتر</span>
                                                </button>
                                            </div>
                                        @endif


                                    </div>
                                    @if($setting->product_message!=null)
                                        <div class="col-12">
                                            <p class="txt_note">

                                                {{ $setting->product_message }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-12 default no-padding bg_single_product">
                        <div class="product-tabs default">
                            <div class="box-tabs default">
                                <ul class="nav" role="tablist">
                                    <li class="box-tabs-tab">
                                        <a class="active " data-toggle="tab" href="#desc" role="tab"
                                           aria-expanded="true">
                                            توضیحات تکمیلی
                                        </a>
                                    </li>
                                    <li class="box-tabs-tab">
                                        <a data-toggle="tab" href="#params" role="tab" aria-expanded="false">
                                            مشخصات محصول
                                        </a>
                                    </li>
                                    <li class="box-tabs-tab">
                                        <a data-toggle="tab" href="#comments" role="tab" aria-expanded="false">
                                            دیدگاه خریداران
                                        </a>
                                    </li>
                                    <li class="box-tabs-tab">
                                        <a data-toggle="tab" href="#comments_questions" role="tab"
                                           aria-expanded="false">
                                            پرسش و نظر
                                        </a>
                                    </li>
                                </ul>
                                <div class="card-body default">
                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="desc" role="tabpanel" aria-expanded="true">

                                            <header class="card-header">
                                                <h3 class="card-title"><span>بررسی تخصصی {{ $product->name }} </span>
                                                </h3>
                                            </header>
                                            <div class="parent-expert default">
                                                <div class="content-expert">
                                                    {!! $product->description !!}
                                                </div>
                                            </div>


                                        </div>
                                        @if(count($product_attributes)>0)
                                            <div class="tab-pane params" id="params" role="tabpanel"
                                                 aria-expanded="false">
                                                <header class="card-header">
                                                    <h3 class="card-title"><span>مشخصات فنی {{ $product->name }} </span>
                                                    </h3>
                                                </header>

                                                @foreach($attribute_Groups as $key=>$attribute_Group)
                                                    <div class="mb-2">
                                                        <div onclick="open_tab_group(this,{{ $attribute_Group->id }})"
                                                             class="bg-base p-2 attr_group_tab"
                                                             style="font-weight: bold !important; font-size: 15px; border: 1px solid #ddd;">
                                                            <img
                                                                class="arrow_icon left_icon {{ $key==0 ? 'd-none' : '' }}"
                                                                src="{{ asset('home/img/left.png') }}">
                                                            <img
                                                                class="arrow_icon down_icon {{ $key==0 ? '' : 'd-none' }}"
                                                                src="{{ asset('home/img/down.png') }}">
                                                            {{ $attribute_Group->name }}
                                                        </div>
                                                        <div id="attr_group_table_{{ $attribute_Group->id }}"
                                                             class="table table-bordered {{ $key==0 ? '' : 'display-none' }} attr_children">
                                                            @foreach($product_attributes as $product_attribute)
                                                                @if($product_attribute->attribute->group_id==$attribute_Group->id)
                                                                    <div class="mt-2 d-flex bg-base-light">
                                                        <span
                                                            class="w-30 ml-5 p-2">{{ $product_attribute->attribute->name }} :</span>
                                                                        <span class="w-70 p-2">
                                                            @php
                                                                $attribute_values=$product_attribute->attributeValues($product_attribute->value,$product_attribute->attribute_id);
                                                            @endphp
                                                                            @if($attribute_values==null)
                                                                                {{ $product_attribute->value }}
                                                                            @else
                                                                                {{ $attribute_values->name }}
                                                                            @endif
                                                        </span>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endforeach


                                            </div>
                                        @endif
                                        <div class="tab-pane" id="comments" role="tabpanel" aria-expanded="false">

                                            <header class="card-header">
                                                <h3 class="card-title"><span>دیدگاه های دیگر کاربران</span></h3>
                                            </header>
                                            <div class="comments_form default">
                                                <ol class="comment-list">
                                                    <!-- #comment-## -->
                                                    <li>
                                                        <div class="comment-body">
                                                            <div class="comment-author">
                                                                <img alt="" src="/home/img/profile/1.png"
                                                                     class="avatar"><span class="star4">4.3</span>
                                                                <div class="text-h5">عالی وشیک</div>
                                                            </div>

                                                            <p>محصول بسیار خوبی است. صفحه‌نمایش با کیفیت فوق‌العاده،
                                                                دوربین‌های با کیفیت و روانی کارکرد دستگاه همگی از
                                                                ویژگی‌های مثبت این محصول هستند.</p>
                                                            <ul class="commentul">
                                                                <li>
                                                                    25 اردیبهشت 1402


                                                                </li>
                                                                <li>
                                                                    رضا صبوری
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="comment-body">
                                                            <div class="comment-author">
                                                                <img alt="" src="/home/img/profile/2.png"
                                                                     class="avatar"><span class="star3">3.2</span>
                                                                <div class="text-h5">جنس ضعیف</div>
                                                            </div>

                                                            <p>
                                                                اینقد قیمتش زیاد هست که نمیشه سمتش رفت، خریدم ولی
                                                                پشیمونم، بنظر من نخرید، نوکیا 1100 از این بهتره، خیلی
                                                                کار باهاش هم دشوار هست.
                                                            </p>
                                                            <ul class="commentul">
                                                                <li>
                                                                    30 اردیبهشت 1402


                                                                </li>
                                                                <li>
                                                                    محمود صفایی
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </li>
                                                </ol>
                                            </div>

                                        </div>
                                        <div class="tab-pane form-comment" id="comments_questions" role="tabpanel"
                                             aria-expanded="false">
                                            <header class="card-header">
                                                <h3 class="card-title"><span>ارسال نظر و پرسش  </span></h3>
                                            </header>

                                            <form action="" class="comment">
                                                <textarea class="form-control" placeholder="متن نظر و پرسش"
                                                          rows="4"></textarea>
                                                <button class="btn btn-main-masai">ارسال برای تایید</button>
                                            </form>

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
