@extends('admin.layouts.admin')

@section('title')
    index orders
@endsection

@section('style')
    <style>
        .input-error-validation {
            color: red;
            font-size: 9pt;
            display: none;
        }

        #NotFound {
            display: none;
        }

        #overlay {
            display: none;
            text-align: center;
        }

        .align-center {
            align-items: center !important
        }

        .mb-0 {
            margin-bottom: 0 !important;
        }

        .error_message {
            font-size: 9pt;
            color: red;
            position: absolute;
            top: 40px;
            right: 0;
        }

        .form-group {
            margin-bottom: 0 !important;
        }

        .mb-2 {
            margin-bottom: 2rem !important;
        }
    </style>

@endsection

@section('script')
    <script>
        $('#report_order_btn').click(function () {
            $('#start_date_error').hide();
            $('#end_date_error').hide();
        })
        //get pagination
        var todayPagination = null;
        var thisWeekPagination = null;
        var thisMonthPagination = null;
        var lastMonthPagination = null;
        var order_start = null;
        var order_end = null;

        function pagination(totalPages, currentPage) {
            var pagelist = "";
            if (totalPages > 1) {
                currentPage = parseInt(currentPage);
                pagelist += `<ul style="list-style: none" class="pagination_ajax justify-content-center d-flex">`;
                const prevClass = currentPage == 1 ? " disabled" : "";
                pagelist += `<li class="page-item${prevClass}"><a class="page-link" data-page="${currentPage - 1}" href="#"><</a></li>`;
                for (let p = 1; p <= totalPages; p++) {
                    const activeClass = currentPage == p ? " active" : "";
                    pagelist += `<li class="page-item${activeClass}"><a class="page-link" href="#" data-page="${p}">${p}</a></li>`;
                }
                const nextClass = currentPage == totalPages ? " disabled" : "";
                pagelist += `<li class="page-item${nextClass}"><a class="page-link" data-page="${currentPage + 1}" href="#">></a></li>`;
                pagelist += `</ul>`;
            }
            $("#pagination").html(pagelist);
        }

        // pagination
        $(document).on("click", "ul.pagination_ajax li a", function (e) {
            e.preventDefault();
            var $this = $(this);
            const pagenum = $this.data("page");
            $("#currentPage").val(pagenum);
            getOrderReport(todayPagination, thisWeekPagination, thisMonthPagination, lastMonthPagination, 'paginate');
        });

        //calculateIndexRow
        function calculateIndexRow(pageNo, row_per_page, key) {
            if (pageNo > 1) {
                pageNo = pageNo;
            } else {
                pageNo = 1;
            }
            return ((pageNo - 1) * (row_per_page)) + (key + 1);
        }

        function getOrderReport(today = null, thisWeek = null, thisMonth = null, lastMonth = null, paginate = null, delivery_status = null) {
            let currentPage = $('#currentPage').val();
            todayPagination = today;
            thisWeekPagination = thisWeek;
            thisMonthPagination = thisMonth;
            lastMonthPagination = lastMonth;
            delivery_status_val= delivery_status==null ? null : $('#delivery_status').val();
            if (paginate == null) {
                order_start = $('#order_start_input').val();
                order_end = $('#order_end_input').val();
            }
            if (order_start == '' && today == null && thisWeek == null && thisMonth == null && lastMonth == null && paginate == null) {
                $('#start_date_error').show()
            } else if (order_end == '' && today == null && thisWeek == null && thisMonth == null && lastMonth == null && paginate == null) {
                $('#end_date_error').show()
            } else {
                $.ajax({
                    url: "{{ route('admin.orders.get') }}",
                    data: {
                        order_start: order_start,
                        order_end: order_end,
                        today: today,
                        thisWeek: thisWeek,
                        thisMonth: thisMonth,
                        lastMonth: lastMonth,
                        page: currentPage,
                        delivery_status_val: delivery_status_val,
                        _token: "{{ csrf_token() }}",
                    },
                    dataType: "json",
                    type: "POST",
                    beforeSend: function () {
                        $("#overlay").fadeIn();
                    },
                    success: function (msg) {
                        if (msg) {
                            $("#Auto_paginate").html('');
                            if (msg[0] = 'ok') {
                                let row;
                                let orders = msg[1];
                                let massage = msg[4];
                                if (orders.length == 0) {
                                    $('#totalSale').text(massage);
                                    $('#totalSale').show();
                                    $('#orderReports').hide();
                                    $("#pagination").html('');

                                } else {
                                    $('#NotFound').hide();
                                    $('#orderReports').show();
                                    $('#orders').html(orders);
                                    let totalRows = msg[2];
                                    let row_per_page = msg[3];
                                    let massage = msg[4];
                                    let totalPages = Math.ceil(totalRows / row_per_page);
                                    const currentPage = $('#currentPage').val();
                                    pagination(totalPages, currentPage);

                                    $('#totalSale').text(massage);
                                    $('#totalSale').show();
                                }
                                $('#filter_order_modal').modal('hide');
                                $('#order_start_input').val('');
                                $('#order_end_input').val('');

                            }
                        }
                        $("#overlay").fadeOut();
                    },
                    fail: function (error) {
                        console.log(error);
                        $("#overlay").fadeOut();
                    }
                })
            }
        }

        function createTableRow(order, key) {
            let pageNo = $('#currentPage').val();
            let row_per_page = 100;
            let indexRow = calculateIndexRow(pageNo, row_per_page, key);
            let url = "{{ route('admin.orders.show',":id") }}";
            url = url.replace(':id', order.id);
            let printUrl = "{{ route('admin.orders.print', ":printUrl_id") }}";
            printUrl = printUrl.replace(':printUrl_id', order.id);
            let printPeykUrl = "{{ route('admin.orders.print_peyk', ":printPeykUrl_id") }}";
            printPeykUrl = printPeykUrl.replace(':printPeykUrl_id', order.id);
            let html = `<tr>
                            <th>${indexRow}</th>
                            <th>${order.user.name}</th>
                            <th>${number_format(order.wallet)}</th>
                            <th>${number_format(order.paying_amount)}</th>
                            <th>${number_format(order.total_amount)}</th>
                            <th>${order.payment_type}</th>
                            <th>${order.payment_status}</th>
                            <th>${order.date}</th>
                            <th>${order.delivery_status}</th>
                            <th>${order.order_number}</th>
                            <th>${order.status}</th>
                            <th>
                                  <a class="btn btn-sm btn-success"
                                   href="${url}">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a target="_blank" class="btn btn-sm btn-dark"
                                   href="${printUrl}">
                                    <i class="fa fa-print"></i>
                                </a>
                                <a target="_blank" class="btn btn-sm btn-dark"
                                   href="${printPeykUrl}">
                                    <i class="fa fa-motorcycle"></i>
                                </a>
                            </th>
                        </tr>`;
            return html;
        }

        //excel output order
        $(`#order_start_excel`).MdPersianDateTimePicker({
            targetTextSelector: `#order_start_input_excel`,
            englishNumber: true,
            enableTimePicker: true,
            textFormat: 'yyyy-MM-dd HH:mm:ss',
        });

        $(`#order_end_excel`).MdPersianDateTimePicker({
            targetTextSelector: `#order_end_input_excel`,
            englishNumber: true,
            enableTimePicker: true,
            textFormat: 'yyyy-MM-dd HH:mm:ss',
        });
        //
        $(`#order_start`).MdPersianDateTimePicker({
            targetTextSelector: `#order_start_input`,
            englishNumber: true,
            enableTimePicker: true,
            textFormat: 'yyyy-MM-dd HH:mm:ss',
        });

        $(`#order_end`).MdPersianDateTimePicker({
            targetTextSelector: `#order_end_input`,
            englishNumber: true,
            enableTimePicker: true,
            textFormat: 'yyyy-MM-dd HH:mm:ss',
        });

        function number_format(number, decimals, dec_point, thousands_sep) {
            // Strip all characters but numerical ones.
            number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
            var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                s = '',
                toFixedFix = function (n, prec) {
                    var k = Math.pow(10, prec);
                    return '' + Math.round(n * k) / k;
                };
            // Fix for IE parseFloat(0.55).toFixed(0) = 0;
            s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
            }
            return s.join(dec);
        }

        function changeOrderStatus(tag, order_id) {
            let delivery_status = $(tag).val();
            $.ajax({
                url: "{{ route('admin.orders.update_delivery_status') }}",
                data: {
                    delivery_status: delivery_status,
                    _token: "{{ csrf_token() }}",
                    order_id: order_id,
                },
                dataType: 'json',
                type: 'POST',
                beforeSend: function () {

                },
                success: function (msg) {
                    if (msg) {
                        if (msg[0] == 'ok') {
                            swal({
                                title: 'با تشکر',
                                text: 'وضعیت سفارش با موفقیت تغییر یافت',
                                icon: 'success',
                                timer: 3000,
                            })
                        }
                    }
                },
                fail: function () {

                },
                error: function () {

                }
            })
        }

        function active_sms(order_id) {
            let selector = '#active_sms_icon_' + order_id;
            $.ajax({
                url: "{{ route('admin.order.active_sms') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    order_id: order_id,
                },
                dataType: "json",
                type: 'POST',
                beforeSend: function () {
                    $("#overlay").fadeIn();
                },
                success: function (msg) {
                    if (msg) {
                        if (msg[1] === 1) {
                            $(selector).removeClass('btn-dark');
                            $(selector).addClass('btn-success text-white');
                            $(selector).text('فعال');
                        }
                        if (msg[1] === 0) {
                            $(selector).removeClass('btn-success text-white');
                            $(selector).addClass('btn-dark');
                            $(selector).text('غیر فعال');
                        }
                    }
                    $("#overlay").fadeOut();

                },
                fail: function (error) {
                    console.log(error);
                    $("#overlay").fadeOut();
                }
            })
        }

        function RemoveModal(order_id) {
            let modal = $('#remove_modal');
            modal.modal('show');
            $('#order_id').val(order_id);
        }

        function RemoveOrder() {
            let order_id = $('#order_id').val();
            $.ajax({
                url: "{{ route('admin.order.remove') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    order_id: order_id,
                },
                dataType: "json",
                type: 'POST',
                beforeSend: function () {

                },
                success: function (msg) {
                    if (msg) {
                        let message = msg[1];
                        if (msg[0] == 0) {
                            swal({
                                title: 'ERROR',
                                text: message,
                                icon: 'error',
                                buttons: 'ok',
                            })
                        }
                        if (msg[0] == 1) {

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

        function custom_pagination(tag) {
            let per_page = $(tag).val();
            let url = '{{ route('admin.orders.pagination',['show_per_page'=>':per_page']) }}';
            url = url.replace(':per_page', per_page);
            window.location.href = url;
        }

    </script>
@endsection

@section('content')

    <!-- Content Row -->
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4 p-4 bg-white">
            <div class="row">
                <div class="col-md-8 col-12 d-flex align-items-center">
                    <h5 class="font-weight-bold mb-3 mb-md-0">تعداد کل سفارشات ({{ $orders->total() }})</h5>
                </div>
                <div class="col-12">
                    <hr>
                </div>
            </div>
            <div id="orderReports" class="table-responsive">
                <table class="table table-bordered table-striped text-center">
                    <thead>
                    <tr class="bg-dark text-white">
                        <th>#</th>
                        <th>نام کاربر</th>
                        <th>کیف پول (تومان)</th>
                        <th>پرداختی (تومان)</th>
                        <th>کل (تومان)</th>
                        <th>نوع پرداخت</th>
                        <th>وضعیت تراکنش</th>
                        <th>تاریخ</th>
                        <th>SMS</th>
                        <th>وضعیت سفارش</th>
                        <th>شماره سفارش</th>
                        <th>شیوه‌ی ارسال</th>
                        <th>عملیات</th>
                    </tr>
                    </thead>
                    <tbody id="orders">
                    @foreach ($orders as $key => $order)
                        <tr>
                            <th>
                                {{ $orders->firstItem() + $key }}
                            </th>
                            <th>
                                <a href="{{ route('admin.user.edit',['user'=>$order->user->id]) }}">
                                    {{ $order->user->name == null ? $order->user->cellphone : $order->user->name }}
                                </a>
                            </th>
                            <th>
                                {{ number_format($order->wallet) }}
                            </th>
                            <th>
                                {{ number_format($order->paying_amount) }}
                            </th>
                            <th>
                                {{ number_format($order->total_amount) }}
                            </th>
                            <th>
                                {{ $order->payment_type }}
                            </th>
                            <th>
                                {{ $order->payment_status }}
                            </th>
                            <th>
                                {{ verta($order->created_at)->format('%d %B ,Y') }}
                            </th>
                            <th>
                                <a title="ارسال پیامک تغییر وضعیت" id="active_sms_icon_{{ $order->id }}"
                                   onclick="active_sms({{ $order->id }})"
                                   class="btn btn-sm {{ $order->getRawOriginal('active_sms')==1 ? 'btn-success text-white' : 'btn-dark' }}">
                                    {{ $order->active_sms }}
                                </a>
                            </th>
                            <th>
                                <select onchange="changeOrderStatus(this,{{ $order->id }})"
                                        class="form-control form-control-sm">
                                    @foreach($order_status as $item)
                                        <option
                                            {{ $order->delivery_status==$item->id ? 'selected' : ' ' }} value="{{ $item->id }}">
                                            {{ $item->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </th>
                            <th>
                                {{ $setting->productCode.'-'.$order->order_number }}
                            </th>
                            <th>
                                {{ $order->DeliveryMethod->name }}
                                @if($order->getRawOriginal('delivery_method')==3 and  $order->getRawOriginal('payment_status')==1)
                                    <a target="_blank" class="btn btn-sm btn-dark"
                                       href="{{ route('admin.orders.print', ['order' => $order->id]) }}">
                                        درخواست الوپیک
                                    </a>
                                @endif
                            </th>
                            <th>
                                <a title="جزئیات" class="btn btn-sm btn-success mb-1"
                                   href="{{ route('admin.orders.show', ['order' => $order->id]) }}">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a title="پرینت" target="_blank" class="btn btn-sm btn-dark mb-1"
                                   href="{{ route('admin.orders.print', ['order' => $order->id]) }}">
                                    <i class="fa fa-print"></i>
                                </a>
                                <a title="پرینت پیک" target="_blank" class="btn btn-sm btn-warning mb-1"
                                   href="{{ route('admin.orders.print_peyk', ['order' => $order->id]) }}">
                                    <i class="fa fa-motorcycle"></i>
                                </a>
                                <button title="حذف" type="button" onclick="RemoveModal({{ $order->id }})"
                                        class="btn btn-sm btn-danger mb-1"
                                        href=""><i class="fa fa-trash"></i></button>
                            </th>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div id="Auto_paginate" class="d-flex justify-content-center mt-5">
                {{ $orders->render() }}
            </div>
            <nav>
                <ul id="pagination" class="pagination justify-content-center">
                </ul>
            </nav>
            <input type="hidden" name="currentPage" id="currentPage" value="1">
            <div id="overlay">
                <div class="spinner-border text-danger" style="width: 3rem; height: 3rem;"></div>
                <br/>
                Loading...
            </div>
        </div>
    </div>

    {{--    //filter_modal--}}
    @include('admin.orders.modal')
    @include('admin.orders.filter_order_modal')
@endsection
