@extends('home.layouts.index')

@section('title')
    مقایسه محصولات
@endsection

@section('description')
@endsection

@section('keywords')
@endsection

@section('script')


@endsection

@section('style')
    <style>
        th {
            text-align: center;
        }

        td {
            text-align: center;
            border: 1px solid #AAA;
            padding: 5px;
        }

        img {
            width: 150px;
            height: auto;
        }

        thead {
            height: 70px;
        }
        .color-green{
            color: green !important;
        }
    </style>
@endsection

@section('content')
    <!-- Compare Page Start -->
    <div
        class="page-section section sb-border pb-xs-50 pt-100 pt-lg-80 pt-md-70 pt-sm-60 pt-xs-50 pb-100 pb-lg-80 pb-md-70 pb-sm-60 pt-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <form action="#">
                        <!-- Compare Table -->
                        <div class="compare-table table-responsive">
                            <table class="table ">
                                <tbody>
                                <tr>
                                    <td></td>
                                    @foreach ($products as $item)
                                        <td>
                                            <a class="d-block w-100"
                                               href="{{ route('home.product',['alias'=>$item->alias]) }}">
                                                @if(file_exists(public_path(env('PRODUCT_IMAGES_THUMBNAIL_UPLOAD_PATH').$item->primary_image))
                             and !is_dir(public_path(env('PRODUCT_IMAGES_THUMBNAIL_UPLOAD_PATH').$item->primary_image)))
                                                    <img
                                                        src="{{ url(env('PRODUCT_IMAGES_UPLOAD_PATH') . $item->primary_image) }}"
                                                        alt="">
                                                @else
                                                    <img src="{{ asset('admin/images/no_image.jpg') }}" alt="">
                                                @endif
                                            </a>
                                            <span class="d-block w-100 mt-3">{{ $item->name }}</span>
                                        </td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td>قیمت</td>
                                    @foreach ($products as $item)
                                        <td>
                                            <div class="product-price d-flex justify-content-center">
                                                    {{ number_format(product_price_for_user_normal($item->id)[0]) }}
                                                    تومان
                                            </div>
                                        </td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td>برند</td>
                                    @foreach ($products as $item)
                                        <td>
                                            {{ $item->brand->name??'-' }}
                                        </td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td>موجودی کالا</td>
                                    @foreach ($products as $item)
                                        <td>
                                            {{ $item->quantity==0 ? 'ناموجود' : $item->quantity }}
                                        </td>
                                    @endforeach
                                </tr>
                                @foreach($attributes as $attribute)
                                    <tr>
                                        <td>{{ $attribute->name }}</td>
                                        @foreach ($products as $item)
                                            <td>
                                                <?php
                                                    $product_attribute = \App\Models\ProductAttribute::where('attribute_id', $attribute->id)
                                                           ->where('product_id', $item->id)->first();
                                           		if($product_attribute!=null){
                                                       $attribute_values=$product_attribute->attributeValues($product_attribute->value,$product_attribute->attribute_id);
                                                }
                                               ?>
                                                @if($attribute_values==null)
                                                    {{ isset($product_attribute->value) ? $product_attribute->value : '-' }}
                                                @else
                                                    {{ $attribute_values->name }}
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                                <tr>
                                    <td>عملیات</td>
                                    @foreach ($products as $item)
                                        <td>
                                            <a onclick="AddToCart({{ $item->id }},1)" title="افزودن به سبد خرید">
                                                <i class="w-icon-cart color-green fa-2x"></i>
                                            </a>
                                            <a href="{{ route('home.compare.remove',['productId'=>$item->id]) }}"
                                               title="حذف">
                                                <i class="fa fa-trash color-red fa-2x"></i>
                                            </a>
                                        </td>
                                    @endforeach
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <!-- Compare Page End -->
@endsection
