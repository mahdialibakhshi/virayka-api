@extends('admin.layouts.admin')

@section('title')
    product attribute index
@endsection

@section('style')
    <style>
        .input-error-validation {
            color: red;
            font-size: 9pt;
        }
        .img-thumbnail{
            max-width: 50px;
            height: auto;
        }
        th,td{
            vertical-align: middle !important;
        }
    </style>
@endsection

@section('script')
    <script>
        $('.btn-outline-primary').click(function () {
            $('#modal_error').html('');
            $('#title').text('افزودن');
            $('#add_update_attribute_values_button').text('افزودن');
            $('#attribute_value_id').val('');
            $('#attribute_value').val('');
        })


        $('#attributeForm').on('submit',function (e) {
            e.preventDefault();
            $('#modal_error').html('');
            let attribute_id = "{{ $attribute->id }}";
            let attribute_value = $('#attribute_value').val();
            let attribute_value_id =  $('#attribute_value_id').val();
            let formData=new FormData(this);
            formData.append('attribute_id',attribute_id);
            formData.append('attribute_value_id',attribute_value_id);
            if (attribute_value == '') {
                alert('هیچ مقداری وارد نکرده اید!')
            } else {
                $.ajax({
                    url: "{{ route('admin.attributes.value.addOrUpdate') }}",
                    data: formData,
                    type: "POST",
                    dataType: "json",
                    contentType: false,
                    processData: false,
                    success: function (msg) {
                        $('#modal_error').html('');
                        if (msg) {
                            if (msg[0] == 'success') {
                                swal({
                                    icon: 'success',
                                    title: 'با تشکر',
                                    text: msg[1],
                                    timer: 1500,
                                })
                                setTimeout(function (){
                                    location.reload();
                                },1500)
                            }
                            if (msg[0] == 'error') {
                                let p = `<p class="input-error-validation">${msg[1]}</p>`;
                                $('#modal_error').append(p);
                            }
                        }
                    },
                    error: function (response) {
                        $.each(response.responseJSON.errors, function (i, v) {
                            let p = `<p class="input-error-validation">${v[0]}</p>`;
                            $('#modal_error').append(p);
                        })
                    }
                })
            }
        })
        function update_attribute_value(attribute_value_id,attribute_value) {
            $('#add_product_attribute').modal('show');
            $('#title').text('ویرایش');
            $('#add_update_attribute_values_button').text('ویرایش');
            $('#attribute_value_id').val(attribute_value_id);
            $('#attribute_value').val(attribute_value);
        }

        function RemoveModal(attr_value_id){
            let modal=$('#remove_modal');
            modal.modal('show');
            $('#attr_value_id').val(attr_value_id);
        }
        function Remove(){
            let attr_value_id=$('#attr_value_id').val();
            let modal_alert=$('#modal_alert');
            $.ajax({
                url:"{{ route('admin.attributes.value.remove') }}",
                data:{
                    _token:"{{ csrf_token() }}",
                    attr_value_id:attr_value_id,
                },
                dataType:"json",
                type:'POST',
                beforeSend:function (){

                },
                success:function (msg){
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
                            window.location.reload();
                        }
                    }
                },
            })
        }
        function priority_show_update(value_id,tag){
            let priority_show=$(tag).val();
            $.ajax({
                url:"{{ route('admin.attribute_values.priority_show_update') }}",
                data:{
                    _token:"{{ csrf_token() }}",
                    value_id:value_id,
                    priority_show:priority_show,
                },
                dataType:"json",
                type:'POST',
                beforeSend: function () {

                },
                success:function (msg){
                    if (msg){
                        if (msg[0]==1){
                            $('#success_alert').show(500);
                            setTimeout(function (){
                                $('#success_alert').hide(500);
                            },3000);
                        }
                    }

                },
                fail: function (error) {
                    console.log(error);
                }
            })
        }
    </script>
@endsection

@section('content')

    <!-- Content Row -->
    <div class="row">

        <div class="col-xl-12 col-md-12 mb-4 p-4 bg-white">
            <div class="d-flex flex-column text-center flex-md-row justify-content-md-between mb-4">
                <h5 class="font-weight-bold mb-3 mb-md-0">
                    مقادیر مربوط به -
                    {{ $attribute->name }}
                </h5>
                <div>
                    <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal"
                            data-target="#add_attribute_values">
                        <i class="fa fa-plus"></i>
                        افزودن
                    </button>
                    <a class="btn btn-sm btn-outline-dark" href="{{ route('admin.attributes.index') }}">
                        بازگشت
                        <i class="fa fa-arrow-left mr-1"></i>
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center">

                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>مقدار</th>
                        <th>تصویر</th>
                        <th>اولویت نمایش</th>
                        <th>ویرایش</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($values as $key => $value)
                        <tr>
                            <th>
                                {{ $value->id }}
                            </th>
                            <th>
                                {{ $value->name }}
                            </th>
                            <th>
                                @if(file_exists(public_path(env('ATTR_UPLOAD_PATH').$value->image)) and !is_dir(public_path(env('ATTR_UPLOAD_PATH').$value->image)))
                                    <img class="img-thumbnail" src="{{ asset(env('ATTR_UPLOAD_PATH').$value->image) }}">
                                @else
                                    -
                                @endif
                            </th>
                            <th>
                                <input onchange="priority_show_update({{ $value->id }},this)" type="number" class="form-control" value="{{ $value->priority_show }}" style="width: 70px">
                            </th>
                            <th>
                               @if($value->id!=346 and $value->id!=217)
                                <button onclick="RemoveModal({{ $value->id }})"
                                        class="btn btn-danger btn-sm">
                                    <i class="fa fa-trash"></i>
                                </button>
                                @endif
                                <button onclick="update_attribute_value({{ $value->id }},'{{ $value->name }}')" class="btn btn-primary btn-sm" data-toggle="modal"
                                        data-target="#add_attribute_values">
                                    <i class="fa fa-edit"></i>
                                </button>
                            </th>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        </div>

        @include('admin.attributes.values.modal')
        @include('admin.attributes.values.removeModal')
        @include('admin.attributes.values.alertModal')
        <div id="success_alert">
            <div class="d-flex justify-content-center align-items-center h-100">تغییرات مورد نظر با موفقیت اعمال شد</div>
        </div>
    </div>
@endsection
