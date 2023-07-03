@extends('admin.layouts.admin')

@section('title')
    index offer
@endsection

@section('style')
    <style>
        .img-thumbnail {
            max-width: 200px;
            height: auto;
        }

        .border-none {
            border: none !important;
        }
        th{
            vertical-align: middle !important;
        }
    </style>
@endsection

@section('content')

    <!-- Content Row -->
    <div class="row">

        <div class="col-xl-12 col-md-12 mb-4 p-4 bg-white">
            <div class="d-flex flex-column text-center flex-md-row justify-content-md-between mb-4">
                <h5 class="font-weight-bold mb-3 mb-md-0">لیست پیشنهاد ها ({{ $offers->total() }})</h5>
                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.offers.create') }}">
                    <i class="fa fa-plus"></i>
                    ایجاد پیشنهاد
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center">

                    <thead>
                    <tr>
                        <th>#</th>
                        <th>تصویر محصول</th>
                        <th>عنوان</th>
                        <th>تصویر پس‌زمینه</th>
                        <th>لینک دکمه</th>
                        <th>نوع نمایش</th>
                        <th>عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($offers as $key => $offer)
                        <tr>
                            <th>
                                {{ $offers->firstItem() + $key }}
                            </th>
                            <th>
                                @if($offer->product_image==null)
                                    بدون تصویر
                                @else
                                    <a target="_blank"
                                       href="{{ url( env('BANNER_IMAGES_UPLOAD_PATH').$offer->product_image ) }}">
                                        <img class="img-thumbnail"
                                             src="{{ url( env('BANNER_IMAGES_UPLOAD_PATH').$offer->product_image ) }}">
                                    </a>
                                @endif
                            </th>
                            <th>
                                {{ $offer->title==null ? '-' : $offer->title }}
                            </th>
                            <th>
                                @if($offer->bg_image==null)
                                    بدون تصویر
                                @else
                                    <a target="_blank"
                                       href="{{ url( env('BANNER_IMAGES_UPLOAD_PATH').$offer->bg_image ) }}">
                                        <img class="img-thumbnail"
                                             src="{{ url( env('BANNER_IMAGES_UPLOAD_PATH').$offer->bg_image ) }}">
                                    </a>
                                @endif
                            </th>
                            <th>
                                {{ $offer->button_link }}
                            </th>
                            <th>
                                {{ $offer->type==2 ? 'دو ستونه' : 'سه ستونه' }}
                            </th>
                            <th class="d-flex justify-content-center border-none">
                                <form action="{{ route('admin.offers.destroy', ['offer' => $offer->id]) }}"
                                      method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-danger" type="submit">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                                <a class="btn btn-sm btn-info mr-3"
                                   href="{{ route('admin.offers.edit', ['offer' => $offer->id]) }}">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </th>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-5">
                {{ $offers->render() }}
            </div>
        </div>
    </div>
@endsection
