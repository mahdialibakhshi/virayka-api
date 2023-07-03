@extends('admin.layouts.admin')

@section('title')
    show products
@endsection
@section('script')
    <script>
        $('#brandSelect').selectpicker({
            'title': 'انتخاب برند'
        });
        $('#tagSelect').selectpicker({
            'title': 'انتخاب تگ'
        });

    </script>
@endsection
@section('content')

    <!-- Content Row -->
    <div class="row">

        <div class="col-xl-12 col-md-12 mb-4 p-4 bg-white">
            <div class="mb-4 text-center text-md-right">
                <h5 class="font-weight-bold">محصول : {{ $product->name }}</h5>
            </div>
            <hr>

            <div class="row">
                <div class="form-group col-md-4">
                    <label for="name">نام</label>
                    <input class="form-control" id="name" name="name" type="text" value="{{ $product->name }}" disabled>
                </div>
                <div class="form-group col-md-4">
                    <label for="is_active">وضعیت</label>
                    <select class="form-control" id="is_active" name="is_active" disabled>
                        <option value="1" {{ $product->getRawOriginal('is_active')==1 ? 'selected' : '' }}>فعال</option>
                        <option value="0" {{ $product->getRawOriginal('is_active')==0 ? 'selected' : '' }}>غیرفعال</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="category_id">دسته بندی</label>
                    <select id="categorySelect" name="category_id" class="form-control" data-live-search="true" disabled>
                        <option value="" {{ $product->category_id==null ? 'selected' : '' }}>
                            -
                        </option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ $category->id==$product->category_id ? 'selected' : '' }}>{{ $category->name }} -
                                {{ $category->parent->name }}
                            </option>
                        @endforeach
                    </select>
                </div> <div class="form-group col-md-4">
                    <label for="is_active">برند</label>
                    <select class="form-control" id="brand" name="brand" disabled>
                        <option value="0" {{ $product->brand_id==0 ? 'selected' : '' }}>بدون برند</option>
                        @foreach($brands as $brand)
                            <option
                                value="{{ $brand->id }}" {{ $product->brand_id==$brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="name">قیمت اصلی (تومان)</label>
                    <input class="form-control" id="price" name="price" type="text" value="{{ number_format($product->price) }}" disabled>
                </div>
                <div class="form-group col-md-4">
                    <label for="name">موجودی انبار</label>
                    <input class="form-control" id="quantity" name="quantity" type="text" value="{{ $product->quantity }}" disabled>
                </div>
                <div class="form-group col-md-4">
                    <label for="is_active">برچسب</label>
                    <select class="form-control" id="label" name="label" disabled>
                        <option value="0" {{ $product->label==0 ? 'selected' : '' }}>بدون برچسب</option>
                        @foreach($labels as $label)
                            <option
                                value="{{ $label->id }}" {{ $product->label==$label->id ? 'selected' : '' }}>{{ $label->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <hr>
                </div>
                {{-- Sale Section --}}
                <div class="col-md-12">
                    <p> تخفیف : </p>
                </div>
                <div class="form-group col-md-3">
                    <label for="name">قیمت با تخفیف ( تومان )</label>
                    <input class="form-control" id="salePrice" name="salePrice" type="text"
                           value="{{ number_format($product->salePrice) }}" disabled>
                </div>
                <div class="form-group col-md-3">
                    <label> تخفیف برحسب درصد ( % )</label>
                    <input id="percentSalePrice" name="percentSalePrice"
                           value="{{ $product->percentSalePrice }}" class="form-control" disabled>
                </div>
                <div class="form-group col-md-3">
                    <label> تاریخ شروع تخفیف </label>
                    <input
                           value="{{ verta($product->DateOnSaleFrom)->format('Y-m-d') }}" class="form-control" disabled>
                </div>
                <div class="form-group col-md-3">
                    <label> تاریخ پایان تخفیف </label>
                    <input
                           value="{{ verta($product->DateOnSaleTo)->format('Y-m-d') }}" class="form-control" disabled>
                </div>
                <div class="col-md-12">
                    <div class="alert d-flex justify-content-between" style="background-color: #eaecf4;border: 1px solid #d1d3e2;">
                        <p class="m-0">آیا تمایل دارید این محصول در بخش شمارش معکوس نمایش داده شود ؟</p>
                        <input type="checkbox" disabled {{ $product->showOnIndex==1 ? 'checked' : '' }}>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12">
                  <hr>
                </div>
                <div class="form-group col-md-12">
                    <label for="shortDescription">توضیحات مختصر</label>
{!! $product->shortDescription !!}
                </div>
                <div class="form-group col-md-12">
                    <hr>
                </div>
                <div class="form-group col-md-12">
                    <label for="description">توضیحات</label>
{!! $product->description !!}
                </div>
                @if($product->video=!null)
                <div class="form-group col-md-12">
                    <?php  echo $product->video ?>
                </div>
                @endif
                {{-- Images --}}
                <div class="col-md-12">
                    <hr>
                    <p>تصویر اصلی : </p>
                </div>

                <div class="col-md-3">
                    <div class="card">
                        <img class="card-img-top"
                         src="{{ url(env('PRODUCT_IMAGES_UPLOAD_PATH') . $product->primary_image) }}"
                            alt="{{ $product->name }}">
                    </div>
                </div>

                <div class="col-md-12">
                    <hr>
                    <p>گالری تصاویر : </p>
                </div>
            @foreach ($images as $image)
                <div class="col-md-3">
                    <div class="card">
                        <img class="card-img-top"
                         src="{{ url(env('PRODUCT_IMAGES_UPLOAD_PATH') . $image->image) }}"
                            alt="{{ $product->name }}">
                    </div>
                </div>
                @endforeach

            </div>

            <a href="{{ route('admin.products.index') }}" class="btn btn-dark mt-5">بازگشت</a>

        </div>

    </div>

@endsection
