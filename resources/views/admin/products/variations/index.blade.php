@extends('admin.layouts.admin')

@section('title')
    index products
@endsection
@section('style')
    <style>
        th, td {
            vertical-align: middle !important;
        }
        .img-thumbnail {
            width: 150px;
            height: auto;
        }
    </style>

@endsection

@section('script')
    <script>
        function RemoveModal(variation_id){
            let modal=$('#remove_modal');
            modal.modal('show');
            $('#variation_id').val(variation_id);
        }
        function Remove(){
            let variation_id=$('#variation_id').val();
            $.ajax({
                url:"{{ route('admin.product.variations.remove') }}",
                data:{
                    _token:"{{ csrf_token() }}",
                    variation_id:variation_id,
                },
                dataType:"json",
                type:'POST',
                beforeSend: function () {
                    $("#overlay").fadeIn();
                },
                success:function (msg){
                    if (msg){
                        if (msg[0]==1){
                            let message=msg[1];
                            swal({
                                title:'باتشکر',
                                text:message,
                                icon:'success',
                                timer:3000,
                            })
                            setTimeout(function (){
                                window.location.reload();
                            },3000)
                        }
                    }
                    $("#overlay").fadeOut();

                },
                fail: function (error) {
                    console.log(error);
                    $("#overlay").fadeOut();
                }
            })
        }
    </script>
@endsection

@section('content')

    <!-- Content Row -->
    <div class="row">

        <div class="col-xl-12 col-md-12 mb-4 p-4 bg-white">
            <div class="d-flex flex-column text-center flex-md-row justify-content-md-between mb-4 align-center">
                <h5 class="font-weight-bold mb-3 mb-md-0">{{ $product->name }} - محصولات چندتایی</h5>
                <div>
                    <a class="btn btn-sm btn-outline-dark" href="{{ route('admin.products.index') }}">
                        صفحه محصولات
                        <i class="fa fa-arrow-left mr-2"></i>
                    </a>
                    <a class="btn btn-sm btn-outline-primary"
                       href="{{ route('admin.product.variations.create',['product'=>$product->id]) }}">
                        <i class="fa fa-plus"></i>
                        افزودن محصول
                    </a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center">
                    <thead>
                    <tr>
                        <th>تصویر اصلی</th>
                        <th>ویژگی</th>
                        <th>مقدار ویژگی</th>
                        <th>تعداد</th>
                        <th>قیمت(تومان)</th>
                        <th>ویرایش</th>
                        <th>حذف</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($product_variations as $item)
                        <tr>
                            @if(file_exists(public_path(env('PRODUCT_IMAGES_THUMBNAIL_UPLOAD_PATH').$item->primary_image))
                            and !is_dir(public_path(env('PRODUCT_IMAGES_THUMBNAIL_UPLOAD_PATH').$item->primary_image)))
                                <td>
                                    <img class="img-thumbnail"
                                         src="{{ asset(env('PRODUCT_IMAGES_THUMBNAIL_UPLOAD_PATH').$item->primary_image) }}">
                                </td>
                            @else
                                <td>
                                    <img class="img-thumbnail" src="{{ asset('admin/images/no_image.jpg') }}">
                                </td>
                            @endif
                            <td>
                                {{ $item->VariationName->name }}
                            </td>
                            <td>
                                {{ $item->VariationValue->name }}
                            </td>
                            <td>
                                {{ $item->quantity }}
                            </td>
                            <td>
                                {{ number_format($item->price) }}
                            </td>
                            <td>
                                <a href="{{ route('admin.product.variations.edit',['variation'=>$item->id]) }}" class="btn btn-sm btn-primary">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </td>
                            <td>
                                <button type="button" onclick="RemoveModal({{ $item->id }})"
                                        class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-5">

            </div>

        </div>
    </div>
    @include('admin.products.variations.modal')
@endsection
