@foreach($products as $product)
    <div class="col-xl-4 col-lg-4 col-md-6 col-12 list_search_p ">
        <div class="product-box position-relative">
            @if(count($product->ProductAttributeVariation($product->id))>0)
                <div class="product-pa-wrapper product_colors">
                    @foreach($product->ProductAttributeVariation($product->id) as $variation)
                        @if($variation->quantity > 0)
                            <span>
                                                    <img class="img-variations"
                                                         src="{{ imageExist(env('ATTR_UPLOAD_PATH'),$variation->Color->image) }}">
                                                </span>
                        @endif
                    @endforeach
                </div>
            @endif
            <div class="product-label">
                @if($product->label>0)
                    <span class="custom_label"
                          style="background-color: {{ $product->Label->color }}">{{ $product->Label->name }}</span>
                @endif
            </div>
            <div class="product-seller-details product-seller-details-item-grid">
                                                        <span class="search_prod_icon">
                                                            <i onclick="window.location.href='{{ route('home.product',['alias'=>$product->alias]) }}'"
                                                               class="fa fa-search search_icon_search"
                                                               aria-hidden="true"></i>
                                                            @include('home.sections.wishlist')
                                                        </span>


                <span onclick="AddToCart({{ $product->id }},1,0)" class="search_prod_btn">
                                                            <i class="fa fa fa-cart-arrow-down search_icon_cart"
                                                               aria-hidden="true"></i>
                                                        </span>
            </div>
            <a class="product-box-img" href="{{ route('home.product',['alias'=>$product->alias]) }}">
                <img src="{{ imageExist(env('PRODUCT_IMAGES_THUMBNAIL_UPLOAD_PATH'),$product->primary_image) }}"
                     alt="{{ $product->name }}">
            </a>
            <div class="product-box-content">
                <div class="product-box-content-row">
                    <div class="product_title">
                        <a href="{{ route('home.product',['alias'=>$product->alias]) }}">
                            {{ $product->name }}
                        </a>
                    </div>
                </div>
                <div class="product-box-row product_price_search">
                    @if(product_price_for_user_normal($product->id)[2]==0)
                        <div class="price">
                            <ins><span>اتمام موجودی</span></ins>
                        </div>
                    @else
                        <div class="price">
                            @if(product_price_for_user_normal($product->id)[1]!=0)
                                <del><span>{{ number_format(product_price_for_user_normal($product->id)[0]) }}<span>تومان</span></span>
                                </del>
                            @endif
                            @if(product_price_for_user_normal($product->id)[1]!=0)
                                <span
                                    class="discount_badge">{{ number_format(product_price_for_user_normal($product->id)[1]).'%' }}</span>
                            @endif

                            <ins>
                                <span>{{ number_format(product_price_for_user_normal($product->id)[2]) }}<span>تومان</span></span>
                            </ins>
                        </div>
                    @endif
                        <?php
                        $product_attributes = \App\Models\ProductAttribute::where('product_id', $product->id)->where('is_original', 1)->where('short_text', '!=', null)->orderby('priority', 'ASC')->take(4)->get();
                        ?>
                    <div class="d-flex justify-content-center align-items-center product_attribute">
                        @foreach($product_attributes as $product_attribute)
                            <div class="mt-2 product_attr_short_text">
                        <span><img title="{{ $product_attribute->attribute->name }}"
                                   class="product_attribute_icon"
                                   src="{{ imageExist(env('ATTR_UPLOAD_PATH'),$product_attribute->attribute->image) }}"
                                   alt="{{ $product_attribute->short_text }}"></span>
                                <span class="short_text">
                            {{ $product_attribute->short_text }}
                    </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
