@extends('admin.layouts.admin')

@section('title')
    index paymentMethods
@endsection

@section('style')
   <style>
       .img-thumbnail{
           width: 70px;
           height: auto;
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
                <h5 class="font-weight-bold mb-3 mb-md-0">درگاه های پرداخت  ({{ $paymentMethods->total() }})</h5>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center">

                    <thead>
                    <tr>
                        <th>#</th>
                        <th>نام</th>
                        <th>نماد</th>
                        <th>توضیحات</th>
                        <th>وضعیت</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($paymentMethods as $key=>$item)
                        <tr>
                            <th>
                                {{ $paymentMethods->firstItem() + $key }}
                            </th>
                            <th>
                                {{ $item->name }}
                            </th>
                            <th>
                                @if(file_exists(public_path(env('LOGO_UPLOAD_PATH').$item->image))
                      and !is_dir(public_path(env('LOGO_UPLOAD_PATH').$item->image)))
                                    <img class="img-thumbnail" src="{{ asset(env('LOGO_UPLOAD_PATH').$item->image) }}">
                                @else
                                    <img class="img-thumbnail" src="{{ asset('admin/images/no_image.jpg') }}">
                                @endif
                            </th>
                            <th>
                                {{ $item->description }}
                            </th>
                            <th>
                                <a href="{{ route('admin.paymentMethods.config',['payment'=>$item->id]) }}" class="btn btn-sm btn-primary">
                                    <i class="fa fa-cogs"></i>
                                </a>
                                <a href="{{ route('admin.paymentMethods.changeStatus',['payment'=>$item->id,'status'=>$item->getRawOriginal('is_active')]) }}" class="text-white btn btn-sm {{ $item->getRawOriginal('is_active') ? 'btn-success' : 'btn-danger'  }}">
                                    {{ $item->is_active }}
                                </a>
                            </th>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-5">
                {{ $paymentMethods->render() }}
            </div>

        </div>

    </div>
@endsection
