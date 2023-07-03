@extends('admin.layouts.admin')

@section('title')
    index provinces
@endsection

@section('style')
    <style>
        .img-thumbnail{
            width: 50px;
            height: auto;
        }
        th{
            vertical-align: middle !important;
        }
        #overlay {
            display: none;
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
                url: "{{ route('admin.province.remove') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                },
                dataType: "json",
                type: 'POST',
                beforeSend: function () {

                },
                success: function (msg) {
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
                <h5 class="font-weight-bold mb-3 mb-md-0">لیست استان ها ({{ $provinces->total() }})</h5>
                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.provinces.create') }}">
                    <i class="fa fa-plus"></i>
                    افزودن استان
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center">

                    <thead>
                    <tr>
                        <th>#</th>
                        <th>نام</th>
                        <th>عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($provinces as $key => $province)
                        <tr>
                            <th>
                                {{ $provinces->firstItem() + $key }}
                            </th>
                            <th>
                                {{ $province->name }}
                            </th>
                            <th>
                                <a class="btn btn-sm btn-info mr-3"
                                   href="{{ route('admin.provinces.edit', ['province' => $province->id]) }}">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a title="لیست شهر ها" class="btn btn-sm btn-warning mr-3"
                                   href="{{ route('admin.cities.index', ['province' => $province->id]) }}">
                                    <i class="fa fa-tasks"></i>
                                </a>
                                                                    <button type="button" onclick="RemoveModal({{ $province->id }})"
                                                                            class="btn btn-sm btn-danger mr-3"
                                                                            href=""><i class="fa fa-trash"></i></button>
                            </th>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-5">
                {{ $provinces->render() }}
            </div>
            <div id="overlay">
                <div class="spinner-border text-danger" style="width: 3rem; height: 3rem;"></div>
                <br/>
                Loading...
            </div>
        </div>
    </div>

    @include('admin.provinces.modal')
    @include('admin.provinces.alertModal')

@endsection
