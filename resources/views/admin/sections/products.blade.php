@foreach ($products as $key => $product)
    <tr>
        @if($products->has('firstItem'))
            <th>
                {{ $products->firstItem() + $key }}
            </th>
        @else
            <th>
                {{ $key+1 }}
            </th>
        @endif

        <th class="position-relative">
            @if(($product->percentSalePrice!=0 && $product->DateOnSaleTo>Carbon\Carbon::now() && $product->DateOnSaleFrom<Carbon\Carbon::now()) or ($product->percentSalePrice!=0 && $product->has_discount==1))
                <img class="sale_img" src="{{ asset('admin/images/sale.jpg') }}">
            @endif
            <a href="{{ route('admin.products.show', ['product' => $product->id]) }}">
                {{ $product->name }}
            </a>
        </th>
        <th>
            {{ $product->product_code==null ? '-' : $product->product_code }}
        </th>
        <th>
            {{ $product->hit }}
        </th>
        <th>
            @foreach($product->Categories as $cat)
                {{ $cat->name }}
                {{ $loop->last ? '' : '/' }}
            @endforeach
        </th>
        <th>
            {{ $product->brand_id==null ? '-' : $product->brand->name }}
        </th>
        <th>
            <img class="img-thumbnail"
                 src="{{ imageExist(env('PRODUCT_IMAGES_THUMBNAIL_UPLOAD_PATH'),$product->primary_image) }}">
        </th>

        <th>
            <a title="مشخصات فنی"
               href="{{ route('admin.product.attributes.index',['product'=>$product->id]) }}"
               class="btn btn-primary btn-sm">
                <i class="fa fa-cog"></i>
            </a>
        </th>
        <th>
            <a title="محصولات دارای رنگ بندی"
               href="{{ route('admin.product.variations.attribute.edit',['product'=>$product->id]) }}"
               class="btn btn-warning btn-sm">
                <i class="fa fa-plus"></i>
            </a>
        </th>
        <th>
            <a title="اقلام افزوده"
               href="{{ route('admin.product.options.index',['product'=>$product->id]) }}"
               class="btn btn-danger btn-sm">
                <i class="fa fa-tasks"></i>
            </a>
        </th>
        <th>
            <div class="btn-group">
                <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    عملیات
                </button>
                <div class="dropdown-menu">

                    <a href="{{ route('admin.products.edit', ['product' => $product->id]) }}"
                       class="dropdown-item text-right"> ویرایش محصول </a>

                    <a href="{{ route('admin.products.images.edit', ['product' => $product->id]) }}"
                       class="dropdown-item text-right"> ویرایش تصاویر </a>
                    <a href="#" class="dropdown-item text-right text-danger"
                       onclick="RemoveModal({{ $product->id }})">حذف محصول</a>

                </div>
            </div>
        </th>
        <th>
            <a title="نمایش کالا" id="status_icon_{{ $product->id }}"
               onclick="productChangeStatus({{ $product->id }})"
               class="btn btn-sm {{ $product->getRawOriginal('is_active')==1 ? 'btn-success text-white' : 'btn-dark' }}">
                {{ $product->is_active }}
            </a>
        </th>
        <th>
            <a title="پیشنهاد ویژه" id="specialSale_icon_{{ $product->id }}"
               onclick="specialSale({{ $product->id }})"
               class="btn btn-sm {{ $product->getRawOriginal('specialSale')==1 ? 'btn-success text-white' : 'btn-dark' }}">
                {{ $product->specialSale }}
            </a>
        </th>

        <th>
            <a title="نمایش در صفحه محصولات جدید" id="Set_as_new_icon_{{ $product->id }}"
               onclick="Set_as_new({{ $product->id }})"
               class="btn btn-sm {{ $product->getRawOriginal('Set_as_new')==1 ? 'btn-success text-white' : 'btn-dark' }}">
                {{ $product->Set_as_new }}
            </a>
        </th>
        <th>
            <a title="نمایش در صفحه محصولات شگفت انگیز" id="amazing_sale_{{ $product->id }}"
               onclick="amazing_sale({{ $product->id }})"
               class="btn btn-sm {{ $product->getRawOriginal('amazing_sale')==1 ? 'btn-success text-white' : 'btn-dark' }}">
                {{ $product->amazing_sale }}
            </a>
        </th>

        <th>
            {{ $product->quantity }}
        </th>
        <th>
            <input onchange="priority_show_update({{ $product->id }},this)" type="number"
                   class="form-control" value="{{ $product->priority_show }}" style="width: 70px">
        </th>
        <th>
            {{ number_format($product->price) }}
        </th>
        <th>
            <button onclick="product_copy({{ $product->id }})" class="btn btn-secondary" type="button">
                <i class="fa fa-copy"></i>
            </button>
        </th>

    </tr>
@endforeach
