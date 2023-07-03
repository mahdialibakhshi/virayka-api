<!DOCTYPE html>
<html lang="fa">

<head>
    <meta charset="utf-8"/>
    <link rel="apple-touch-icon" sizes="76x76" href="/home/img/favicon.png">
    <link rel="icon" type="image/png" href="{{ imageExist(env('LOGO_UPLOAD_PATH'),$setting->favicon) }}">
    <title>@yield('title')</title>
    <meta name="description" content="@yield('description')">
    <meta name="author" content="Mirazimi">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
          name='viewport'/>


    <!-- CSS Files -->
    <link href="/home/fonts/font-awesome/css/fontawesome.min.css" rel="stylesheet"/>
    <link href="/home/fonts/font-awesome/css/solid.css" rel="stylesheet"/>
    <link href="/home/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="/home/css/main_ui.css" rel="stylesheet"/>
    <link href="/home/css/icon.css" rel="stylesheet"/>
    <link href="/home/css/plugins/owl.carousel.css" rel="stylesheet"/>
    <link href="/home/css/plugins/owl.theme.default.min.css" rel="stylesheet"/>
    <link href="/home/css/main.css" rel="stylesheet"/>
    <link href="/home/css/style.css" rel="stylesheet"/>
    <link href="/home/css/profile.css" rel="stylesheet"/>
    <script src="{{ asset('home/js/sweetalert.min.js') }}"></script>
    @yield('style')
</head>

<body class="index-page sidebar-collapse">
@if($setting->top_page_banner_active)
    <div class="top-section fullscreen-container">
        <img src="{{ imageExist(env('BANNER_PAGES_UPLOAD_PATH'),$setting->top_page_banner) }}" class="h-100">
    </div>
@endif
<!--start mobile header -->
<nav class="navbar direction-ltr fixed-top header-responsive">
    <div class="container">
        <div class="navbar-translate">

            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse"
                    data-target="#navigation" aria-controls="navigation-index" aria-expanded="false"
                    aria-label="Toggle navigation">
                <span class="navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
            </button>
            <div class="search-nav default">
                <form action="">
                    <input type="text" placeholder="جستجو ...">
                    <button type="submit"><img src="/home/img/search.png" alt=""></button>
                </form>

                <ul>
                    <li><a href="#"><i class="fa fa-user-large colormain" aria-hidden="true"></i></a></li>
                    <li><a href="#"><i class="fa fa-cart-arrow-down colormain" aria-hidden="true"></i></a></li>
                </ul>
            </div>
        </div>
        <div class="collapse navbar-collapse justify-content-end" id="navigation">
            <div class="logo-nav-res default text-center">
                <a href="{{ route('home.index') }}">
                    <img src="{{ imageExist(env('LOGO_UPLOAD_PATH'),$setting->image) }}" alt="{{ $setting->name }}">
                </a>
            </div>
            <ul class="navbar-nav default">
                <li class="sub-menu">
                    <a href="#">موبایل</a>
                    <ul>
                        <li class="sub-menu">
                            <a href="#">لوازم جانبی</a>
                            <ul>
                                <li>
                                    <a href="#">کیف گوشی</a>
                                </li>
                                <li>
                                    <a href="#">کاور گوشی</a>
                                </li>
                                <li>
                                    <a href="#">شارژر همراه</a>
                                </li>
                                <li>
                                    <a href="#">گارد گوشی</a>
                                </li>
                            </ul>
                        </li>
                        <li class="sub-menu">
                            <a href="#">برند ترین ها</a>
                            <ul>
                                <li>
                                    <a href="#">اپل</a>
                                </li>
                                <li>
                                    <a href="#">سامسونگ</a>
                                </li>
                                <li>
                                    <a href="#">هوآوی</a>
                                </li>
                                <li>
                                    <a href="#">شیائومی</a>
                                </li>

                            </ul>
                        </li>
                        <li class="sub-menu">
                            <a href="#">سیستم عامل</a>
                            <ul>
                                <li>
                                    <a href="#">اندروید</a>
                                </li>
                                <li>
                                    <a href="#">آی او اس</a>
                                </li>
                                <li>
                                    <a href="#">ویندوز</a>
                                </li>

                            </ul>
                        </li>
                        <li class="sub-menu">
                            <a href="#">گوشی براساس قیمت</a>
                            <ul>
                                <li>
                                    <a href="#">گوشی تا 2 میلیون تومان</a>
                                </li>
                                <li>
                                    <a href="#">گوشی تا 5 میلیون تومان</a>
                                </li>
                                <li>
                                    <a href="#">گوشی تا 7 میلیون تومان</a>
                                </li>
                                <li>
                                    <a href="#">گوشی تا 15 میلیون تومان</a>
                                </li>
                                <li>
                                    <a href="#">گوشی بالای 15 میلیون تومان</a>
                                </li>

                            </ul>
                        </li>

                        <li class="sub-menu">
                            <a href="#">گوشی براساس حافظه داخلی</a>
                            <ul>
                                <li>
                                    <a href="#">گوشی تا 16 گیگابایت</a>
                                </li>
                                <li>
                                    <a href="#">گوشی تا 32 گیگابایت</a>
                                </li>
                                <li>
                                    <a href="#">گوشی تا 64 گیگابایت</a>
                                </li>
                                <li>
                                    <a href="#">گوشی تا 128 گیگابایت</a>
                                </li>
                                <li>
                                    <a href="#">گوشی تا 256 گیگابایت</a>
                                </li>
                            </ul>
                        </li>
                        <li class="sub-menu">
                            <a href="#">رزولوشن عکس</a>
                            <ul>
                                <li>
                                    <a href="#">تا 13 مگاپیکسل</a>
                                </li>
                                <li>
                                    <a href="#">تا 16 مگاپیکسل</a>
                                </li>
                                <li>
                                    <a href="#">تا 48 مگاپیکسل</a>
                                </li>
                                <li>
                                    <a href="#">تا 64 مگاپیکسل</a>
                                </li>
                                <li>
                                    <a href="#">تا 128 مگاپیکسل</a>
                                </li>
                            </ul>
                        </li>
                        <li class="sub-menu">
                            <a href="#"> گوشی براساس کاربری</a>
                            <ul>
                                <li>
                                    <a href="#">گوشی اقتصادی</a>
                                </li>
                                <li>
                                    <a href="#"> گوشی میان رده</a>
                                </li>
                                <li>
                                    <a href="#">گوشی دانش آموزی</a>
                                </li>
                                <li>
                                    <a href="#">گوشی گیمینگ</a>
                                </li>
                                <li>
                                    <a href="#">گوشی پرچمدار</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="sub-menu">
                    <a href="#">لپ تاپ</a>
                    <ul>
                        <li class="sub-menu">
                            <a href="#">لوازم جانبی</a>
                            <ul>
                                <li>
                                    <a href="#">کیف لپ تاپ</a>
                                </li>
                                <li>
                                    <a href="#">کاور لپ تاپ</a>
                                </li>
                                <li>
                                    <a href="#">شارژر لپ تاپ</a>
                                </li>
                                <li>
                                    <a href="#">فن لپ تاپ</a>
                                </li>
                            </ul>
                        </li>
                        <li class="sub-menu">
                            <a href="#">برند ترین ها</a>
                            <ul>
                                <li>
                                    <a href="#">ایسوس</a>
                                </li>
                                <li>
                                    <a href="#">سامسونگ</a>
                                </li>
                                <li>
                                    <a href="#">اچ پی</a>
                                </li>
                                <li>
                                    <a href="#">اپل</a>
                                </li>

                            </ul>
                        </li>
                        <li class="sub-menu">
                            <a href="#">سیستم عامل</a>
                            <ul>
                                <li>
                                    <a href="#">لینوکس</a>
                                </li>
                                <li>
                                    <a href="#">آی او اس</a>
                                </li>
                                <li>
                                    <a href="#">ویندوز</a>
                                </li>

                            </ul>
                        </li>
                        <li class="sub-menu">
                            <a href="#">لپ تاپ براساس قیمت</a>
                            <ul>
                                <li>
                                    <a href="#">لپ تاپ تا 15 میلیون تومان</a>
                                </li>
                                <li>
                                    <a href="#">لپ تاپ تا 25 میلیون تومان</a>
                                </li>
                                <li>
                                    <a href="#">لپ تاپ تا 40 میلیون تومان</a>
                                </li>
                                <li>
                                    <a href="#">لپ تاپ بالای 40 میلیون تومان</a>
                                </li>

                            </ul>
                        </li>

                        <li class="sub-menu">
                            <a href="#">لپ تاپ براساس حافظه داخلی</a>
                            <ul>
                                <li>
                                    <a href="#">لپ تاپ تا 16 گیگابایت</a>
                                </li>
                                <li>
                                    <a href="#">لپ تاپ تا 32 گیگابایت</a>
                                </li>
                                <li>
                                    <a href="#">لپ تاپ تا 64 گیگابایت</a>
                                </li>
                                <li>
                                    <a href="#">لپ تاپ تا 128 گیگابایت</a>
                                </li>
                                <li>
                                    <a href="#">لپ تاپ تا 256 گیگابایت</a>
                                </li>
                            </ul>
                        </li>
                        <li class="sub-menu">
                            <a href="#">ابعاد صفحه نمایش</a>
                            <ul>
                                <li>
                                    <a href="#">صفحه نمایش 14 اینچ</a>
                                </li>
                                <li>
                                    <a href="#">صفحه نمایش 16 اینچ</a>
                                </li>
                                <li>
                                    <a href="#">صفحه نمایش 18 اینچ</a>
                                </li>
                                <li>
                                    <a href="#">صفحه نمایش 20 اینچ</a>
                                </li>
                                <li>
                                    <a href="#">صفحه نمایش 22 اینچ</a>
                                </li>
                                <li>
                                    <a href="#">صفحه نمایش 24 اینچ</a>
                                </li>
                            </ul>
                        </li>

                    </ul>
                </li>
                <li class="sub-menu">
                    <a href="#">ساعت هوشمند</a>
                    <ul>
                        <li class="sub-menu">
                            <a href="#">لوازم جانبی</a>
                            <ul>
                                <li>
                                    <a href="#">کیف ساعت هوشمند</a>
                                </li>
                                <li>
                                    <a href="#">کاور ساعت هوشمند</a>
                                </li>
                                <li>
                                    <a href="#">شارژر ساعت هوشمند</a>
                                </li>
                                <li>
                                    <a href="#">فن ساعت هوشمند</a>
                                </li>
                            </ul>
                        </li>
                        <li class="sub-menu">
                            <a href="#">برند ترین ها</a>
                            <ul>
                                <li>
                                    <a href="#">ایسوس</a>
                                </li>
                                <li>
                                    <a href="#">سامسونگ</a>
                                </li>
                                <li>
                                    <a href="#">اچ پی</a>
                                </li>
                                <li>
                                    <a href="#">اپل</a>
                                </li>

                            </ul>
                        </li>
                        <li class="sub-menu">
                            <a href="#">سیستم عامل</a>
                            <ul>
                                <li>
                                    <a href="#">لینوکس</a>
                                </li>
                                <li>
                                    <a href="#">آی او اس</a>
                                </li>
                                <li>
                                    <a href="#">ویندوز</a>
                                </li>

                            </ul>
                        </li>
                        <li class="sub-menu">
                            <a href="#">ساعت هوشمند براساس قیمت</a>
                            <ul>
                                <li>
                                    <a href="#">ساعت هوشمند تا 15 میلیون تومان</a>
                                </li>
                                <li>
                                    <a href="#">ساعت هوشمند تا 25 میلیون تومان</a>
                                </li>
                                <li>
                                    <a href="#">ساعت هوشمند تا 40 میلیون تومان</a>
                                </li>
                                <li>
                                    <a href="#">ساعت هوشمند بالای 40 میلیون تومان</a>
                                </li>

                            </ul>
                        </li>

                        <li class="sub-menu">
                            <a href="#">ساعت هوشمند براساس حافظه داخلی</a>
                            <ul>
                                <li>
                                    <a href="#">ساعت هوشمند تا 16 گیگابایت</a>
                                </li>
                                <li>
                                    <a href="#">ساعت هوشمند تا 32 گیگابایت</a>
                                </li>
                                <li>
                                    <a href="#">ساعت هوشمند تا 64 گیگابایت</a>
                                </li>
                                <li>
                                    <a href="#">ساعت هوشمند تا 128 گیگابایت</a>
                                </li>
                                <li>
                                    <a href="#">ساعت هوشمند تا 256 گیگابایت</a>
                                </li>
                            </ul>
                        </li>


                    </ul>
                </li>

                <li class="sub-menu">
                    <a href="#">مودم</a>
                    <ul>

                        <li class="sub-menu">
                            <a href="#">برند ترین ها</a>
                            <ul>
                                <li>
                                    <a href="#">تی پی لینک</a>
                                </li>
                                <li>
                                    <a href="#">دی لینک</a>
                                </li>
                                <li>
                                    <a href="#">اچ پی</a>
                                </li>
                                <li>
                                    <a href="#">همراه اول</a>
                                </li>

                            </ul>
                        </li>
                        <li class="sub-menu">
                            <a href="#">فرکانس تحت پوشش</a>
                            <ul>
                                <li>
                                    <a href="#">1 گیگاهرتز</a>
                                </li>
                                <li>
                                    <a href="#">2.5 گیگاهرتز</a>
                                </li>
                                <li>
                                    <a href="#">4 گیگاهرتز</a>
                                </li>
                                <li>
                                    <a href="#">5.5 گیگاهرتز</a>
                                </li>
                            </ul>
                        </li>
                        <li class="sub-menu">
                            <a href="#">گوشی براساس قیمت</a>
                            <ul>
                                <li>
                                    <a href="#">گوشی تا 2 میلیون تومان</a>
                                </li>
                                <li>
                                    <a href="#">گوشی تا 5 میلیون تومان</a>
                                </li>
                                <li>
                                    <a href="#">گوشی تا 7 میلیون تومان</a>
                                </li>
                                <li>
                                    <a href="#">گوشی تا 15 میلیون تومان</a>
                                </li>


                            </ul>
                        </li>

                        <li class="sub-menu">
                            <a href="#">مودم براساس رنگ</a>
                            <ul>
                                <li>
                                    <a href="#">مودم قرمز</a>
                                </li>
                                <li>
                                    <a href="#">مودم قهوه ای</a>
                                </li>
                                <li>
                                    <a href="#">مودم آبی</a>
                                </li>
                                <li>
                                    <a href="#">مودم مشکی</a>
                                </li>
                                <li>
                                    <a href="#">مودم سفید</a>
                                </li>
                            </ul>
                        </li>
                        <li class="sub-menu">
                            <a href="#">بر اساس برند</a>
                            <ul>
                                <li>
                                    <a href="#">مودم همراه اول</a>
                                </li>
                                <li>
                                    <a href="#">مودم ایرانسل</a>
                                </li>
                                <li>
                                    <a href="#">مودم تی پی لینک</a>
                                </li>
                                <li>
                                    <a href="#">مودم دی لینک</a>
                                </li>
                                <li>
                                    <a href="#">مودم یوتل</a>
                                </li>
                                <li>
                                    <a href="#">مودم ایسوس</a>
                                </li>
                                <li>
                                    <a href="#">مودم تندا</a>
                                </li>
                                <li>
                                    <a href="#">مودم سورنا</a>
                                </li>
                                <li>
                                    <a href="#">مودم مسای</a>
                                </li>
                                <li>
                                    <a href="#">مودم ال جی</a>
                                </li>
                                <li>
                                    <a href="#">مودم سامسونگ</a>
                                </li>


                            </ul>
                        </li>

                    </ul>
                </li>

                <li class="sub-menu">
                    <a href="#">تبلت</a>
                    <ul>
                        <li class="sub-menu">
                            <a href="#">لوازم جانبی</a>
                            <ul>
                                <li>
                                    <a href="#">کیف تبلت</a>
                                </li>
                                <li>
                                    <a href="#">کاور تبلت</a>
                                </li>
                                <li>
                                    <a href="#">شارژر همراه</a>
                                </li>
                                <li>
                                    <a href="#">گارد تبلت</a>
                                </li>
                            </ul>
                        </li>
                        <li class="sub-menu">
                            <a href="#">برند ترین ها</a>
                            <ul>
                                <li>
                                    <a href="#">اپل</a>
                                </li>
                                <li>
                                    <a href="#">سامسونگ</a>
                                </li>
                                <li>
                                    <a href="#">هوآوی</a>
                                </li>
                                <li>
                                    <a href="#">شیائومی</a>
                                </li>

                            </ul>
                        </li>
                        <li class="sub-menu">
                            <a href="#">سیستم عامل</a>
                            <ul>
                                <li>
                                    <a href="#">اندروید</a>
                                </li>
                                <li>
                                    <a href="#">آی او اس</a>
                                </li>
                                <li>
                                    <a href="#">ویندوز</a>
                                </li>

                            </ul>
                        </li>
                        <li class="sub-menu">
                            <a href="#">تبلت براساس قیمت</a>
                            <ul>
                                <li>
                                    <a href="#">تبلت تا 2 میلیون تومان</a>
                                </li>
                                <li>
                                    <a href="#">تبلت تا 5 میلیون تومان</a>
                                </li>
                                <li>
                                    <a href="#">تبلت تا 7 میلیون تومان</a>
                                </li>
                                <li>
                                    <a href="#">تبلت تا 15 میلیون تومان</a>
                                </li>
                                <li>
                                    <a href="#">تبلت بالای 15 میلیون تومان</a>
                                </li>

                            </ul>
                        </li>

                        <li class="sub-menu">
                            <a href="#">تبلت براساس حافظه داخلی</a>
                            <ul>
                                <li>
                                    <a href="#">تبلت تا 16 گیگابایت</a>
                                </li>
                                <li>
                                    <a href="#">تبلت تا 32 گیگابایت</a>
                                </li>
                                <li>
                                    <a href="#">تبلت تا 64 گیگابایت</a>
                                </li>
                                <li>
                                    <a href="#">تبلت تا 128 گیگابایت</a>
                                </li>
                                <li>
                                    <a href="#">تبلت تا 256 گیگابایت</a>
                                </li>
                            </ul>
                        </li>
                        <li class="sub-menu">
                            <a href="#">رزولوشن عکس</a>
                            <ul>
                                <li>
                                    <a href="#">تا 13 مگاپیکسل</a>
                                </li>
                                <li>
                                    <a href="#">تا 16 مگاپیکسل</a>
                                </li>
                                <li>
                                    <a href="#">تا 48 مگاپیکسل</a>
                                </li>
                                <li>
                                    <a href="#">تا 64 مگاپیکسل</a>
                                </li>
                                <li>
                                    <a href="#">تا 128 مگاپیکسل</a>
                                </li>
                            </ul>
                        </li>
                        <li class="sub-menu">
                            <a href="#"> تبلت براساس کاربری</a>
                            <ul>
                                <li>
                                    <a href="#">تبلت اقتصادی</a>
                                </li>
                                <li>
                                    <a href="#"> تبلت میان رده</a>
                                </li>
                                <li>
                                    <a href="#">تبلت دانش آموزی</a>
                                </li>
                                <li>
                                    <a href="#">تبلت گیمینگ</a>
                                </li>
                                <li>
                                    <a href="#">تبلت پرچمدار</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>


                <li class="sub-menu">
                    <a href="#">کامپیوتر</a>
                    <ul>
                        <li class="sub-menu">
                            <a href="#">لوازم جانبی</a>
                            <ul>
                                <li>
                                    <a href="#">کیف کامپیوتر</a>
                                </li>
                                <li>
                                    <a href="#">کاور کامپیوتر</a>
                                </li>
                                <li>
                                    <a href="#">شارژر کامپیوتر</a>
                                </li>
                                <li>
                                    <a href="#">فن کامپیوتر</a>
                                </li>
                            </ul>
                        </li>
                        <li class="sub-menu">
                            <a href="#">برند ترین ها</a>
                            <ul>
                                <li>
                                    <a href="#">ایسوس</a>
                                </li>
                                <li>
                                    <a href="#">سامسونگ</a>
                                </li>
                                <li>
                                    <a href="#">اچ پی</a>
                                </li>
                                <li>
                                    <a href="#">اپل</a>
                                </li>

                            </ul>
                        </li>
                        <li class="sub-menu">
                            <a href="#">سیستم عامل</a>
                            <ul>
                                <li>
                                    <a href="#">لینوکس</a>
                                </li>
                                <li>
                                    <a href="#">آی او اس</a>
                                </li>
                                <li>
                                    <a href="#">ویندوز</a>
                                </li>

                            </ul>
                        </li>
                        <li class="sub-menu">
                            <a href="#">کامپیوتر براساس قیمت</a>
                            <ul>
                                <li>
                                    <a href="#">کامپیوتر تا 15 میلیون تومان</a>
                                </li>
                                <li>
                                    <a href="#">کامپیوتر تا 25 میلیون تومان</a>
                                </li>
                                <li>
                                    <a href="#">کامپیوتر تا 40 میلیون تومان</a>
                                </li>
                                <li>
                                    <a href="#">کامپیوتر بالای 40 میلیون تومان</a>
                                </li>

                            </ul>
                        </li>

                        <li class="sub-menu">
                            <a href="#">کامپیوتر براساس حافظه داخلی</a>
                            <ul>
                                <li>
                                    <a href="#">کامپیوتر تا 16 گیگابایت</a>
                                </li>
                                <li>
                                    <a href="#">کامپیوتر تا 32 گیگابایت</a>
                                </li>
                                <li>
                                    <a href="#">کامپیوتر تا 64 گیگابایت</a>
                                </li>
                                <li>
                                    <a href="#">کامپیوتر تا 128 گیگابایت</a>
                                </li>
                                <li>
                                    <a href="#">کامپیوتر تا 256 گیگابایت</a>
                                </li>
                            </ul>
                        </li>
                        <li class="sub-menu">
                            <a href="#">ابعاد صفحه نمایش</a>
                            <ul>
                                <li>
                                    <a href="#">صفحه نمایش 14 اینچ</a>
                                </li>
                                <li>
                                    <a href="#">صفحه نمایش 16 اینچ</a>
                                </li>
                                <li>
                                    <a href="#">صفحه نمایش 18 اینچ</a>
                                </li>
                                <li>
                                    <a href="#">صفحه نمایش 20 اینچ</a>
                                </li>
                                <li>
                                    <a href="#">صفحه نمایش 22 اینچ</a>
                                </li>
                                <li>
                                    <a href="#">صفحه نمایش 24 اینچ</a>
                                </li>
                            </ul>
                        </li>

                    </ul>
                </li>


                <li class="sub-menu">
                    <a href="#">آیپد اپل</a>
                    <ul>
                        <li class="sub-menu">
                            <a href="#">لوازم جانبی</a>
                            <ul>
                                <li>
                                    <a href="#">کیف آیپد اپل</a>
                                </li>
                                <li>
                                    <a href="#">کاور آیپد اپل</a>
                                </li>
                                <li>
                                    <a href="#">شارژر آیپد اپل</a>
                                </li>
                                <li>
                                    <a href="#">فن آیپد اپل</a>
                                </li>
                            </ul>
                        </li>
                        <li class="sub-menu">
                            <a href="#">برند ترین ها</a>
                            <ul>
                                <li>
                                    <a href="#">ایسوس</a>
                                </li>
                                <li>
                                    <a href="#">سامسونگ</a>
                                </li>
                                <li>
                                    <a href="#">اچ پی</a>
                                </li>
                                <li>
                                    <a href="#">اپل</a>
                                </li>

                            </ul>
                        </li>
                        <li class="sub-menu">
                            <a href="#">سیستم عامل</a>
                            <ul>
                                <li>
                                    <a href="#">لینوکس</a>
                                </li>
                                <li>
                                    <a href="#">آی او اس</a>
                                </li>
                                <li>
                                    <a href="#">ویندوز</a>
                                </li>

                            </ul>
                        </li>
                        <li class="sub-menu">
                            <a href="#">آیپد اپل براساس قیمت</a>
                            <ul>
                                <li>
                                    <a href="#">آیپد اپل تا 15 میلیون تومان</a>
                                </li>
                                <li>
                                    <a href="#">آیپد اپل تا 25 میلیون تومان</a>
                                </li>
                                <li>
                                    <a href="#">آیپد اپل تا 40 میلیون تومان</a>
                                </li>
                                <li>
                                    <a href="#">آیپد اپل بالای 40 میلیون تومان</a>
                                </li>

                            </ul>
                        </li>

                        <li class="sub-menu">
                            <a href="#">آیپد اپل براساس حافظه داخلی</a>
                            <ul>
                                <li>
                                    <a href="#">آیپد اپل تا 16 گیگابایت</a>
                                </li>
                                <li>
                                    <a href="#">آیپد اپل تا 32 گیگابایت</a>
                                </li>
                                <li>
                                    <a href="#">آیپد اپل تا 64 گیگابایت</a>
                                </li>
                                <li>
                                    <a href="#">آیپد اپل تا 128 گیگابایت</a>
                                </li>
                                <li>
                                    <a href="#">آیپد اپل تا 256 گیگابایت</a>
                                </li>
                            </ul>
                        </li>


                    </ul>
                </li>


                <li class="sub-menu">
                    <a href="#">تلویزیون</a>
                    <ul>
                        <li class="sub-menu">
                            <a href="#">لوازم جانبی</a>
                            <ul>
                                <li>
                                    <a href="#">کیف تلویزیون</a>
                                </li>
                                <li>
                                    <a href="#">کاور تلویزیون</a>
                                </li>
                                <li>
                                    <a href="#">شارژر تلویزیون</a>
                                </li>
                                <li>
                                    <a href="#">فن تلویزیون</a>
                                </li>
                            </ul>
                        </li>
                        <li class="sub-menu">
                            <a href="#">برند ترین ها</a>
                            <ul>
                                <li>
                                    <a href="#">ایسوس</a>
                                </li>
                                <li>
                                    <a href="#">سامسونگ</a>
                                </li>
                                <li>
                                    <a href="#">اچ پی</a>
                                </li>
                                <li>
                                    <a href="#">اپل</a>
                                </li>

                            </ul>
                        </li>
                        <li class="sub-menu">
                            <a href="#">سیستم عامل</a>
                            <ul>
                                <li>
                                    <a href="#">لینوکس</a>
                                </li>
                                <li>
                                    <a href="#">آی او اس</a>
                                </li>
                                <li>
                                    <a href="#">ویندوز</a>
                                </li>

                            </ul>
                        </li>
                        <li class="sub-menu">
                            <a href="#">تلویزیون براساس قیمت</a>
                            <ul>
                                <li>
                                    <a href="#">تلویزیون تا 15 میلیون تومان</a>
                                </li>
                                <li>
                                    <a href="#">تلویزیون تا 25 میلیون تومان</a>
                                </li>
                                <li>
                                    <a href="#">تلویزیون تا 40 میلیون تومان</a>
                                </li>
                                <li>
                                    <a href="#">تلویزیون بالای 40 میلیون تومان</a>
                                </li>

                            </ul>
                        </li>

                        <li class="sub-menu">
                            <a href="#">تلویزیون براساس حافظه داخلی</a>
                            <ul>
                                <li>
                                    <a href="#">تلویزیون تا 16 گیگابایت</a>
                                </li>
                                <li>
                                    <a href="#">تلویزیون تا 32 گیگابایت</a>
                                </li>
                                <li>
                                    <a href="#">تلویزیون تا 64 گیگابایت</a>
                                </li>
                                <li>
                                    <a href="#">تلویزیون تا 128 گیگابایت</a>
                                </li>
                                <li>
                                    <a href="#">تلویزیون تا 256 گیگابایت</a>
                                </li>
                            </ul>
                        </li>
                        <li class="sub-menu">
                            <a href="#">ابعاد صفحه نمایش</a>
                            <ul>
                                <li>
                                    <a href="#">صفحه نمایش 14 اینچ</a>
                                </li>
                                <li>
                                    <a href="#">صفحه نمایش 16 اینچ</a>
                                </li>
                                <li>
                                    <a href="#">صفحه نمایش 18 اینچ</a>
                                </li>
                                <li>
                                    <a href="#">صفحه نمایش 20 اینچ</a>
                                </li>
                                <li>
                                    <a href="#">صفحه نمایش 22 اینچ</a>
                                </li>
                                <li>
                                    <a href="#">صفحه نمایش 24 اینچ</a>
                                </li>
                            </ul>
                        </li>

                    </ul>
                </li>
                <li>
                    <a href="#"> دمو محصولات</a>
                </li>
                <li>
                    <a href="#">تخفیفات و پیشنهادات</a>
                </li>
                <li>
                    <a href="#">مَسای امن</a>
                </li>
                <li>
                    <a href="#">مَسای پلاس</a>
                </li>
                <li>
                    <a href="#"> مَسای کلاب </a>
                </li>
                <li>
                    <a href="#">مَسای پی </a>
                </li>
                <li>
                    <a href="#">سوالی دارید؟</a>
                </li>
                <li>
                    <a href="#">فروشنده شوید</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- end mobile header -->
<div class="wrapper default">

    <!--start pc header -->
    @include('home.sections.header')
    <!-- end pc header -->
    @yield('content')

    @include('home.sections.footer')
</div>
<!-- LOGIN MODAL -->
@include('auth.login_modal')
<!--    JS Files   -->
<script src="/home/js/core/jquery.3.2.1.min.js" type="text/javascript"></script>
<script src="/home/js/core/popper.min.js" type="text/javascript"></script>
<script src="/home/js/core/bootstrap.min.js" type="text/javascript"></script>
<script src="/home/js/plugins/bootstrap-switch.js"></script>
<script src="/home/js/plugins/nouislider.min.js" type="text/javascript"></script>
<script src="/home/js/plugins/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="/home/js/plugins/jquery.sharrre.js" type="text/javascript"></script>
<script src="/home/js/now-ui-kit.js" type="text/javascript"></script>
<script src="/home/js/plugins/countdown.min.js" type="text/javascript"></script>
<script src="/home/js/plugins/owl.carousel.min.js" type="text/javascript"></script>
<script src="/home/js/plugins/jquery.easing.1.3.min.js" type="text/javascript"></script>
<!-- custom Js -->
<script src="/home/js/main.js" type="text/javascript"></script>
@yield('script')
{{--//global functions--}}
{{--    //modal login--}}
<script>
    $('.loginWithSMS').click(function () {
        $('.DefaultLogin').hide();
        $('.SMSLoginBox').show();
    })
    $('.SwitchToDefaultLogin').click(function () {
        $('.SMSLoginBox').hide();
        $('.DefaultLogin').show();
    })
    $('#checkOTPForm').hide();
    $('#resendOTPButton').hide();
    //ready to Send Code Ajax
    let login_token;
    $('#loginOTPForm').submit(function (event) {
        var cellphone = $('#cellphoneInput').val();
        event.preventDefault();
        $.post("{{ url('/smsLogin') }}", {
            '_token': "{{ csrf_token() }}",
            'cellphone': cellphone
        }, function (response, status) {
            console.log(response, status);
            login_token = response.login_token;
            swal({
                icon: 'success',
                text: 'رمز یکبار مصرف برای شما ارسال شد',
                timer: 2000
            });
            $('#loginOTPForm').fadeOut();
            $('#checkOTPForm').fadeIn();
            timer();

        }).fail(function (response) {
            console.log(response.responseJSON);
            $('#cellphoneInput').addClass('mb-1');
            $('#cellphoneInputError').fadeIn()
            $('#cellphoneInputErrorText').html(response.responseJSON.errors.cellphone[0])
        })
    })

    //ready to check Code and login Ajax
    $('#checkOTPForm').submit(function (event) {
        let otp = $('#checkOTPInput').val();
        let is_product_page = "{{ request()->is('product/*') }}";
        event.preventDefault();
        $.post("{{ url('/check-otp') }}", {
            '_token': "{{ csrf_token() }}",
            'otp': otp,
            'login_token': login_token
        }, function (response, status) {
            swal({
                icon: 'success',
                text: 'ورود با موفقیت انجام شد',
                timer: 2000
            });
            let login_modal = $('#login_modal');
            login_modal.modal('hide');
            setTimeout(function () {
                if (is_product_page) {
                    window.location.reload();
                } else {
                    $('#addToCartBtn').trigger('click');
                }
            }, 1000)


        }).fail(function (response) {
            // console.log(response.responseJSON);
            $('#checkOTPInput').addClass('mb-1');
            $('#checkOTPInputError').fadeIn()
            $('#checkOTPInputErrorText').html(response.responseJSON.errors.otp[0])
        })
    });

    //resend Code
    $('#resendOTPButton').click(function (event) {
        event.preventDefault();
        $.post("{{ url('/resend-otp') }}", {
            '_token': "{{ csrf_token() }}",
            'login_token': login_token
        }, function (response, status) {
            console.log(response, status);
            login_token = response.login_token;
            swal({
                icon: 'success',
                text: 'رمز یکبار مصرف برای شما ارسال شد',
                timer: 2000
            });
            $('#resendOTPButton').fadeOut();
            timer();
            $('#resendOTPTime').fadeIn();
            $('#resendCodeDiv').fadeIn();

        }).fail(function (response) {
            console.log(response.responseJSON);
            swal({
                icon: 'error',
                text: 'مشکل در ازسال مجدد رمز یکبار مصرف.دوباره تلاش کنید',
                timer: 2000
            });
        })
    })

    //timer for resend Code
    function timer() {
        let time = "2:01";
        let interval = setInterval(function () {
            let countdown = time.split(':');
            let minutes = parseInt(countdown[0], 10);
            let seconds = parseInt(countdown[1], 10);
            --seconds;
            minutes = (seconds < 0) ? --minutes : minutes;
            if (minutes < 0) {
                clearInterval(interval);
                $('#resendOTPTime').hide();
                $('#resendCodeDiv').hide();
                $('#resendOTPButton').fadeIn();
            }
            ;
            seconds = (seconds < 0) ? 59 : seconds;
            seconds = (seconds < 10) ? '0' + seconds : seconds;
            //minutes = (minutes < 10) ?  minutes : minutes;
            $('#resendOTPTime').html(minutes + ':' + seconds);
            time = minutes + ':' + seconds;
        }, 1000);
    }

    function login_modal() {
        $('#cellphoneInput').val('');
        $('#checkOTPInput').val('');
        let login_modal = $('#login_modal');
        login_modal.modal('show');
    }

    //add To CompairList
    function AddToCompareList(event, productId) {
        event.preventDefault();
        $.ajax({
            url: "{{ route('home.compare.add') }}",
            type: "POST",
            dataType: "json",
            data: {
                productId: productId,
                _token: "{{ csrf_token() }}"
            },
            success: function (msg) {
                if (msg[0] === 'ok') {
                    swal({
                        title: "با تشکر",
                        text: "کالای مورد نظر با موفقیت به لیست مقایسه شما اضافه شد",
                        icon: "success",
                        timer: 3000,
                    })
                    let count = msg[1];
                    updateCompareList();
                }
                if (msg[0] === 'exist') {
                    swal({
                        title: "دقت کنید",
                        text: "این کالا از قبل به لیست مقایسه شما اضافه شده است",
                        icon: "warning",
                        timer: 3000,
                    })
                }
                if (msg[0] === 'full') {
                    swal({
                        title: "دقت کنید",
                        text: "حداکثر چهار کالا را میتوانید به لیست مقایسه اضافه کنید",
                        icon: "warning",
                        buttons: 'ok',
                    })
                }
                $('.compare-dropdown').addClass('opened');
            },
            error: function () {
                console.log("something went wrong");
            },
        });
    }

    function updateCompareList() {
        $.ajax({
            url: "{{ route('home.compare.get') }}",
            type: "POST",
            dataType: "json",
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function (msg) {
                if (msg[0] === 'ok') {
                    let count = msg[1];
                    $('#compare_count').text(count);
                    $('#Compare_Items').html(msg[2]);
                }
            },
            error: function () {
                console.log("something went wrong");
            },
        });
    }

    function AddToCart(product_id,
                       quantity,
                       is_single_page = 0,
                       product_has_variation = null,
                       variation_id = null,
                       product_has_color = null,
                       color_id = null,
                       product_has_option = null,
                       option_ids = null) {
        $.ajax({
            url: "{{ route('home.cart.add') }}",
            type: "POST",
            dataType: "json",
            data: {
                _token: "{{ csrf_token() }}",
                product_id: product_id,
                quantity: quantity,
                is_single_page: is_single_page,
                product_has_variation: product_has_variation,
                variation_id: variation_id,
                product_has_color: product_has_color,
                color_id: color_id,
                product_has_option: product_has_option,
                option_ids: option_ids,

            },
            success: function (msg) {
                if (msg[0] == 0) {
                    if (msg[1] == 'quantity') {
                        swal({
                            title: "متاسفیم",
                            text: "تعداد بیشتری از این کالا در انبار موجود نیست",
                            icon: "error",
                            timer: 3000,
                        })
                    }
                    if (msg[1] == 'price_error') {
                        swal({
                            title: "متاسفیم",
                            text: "جهت استعلام قیمت با پشتیبانی تماس بگیرید",
                            icon: "error",
                            timer: 3000,
                        })
                    }
                    if (msg[1] == 'login') {
                        login_modal();
                    }
                }
                if (msg[0] == 1) {
                    if (msg[1] == 'ok') {
                        UpdateCart();
                    }
                }
                if (msg[0] == 'has_attr') {
                    window.location.href = msg[1];
                }
                if (msg[0] == 'has_option') {
                    window.location.href = msg[1];
                }

            },
            error: function () {
                console.log("something went wrong");
            },
        });
    }

    //updateCart
    function UpdateCart() {
        $.ajax({
            url: "{{ route('home.cart.get') }}",
            type: "POST",
            dataType: "json",
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function (msg) {
                if (msg[0] == 'ok') {
                    $('#navbar_a').trigger('click');
                    $('#cart_header').html(msg[1]);

                }
            },
            error: function () {
                console.log("something went wrong");
            },
        });
    }

    //add To WishList
    function AddToWishList(tag, event, productId) {
        event.preventDefault();
        $.ajax({
            url: "{{ route('home.wishlist.add') }}",
            type: "POST",
            dataType: "json",
            data: {
                productId: productId,
                _token: "{{ csrf_token() }}"
            },
            success: function (msg) {
                if (msg[0] == 'ok') {
                    swal({
                        title: "با تشکر",
                        text: "کالای مورد نظر با موفقیت به لیست علاقه‌مندی های شما اضافه شد",
                        icon: "success",
                        timer: 3000,
                    })
                    $(tag).removeClass('white');
                    $(tag).removeAttr('onclick');
                    $(tag).attr('onclick', 'RemoveFromWishList(this,event,' + productId + ')');
                    wishListUpdate();
                }
                if (msg[0] == 'exist') {
                    swal({
                        title: "دقت کنید",
                        text: "کالای مورد نظر در لیست علاقه‌مندی های شما موجود است",
                        icon: "warning",
                        timer: 3000,
                    })
                }
                if (msg[0] == 'login') {
                    swal({
                        title: "دقت کنید",
                        text: "ابتدا باید وارد شوید",
                        icon: "warning",
                        timer: 3000,
                    })
                }
            },
            error: function () {
                console.log("something went wrong");
            },
        });
    }

    //remove from wishlist
    function RemoveFromWishList(tag, event, productId) {
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
                if (msg[0] == 'ok') {
                    swal({
                        title: "با تشکر",
                        text: "کالای مورد نظر با موفقیت از لیست علاقه‌مندی های شما حذف شد",
                        icon: "success",
                        timer: 3000,
                    })
                    $(tag).addClass('white');
                    $(tag).removeAttr('onclick');
                    $(tag).attr('onclick', 'AddToWishList(this,event,' + productId + ')');
                    wishListUpdate();
                }
                if (msg[0] == 'login') {
                    swal({
                        title: "دقت کنید",
                        text: "ابتدا باید وارد شوید",
                        icon: "warning",
                        timer: 3000,
                    })
                }
            },
            error: function () {
                console.log("something went wrong");
            },
        });
    }

    //update wishListCount
    function wishListUpdate() {
        $.ajax({
            url: "{{ route('home.wishlist.get') }}",
            type: "POST",
            dataType: "json",
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function (msg) {
                $('.wishListCount').text(msg[0]);
            },
            error: function () {
                console.log("something went wrong");
            },
        });

    }

    //remove cart sideBar
    function cart_side_bar(cart_id) {
        $.ajax({
            url: "{{ route('home.cart.remove') }}",
            data: {
                _token: "{{ csrf_token() }}",
                cart_id: cart_id,
            },
            dataType: "json",
            type: 'POST',
            beforeSend: function () {

            },
            success: function (msg) {
                console.log(msg);
                if (msg) {
                    if (msg[0] == 1) {
                        UpdateCart();
                    }
                    if (msg[0] == 0) {
                        let message = msg[1];
                        swal({
                            title: 'error',
                            text: message,
                            icon: 'error',
                            buttons: 'ok',
                        })
                    }
                }
            },
        })
    }

    //remove compare sideBar
    function compare_side_bar(product_id) {
        $.ajax({
            url: "{{ route('home.compare.remove_sideBar') }}",
            data: {
                _token: "{{ csrf_token() }}",
                product_id: product_id,
            },
            dataType: "json",
            type: 'POST',
            beforeSend: function () {

            },
            success: function (msg) {
                console.log(msg);
                if (msg) {
                    if (msg[0] == 1) {
                        updateCompareList();
                    }
                    if (msg[0] == 0) {
                        let message = msg[1];
                        swal({
                            title: 'error',
                            text: message,
                            icon: 'error',
                            buttons: 'ok',
                        })
                    }
                }
            },
        })
    }

    function search_header() {
        $('#divParent').hide();
        $('#product_search_box').html('');
        let value = $('#search_input').val();
        let brand = $('#brand').val();
        if (value.length > 1) {
            $.ajax({
                url: "{{ route('home.product.search') }}",
                data: {
                    title: value,
                    brand: brand,
                    _token: "{{ csrf_token() }}"
                },
                dataType: 'json',
                type: 'post',
                beforeSend: function () {

                },
                success: function (msg) {
                    if (msg[0] == 1) {
                        let products = msg[1];
                        $('#product_search_box').html(products);
                        $('#divParent').show();
                        $('#product_search_box').slideDown(500);
                    }
                },
                error: function () {

                }
            })
        }
    }

    function search_header_mobile() {
        $('#divParent_mobile').hide();
        $('#product_search_box_mobile').html('');
        let value = $('#search_input_mobile').val();
        let brand = 0;
        let alertNotFound = '<div class="alert alert-danger text-center">کالایی یافت نشد</div>';
        let searching = '<div class="alert alert-info text-center">در حال جست و جو...</div>';
        if (value.length > 1) {
            $.ajax({
                url: "{{ route('home.product.search') }}",
                data: {
                    title: value,
                    brand: brand,
                    _token: "{{ csrf_token() }}"
                },
                dataType: 'json',
                type: 'post',
                beforeSend: function () {
                    $('#product_search_box_mobile').html(searching);
                    $('#divParent_mobile').show();
                    $('#product_search_box_mobile').slideDown(500);
                    $('#mobile_search_icon').hide();
                    $('#mobile_close_icon').show();
                },
                success: function (msg) {
                    let products = msg[1];
                    if (msg[0] == 1) {
                        if (msg[2] > 0) {
                            $('#product_search_box_mobile').html(products);
                            $('#divParent_mobile').show();
                            $('#product_search_box_mobile').slideDown(500);
                            $('#mobile_search_icon').hide();
                            $('#mobile_close_icon').show();
                        } else {
                            $('#product_search_box_mobile').html(alertNotFound);
                            $('#divParent_mobile').show();
                            $('#product_search_box_mobile').slideDown(500);
                            $('#mobile_search_icon').hide();
                            $('#mobile_close_icon').show();
                        }

                    }
                },
                error: function () {

                }
            })
        }
    }

    $('#search_input').blur(function () {
        $('#divParent').slideUp(1000);
    })
    $('#mobile_close_icon').click(function () {
        $('#search_input_mobile').val('');
        $('#divParent_mobile').slideUp(1000);
        $('#mobile_search_icon').show();
        $('#mobile_close_icon').hide();
    })

    function search_status_order() {
        $('#order_status_header').html('');
        let order_number = $('#check_order_status_input').val();
        if (order_number.length > 1) {
            $.ajax({
                url: "{{ route('home.check_order') }}",
                data: {
                    order_number: order_number,
                    _token: "{{ csrf_token() }}"
                },
                dataType: 'json',
                type: 'post',
                beforeSend: function () {

                },
                success: function (msg) {
                    if (msg) {
                        if (msg[0] == 1) {
                            $('#order_status_header').html(msg[1]);
                            $('#order_status_header').show();
                        }
                    }
                },
                error: function () {

                }
            })
        } else {
            $('#order_status_header').hide(1000);
        }
    }

    $('#check_order_status_btn').click(function () {
        $('#check_order_status_input').show(1000);
    })
    $('#check_order_status_input').blur(function () {
        $('#order_status_header').hide();
        $('#check_order_status_input').hide(1000);
    });

    function informMe(product_id) {
        $.ajax({
            url: "{{ route('product.informMe') }}",
            type: "POST",
            dataType: "json",
            data: {
                _token: "{{ csrf_token() }}",
                product_id: product_id,
            },
            success: function (msg) {
                if (msg[0] == 0) {
                    if (msg[1] == 'login') {
                        login_modal();
                    }
                    if (msg[1] == 'exists') {
                        swal({
                            title: "دقت کنید",
                            text: "این کالا از قبل در لیست در انتظار موجودی شما وجود دارد",
                            icon: "warning",
                            button: 'متوجه شدم'
                        });
                    }
                }
                if (msg[0] == 1) {
                    if (msg[1] == 'ok') {
                        swal({
                            title: "با تشکر",
                            text: "موجود شدن این کالا از طریق پیامک به اطلاع شما خواهد رسید",
                            icon: "success",
                            button: 'ok'
                        });
                    }
                }
            },
            error: function () {
                console.log("something went wrong");
            },
        });
    }

    $('.compare-dropdown').click(function () {
        $(this).addClass('opened');
    });

    $(window).scroll(function (){
        if (window.pageYOffset>140){
            $('.header_login').addClass('relative_login_menu');
        }else {
            $('.header_login').removeClass('relative_login_menu');
        }

    })

</script>
@include('sweet::alert')
</body>


</html>
