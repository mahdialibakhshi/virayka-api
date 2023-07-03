<?php
$brands = \App\Models\Brand::all()
?>
<style>
    .img-thumbnail {
        width: 80px;
    }

    .header-right {
        position: relative;
    }

    .w-icon-telegram:before {
        content: "f2c6";
    }

    .telegram_image {
        width: 20px;
    }

    #divParent_mobile {
        display: none;
    }
</style>
<header class="Masai-header default bg-black">
    <div class="container-fluid">
        <div class="row align-center p-3">
            <div class="col-lg-5 col-md-5 col-sm-8 col-7">
                <div class="search-area default">
                    <form method="get" action="{{ route('home.product.search') }}" class="search">
                        <button type="submit" class="bg-yellow with-50 color_red_base"><img src="/home/img/search.png"
                                                                                            alt=""></button>
                        <input name="search" type="text" placeholder="جستجو">
                    </form>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-4 col-5">
                <div class="logo-area default">
                    <a href="{{ route('home.index') }}">
                        <img src="{{ imageExist(env('LOGO_UPLOAD_PATH'),$setting->image) }}" alt="{{ $setting->name }}">
                    </a>
                </div>
            </div>

            <div class="col-md-5 col-sm-12 header_login">
                <div class="cart dropdown masai_dropdown">
                    <a href="#" class="dropdown-toggle iconhead bg-yellow with-50" data-toggle="dropdown" id="navbar_a">
                        <i class="fa fa-cart-arrow-down font-20 color_red_base" aria-hidden="true"></i>
                    </a>
                    @auth
                        <?php
                        $carts = \App\Models\Cart::where('user_id', auth()->id())->get();
                        foreach ($carts as $cart) {
                            $product_attr_variation = \App\Models\ProductAttrVariation::where('product_id', $cart->product_id)
                                ->where('attr_value', $cart->variation_id)
                                ->where('color_attr_value', $cart->color_id)
                                ->first();
                            if ($product_attr_variation != null) {
                                $product_attr_variation_id = $product_attr_variation->id;
                                $cart['product_attr_variation_id'] = $product_attr_variation_id;
                            }
                            $option_ids = json_decode($cart->option_ids);
                            $cart['option_ids'] = $option_ids;
                        }
                        ?>
                    @else
                        <?php
                        $carts = [];
                        ?>
                    @endauth
                    <div id="cart_header" class="@if(count($carts)>0) dropdown-menu @endif" aria-labelledby="navbar_a">

                        @include('home.sections.cart')
                    </div>
                </div>
                <div class="user_head">
                    <a href="{{ route('login') }}" class="iconhead bg-yellow with-50 ml-10">
                        <i class="fa fa-user-large font-20 color_red_base " aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid bg-bold-gray">
        <nav class="row nav_header pr-3">
            <div class="col-12">
                <ul class="nav__ullist">
                    <li class="list_style">
                        <i class="fa fa-bars icon-icon" aria-hidden="true"></i><a href="#" class="list__link">دسته بندی
                            کالاها</a>
                        <div class="submeno">
                            <ul class="main_meno-drobdown">
                                @foreach($categories as $category)
                                    <li class="child_mno-drobdown">
                                        <a href="{{ route('home.product_categories',['category'=>$category->id]) }}"
                                           class="run">{{ $category->name }}</a>
                                        <div class="mega_meno">
                                            @if(count($category->children)>0)
                                                <ul class="list_drobdown--item">
                                                    <ul class="ul_list">
                                                        <ul class="mega_meno--block">
                                                            @foreach($category->children as $category)
                                                                <li class="list_mega ">
                                                                    <a href="{{ route('home.product_categories',['category'=>$category->id]) }}"
                                                                       class="mega_link--link texr_header">{{ $category->name }}</a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </ul>
                                                </ul>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                    <li class="list_style">
                        <i class="fa fa-film icon-icon" aria-hidden="true"></i><a href="{{ route('home.brands') }}"
                                                                                  class="list__link">برند ها</a>
                    </li>
                    <li class="list_style">
                        <i class="fa fa-percent icon-icon" aria-hidden="true"></i><a
                            href="{{ route('home.products.new') }}"
                            class="list__link">
                            محصولات جدید
                        </a>
                    </li>
                    <li class="list_style">
                        <i class="fa fa-user-secret icon-icon" aria-hidden="true"></i><a
                            href="{{ route('home.products.special') }}" class="list__link">
                            فروش ویژه
                        </a>
                    </li>
                    <li class="list_style">
                        <i class="fa fa-user-secret icon-icon" aria-hidden="true"></i><a
                            href="{{ route('home.contact') }}" class="list__link">
                            تماس با ما
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>

</header>
