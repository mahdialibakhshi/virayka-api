<div class="myaccount-tab-menu nav" role="tablist">
@php
$user=\Illuminate\Support\Facades\Auth::user();
$path=public_path(env('USER_IMAGES_UPLOAD_PATH').$user->avatar);
if (file_exists($path) and !is_dir($path)){
    $src=asset(env('USER_IMAGES_UPLOAD_PATH').$user->avatar);
}else{
    $src=asset('/home/images/user.png');
}
@endphp
    <span class="text-center p-3">
       <img class="avatar" src="{{ $src }}">
        <div>{{ $user->name.'-'.$user->Role->display_name }}</div>
    </span>
    <a href="{{ route('home.users_profile.index') }}" class="{{ request()->is('profile') ? 'active' : '' }}">
        <i class="w-icon-user ml-1"></i>
        پروفایل
    </a>
    <a href="{{ route('home.orders.users_profile.index') }}" class="{{ request()->is('profile/orders') ? 'active' : '' }}">
        <i class="w-icon-orders ml-1"></i>
        سفارشات
    </a>

    <a href="{{ route('home.addresses.index') }}" class="{{ request()->is('profile/addresses') ? 'active' : '' }}">
        <i class="w-icon-products ml-1"></i>
        آدرس ها
    </a>

    <a href="{{ route('home.wishlist.users_profile.index') }}" class="{{ request()->is('profile/wishlist') ? 'active' : '' }}">
        <i class="w-icon-heart ml-1"></i>
        لیست علاقه مندی ها
    </a>
    <a href="{{ route('home.comments.users_profile.index') }}" class="{{ request()->is('profile/comments') ? 'active' : ( request()->is('comments/create') ? 'active' : '') }}">
        <i class="w-icon-comment ml-1"></i>
        دیدگاه شما
    </a>
    <a href="{{ route('home.ticket.index') }}" class="{{ request()->is('profile/ticketIndex') ? 'active' : '' }}">
        <i class="w-icon-cog2 ml-1"></i>
        درخواست پشتیبانی
    </a>
    <a href="{{ route('home.profile.wallet.index') }}" class="{{ request()->is('profile/wallet') ? 'active' : '' }}">
        <i class="w-icon-wallet2 ml-1"></i>
        کیف پول من
    </a>
    <a href="{{ route('home.profile.informMe.index') }}" class="{{ request()->is('profile/informMe') ? 'active' : '' }}">
        <i class="fas fa-bell ml-1"></i>
        در انتظار موجودی
    </a>
    <a href="{{ route('home.profile.role_request.index') }}" class="{{ request()->is('profile/role_request') ? 'active' : '' }}">
        <i class="fas fa-question ml-1"></i>
        درخواست اکانت همکار
    </a>

    <a href="{{ route('logout') }}">
        <i class="sli sli-logout ml-1"></i>
        خروج
    </a>

</div>
