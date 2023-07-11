<?php

namespace App\Http\Controllers\Home;

use App\Api\SpeedApi;
use App\Http\Controllers\Controller;
use App\Models\AnimationBanner;
use App\Models\Article;
use App\Models\ArticleCategoriy;
use App\Models\Attribute;
use App\Models\AttributeGroup;
use App\Models\AttributeValues;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\CategoryProduct;
use App\Models\CommentIndex;
use App\Models\FunctionalTypes;
use App\Models\JAttrGroup;
use App\Models\JAttrValues;
use App\Models\JCategory;
use App\Models\JExtraField;
use App\Models\JExtraFieldValue;
use App\Models\JProduct;
use App\Models\JProductAttr;
use App\Models\JProductCategory;
use App\Models\JProductsImages;
use App\Models\JUser;
use App\Models\Khabarname;
use App\Models\Offer;
use App\Models\Product;
use App\Models\Page;
use App\Models\ProductAttribute;
use App\Models\ProductAttrVariation;
use App\Models\ProductColorVariation;
use App\Models\ProductImage;
use App\Models\ProductOption;
use App\Models\ProductVariation;
use App\Models\Slider;
use App\Models\User;
use Carbon\Carbon;
use Darryldecode\Cart\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;


class IndexHomeController extends Controller
{
    public function index()
    {
//        $api = new SpeedApi();
//
//        $products = $api->SpeedGet('/Serv/Speed/GetItem','get',null);
//        dd($products);
        visitor()->visit();
        $sliders = Slider::where('is_active', 1)->orderby('priority', 'asc')->get();
        $banners = Banner::all();
        $brands = Brand::all();
        $product_has_sale = Product::where(function ($query) {
            $query->where('DateOnSaleTo', '>', Carbon::now())
                ->where('DateOnSaleFrom', '<', Carbon::now())
                ->where('is_active', 1)
                ->where('percent_sale_price', '>', 0);
        })->latest()
            ->take(10)
            ->get();
        $products_has_sale = Product::where('has_discount', 1)->where('is_active', 1)
            ->latest()
            ->take(10)
            ->get();
        $articles = Article::latest()->take(4)->get();
        //جدیدترین محصولات
        $products_new = Product::where('quantity', '>', 0)
            ->where('Set_as_new', 1)
            ->where('is_active', 1)
            ->orderby('updated_at', 'desc')
            ->take(5)
            ->get();
        //فروش ویژه
        $products_special_sale = Product::where('quantity', '>', 0)
            ->where('is_active', 1)
            ->where('specialSale', 1)
            ->take(8)
            ->orderby('updated_at')
            ->get();
        $products_amazing_sale = Product::where('quantity', '>', 0)
            ->where('is_active', 1)
            ->where('amazing_sale', 1)
            ->orderby('updated_at')
            ->get();
        $products_hit = Product::where('quantity', '>', 0)
            ->where('hit', '>', 0)
            ->orderby('hit', 'asc')
            ->take(10)
            ->get();
        $animation_banner = AnimationBanner::first();
        //دسته بندی های ویژه
        $active_categories = Category::where('showOnIndex', 1)->orderby('priority', 'asc')->get();
        //دسته بندی براساس عملکرد
        $types = FunctionalTypes::orderby('priority', 'asc')->get();
        return view('home.index', compact('sliders',
            'brands',
            'articles',
            'product_has_sale',
            'products_new',
            'products_special_sale',
            'animation_banner',
            'banners',
            'active_categories',
            'products_amazing_sale',
            'types',
            'products_has_sale',
            'products_hit',
        ));
    }

    public
    function categories()
    {
        $categories = Category::where('is_active', 1)->paginate(12);
        return view('home.categories', compact('categories'));
    }

    public
    function getCategoryChild($parent)
    {
        return Category::where('parent_id', $parent)->get();
    }

    public
    function logout()
    {
        auth()->logout();
        return redirect()->route('home.index');
    }

    public
    function redirects()
    {
        $user = auth()->user();
        $cellphone = $user->cellphone;
        if ($cellphone == null) {
            $email = $user->email;
            \auth()->logout();
            return view('auth.confirmMobile', compact('email'));
        } else {
            $role = $user->getRawOriginal('role');
            if ($role != 1) {
                return redirect()->route('home.users_profile.index');
            } elseif ($role == 1) {
                return redirect()->route('dashboard');
            }
        }
    }

//khabarName
    public
    function AddEmailNews(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:Khabarname|email',
        ]);

        if ($validator->fails()) {
            return response()->json('error');
        }

        Khabarname::create([
            'email' => $request->email,
        ]);
        return response()->json('ok');

    }

//get product Info
    public
    function getProductInfo(Request $request)
    {
        $productID = $request->productID;
        $product = Product::where('id', $productID)->first()->load('images');;
        //rate
        $rate = ceil($product->rates->where('product_id', $product->id)->avg('rate'));
        //price
        if ($product->percentSalePrice != 0 && $product->DateOnSaleTo > Carbon::now() && $product->DateOnSaleFrom < Carbon::now()) {
            $price = '<div class="product_price float-left mr-3"><del class="price text-dark">' . number_format($product->price) . ' تومان</del></div><div class="product_price float-left"><span class="price">' . number_format($product->salePrice) . ' تومان</span></div>';
        } else {
            $price = '<div class="product_price float-left"><span class="price">' . number_format($product->price) . ' تومان</span></div>';
        }
        if ($rate == 0) {
            $class = 'text-left emptyStar';
        } else {
            $class = 'text-left';
        }
        //wishlistIcon
        if (Auth::check()) {
            if ($product->checkUserWishlist(auth()->id())) {
                $heart = '<a class="add_wishlist bg-red" onclick="RemoveFromWishList(this,event,' . $product->id . ')" href="#"><i class="ti-heart"></i></a>';
            } else {
                $heart = '<a class="add_wishlist" onclick="AddToWishList(this,event,' . $product->id . ')" href="#"><i class="ti-heart"></i></a>';
            }
        } else {
            $heart = '<a class="add_wishlist bg-red" onclick="RemoveFromWishList(this,event,' . $product->id . ')" href="#"><i class="ti-heart"></i></a>';
        }


        return response()->json([$product, $rate, $price, $heart, $class]);
    }

    public
    function blogs()
    {
        $blogs = Article::latest()->paginate(12);
        $categories = ArticleCategoriy::all();
        return view('home.blogs', compact('blogs', 'categories'));
    }

    public
    function articles_category_sort($cat)
    {
        $cat = ArticleCategoriy::where('alias', $cat)->first();
        $articles = Article::where('category_id', $cat->id)->latest()->paginate(12);
        $categories = ArticleCategoriy::all();
        return view('home.articles', compact('articles', 'categories', 'cat'));
    }

    public
    function blog($alias)
    {
        $blog = Article::where('alias', $alias)->first();
        $categories = ArticleCategoriy::all();
        return view('home.blog', compact('blog', 'categories'));
    }

    public
    function variation_getPrice(Request $request)
    {
        $variation_ids = $request->attr_ids;
        $total_price = 0;
        $titles = [];
        if ($variation_ids != null) {
            foreach ($variation_ids as $variation_id) {
                $variation = ProductOption::where('id', $variation_id)->first();
                $total_price = $total_price + $variation->price;
                array_push($titles, $variation->VariationValue->name);
            }
        }
        return response()->json([1, $total_price, $titles]);
    }

    public
    function page(Page $page)
    {
        return view('home.page', compact('page'));
    }


}
