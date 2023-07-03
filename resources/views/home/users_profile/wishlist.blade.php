@extends('home.users_profile.layout')

@section('title')
    صفحه ای لیست علاقه مندی ها
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('home/css/profile_panel.css') }}">

    <style>
        table{
            width: 100%;
        }
        tr{
            border-bottom: 1px solid #eeeeee;
        }
        table th{
            text-align: center;
            padding: 20px;

        }
        table td{
            text-align: center;
            padding: 20px;

        }
        .fa-trash{
            color: red;
        }

        li{
            list-style: none !important;
        }
        #myaccountContent{
            margin-top: 0 !important;
        }
        td{
            vertical-align: middle !important;
        }
    </style>
@endsection

@section('script')
    <script>
        //remove from wishlist
        function RemoveFromProfileWishList(tag,event,productId){
            event.preventDefault();
            $.ajax({
                url: "{{ route('home.wishlist.remove') }}",
                type: "POST",
                dataType: "json",
                data: {
                    productId: productId,
                    _token: "{{ csrf_token() }}"
                },
                success: function (msg) {
                    if (msg[0]=='ok'){
                        swal({
                            title: "با تشکر",
                            text: "کالای مورد نظر با موفقیت از لیست علاقه‌مندی های شما حذف شد",
                            icon: "success",
                            timer:3000,
                        })
                        window.location.reload();
                    }
                },
                error: function () {
                    console.log("something went wrong");
                },
            });
        }
    </script>
@endsection

@section('main_content')
    <div class="col-lg-9 col-md-8 order-2">
        @if($user->name==null or $user->national_code==null)
            <div class="alert alert-info text-center">
                برای فعال شدن منو ها از قسمت <a class="ht-btn mx-2" href="{{ route('home.users_profile.index') }}">پروفایل</a> اطلاعات خود را تکمیل نمایید
            </div>
        @else
            <div class="tab-content" id="myaccountContent">

                <div class="myaccount-content">
                    <h3>لیست علاقه مندی ها</h3>
                    <div class="review-wrapper">
                        @if($wishlist->isEmpty())
                            <div class="alert alert-danger text-center">
                                لیست علاقه مندی های شما خالی می باشد
                            </div>
                        @else
                            <div class="table-content cart-table-content table-responsive-sm">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th> تصویر محصول </th>
                                        <th> نام محصول </th>
                                        <th> حذف </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($wishlist as $item)
                                        <tr>
                                            <td class="product-thumbnail">
                                                <a href="{{ route('home.product' , ['alias' => $item->product->alias]) }}">
                                                    <img width="100" src="{{ asset(env('PRODUCT_IMAGES_THUMBNAIL_UPLOAD_PATH') . $item->product->primary_image) }}"
                                                         alt="">
                                                </a>
                                            </td>
                                            <td class="product-name">
                                                <a href="{{ route('home.product' , ['alias' => $item->product->alias]) }}">
                                                    {{ $item->product->name }}
                                                </a>
                                            </td>
                                            <td class="product-name">
                                                <a onclick="RemoveFromProfileWishList(this,event,{{ $item->product->id }})"> <i class="fa fa-trash"
                                                                                                                                style="font-size: 20px"></i> </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        @endif
    </div>
@endsection
