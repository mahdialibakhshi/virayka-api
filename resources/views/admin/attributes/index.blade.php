@extends('admin.layouts.admin')

@section('title')
    مشخصات فنی
@endsection

@section('style')
    <style>
        .img-thumbnail {
            width: 50px;
            height: auto;
        }

        th {
            vertical-align: middle !important;
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
                url: "{{ route('admin.attribute.remove') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    attr_id: attr_id,
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
                <h5 class="font-weight-bold mb-3 mb-md-0">لیست مشخصات فنی ({{ $attributes->total() }})</h5>
                <div>
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.attributes.groups.index') }}">
                        <i class="fa fa-list"></i>
                        گروه بندی
                    </a>
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.attributes.create') }}">
                        <i class="fa fa-plus"></i>
                        افزودن
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center">

                    <thead>
                    <tr>
                        <th>اولویت نمایش</th>
                        <th>نام</th>
                        <th>تصویر</th>
                        <th>مقادیر</th>
                        <th>محدودیت انتخاب در اقلام همراه</th>
                        <th>وابسته به رنگ</th>
                        <th>قرار گرفتن در بخش فیلتر</th>
                        <th>گروه</th>
                        <th>عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($attributes as $key => $attribute)
                        <tr>
                            <th>
                                {{ $attribute->priority }}
                            </th>
                            <th>
                                {{ $attribute->name }}
                            </th>
                            <th>
                                <img class="img-thumbnail"
                                     src="{{ imageExist(env('ATTR_UPLOAD_PATH'),$attribute->image) }}">
                            </th>
                            <th>
                                <a title="مقادیر"
                                   href="{{ route('admin.attributes.values.index',['attribute' => $attribute->id]) }}"
                                   class="btn btn-sm btn-primary">
                                    <i class="fa fa-eye"></i>
                                </a>
                            </th>
                            <th>
                                @if($attribute->limit_select==1)
                                    <i class="fa fa-check-circle text-success"></i>
                                @else
                                    <i class="fa fa-times-circle text-danger"></i>
                                @endif
                            </th>
                            <th>
                                @if($attribute->is_dependence==1)
                                    <i class="fa fa-check-circle text-success"></i>
                                @else
                                    <i class="fa fa-times-circle text-danger"></i>
                                @endif
                            </th>
                            <th>
                                @if($attribute->is_filter==1)
                                    <i class="fa fa-check-circle text-success"></i>
                                @else
                                    <i class="fa fa-times-circle text-danger"></i>
                                @endif
                            </th>
                            <th>
                                {{ isset($attribute->Group->name) ? $attribute->Group->name : '-' }}
                            </th>
                            <th>
                                <a title="نمایش" class="btn btn-sm btn-success"
                                   href="{{ route('admin.attributes.show', ['attribute' => $attribute->id]) }}"><i
                                        class="fa fa-eye"></i>
                                </a>
                                <a title="ویرایش" class="btn btn-sm btn-info mr-3"
                                   href="{{ route('admin.attributes.edit', ['attribute' => $attribute->id]) }}"><i
                                        class="fa fa-edit"></i>
                                </a>
                                <button title="حذف" type="button" onclick="RemoveModal({{ $attribute->id }})"
                                        class="btn btn-sm btn-danger mr-3"><i class="fa fa-trash"></i>
                                </button>
                            </th>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-5">
                {{ $attributes->render() }}
            </div>
        </div>
    </div>
    @include('admin.attributes.modal')
    @include('admin.attributes.alertModal')
@endsection
