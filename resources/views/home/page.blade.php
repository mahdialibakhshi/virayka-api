@extends('home.layouts.index')

@section('title')
    {{ $page->title }}
@endsection


@section('description')

@endsection

@section('keywords')

@endsection

@section('style')
<style>
    .p-0{
        padding: 0 !important;
    }
    #send_price_peyk{
        overflow-x: scroll;
    }
    #send_price_peyk > img{
       max-width: none !important;
    }
</style>
@endsection

@section('script')

@endsection

@section('content')
    <!-- Start of Main -->
    <main class="main">
        <!-- Start of Page Content -->
        <div class="page-content">
            <div class="container">
                <div class="row">
                    @if($page->banner_is_active==1)
                        <div class="col-12 p-0 mt-2">
                            <div class="shop-default-banner banner d-flex align-items-center br-xs"
                                 style="background-image: url({{ imageExist(env('BANNER_PAGES_UPLOAD_PATH'),$page->image) }}); background-color: #FFC74E;">
                            </div>
                        </div>
                    @endif
                    <div class="col-12">
                        <div class="row bg-white p-4 my-4">
{{--                            <div class="col-12">--}}
{{--                                <h2>--}}
{{--                                    {!! $page->title !!}--}}
{{--                                </h2>--}}
{{--                            </div>--}}
{{--                            <div class="col-12 justify-content-center">--}}
{{--                                <hr>--}}
{{--                            </div>--}}
                            <div class="col-12">
                                {!! $page->description !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Page Content -->
    </main>
    <!-- End of Main -->

@endsection
