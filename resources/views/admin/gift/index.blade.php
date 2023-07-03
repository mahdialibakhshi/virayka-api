@extends('admin.layouts.admin')

@section('title')
    index Gifts
@endsection

@section('style')
    <style>
        .img-thumbnail{
         max-width: 100px;
            height: auto;
        }
        th{
            vertical-align: middle !important;
        }
    </style>
@endsection

@section('script')
    <script>
        function RemoveModal(gift_id){
            let modal=$('#remove_modal');
            modal.modal('show');
            $('#gift_id').val(gift_id);
        }
        function Remove(){
            let gift_id=$('#gift_id').val();
            let modal_alert=$('#modal_alert');
            $.ajax({
                url:"{{ route('admin.gift.remove') }}",
                data:{
                    _token:"{{ csrf_token() }}",
                    gift_id:gift_id,
                },
                dataType:"json",
                type:'POST',
                beforeSend:function (){

                },
                success:function (msg){
                    console.log(msg);
                    if (msg){
                        if (msg[0]==0){
                            let error=msg[1];
                            let items=msg[2];
                            let row='';
                            console.log(items);
                            $.each(items,function (i,item){
                                row+=`<div><a href="${item.link}">${item.name}</a></div>`;
                            })
                            $('#modal_text').html(error+row);
                            modal_alert.modal('show');
                        }
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
                <h5 class="font-weight-bold mb-3 mb-md-0">لیست هدیه ها ({{ $gifts->total() }})</h5>
                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.gift.create') }}">
                    <i class="fa fa-plus"></i>
                    ایجاد هدیه
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>حداقل تراکنش</th>
                            <th>هدیه</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($gifts as $key => $gift)
                            <tr>
                                <th>
                                    {{ $gifts->firstItem() + $key }}
                                </th>
                                <th>
                                    {{ number_format($gift->transaction).' تومان ' }}
                                </th>
                                <th>
                                    {{ number_format($gift->gift).' تومان ' }}
                                </th>
                                <th>
                                    <a class="btn btn-sm btn-outline-info mr-3"
                                        href="{{ route('admin.gift.edit',['gift'=>$gift->id]) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <button class="btn btn-sm btn-danger" onclick="RemoveModal({{ $gift->id }})" type="button">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </th>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-5">
                {{ $gifts->render() }}
            </div>
        </div>
    </div>
    @include('admin.gift.modal')
    @include('admin.gift.alertModal')
@endsection
