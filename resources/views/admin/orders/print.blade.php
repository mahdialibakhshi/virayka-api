<!doctype html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>پرینت سفارش</title>
    <link rel="icon" type="image/png" href="{{ asset('home/images/icons/favicon.png') }}">
    <style>
        /*//public Style*/
        @font-face {
            font-family: "Vazir";
            src: url({{ asset('/fonts/Vazir.eot?d28c322f9e3b83d8048808d966fa01c1') }});
            /* IE9 Compat Modes */
            src: url({{ asset('/fonts/Vazir.eot?d28c322f9e3b83d8048808d966fa01c1') }}) format("embedded-opentype"),
            url({{ asset('/fonts/Vazir.woff2?f6b0854b99af25b683b1017431881340') }}) format("woff2"),
            url({{ asset('/fonts/Vazir.woff?d0b45fe799885bab47a9fc07de9563e3') }}) format("woff"),
            url({{ asset('/fonts/Vazir.ttf?e3e8c52a5a6a92c839fb985db61fa3ab') }}) format("truetype");
            /* Safari, Android, iOS */
        }

        * {
            font-family: "Vazir";
            font-size: 10px !important;
        }

        #element-to-print {
            width: 98% !important;
            margin: -15px auto;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            border: 1px solid black;
            padding: 1%;
        }

        table {
            width: 100%;
            text-align: center;
            border: 1px solid #CCCCCC;
            border-collapse: collapse;
        }

        .head > td{
            background-color: #7DCACE !important;
        }

        td {
            padding: 10px 5px;
            border: 3px solid #fff;
            background: #ededed;
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
            width: 60%;
            height: auto;
            max-height: 70%;
        }

        .text-center {
            text-align: center;
        }

        .customerInfo {
            text-align: center;
            background-color: #ce91ef;
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
            align-items: center;
            /*border: 1px solid #c0bfbf;*/
        }

        #shopLogo {
            width: 50%;
            text-align: center;
        }

        #shopInfo {
            width: 50%;
        }

        #shopInfo > p{
            padding: 3px;
        }

        #shopInfo > div {
            display: flex;
            justify-content: space-between;
        }

        p {
            margin: 0 ;
        }
        .border{
            border: 1px solid black;
        }

        .line {
            width: 80%;
            height: 1px;
            background-color: black;
            margin: 15px auto;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .line > span{
            padding: 0 10px;
            color: black;
            font-size: 18px !important;
            background-color: white;
        }

        #customerInformation{
            width: 100%;
            margin: 10px 0;
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
        .head{
            background-color: #7DCACE;
            color: white;
        }
        .color_base{
            color: #7DCACE;
        }
        table { page-break-inside:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }

    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js" integrity="sha512-YcsIPGdhPK4P/uRW6/sruonlYj+Q7UHWeKfTAkBW+g83NKM+jMJFJ4iAPfSnVp7BKD4dKMHmVSvICUbE/V1sSw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function ($){
            // var element = document.getElementById('element-to-print');
            // var opt = {
            //     margin: 1,
            //     filename: 'myfile.pdf',
            //     image: {type: 'jpeg', quality: 100},
            //     html2canvas: {
            //         scale: 0.45
            //     },
            //     direction: "rtl",
            //     jsPDF: {
            //         orientation: 'p',
            //         unit: 'mm',
            //         format: 'letter',
            //         putOnlyUsedFonts: true
            //     }
            // };
            // html2pdf().set(opt).from(element).save();
            window.print();
        });
    </script>
</head>
<body>
<div id="element-to-print">
    <div id="shopInformation">
        <div id="shopLogo">
            <img class="logoImage" src="{{ asset(env('LOGO_UPLOAD_PATH').$setting->image) }}">
        </div>
        <div id="shopInfo">
            <div>
                <div style="background-color: #cccccc;padding: 10px">
                    <p>
                        <span class="color_base">آدرس</span>
                        <span>:</span>
                        {{ $setting->address }}
                    </p>
                    <p>
                        <span class="color_base">کد پستی</span>

                        <span>:</span>
                        {{ $setting->postalCode }}
                    </p>
                    <p>
                        <span class="color_base">تلفن</span>
                        تلفن
                        <span>:</span>
                        {{ $setting->tel }}
                    </p>
                    <p>
                        <span class="color_base">شماره همراه</span>
                        شماره همراه
                        <span>:</span>
                        {{ $setting->cellphone }}
                    </p>
                </div>
                <div>

                </div>
            </div>
        </div>
    </div>
    <div id="customerInformation">
        <div style="display: flex;justify-content: space-between;align-items: center">
            <div style="width: 48%;padding: 0 10px;">
                <p>
                    شماره سفارش
                    <span>:</span>
                    {{ $setting->productCode.'-'.$order->order_number }}
                </p>
                <p>
                    تاریخ سفارش
                    <span>:</span>
                    {{ verta($order->created_at)->format('%d %B ,Y') }}
                </p>
                @if($setting->shomare_sabt!=null)
                    <p>
                        شماره ثبت سورین همراه
                        <span>:</span>
                        {{ $setting->shomare_sabt }}
                    </p>
                @endif
                @if($setting->EconomicCode!=null)
                    <p>
                        کد اقتصادی
                        <span>:</span>
                        {{ $setting->EconomicCode }}
                    </p>
                @endif
            </div>
            <div style="width: 49%;padding: 0 10px;" class="border">

                <p>
                    نام و نام‌خانوادگی                 <span>:</span>
                    {{ $order->user->name }}
                </p>
                <p>
                    کد ملی                 <span>:</span>
                    {{ $order->user->national_code }}
                </p>
                <p>
                    آدرس
                    <span>:</span>

                    {{ province_name($order->address->province_id).'-'.city_name($order->address->city_id) }} {{ $order->address->address }}
                </p>
                <p>
                    کد پستی

                    <span>:</span>
                    {{ $order->address->postal_code }}

                </p>
                <p>
                    شماره همراه                 <span>:</span>
                    {{ $order->user->cellphone }}
                </p>
                <p>
                    شماره ثابت
                    <span>:</span>

                    {{ $order->address->tel }}
                </p>
                @if($order->DeliveryMethod->id==1)
                    <p>
                        کد رهگیری
                        <span>:</span>

                        {{ $order->TrackingCode }}
                    </p>
                @endif
            </div>
        </div>
    </div>
    <div class="line">
        <span>صورت حساب</span>
    </div>
    <table class="mt10">
        <tr class="head">
            <td> نام محصول</td>
            <td>مبلغ واحد</td>
            <td> تعداد</td>
            {{--            <th>اقلام افزوده</th>--}}
            <td>جمع مبلغ کالا</td>
            <td>جمع ارزش افزوده و عوارض</td>
            <td>قابل پرداخت</td>
        </tr>
        @php
            $i=1;
            $total_quantitiy=0;
            $total_product_price=0;
            $total_tax_price=0;
            $total_price=0;
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
            <tr class="size">
                <td class="product-name">
                    <div>{{ $item->Product->name }}</div>
                    @if(isset($item->AttributeValues->id) and  $item->AttributeValues->id!=217)
                        <p>{{ $item->AttributeValues->name ?? '' }}</p>
                    @endif
                    @if(isset($item->Color->id) and  $item->Color->id!=346)
                        <p>{{ $item->Color->name ??'' }}</p>
                    @endif
                </td>
                <td class="product-price">
                                    <span class="amount">
                                        {{ number_format($item->product_price) }} تومان
                                        @if($item->option_ids!=null)
                                            @foreach(json_decode($item->option_ids) as $option)
                                                <br>
                                                + {{ number_format(\App\Models\ProductOption::where('id',$option)->first()->price).' تومان ' }}
                                            @endforeach
                                        @endif
                                    </span>
                </td>
                <td>
                    {{ $item->quantity }}
                </td>
                {{--                <td class="product-price">--}}
                {{--                    <a>--}}
                {{--                        @if($item->option_ids!=null)--}}
                {{--                            @if(product_price($item->product_id,$item->product_attr_variation_id)[1]!=0)--}}
                {{--                                <br>--}}
                {{--                            @endif--}}
                {{--                            @foreach(json_decode($item->option_ids) as $option)--}}
                {{--                                <br>{{ \App\Models\ProductOption::where('id',$option)->first()->VariationValue->name }}--}}
                {{--                            @endforeach--}}
                {{--                        @else--}}
                {{--                            ---}}
                {{--                        @endif--}}
                {{--                    </a>--}}
                {{--                </td>--}}
                <td class="product-subtotal">
                                    <span class="amount">
                {{ number_format($item->subtotal/1.09) }}
                                        تومان </span>
                </td>
                <td class="product-subtotal">
                                    <span class="amount">
                {{ number_format($item->subtotal*0.09) }}
                                        تومان </span>
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
                $total_product_price=$total_product_price+($item->subtotal/1.09);
                $total_tax_price=$total_tax_price+($item->subtotal*0.09);
                $total_price=$total_price+$item->subtotal;
                ?>
        @endforeach
        <tr>
            <td style="text-align: right;padding-right: 10px" colspan="2">حاصل جمع</td>
            <td>{{ $order->orderItems()->sum('quantity') }}</td>
            <td>{{ number_format($total_product_price) }} تومان</td>
            <td>{{ number_format($total_tax_price) }} تومان</td>
            <td>{{ number_format($total_price) }} تومان</td>
        </tr>
        <tr>
            <td style="text-align: right;padding-right: 10px" colspan="5">هزینه ی حمل ({{ $order->DeliveryMethod->name }})</td>
            <td>{{ number_format($order->delivery_amount) }} تومان</td>
        </tr>
        <tr>
            <td style="text-align: right;padding-right: 10px" colspan="5">قابل پرداخت</td>
            <td>{{ number_format($order->total_amount) }} تومان</td>
        </tr>
    </table>
    {{--    <table class="mt10">--}}
    {{--        <tr class="head">--}}
    {{--            <td>هزینه ارسال</td>--}}
    {{--            <td>پرداخت شده از کیف پول</td>--}}
    {{--            <th>مبلغ پرداخت شده</th>--}}
    {{--            <th>تعداد کل</th>--}}
    {{--            <td>جمع کل</td>--}}
    {{--        </tr>--}}
    {{--        <tr>--}}
    {{--            <td>{{ number_format($order->delivery_amount) }} تومان</td>--}}
    {{--            <td>{{ number_format($order->wallet) }} تومان</td>--}}
    {{--            <td>{{ number_format($order->paying_amount) }} تومان</td>--}}
    {{--            <td>{{ $total_quantitiy }}</td>--}}
    {{--            <td>{{ number_format($order->total_amount) }} تومان</td>--}}
    {{--        </tr>--}}
    {{--    </table>--}}
</div>
</body>
</html>
