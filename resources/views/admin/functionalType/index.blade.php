@extends('admin.layouts.admin')

@section('title')
    بر اساس عملکرد
@endsection

@section('style')
    <style>
        .img-thumbnail {
            width: 100px;
            height: auto;
        }
        .banner-thumbnail {
            width: 300px;
            height: auto;
        }
    </style>
@endsection

@section('script')
    <script>
        function RemoveModal(id) {
            let modal = $('#remove_modal');
            modal.modal('show');
            $('#id').val(id);
        }

        function Remove() {
            let id = $('#id').val();
            let modal_alert = $('#modal_alert');
            $.ajax({
                url: "{{ route('admin.functionalType.remove') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                },
                dataType: "json",
                type: 'POST',
                beforeSend: function () {

                },
               success:function (msg){
                   if (msg) {
                       if (msg[0] == 0) {
                           let error = msg[1];
                           let items = msg[2];
                           let row = '';
                           console.log(items);
                           $.each(items, function (i, item) {
                               row += `<div><a href="${item.link}">${item.name}</a></div>`;
                           })
                           $('#modal_text').html(error + row);
                           modal_alert.modal('show');
                       }
                       if (msg[0] == 1) {
                           let message = msg[1];
                           swal({
                               title: 'باتشکر',
                               text: message,
                               icon: 'success',
                               timer: 3000,
                           })
                           setTimeout(function (){
                               window.location.reload();
                           },3000)
                       }
                   }
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
                <h5 class="font-weight-bold mb-3 mb-md-0">لیست عملکردها ({{ $types->total() }})</h5>
                <div>
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.functionalType.create') }}">
                        <i class="fa fa-plus"></i>
                        افزودن
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center">

                    <thead>
                    <tr>
                        <th>#</th>
                        <th>عنوان</th>
                        <th>تصویر</th>
                        <th>بنر</th>
                        <th>عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($types as $key => $type)
                        <tr>
                            <th>
                                {{ $types->firstItem() + $key }}
                            </th>
                            <th>
                                {{ $type->title }}
                            </th>
                            <th>
                                <img class="img-thumbnail"
                                     src="{{ imageExist(env('FUNCTIONAL_TYPE_UPLOAD_PATH'),$type->image) }}">
                            </th>
                            <th>
                                <img class="banner-thumbnail"
                                     src="{{ imageExist(env('FUNCTIONAL_TYPE_UPLOAD_PATH'),$type->banner_image) }}">
                            </th>
                            <th>
                                <a title="ویرایش" class="btn btn-sm btn-info mr-3"
                                   href="{{ route('admin.functionalType.edit', ['functionalType' => $type->id]) }}"><i
                                        class="fa fa-edit"></i>
                                </a>
                                <button title="حذف" type="button" onclick="RemoveModal({{ $type->id }})"
                                        class="btn btn-sm btn-danger mr-3"><i class="fa fa-trash"></i>
                                </button>
                            </th>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-5">
                {{ $types->render() }}
            </div>
        </div>
    </div>
    @include('admin.functionalType.modal')
    @include('admin.functionalType.alertModal')
@endsection
