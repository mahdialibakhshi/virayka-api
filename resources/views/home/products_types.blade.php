@extends('home.layouts.index')

@section('title')
    محصولات بر اساس عملکرد
@endsection

@section('description')

@endsection

@section('keywords')

@endsection

@section('style')

@endsection

@section('script')
<script>
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
    <main class="main">
        <!-- Start of Page Content -->
        <div class="page-content mb-10">
            <div class="container">
                @if($type->banner_image!=null)
                <!-- Start of Shop Banner -->
                <div class="shop-default-banner banner d-flex align-items-center mb-5 br-xs"
                     style="background-image: url({{ imageExist(env('FUNCTIONAL_TYPE_UPLOAD_PATH'),$type->banner_image) }}); background-color: #FFC74E;">
                </div>
                @endif
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
