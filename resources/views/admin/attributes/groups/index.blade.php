@extends('admin.layouts.admin')

@section('title')
    گروه بندی مشخصات فنی
@endsection

@section('style')
    <style>
        .img-thumbnail {
            width: 50px;
            height: auto;
        }
    </style>
@endsection

@section('script')
    <script>
        function RemoveModal(attr_id) {
            let modal = $('#remove_modal');
            modal.modal('show');
            $('#attr_id').val(attr_id);
        }

        function Remove() {
            let attr_id = $('#attr_id').val();
            let modal_alert = $('#modal_alert');
            $.ajax({
                url: "{{ route('admin.attributes.group.remove') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    attr_group_id: attr_id,
                },
                dataType: "json",
                type: 'POST',
                beforeSend: function () {

                },
                success: function (msg) {
                    console.log(msg);
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
                            window.location.reload();
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
                <h5 class="font-weight-bold mb-3 mb-md-0">لیست گروه بندی ({{ $attr_groups->total() }})</h5>
                <div>
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.attributes.group.create') }}">
                        <i class="fa fa-plus"></i>
                        افزودن
                    </a>
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.attributes.index') }}">
                        بازگشت
                        <i class="fa fa-arrow-left"></i>
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center">

                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>نام</th>
                        <th>اولویت نمایش</th>
                        <th>عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($attr_groups as $key => $attr_group)
                        <tr>
                            <th>
                                {{ $attr_group->id }}
                            </th>
                            <th>
                                {{ $attr_group->name }}
                            </th>
                            <th>
                                {{ $attr_group->priority }}
                            </th>
                            <th>
                                <a title="ویرایش" class="btn btn-sm btn-info mr-3"
                                   href="{{ route('admin.attributes.group.edit',['group'=>$attr_group->id]) }}"><i class="fa fa-edit"></i>
                                </a>
                                <button title="حذف" type="button" onclick="RemoveModal({{ $attr_group->id }})"
                                        class="btn btn-sm btn-danger mr-3"><i class="fa fa-trash"></i>
                                </button>
                            </th>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-5">
                {{ $attr_groups->render() }}
            </div>
        </div>
    </div>
    @include('admin.attributes.groups.modal')
    @include('admin.attributes.groups.alertModal')
@endsection
