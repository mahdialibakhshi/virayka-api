@extends('admin.layouts.admin')

@section('title')
    show orders
@endsection

@section('style')
    <style>


        table {
            width: 100%;
            text-align: center;
            border: 1px solid #CCCCCC;
            border-collapse: collapse;
        }

        .head {
            background-color: #EEEEEE;
        }

        td {
            padding: 2%;
        }

        tr {
            border: 1px solid #CCCCCC;
        }

        .info {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .info > div {
            width: 100%;
            height: auto;
            display: inline-block;
        }

        .p20 {
            padding: 20px;
        }

        .mt10 {
            margin-top: 10px;
        }

        .logoImage {
            width: 50%;
            max-width: 150px;
            height: auto;
            max-height: 70%;
        }

        .text-center {
            text-align: center;
        }

        .customerInfo {
            text-align: center;
            background-color: #EEEEEE;
            padding: 2%;
            border: 1px solid #CCCCCC;
        }

        .sellerInfo {
            display: flex;
            justify-content: space-around;
            margin: 10px 0;
        }

        #shopInformation {
            display: flex;
        }

        #shopLogo {
            width: 30%;
            text-align: center;
        }

        #shopInfo {
            border-right: 1px solid #CCCCCC;
            padding: 10px;
            width: 70%;
        }

        #shopInfo > p{
            padding: 3px;
        }

        #shopInfo > div {
            display: flex;
            justify-content: space-between;
            padding: 10px;
        }

        p {
            margin: 0;
        }

        .line {
            width: 80%;
            height: 1px;
            background-color: black;
            margin: 10px auto;
        }

        #customerInformation{
            width: 100%;
            border: 1px solid #eee;
        }
        #customerInformation > div {
            display: flex;
            justify-content: space-between;
        }
        #customerInformation p{
            padding: 3px;
        }
        .img-thumbnail{
            width: 50px !important;
            height: auto;
        }
        table { page-break-inside:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }

    </style>

@endsection

@section('script')
    <script>
        $('#delivery_status_btn').click(function () {
            let delivery_status = $('#delivery_status').val();
            $.ajax({
                url: "{{ route('admin.orders.update_delivery_status') }}",
                data: {
                    delivery_status: delivery_status,
                    _token: "{{ csrf_token() }}",
                    order_id: "{{ $order->id }}",
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
        })
    </script>
@endsection

@section('style')
    <style>
        .product-thumbnail {
            width: 150px !important;
            height: auto;
        }
    </style>
@endsection

@section('content')
    <!-- Content Row -->
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4 p-4 bg-white">
            <div class="row">
                <div class="col-12 {{ $order->DeliveryMethod->id==1?'col-md-4':'col-md-6' }}">
                    <div class="mb-4 text-center text-md-right">
                       <h5 class="font-weight-bold">شماره سفارش : {{ $setting->productCode.'-'.$order->order_number }}</h5>
                    </div>
                </div>
                @if($order->DeliveryMethod->id==1)
                    <form class="col-12 col-md-4 d-flex align-items-center"
                          action="{{ route('admin.order.TrackingCodeUpdate',['order'=>$order->id]) }}" method="post">
                        @csrf
                        <input class="form-control form-control-sm" name="TrackingCode" value=""
                               placeholder="کد رهگیری مرسله پستی">
                        @if($errors->has('TrackingCode'))
                            <div class="error">{{ $errors->first('TrackingCode') }}</div>
                        @endif
                        <button type="submit"
                                class="btn btn-sm btn-dark mr-3">
                            ثبت
                        </button>
                    </form>
                @endif
                <div class="col-12 {{ $order->DeliveryMethod->id==1?'col-md-4':'col-md-6' }} d-flex align-items-center">
                    <select id="delivery_status" class="form-control form-control-sm">
                        @foreach($order_status as $item)
                            <option {{ $order->delivery_status==$item->id ? 'selected' : ' ' }} value="{{ $item->id }}">
                                {{ $item->title }}
                            </option>
                        @endforeach
                    </select>
                    <button id="delivery_status_btn"
                            class="btn btn-sm btn-success mr-3">
                        تایید
                    </button>
                </div>
            </div>
            <hr>

            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label>نام کاربر</label>
                            <input class="form-control" type="text"
                                   value="{{ $order->user->name == null ? 'کاربر' : $order->user->name }}" disabled>
                        </div>
                        <div class="form-group col-md-3">
                            <label>شماره همراه</label>
                            <input class="form-control" type="text"
                                   value="{{ $order->user->cellphone }}" disabled>
                        </div>
                        <div class="form-group col-md-3">
                            <label>کد کوپن</label>
                            <input class="form-control" type="text"
                                   value="{{ $order->coupon_id == null ? 'استفاده نشده' : $order->coupon->code }}"
                                   disabled>
                        </div>
                        <div class="form-group col-md-3">
                            <label>وضعیت</label>
                            <input class="form-control" type="text" value="{{ $order->DeliveryMethodStatus->title }}"
                                   disabled>
                        </div>
                        <div class="form-group col-md-3">
                            <label>مبلغ(تومان)</label>
                            <input class="form-control" type="text" value="{{ number_format($order->total_amount) }}"
                                   disabled>
                        </div>
                        <div class="form-group col-md-3">
                            <label>هزینه ارسال(تومان)</label>
                            <input class="form-control" type="text" value="{{ number_format($order->delivery_amount) }}"
                                   disabled>
                        </div>
                        <div class="form-group col-md-3">
                            <label>مبلغ کد تخفیف(تومان)</label>
                            <input class="form-control" type="text" value="{{ number_format($order->coupon_amount) }}"
                                   disabled>
                        </div>
                        <div class="form-group col-md-3">
                            <label>کسر از کیف پول(تومان)</label>
                            <input class="form-control" type="text" value="{{ number_format($order->wallet) }}"
                                   disabled>
                        </div>
                        <div class="form-group col-md-3">
                            <label>مبلغ پرداختی(تومان)</label>
                            <input class="form-control" type="text" value="{{ number_format($order->paying_amount) }}"
                                   disabled>
                        </div>
                        <div class="form-group col-md-3">
                            <label>نوع پرداخت</label>
                            <input class="form-control" type="text" value="{{ $order->payment_type }}" disabled>
                        </div>
                        <div class="form-group col-md-3">
                            <label>وضعیت پرداخت</label>
                            <input class="form-control" type="text" value="{{ $order->payment_status }}" disabled>
                        </div>
                        <div class="form-group col-md-3">
                            <label>تاریخ ایجاد</label>
                            <input class="form-control" type="text" value="{{ verta($order->created_at) }}" disabled>
                        </div>
                        <div class="form-group col-md-3 mb-2">
                            <label>تاریخ و ساعت دریافت کالا</label>
                            <input class="form-control" type="text"
                                   value="{{ $order->delivery_date.'  '.$order->delivery_time }}"
                                   disabled>
                        </div>
                        <div class="form-group col-md-3 mb-2">
                            <label>روش ارسال</label>
                            <input class="form-control" type="text" value="{{ $order->DeliveryMethod->name }}"
                                   disabled>
                        </div>
                        @if($order->DeliveryMethod->id==1)
                            <div class="form-group col-md-3 mb-2">
                                <label>کد رهگیری</label>
                                <input class="form-control" type="text" value="{{ $order->TrackingCode }}"
                                       disabled>
                            </div>
                        @endif
                        <div class="form-group col-md-12">
                            <label>آدرس</label>
                            <textarea class="form-control"
                                      disabled>{{ province_name($order->address->province_id).' / '.city_name($order->address->city_id).' - '.$order->address->address }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $customer_address = json_decode($order->customer_address);
            ?>
            @if($customer_address!=null)
                <div class="row">
                    <div class="col-md-12">
                        <h4>تحویل به مشتری:</h4>
                    </div>
                    <div class="col-12">
                        <hr>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-3">
                        <label>نام و نام‌خانوادگی :</label>
                        <input class="form-control" type="text"
                               value="{{ $customer_address->name_1 }}" disabled>
                    </div>
                    <div class="form-group col-md-3">
                        <label>شماره همراه :</label>
                        <input class="form-control" type="text"
                               value="{{ $customer_address->cellphone_1 }}" disabled>
                    </div>
                    <div class="form-group col-md-3">
                        <label>شماره ثابت :</label>
                        <input class="form-control" type="text"
                               value="{{ $customer_address->tel_1==null ? '-' : $customer_address->tel_1 }}"
                               disabled>
                    </div>
                    <div class="form-group col-md-3">
                        <label>کد پستی :</label>
                        <input class="form-control" type="text"
                               value="{{ $customer_address->postal_code_1==null ? '-' : $customer_address->postal_code_1 }}"
                               disabled>
                    </div>
                    <div class="form-group col-md-12">
                        <label>آدرس</label>
                        <textarea class="form-control"
                                  disabled>{{ province_name($customer_address->province_id_1).' / '.city_name($customer_address->city_id_1).' - '.$customer_address->address_1 }}</textarea>
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <hr>
                    <h5>سفارشات</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped text-center">
                            <thead>
                            <tr>
                                <th class="product-name"><span>تصویر</span></th>
                                <th class="product-name"><span>عنوان</span></th>
                                <th class="product-name"><span>اقلام افزوده</span></th>
                                <th class="product-price"><span>قیمت</span></th>
                                <th class="product-quantity"><span>تعداد</span></th>
                                <th class="product-subtotal"><span>جمع</span></th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $i=1;
                                $total_quantitiy=0;
                            @endphp
                            @foreach ($order->orderItems as $item)
                                    <?php
                                    $product_attr_variation = \App\Models\ProductAttrVariation::where('product_id', $item->product_id)
                                        ->where('attr_value', $item->variation_id)
                                        ->where('color_attr_value', $item->color_id)
                                        ->first();
                                    if ($product_attr_variation != null) {
                                        $product_attr_variation_id = $product_attr_variation->id;
                                        $item['product_attr_variation_id'] = $product_attr_variation_id;
                                    }
                                    ?>
                                <tr>
                                    <td class="product-thumbnail">
                                        <div class="p-relative">
                                            <a href="{{ route('home.product',['alias'=>$item->Product->alias]) }}">
                                                <figure>
                                                    <img class="img-thumbnail"
                                                         src="{{ imageExist(env('PRODUCT_IMAGES_THUMBNAIL_UPLOAD_PATH'),$item->Product->primary_image) }}"
                                                         alt="product">
                                                </figure>
                                            </a>
                                        </div>
                                    </td>
                                    <td class="product-name">
                                        <a href="{{ route('home.product',['alias'=>$item->Product->alias]) }}">
                                            {{ $item->Product->name }}
                                            <br>
                                            <br>
                                            @if(isset($item->AttributeValues->id) and  $item->AttributeValues->id!=217)
                                                <br>
                                                {{ $item->AttributeValues->name ?? '' }}
                                            @endif
                                            @if(isset($item->Color->id) and  $item->Color->id!=346)
                                                <br>
                                                {{ $item->Color->name ??'' }}
                                                <br>
                                            @endif
                                        </a>
                                    </td>
                                    <td class="product-name">
                                        <a>
                                            @if($item->option_ids!=null)
                                                @if(product_price($item->product_id,$item->product_attr_variation_id)[1]!=0)
                                                    <br>
                                                @endif
                                                @foreach(json_decode($item->option_ids) as $option)
                                                    <br>{{ \App\Models\ProductOption::where('id',$option)->first()->VariationValue->name }}
                                                @endforeach
                                            @else
                                                -
                                            @endif
                                        </a>
                                    </td>
                                    <td class="product-price">
                                    <span class="amount">
                                        {{ number_format($item->product_price) }} تومان
                                        @if($item->option_ids!=null)
                                            {{ $item->option_price }}
                                        @endif
                                    </span>
                                    </td>
                                    <td>
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="product-subtotal">
                                    <span class="amount">
                                        {{ number_format($item->subtotal) }}
                                          تومان </span>
                                    </td>
                                </tr>
                                    <?php
                                    $i++;
                                    $total_quantitiy=$total_quantitiy+$item->quantity;
                                    ?>
                            @endforeach
                            </tbody>
                        </table>
                        <table class="mt10">
                            <tr class="head">
                                <td>هزینه ارسال</td>
                                <td>پرداخت شده از کیف پول</td>
                                <th>مبلغ پرداخت شده</th>
                                <th>تعداد کل</th>
                                <td>جمع کل</td>
                            </tr>
                            <tr>
                                <td>{{ number_format($order->delivery_amount) }} تومان</td>
                                <td>{{ number_format($order->wallet) }} تومان</td>
                                <td>{{ number_format($order->paying_amount) }} تومان</td>
                                <td>{{ $total_quantitiy }}</td>
                                <td>{{ number_format($order->total_amount) }} تومان</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-dark mt-5">بازگشت</a>

        </div>

    </div>

@endsection
