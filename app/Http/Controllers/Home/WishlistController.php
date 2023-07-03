<?php

namespace App\Http\Controllers\Home;

use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WishlistController extends Controller
{
    public function add(Request $request)
    {
        $product = Product::where('id', $request->productId)->first();
        if (auth()->check()) {
            if ($product->checkUserWishlist(auth()->id())) {
                return response()->json(['exist']);
            } else {
                Wishlist::create([
                    'user_id' => auth()->id(),
                    'product_id' => $product->id
                ]);

                return response()->json(['ok']);
            }
        } else {
            return response()->json(['login']);
        }
    }

    public function remove(Request $request)
    {
        $product = Product::where('id', $request->productId)->first();
        if (auth()->check()) {
            $wishlist = Wishlist::where('product_id', $product->id)->where('user_id', auth()->id())->firstOrFail();
            if ($wishlist) {
                Wishlist::where('product_id', $product->id)->where('user_id', auth()->id())->delete();
            }
            return response()->json(['ok']);
        } else {
            return response()->json(['login']);
        }
    }

    public function get()
    {
        if (auth()->check()) {
            $wishlist = Wishlist::where('user_id', auth()->id())->get();
            return response()->json([count($wishlist)]);
        } else {
            return response()->json(['login']);
        }
    }

    public function usersProfileIndex()
    {
        $user = User::find(auth()->id());
        $wishlist = Wishlist::where('user_id', auth()->id())->get();
        return view('home.users_profile.wishlist', compact('wishlist', 'user'));
    }
}
