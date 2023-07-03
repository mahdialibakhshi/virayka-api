<!doctype html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>پرینت پیک</title>
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
            font-size: 14px;
        }

        .border-dashed {
            border: 5px dashed black;
        }

        .container {
            width: 90%;
            margin: 20px auto;
            padding: 1%;
        }

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

        #shopInfo > p {
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

        #customerInformation {
            width: 100%;
            border: 1px solid #eee;
        }

        #customerInformation > div {
            display: flex;
            justify-content: space-between;
        }

        #customerInformation p {
            padding: 3px;
        }

        .img-thumbnail {
            width: 50px !important;
            height: auto;
        }

        table {
            page-break-inside: auto
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto
        }

        thead {
            display: table-header-group
        }

        tfoot {
            display: table-footer-group
        }

        .border {
            border: 1px solid black !important;
        }

        .col-12 {
            border: 1px solid black;
        }
        .QrCode{
            margin-top: 20px;
            border: 0 !important;
        }
        .order_resume{
            background: #625757;
            color: white;
        }
        .delivery_image{
            width: 100px;
            height: auto;
            margin: 5px auto;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"
            integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript">
        $(document).ready(function ($) {
            let size = $('.size').css('height');
            var element = document.getElementById('element-to-print');
            var opt = {
                margin: 1,
                filename: 'myfile.pdf',
                image: {type: 'jpeg', quality: 100},
                html2canvas: {scale: 2},
                direction: "rtl",
                jsPDF: {unit: 'in', format: 'letter', orientation: 'p'}
            };
            html2pdf().set(opt).from(element).save();
        })
    </script>
</head>
<body>
<div id="element-to-print" class="container-fluid">
    <div class="row p-3 border-dashed">
        <div class="col-12">
            <div class="row">
                <div class="col-md-6 border-right text-center d-flex">
                    @if($order->DeliveryMethod->id==4)
                        <img class="delivery_image" src="{{ asset('QRtipax.png') }}">
                    @endif
                    @if($order->DeliveryMethod->id==2)
                        <img class="delivery_image" src="{{ asset('Peyk.jpg') }}">
                    @endif
                    @if($order->DeliveryMethod->id==1)
                        <img class="delivery_image" src="{{ asset('Post.jpg') }}">
                    @endif
                </div>
                <div class="col-md-6 col-12 d-flex align-center">
                    <div class="m-3">
                        <div>فرستنده <span>:</span>{{ $setting->name }}</div>
                        <div>آدرس <span>:</span> {{ $setting->address }}</div>
                        <div>موبایل <span>:</span> {{ $setting->cellphone }}</div>
                        <div>تلفن <span>:</span> {{ $setting->tel }}</div>
                        <div>فروشگاه <span>:</span> {{ $setting->name }}</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 border-right d-flex align-center">
                    <div class="m-3">
                        <div>گیرنده
                            <span>:</span> {{ province_name($order->address->province_id).'-'.city_name($order->address->city_id) }} {{ $order->address->address }}
                        </div>
                        <div>نام و نام خانوادگی <span>:</span> {{ $order->user->name }}</div>
                        <div>کدپستی <span>:</span> {{ $order->address->postal_code }}</div>
                        <div>شماره تماس <span>:</span> {{ $order->user->cellphone }}</div>
                        <div>تاریخ سفارش <span>:</span> {{ verta($order->created_at)->format('d-m-Y') }} <span> </span>{{ verta($order->created_at)->format('H:i') }}</div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="text-center p-3">
                        روش حمل و نقل<span>:</span> {{ $order->DeliveryMethod->name }} | شماره سفارش
                        <span>:</span> {{ $setting->productCode.' - '.$order->order_number }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 d-flex order_resume p-4">
                    <div class="w-25 text-center">تعداد <span>:</span></div>
                    <div class="w-25 text-center">{{ $order->orderItems()->sum('quantity') }} عدد</div>
                    <div class="w-25 text-center" style="border-right: 1px solid white">حاصل جمع <span>:</span></div>
                    <div class="w-25 text-center">{{ number_format($order->total_amount) }} تومان</div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
        integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
</body>
</html>
