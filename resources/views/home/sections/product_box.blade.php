<div class="item position-relative">
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
        @if(!request()->is('/'))
            @if($product->label>0)
                <span class="custom_label"
                      style="background-color: {{ $product->Label->color }}">{{ $product->Label->name }}</span>
            @endif
        @endif
    </div>
    <a href="{{ route('home.product',['alias'=>$product->alias]) }}">
        <img src="{{ imageExist(env('PRODUCT_IMAGES_THUMBNAIL_UPLOAD_PATH'),$product->primary_image) }}"
             class="img-fluid" alt="{{ $product->name }}">
    </a>
    <p class="product_title">
        <a href="{{ route('home.product',['alias'=>$product->alias]) }}"> {{ $product->name }} </a>
    </p>
    @if(product_price_for_user_normal($product->id)[2]==0)
        <div class="price">
            <ins><span>اتمام موجودی</span></ins>
        </div>
    @else
        <div class="price">
            <div class="d-flex justify-content-center align-center text-center E-height">
            @if(product_price_for_user_normal($product->id)[1]!=0)

<span class="pre_price">{{ number_format(product_price_for_user_normal($product->id)[0]) }}
                                                                    <span>ت</span>
                                                                </span>


            @endif
            </div>
                @if(product_price_for_user_normal($product->id)[1]!=0)

                    <span class="discount_badge d-flex justify-content-center align-center">
                                                    {{ number_format(product_price_for_user_normal($product->id)[1]).'%' }}
                                                <br>
                                                    OFF
                                                </span>
                @endif
                <div class="d-flex justify-content-center align-center text-center E-height">
                    <span class="original_price">{{ number_format(product_price_for_user_normal($product->id)[2]) }}
                                                                    <span>ت</span>
                                                                </span>
               </div>
        </div>
    @endif
</div>
