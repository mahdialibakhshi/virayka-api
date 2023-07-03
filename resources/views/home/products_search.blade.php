@extends('home.layouts.index')

@section('title')
    محصولات جدید | {{ $setting->name }}
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
    </style>
@endsection

@section('script')
    <script>
        function filter_products() {
            let sort = $('#sort_products').val();
            $('#orderby').val(sort);
            let price_amount = $('#price-amount').val();
            price_amount = price_amount.replaceAll(',', '');
            price_amount = price_amount.replaceAll('تومان', '');
            price_amount = price_amount.replaceAll(' ', '');
            $('#price_range_filter').val(price_amount);
            $('#filter_products').submit();
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

    </script>
@endsection

@section('content')
    <!-- main -->
    <main class="search-page default space-top-30">
        <div class="container">
            <div class="row">
                <div class="col-12 hidden-xs">
                    <header class="card-header">
                        <h3 class="card-title"><span>
                        نتیجه جست و جوی برای عبارت  <span class="font-weight-bolder mr-3">" {{ $title }} "</span>
                            </span>
                        </h3>
                    </header>
                </div>
                <div class="col-12">
                    <div class="listing default ">
                        <div class="tab-content default text-center">
                            <div class="tab-pane active" id="suggestion" role="tabpanel" aria-expanded="true">

                                <div class="row listing-items">
                                    @foreach($products as $product)
                                        <div class="col-xl-3 col-lg-3 col-md-6 col-12 list_search_p ">
                                            @include('home.sections.product_box_2')
                                        </div>
                                    @endforeach
                                </div>
                                <div class="row mt-3">
                                    <div class="col-12 d-flex justify-content-around">
                                        {{ $products->withQueryString()->links() }}
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
