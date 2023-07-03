@extends('admin.layouts.admin')

@section('title')
    ارسال از طریق پیک فروشگاه
@endsection

@section('style')
    <style>
        .close{
            margin: 0 !important;
        }
        .modal-header{
            align-items: center;
        }
    </style>
@endsection

@section('script')
    <script>
        function removeItem(deliveryAmountItem) {
            $('#Item').val(deliveryAmountItem);
        }
    </script>
@endsection

@section('content')

    <!-- Content Row -->
    <div class="row">

        <div class="col-xl-12 col-md-12 mb-4 p-4 bg-white">
            <div class="d-flex flex-column text-center flex-md-row justify-content-md-between mb-4">
                <h5 class="font-weight-bold mb-3 mb-md-0"> پیک فروشگاه -لیست استان ها</h5>
                <div>
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.delivery_method.create',['method'=>'peyk']) }}">
                        <i class="fa fa-plus"></i>
                        افزودن استان
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center">

                    <thead>
                    <tr>
                        <th>ردیف</th>
                        <th>استان</th>
                        <th>تعرفه( تومان )</th>
                        <th>عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($DeliveryMethodAmount as $key=>$item)
                        <tr>
                            <th>
                                {{ $DeliveryMethodAmount->firstItem() + $key }}
                            </th>
                            <th>{{ $item->Province->name }}</th>
                            <th>{{ number_format($item->price) }}</th>
                            <th>
                                <button onclick="removeItem({{ $item->id }})" class="btn btn-danger btn-sm"
                                        data-toggle="modal" data-target="#RemoveModal" type="button">
                                    <i class="fa fa-trash"></i>
                                </button>
                                <a class="btn btn-info btn-sm" href="{{ route('admin.delivery_method.peyk.edit',['id'=>$item->id]) }}">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </th>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

{{--            <div class="d-flex justify-content-center mt-5">--}}
{{--                {{ $coupons->render() }}--}}
{{--            </div>--}}

        </div>

    </div>
    {{--//modal--}}

    @include('admin.deliveryMethods.peyk.delete')


@endsection
