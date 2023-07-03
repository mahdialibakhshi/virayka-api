@extends('admin.layouts.admin')

@section('title')
    index labels
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
        function RemoveModal(label_id){
            let modal=$('#remove_modal');
            modal.modal('show');
            $('#label_id').val(label_id);
        }
        function Remove(){
            let label_id=$('#label_id').val();
            let modal_alert=$('#modal_alert');
            $.ajax({
                url:"{{ route('admin.label.remove') }}",
                data:{
                    _token:"{{ csrf_token() }}",
                    label_id:label_id,
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
                <h5 class="font-weight-bold mb-3 mb-md-0">لیست برچسب ها ({{ $labels->total() }})</h5>
                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.labels.create') }}">
                    <i class="fa fa-plus"></i>
                    ایجاد برچسب
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>نام</th>
                            <th>رنگ</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($labels as $key => $label)
                            <tr>
                                <th>
                                    {{ $labels->firstItem() + $key }}
                                </th>
                                <th>
                                    {{ $label->name }}
                                </th>

                                <th>
                                    <span style="width: 40px;height: 40px;display:block;background-color: {{ $label->color }};margin: 0 auto"></span>
                                </th>

                                <th>
                                    <a class="btn btn-sm btn-outline-info mr-3"
                                        href="{{ route('admin.labels.edit',['label'=>$label->id]) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    @if($label->id!=1)
                                    <button class="btn btn-sm btn-danger" onclick="RemoveModal({{ $label->id }})" type="button">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    @endif
                                </th>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-5">
                {{ $labels->render() }}
            </div>
        </div>
    </div>
    @include('admin.labels.modal')
    @include('admin.labels.alertModal')
@endsection
