@extends('admin.layouts.admin')

@section('title')
    کیف پول کاربر
@endsection

@section('style')
    <style>
        .img-thumbnail {
            max-width: 100px;
            height: auto;
        }

        th {
            vertical-align: middle !important;
        }
    </style>
@endsection

@section('script')
    <script>
        function RemoveModal(label_id) {
            let modal = $('#remove_modal');
            modal.modal('show');
            $('#label_id').val(label_id);
        }

        function Remove() {
            let label_id = $('#label_id').val();
            let modal_alert = $('#modal_alert');
            $.ajax({
                url: "{{ route('admin.label.remove') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    label_id: label_id,
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
                            setTimeout(function () {
                                window.location.reload();
                            }, 3000)
                        }
                    }
                },
            })
        }

        function AddWallet(increase_type) {
            let amount = $('#amount').val();
            if (increase_type===1){
                msg='آیا از شارژ کیف پول کاربر اطمینان دارید؟';
            }else {
                msg='شما در حال کم کردن کیف پول کاربر هستید.ادامه میدهید؟';
            }
            if (confirm(msg)){
                $.ajax({
                    url: "{{ route('admin.wallet.add') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        amount: amount,
                        increase_type: increase_type,
                        user_id: {{ $user->id }}
                    },
                    method: "post",
                    dataType: "json",
                    success: function (msg) {
                        if (msg) {
                            if (msg[0] == 1) {
                                swal({
                                    title: 'با تشکر',
                                    text: msg[1],
                                    icon: 'success',
                                    timer: 1500
                                })
                                setTimeout(function () {
                                    window.location.reload();
                                }, 1500)
                            }
                            if (msg[0] == 0) {
                                swal({
                                    title: 'خطا',
                                    text: msg[1],
                                    icon: 'error',
                                    buttons: 'ok',
                                })
                            }
                        }
                    }
                });
            }
        }
    </script>
@endsection

@section('content')

    <!-- Content Row -->
    <div class="row">

        <div class="col-xl-12 col-md-12 mb-4 p-4 bg-white">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex flex-column text-center flex-md-row justify-content-md-between mb-4">
                        <h5 class="font-weight-bold mb-3 mb-md-0">تاریخچه کیف پول کاربر
                            ( {{ $user->name==null ? $user->cellphone : $user->name }} )</h5>
                        <a title="بازگشت" href="{{ route('admin.user.index') }}"
                           class="btn btn-sm btn-secondary">
                            <i class=" fa fa-arrow-left"></i>
                        </a>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-12 col-md-6 col-lg-4">
                    <p>موجودی کیف پول : {{ number_format($wallet->amount) }} تومان</p>
                </div>
                <div class="col-12 col-md-6 col-lg-8">
                    <div class="position-relative text-center mb-4">
                        <input onkeyup="NumberFormat(this)" id="amount" type="text" class="form-control form-control-sm"
                               value="0" placeholder="مقدار دلخواه خود را وارد نمایید">
                        <div class="input-button">
                            <button onclick="AddWallet(1)" type="button" class="btn btn-sm btn-primary">افزایش</button>
                            <button onclick="AddWallet(0)" type="button" class="btn btn-sm btn-danger">بستانکار</button>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center">

                    <thead>
                    <tr>
                        <th>#</th>
                        <th>موجودی کیف پول (تومان)</th>
                        <th>تغییرات (تومان)</th>
                        <th>نوع</th>
                        <th>تاریخ</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($wallet_history as $key => $item)
                        <tr>
                            <th>
                                {{ $wallet_history->firstItem() + $key }}
                            </th>
                            <th>
                                {{ number_format($item->previous_amount) }}
                            </th>
                            <th class="d-flex justify-content-center">
                                <span style="width: 200px;display: block">
                                    {{ number_format($item->amount) }}
                                </span>
                                @if($item->increase_type==1)
                                    <i class="text-success fa fa-arrow-up"></i>
                                @else
                                    <i class="text-danger fa fa-arrow-down"></i>
                                @endif
                            </th>
                            <th>
                                {{ $item->Type->description }}
                            </th>
                            <th>
                                {{ verta($item->created_at)->format('Y-m-d H:i') }}
                            </th>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-5">
                {{ $wallet_history->render() }}
            </div>
        </div>
    </div>
    @include('admin.labels.modal')
    @include('admin.labels.alertModal')
@endsection
