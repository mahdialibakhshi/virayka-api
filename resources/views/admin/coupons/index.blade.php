@extends('admin.layouts.admin')

@section('title')
    index coupons
@endsection

@section('script')
    <script>
        function RemoveModal(coupon_id){
            let modal=$('#remove_modal');
            modal.modal('show');
            $('#coupon_id').val(coupon_id);
        }
        function Remove(){
            let coupon_id=$('#coupon_id').val();
            let modal_alert=$('#modal_alert');
            $.ajax({
                url:"{{ route('admin.coupon.remove') }}",
                data:{
                    _token:"{{ csrf_token() }}",
                    coupon_id:coupon_id,
                },
                dataType:"json",
                type:'POST',
                beforeSend:function (){

                },
                success:function (msg){
                    console.log(msg);
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
                },
            })
        }
    </script>
@endsection

@section('content')

    <!-- Content Row -->
    <div class="row">

        <div class="col-xl-12 col-md-12 mb-4 p-4 bg-white">
            <div class="d-flex flex-column text-center flex-md-row justify-content-md-between mb-4">
                <h5 class="font-weight-bold mb-3 mb-md-0">لیست کوپن ها ({{ $coupons->total() }})</h5>
                <div>
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.coupons.create') }}">
                        <i class="fa fa-plus"></i>
                        ایجاد کوپن
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>کد</th>
                            <th>نوع</th>
                            <th>کاربر</th>
                            <th>باقی مانده</th>
                            <th>تاریخ انقضا</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($coupons as $key => $coupon)
                            <tr>
                                <th>
                                    {{ $coupons->firstItem() + $key }}
                                </th>
                                <th>
                                    {{ $coupon->code }}
                                </th>
                                <th>
                                    {{ $coupon->type }}
                                </th>
                                <th>
                                    {{ $coupon->user_id==null ? 'برای همه کاربران' : $coupon->User->name }}
                                </th>
                                <th>
                                    {{ $coupon->times }}
                                </th>
                                <th>
                                    {{ verta($coupon->expired_at)->format('Y-m-d') }}
                                </th>
                                <th>
                                    <a class="btn btn-sm btn-outline-success"
                                        href="{{ route('admin.coupons.show', ['coupon' => $coupon->id]) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <button onclick="RemoveModal({{ $coupon->id }})" class="btn btn-sm btn-danger mr-3">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </th>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-5">
                {{ $coupons->render() }}
            </div>
        </div>
    </div>
    @include('admin.coupons.modal')
@endsection
