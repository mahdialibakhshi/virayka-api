<div class="profile-page-aside col-xl-3 col-lg-4 col-md-6 center-section order-1">
    <div class="profile-card-1">
        <!--image-->
        <div class="img">
            <img src="{{ imageExist(env('USER_IMAGES_UPLOAD_PATH'),$user->avatar) }}" />
        </div>
        <!--text-->
        <div class="mid-section">
            <div class="name">{{ $user->name }}</div>
            <div class="name">{{ $user->Role->display_name }}</div>
        </div>
    </div>
    <div class="responsive-profile-menu show-md location_me">
        <div class="btn-group">
            <button type="button" class="btn btn-second-masai dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-navicon"></i>
                حساب کاربری شما
            </button>
            <div class="dropdown-menu dropdown-menu-right text-right">
                <a href="order-delivered.html" class="dropdown-item  ">
                    <i class="fa fa-cart-arrow-down colormain" aria-hidden="true"></i>
                    تحویل داده شده
                </a>
                <a href="order-current.html" class="dropdown-item ">
                    <i class="fa fa-cart-arrow-down colormain" aria-hidden="true"></i>   سفارش جاری
                </a>
                <a href="order-cancelled.html" class="dropdown-item">
                    <i class="fa fa-times colormain" aria-hidden="true"></i>
                    لغو شده
                </a>
                <a href="orders-return.html" class="dropdown-item">
                    <i class="fa fa-thumbs-down colormain" aria-hidden="true"></i>
                    مرجوع محصول
                </a>

                <a href="profile-favorites.html" class="dropdown-item">
                    <i class="fa fa-bookmark colormain" aria-hidden="true"></i>
                    لیست های من
                </a>

                <a href="order-address.html" class="dropdown-item">
                    <i class="fa fa-map icon-icon colormain" aria-hidden="true"></i>
                    آدرس ها
                </a>

                <a href="order-message.html" class="dropdown-item">
                    <i class="fa fa-bell colormain" aria-hidden="true"></i>
                    پیغام ها
                </a>

                <a href="profile.html" class="dropdown-item active-menu">
                    <i class="fa fa-user-large colormain"></i>
                    پروفایل
                </a>

                <a href="edit-profile.html" class="dropdown-item">
                    <i class="fa fa-pencil colormain" aria-hidden="true"></i>
                    ویرایش اطلاعات
                </a>

                <a href="password-update.html" class="dropdown-item">
                    <i class="fa fa-shield colormain" aria-hidden="true"></i>
                    امنیت و تغییر رمز
                </a>


            </div>
        </div>
    </div>
    <div class="profile-menu ">
        <ul class="profile-menu-items">
            <li>
                <a href="{{ route('home.users_profile.index') }}" class="dropdown-item {{ request()->is('profile') ? 'active' : ''  }}">
                    <i class="fa fa-user-large colormain"></i>
                    پروفایل
                </a>
            </li>
            <li>
                <a href="{{ route('home.orders.users_profile.index') }}" class="dropdown-item {{ request()->is('profile/orders') ? 'active' : '' }}">
                    <i class="w-icon-orders ml-1"></i>
                    سفارشات
                </a>
            </li>
            <li>
                <a href="{{ route('home.addresses.index') }}" class="dropdown-item {{ request()->is('profile/addresses') ? 'active' : ''  }}">
                    <i class="fa fa-map icon-icon colormain" aria-hidden="true"></i>
                    آدرس ها
                </a>
            </li>
            <li>
                <a href="{{ route('home.wishlist.users_profile.index') }}" class="dropdown-item {{ request()->is('profile/wishlist') ? 'active' : '' }}">
                    <i class="w-icon-heart ml-1"></i>
                    لیست علاقه مندی ها
                </a>
            </li>
            <li>
                <a href="{{ route('home.comments.users_profile.index') }}" class="dropdown-item {{ request()->is('profile/comments') ? 'active' : ( request()->is('comments/create') ? 'active' : '') }}">
                    <i class="w-icon-comment ml-1"></i>
                    دیدگاه شما
                </a>
            </li>
            <li>
                <a href="{{ route('home.ticket.index') }}" class="dropdown-item {{ request()->is('profile/ticket*') ? 'active' : '' }}">
                    <i class="w-icon-cog2 ml-1"></i>
                    درخواست پشتیبانی
                </a>
            </li>
            <li>
                <a href="{{ route('home.profile.wallet.index') }}" class="dropdown-item {{ request()->is('profile/wallet') ? 'active' : '' }}">
                    <i class="w-icon-wallet2 ml-1"></i>
                    کیف پول من
                </a>
            </li>
            <li>
                <a href="{{ route('home.profile.informMe.index') }}" class="dropdown-item {{ request()->is('profile/informMe') ? 'active' : '' }}">
                    <i class="fas fa-bell ml-1"></i>
                    در انتظار موجودی
                </a>
            </li>
            <li>
                <a href="{{ route('home.profile.role_request.index') }}" class="dropdown-item {{ request()->is('profile/role_request') ? 'active' : '' }}">
                    <i class="fas fa-question ml-1"></i>
                    درخواست اکانت همکار
                </a>
            </li>
            <li>
                <a href="{{ route('logout') }}">
                    <i class="sli sli-logout ml-1"></i>
                    خروج
                </a>
            </li>
        </ul>
    </div>
</div>

