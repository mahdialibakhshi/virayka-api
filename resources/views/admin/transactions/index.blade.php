@extends('admin.layouts.admin')

{{-- ===========  meta Title  =================== --}}
@section('title')
    لیست تراکنش ها
@endsection
{{-- ===========  My Css Style  =================== --}}
@section('style')
    <style>
        #overlay {
            display: none;
        }

        #totalReportPricesAlertDiv {
            display: none;
        }
    </style>
@endsection
{{-- ===========  My JavaScript  =================== --}}

@section('script')
    <script>
        let $=jQuery;
        $(document).ready(function (){
            //get localStorage
            let FilterItems = JSON.parse(localStorage.getItem("TransactionFilterItems"));
            if (FilterItems!=null){
                //get all saved in localStorage
                let userNameSearch=localStorage.getItem("userNameSearch");
                let transaction_start=localStorage.getItem("transaction_start");
                let transaction_end=localStorage.getItem("transaction_end");
                let transaction_status=localStorage.getItem("transaction_status");
                //
                $('#transaction_start_input').val(transaction_start);
                $('#transaction_end_input').val(transaction_end);
                $('#userNameSearch').val(userNameSearch);
                $('#transaction_status option[value="'+transaction_status+'"]').prop('selected',true);
                filter();

            }
        })
        //numberFormat
        function number_format (number, decimals, dec_point, thousands_sep) {
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
        function getOrderItem(order_id){
            $('#tbody').html('');
            $.ajax({
                url:"",
                data:{
                    order_id:order_id,
                    _token:"{{ csrf_token() }}",
                },
                dataType:"json",
                type:"post",
                success:function (msg){
                    if (msg[0]==1){
                        let orders=msg[1];
                        let tr='';
                        $.each(orders,function (i,order){
                            tr+=`<tr><td>${order.title}</td><td>${order.type.title}</td><td>${number_format(order.price)+' تومان '}</td></tr>`
                        })
                        $('#tbody').html(tr);
                    }
                }
            })
        }
        function getTransactionReport() {
            let transaction_start = $('#transaction_start_input').val();
            let transaction_end = $('#transaction_end_input').val();
            let transaction_status = $('#transaction_status').val();
            let userNameSearch = $('#userNameSearch').val();
            $.ajax({
                url: "{{ route('admin.transactions.get') }}",
                data: {
                    transaction_start: transaction_start,
                    transaction_end: transaction_end,
                    transaction_status: transaction_status,
                    userNameSearch: userNameSearch,
                    _token: "{{ csrf_token() }}",
                },
                dataType: "json",
                type: "POST",
                beforeSend: function () {
                    $("#overlay").fadeIn();
                },
                success: function (msg) {
                    if (msg) {
                        if (msg[0] == 1) {
                            let rows = msg[1];
                            let total_amount = msg[2];
                            //clear localStorage
                            localStorage.clear();
                            //save in localStorage
                            localStorage.setItem("TransactionFilterItems", JSON.stringify(rows));
                            localStorage.setItem("userNameSearch", userNameSearch);
                            localStorage.setItem("transaction_start", transaction_start);
                            localStorage.setItem("transaction_end", transaction_end);
                            localStorage.setItem("transaction_status", transaction_end);
                            localStorage.setItem("totalReportPrices", total_amount);
                            //
                            $('#transaction_start_input').val(transaction_start);
                            $('#transaction_end_input').val(transaction_end);
                            $('#main_table_tbody').html(rows);
                            $('#totalReportPrices').text(number_format(total_amount))
                            $('#totalReportPricesAlertDiv').show();
                            $('.pagination').hide();
                        }
                    }
                    $('#filter_modal').modal('hide');
                    $("#overlay").fadeOut();
                    $("#pagination").hide();
                },
                fail: function (error) {
                    console.log(error);
                    $("#overlay").fadeOut();
                }
            })
        }
        $('#clearTransactionBtn').click(function () {
            localStorage.clear();
            window.location.reload();
        })
        $(`#transaction_start`).MdPersianDateTimePicker({
            targetTextSelector: `#transaction_start_input`,
            englishNumber: true,
            enableTimePicker: true,
            textFormat: 'yyyy-MM-dd HH:mm:ss',
        });
        $(`#transaction_end`).MdPersianDateTimePicker({
            targetTextSelector: `#transaction_end_input`,
            englishNumber: true,
            enableTimePicker: true,
            textFormat: 'yyyy-MM-dd HH:mm:ss',
        });

        function custom_pagination(tag){
            let per_page=$(tag).val();
            let url='{{ route('admin.transactions.pagination',['show_per_page'=>':per_page']) }}';
            url=url.replace(':per_page',per_page);
            window.location.href=url;
        }
    </script>
@endsection
{{-- ===========      CONTENT      =================== --}}
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8 col-12 d-flex align-items-center">
                        <h5 class="font-weight-bold mb-3 mb-md-0">تعداد کل تراکنش ها ({{ $transactions->total() }})</h5>
                    </div>
                    <div class="col-md-4 col-12 d-flex justify-content-end align-items-center">
                        <form id="paginate_form" method="get">
                            <select onchange="custom_pagination(this)" name="show_per_page"
                                    class="form-control form-control-sm">
                                <option value="default" {{$show_per_page==1?'selected':''}}>پیش فرض</option>
                                <option value="50" {{$show_per_page==50?'selected':''}}> نمایش 50 تایی</option>
                                <option value="100" {{$show_per_page==100?'selected':''}}> نمایش 100 تایی</option>
                                <option value="200" {{$show_per_page==200?'selected':''}}> نمایش 200 تایی</option>
                                <option value="all" {{$show_per_page==0?'selected':''}}> نمایش همه</option>
                            </select>
                        </form>
                    </div>
                    <div class="col-12">
                        <hr>
                    </div>
                </div>
                <div class="row d-lg-flex justify-content-between align-items-center">
                    <div class="col-md-10 col-12 d-flex align-items-center">
                        <div class="form-group ml-3">
                            <label> نمایش : </label>
                            <select id="transaction_status" class="form-control form-control-sm">
                                <option value="0" >پرداخت های ناموفق</option>
                                <option value="1" selected>پرداخت های موفق</option>
                            </select>
                        </div>
                        <div class="form-group ml-3">
                            <label> تاریخ شروع : </label>
                            <div class="input-group">
                                <div class="input-group-prepend order-2">
                                                    <span class="input-group-text" id="transaction_start">
                                                        <i class="fas fa-clock"></i>
                                                    </span>
                                </div>
                                <input type="text" class="form-control form-control-sm" id="transaction_start_input"
                                       name="date_on_sale_from"
                                       value="">
                            </div>
                        </div>
                        <div class="form-group ml-3">
                            <label> تاریخ پایان : </label>

                            <div class="input-group">
                                <div class="input-group-prepend order-2">
                                                    <span class="input-group-text" id="transaction_end">
                                                        <i class="fas fa-clock"></i>
                                                    </span>
                                </div>
                                <input type="text" class="form-control form-control-sm" id="transaction_end_input"
                                       name="date_on_sale_to"
                                       value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label> جست و جوی کاربر : </label>
                            <div class="input-group input-group-md d-flex flex-row-reverse border-radius">
                                <input type="text" class="form-control form-control-sm"
                                       aria-label="Sizing example input"
                                       aria-describedby="inputGroup-sizing-lg" placeholder="جست و جوی کاربر..."
                                       id="userNameSearch" autocomplete="off">
                                <div class="input-group-prepend border-radius">
                    <span class="input-group-text" id="basic-addon2"><i class="fa fa-search"
                                                                        aria-hidden="true"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-12">
                        <div class="d-lg-flex justify-content-end align-items-center">
                            <div>
                                <button onclick="getTransactionReport()" type="button" class="btn btn-sm btn-primary ml-1">
                                    فیلتر
                                </button>
                                <button id="clearTransactionBtn" class="btn btn-dark btn-sm ml-1" type="button"
                                        data-toggle="modal">
                                    پاکسازی فیلتر
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <table class="table text-center table-striped table-responsive-sm">
                            <thead class="thead-dark text-white">
                            <tr>
                                <th scope="col">ردیف</th>
                                <th scope="col">کاربر</th>
                                <th scope="col">مبلغ</th>
                                <th scope="col">ref_id</th>
                                <th scope="col">درگاه پرداخت</th>
                                <th scope="col">وضعیت</th>
                                <th scope="col">تاریخ</th>
                            </tr>
                            </thead>
                            <tbody id="main_table_tbody">
                            @foreach($transactions as $key=>$item)
                                <tr class="{{ $item->getRawOriginal('status')==0 ? 'text-danger' : 'text-success' }}">
                                    <td>{{ $transactions->firstItem()+$key }}</td>
                                    <td>
                                        <a href="{{ route('admin.user.edit',['user'=>$item->user->id]) }}">
                                            {{ $item->user->name == null ? $item->user->cellphone : $item->user->name }}
                                        </a>
                                    </td>
                                    <td>{{ number_format($item->amount).' تومان ' }}</td>
                                    <td>{{ $item->ref_id==null ? '-' : $item->ref_id }}</td>
                                    <td>{{ $item->gateway_name }}</td>
                                    <td>{{ $item->status }}</td>
                                    <td>{{ verta($item->created_at)->format('%d %B,Y H:i') }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div id="totalReportPricesAlertDiv" class="col-12">
                        <div class="alert alert-info text-center">
                            مجموع <span id="totalReportPrices">3500</span> تومان
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    {{ $transactions->render() }}
                </div>
            </div>
        </div>
    </div>


@endsection
