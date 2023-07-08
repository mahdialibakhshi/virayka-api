@auth
    @if ($product->checkUserWishlist(auth()->id()))
        <i title="حذف از به علاقه مندیها"
           onclick="RemoveFromWishList(this,event,{{ $product->id }})"
           class="w-icon-heart black"
           aria-hidden="true"></i>
    @else
        <i title="افزودن به علاقه مندیها"
           onclick="AddToWishList(this,event,{{ $product->id }})"
           class="w-icon-heart black"
           aria-hidden="true"></i>
    @endif
@else
    <i title="افزودن به علاقه مندیها"
       onclick="AddToWishList(this,event,{{ $product->id }})"
       class="w-icon-heart black"
       aria-hidden="true"></i>
@endauth

