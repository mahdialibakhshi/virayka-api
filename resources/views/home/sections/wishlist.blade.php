@auth
    @if ($product->checkUserWishlist(auth()->id()))
        <i title="حذف از به علاقه مندیها"
           onclick="RemoveFromWishList(this,event,{{ $product->id }})"
           class="fa fa-heart search_icon_like"
           aria-hidden="true"></i>
    @else
        <i title="افزودن به علاقه مندیها"
           onclick="AddToWishList(this,event,{{ $product->id }})"
           class="fa fa-heart search_icon_like white"
           aria-hidden="true"></i>
    @endif
@else
    <i title="افزودن به علاقه مندیها"
       onclick="AddToWishList(this,event,{{ $product->id }})"
       class="fa fa-heart search_icon_like white"
       aria-hidden="true"></i>
@endauth

