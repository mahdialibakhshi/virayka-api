@extends('admin.layouts.admin')

@section('title')
    index products
@endsection
@section('style')
    <style>
        th, td {
            vertical-align: middle !important;
        }
    </style>

@endsection

@section('script')
    <script>
        function RemoveModal(option_id){
            let modal=$('#remove_modal');
            modal.modal('show');
            $('#option_id').val(option_id);
        }
        function Remove(){
            let option_id=$('#option_id').val();
            let product_id="{{ $product->id }}";
            $.ajax({
                url:"{{ route('admin.product.options.remove') }}",
                data:{
                    _token:"{{ csrf_token() }}",
                    option_id:option_id,
                    product_id:product_id,
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
                <h5 class="font-weight-bold mb-3 mb-md-0">{{ $product->name }} - اقلام افزوده</h5>
                <div>
                    <a class="btn btn-sm btn-outline-primary"
                       href="{{ route('admin.product.options.create',['product'=>$product->id]) }}">
                        <i class="fa fa-plus"></i>
                        افزودن
                    </a>
                    <a class="btn btn-sm btn-outline-dark" href="{{ $pre_url }}">
                        <i class="fa fa-arrow-left mr-2"></i>
                    </a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center">

                    <thead>
                    <tr>
                        <th>ویژگی</th>
                        <th>مقدار ویژگی</th>
                        <th>قیمت(تومان)</th>
                        <th>ویرایش</th>
                        <th>حذف</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($product_options as $item)
                        <tr>
                            <td>
                                {{ $item->VariationName->name }}
                            </td>
                            <td>
                                {{ $item->VariationValue->name }}
                            </td>
                            <td>
                                {{ number_format($item->price) }}
                            </td>
                            <td>
                                <a href="{{ route('admin.product.options.edit',['option'=>$item->id]) }}" class="btn btn-sm btn-primary">
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
    @include('admin.products.options.modal')
@endsection
