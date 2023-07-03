@extends('admin.layouts.admin')

@section('title')
    edit products
@endsection

@section('style')
    <style>
        .img-thumbnail{
            width: 300px !important;
            height: auto;
        }
    </style>
@endsection
@section('script')
    <script>
        $('#categorySelect').selectpicker({
            'title': 'انتخاب دسته بندی'
        });
        $('#functionalTypesSelect').selectpicker({
            'title': 'انتخاب براساس عملکرد'
        });

        $(`#variationDateOnSaleFrom`).MdPersianDateTimePicker({
            targetTextSelector: `#variationInputDateOnSaleFrom`,
            englishNumber: true,
            enableTimePicker: true,
            textFormat: 'yyyy-MM-dd HH:mm:ss',
        });

        $(`#variationDateOnSaleTo`).MdPersianDateTimePicker({
            targetTextSelector: `#variationInputDateOnSaleTo`,
            englishNumber: true,
            enableTimePicker: true,
            textFormat: 'yyyy-MM-dd HH:mm:ss',
        });

        //calculate Discount
        $('#sale_price').keyup(function () {
            let mainPrice = $('#price').val();
            mainPrice=mainPrice.replaceAll(',','',mainPrice);
            let discount = $(this).val();
            if (parseInt(discount) > parseInt(mainPrice)) {
                alert('قیمت پس از تخفیف باید کمتر از قیمت اصلی باشد');
                $('#sale_price').val(number_format(mainPrice));
                $('#percent_sale_price').val(0);
            } else {
                let percentDiscount = calculatePercentDiscount(discount, mainPrice);
                $('#percent_sale_price').val(percentDiscount);
            }
        })
        $('#percent_sale_price').keyup(function () {
            let percentDiscount = $(this).val();
            let mainPrice = $('#price').val();
            mainPrice=mainPrice.replaceAll(',','',mainPrice);
            if (parseInt(mainPrice) < 0) {
                alert('ابتدا برای کالا قیمت اصلی را وارد کنید');
            } else {
                if (percentDiscount > 100) {
                    $('#percent_sale_price').val(100);
                    percentDiscount = 100;
                }
                if (percentDiscount < 0) {
                    $('#percent_sale_price').val(0);
                    percentDiscount = 0;
                }
                let Discount = calculateDiscount(percentDiscount, mainPrice);
                $('#sale_price').val(Discount);
            }
        })

        $('#price').keyup(function (){
            let price=$(this).val();
            $('#sale_price').val(price);
            $('#percent_sale_price').val(0);
        })
        //
        function calculateDiscount(percentDiscount, mainPrice) {
            let Discount = ((100 - percentDiscount) * mainPrice) / 100;
            return Discount;
        }
        function calculatePercentDiscount(discount, mainPrice) {
            let percentDiscount = ((mainPrice - discount) / mainPrice) * 100;
            return percentDiscount;
        }


        $("#showOnIndex").change(function () {
            if (this.checked) {
                let variationInputDateOnSaleFromVal = $('#variationInputDateOnSaleFrom').val();
                let variationInputDateOnSaleToVal = $('#variationInputDateOnSaleTo').val();
                if (variationInputDateOnSaleFromVal === "" || variationInputDateOnSaleToVal == "") {
                    alert('برای نمایش در بخش شمارش معکوس ابتدا بایستی زمان شروع و پایان تخفیف را وارد نمایید');
                    $("#showOnIndex").prop('checked', false);
                }
            }
        });

        function searchProduct() {
            let keyWord = $('#productSearchInput').val();
            let product_id="{{ $product->id }}";
            $.ajax({
                url: "{{ route('admin.products.search') }}",
                type: "POST",
                dataType: "json",
                data: {
                    _token: "{{ csrf_token() }}",
                    keyword: keyWord,
                    product_id: product_id
                },
                success: function (products) {
                    if (products) {
                        let row = "";
                        $.each(products, function (index, product) {
                            row += creatRelatedProduct(product);
                            $('#productRelatedRow').html(row);
                        })
                    }
                },
                error: function () {
                    console.log("something went wrong");
                },
            });
        }

        function creatRelatedProduct(product) {
            let id = product.id;
            let name = product.name;
            let env = '{{ asset(env('PRODUCT_IMAGES_THUMBNAIL_UPLOAD_PATH')) }}';
            let image = product.primary_image;
            let html = '<div id="' + id + '" class="col-6 col-md-2 text-center"><div>' +
                '<img class="img-thumbnail mb-3" src="' + env + '/' + image + '">' +
                '<span">' + name + '</span>' +
                '<button onclick="addToRelated(' + id + ')" class="btn btn-sm btn-outline-dark mr-2" type="button">افزودن</button>' +
                '</div></div>';
            return html;
        }

        function addToRelated(id) {
            $.ajax({
                url: "{{ route('admin.product.ajax') }}",
                type: "POST",
                dataType: "json",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                success: function (product) {
                    if (product) {
                        let id = product.id;
                        let name = product.name;
                        let env = '{{ asset(env('PRODUCT_IMAGES_THUMBNAIL_UPLOAD_PATH')) }}';
                        let image = product.primary_image;
                        let html = '<div id="' + id + '" class="col-6 col-md-2 text-center"><div>' +
                            '<img class="img-thumbnail mb-3" src="' + env + '/' + image + '">' +
                            '<span">' + name + '</span>' +
                            '<input type="hidden" name="relatedProduct[]" value="' + id + '">' +
                            '<button onclick="deleteFromRelated(this,' + id + ')" class="btn btn-sm btn-outline-danger mr-2" type="button">حذف</button>' +
                            '</div></div>';
                        $('#relatedSelected').append(html);
                    }
                },
                error: function () {
                    console.log("something went wrong");
                },
            });
        }

        function deleteFromRelated(tag, id) {
            $(tag).parents('#' + id).remove();
        }

        function clearSearchBox(){
            $('#productRelatedRow').html('');
        }
        function NumberFormat(tag) {
            let price = $(tag).val();
            $(tag).val(number_format(price));
        }
        $('#saveClose').click(function () {
            $('#close').val('saveClose');
            $('form').submit();
        });

        $('#save').click(function () {
            $('#close').val('save');
            $('form').submit();
        });

    </script>
    {{--    //ckEditor--}}
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script>
        CKEDITOR.replace('description', {
            language: 'fa',
            filebrowserUploadUrl: "{{route('upload', ['_token' => csrf_token() ])}}",
            filebrowserUploadMethod: 'form'
        });
        //remove style
        CKEDITOR.on('instanceReady', function (ev) {
            ev.editor.on('paste', function (evt) {
                if (evt.data.type == 'html') {
                    evt.data.dataValue = evt.data.dataValue.replace(/ style=".*?"/g, '');
                }
            }, null, null, 9);
        });
    </script>
    <script src="{{ asset('admin/tinymce/js/tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript">
        tinymce.init({
            language: 'fa',
            selector: '#shortDescription'
        });
    </script>
@endsection

@section('content')
    <!-- Content Row -->
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4 p-4 bg-white">
            <div class="mb-4 text-center text-md-right d-flex justify-content-between">
                <h5 class="font-weight-bold">ویرایش محصول {{ $product->name }}</h5>
                <div>
                    <button id="save" class="btn btn-sm btn-success">به‌روزرسانی</button>
                    <button id="saveClose" class="btn btn-sm btn-success">به‌روزرسانی و بستن</button>
                    <a href="{{ $pre_url }}" class="btn btn-sm btn-dark">بازگشت</a>
                </div>
            </div>
            <hr>
            <div class="d-flex justify-content-center mb-3">
                <img class="img-thumbnail"
                     src="{{ imageExist(env('PRODUCT_IMAGES_THUMBNAIL_UPLOAD_PATH'),$product->primary_image) }}"
                     alt="{{ $product->name }}">
            </div>
            <hr>
            @include('admin.sections.errors')

            <form id="productForm" action="{{ route('admin.products.update', ['product' => $product->id]) }}"
                  method="POST">
                <input type="hidden" name="previous_rout" value="{{ url()->previous() }}">
                @csrf
                @method('put')
                <input type="hidden" name="close" id="close" value="">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="name">نام</label>
                        <input class="form-control" id="name" name="name" type="text" value="{{ $product->name }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="name">alias</label>
                        <input class="form-control" id="alias" name="alias" type="text" value="{{ $product->alias }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="name">کلمات مشابه</label>
                        <input class="form-control" id="similarWords" name="similarWords" type="text" value="{{ $product->similarWords }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="name">کد کالا</label>
                        <input class="form-control" id="product_code" name="product_code" type="text" value="{{ $product->product_code }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="category_id">دسته بندی</label>
                        <select id="categorySelect" name="category_id[]" class="form-control" data-live-search="true"
                                multiple>
                            @foreach ($categories as $category)
                                <option
                                    {{ in_array($product->id,$category->Products()->pluck('id')->toArray()) ? 'selected' : '' }} value="{{ $category->id }}">
                                    {{ isset($category->parent->name) ? $category->parent->name : $category->name }}
                                    {{ isset($category->parent->name) ? '/'.$category->name : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="category_id">انتخاب بر اساس عملکرد</label>
                        <select id="functionalTypesSelect" name="functional_type_id[]" class="form-control" data-live-search="true" multiple>
                            @foreach ($functionalTypes as $functionalType)
                                <option
                                    {{ in_array($product->id,$functionalType->products()->pluck('id')->toArray()) ? 'selected' : '' }} value="{{ $functionalType->id }}">
                                    {{ $functionalType->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="is_active">وضعیت</label>
                        <select class="form-control" id="is_active" name="is_active">
                            <option value="1" {{ $product->getRawOriginal('is_active')==1 ? 'selected' : '' }}>فعال
                            </option>
                            <option value="0" {{ $product->getRawOriginal('is_active')==0 ? 'selected' : '' }}>غیرفعال
                            </option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="name">موجودی انبار</label>
                        <input {{ $product_has_variations==1 ? 'readonly' : ''}} class="form-control" id="quantity" name="quantity" type="text"
                               value="{{ $product->quantity }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="is_active">برچسب</label>
                        <select class="form-control" id="label" name="label">
                            <option value="0" {{ $product->label==0 ? 'selected' : '' }}>بدون برچسب</option>
                            @foreach($labels as $label)
                                <option
                                    value="{{ $label->id }}" {{ $product->label==$label->id ? 'selected' : '' }}>{{ $label->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="is_active">برند</label>
                        <select class="form-control" id="brand" name="brand">
                            <option value="0" {{ $product->brand_id==0 ? 'selected' : '' }}>بدون برند</option>
                            @foreach($brands as $brand)
                                <option
                                    value="{{ $brand->id }}" {{ $product->brand_id==$brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="name">قیمت (تومان)</label>
                        <input {{ $product_has_variations==1 ? 'readonly' : ''}} onkeyup="NumberFormat(this)" class="form-control price" id="price" name="price" type="text"
                               value="{{ number_format($product->price) }}">
                    </div>

                    <div class="form-group col-md-12">
                        <label for="shortDescription">توضیحات مختصر</label>
                        <textarea class="form-control" id="shortDescription"
                                  name="shortDescription">{!! $product->shortDescription !!}</textarea>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="description">توضیحات</label>
                        <textarea class="form-control" id="description" name="description"
                                  rows="4">{!! $product->description !!}</textarea>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="description">ویدئو(لینک آپارات)</label>
                        <textarea class="form-control" id="video" name="video"
                                  rows="4">{{ $product->video }}</textarea>
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
                    <div class="form-group col-md-6">
                        <label for="name">قیمت با تخفیف( تومان )</label>
                        <input {{ $product_has_variations==1 ? 'readonly' : ''}} class="form-control" id="sale_price" name="sale_price" type="text"
                               value="{{ number_format($product->sale_price) }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label> تخفیف( % )</label>
                        <input {{ $product_has_variations==1 ? 'readonly' : ''}} id="percent_sale_price" type="number" name="percent_sale_price"
                               value="{{ $product->percent_sale_price }}" class="form-control">
                    </div>

                    <div class="form-group col-md-6">
                        <label> تاریخ شروع تخفیف </label>
                        <div class="input-group">
                            <div class="input-group-prepend order-2">
                                                    <span class="input-group-text" id="variationDateOnSaleFrom">
                                                        <i class="fas fa-clock"></i>
                                                    </span>
                            </div>
                            <input type="text" class="form-control" id="variationInputDateOnSaleFrom"
                                   name="date_on_sale_from"
                                   value="{{ $product->DateOnSaleFrom==null ? null : verta($product->DateOnSaleFrom)->format('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label> تاریخ پایان تخفیف </label>

                        <div class="input-group">
                            <div class="input-group-prepend order-2">
                                                    <span class="input-group-text" id="variationDateOnSaleTo">
                                                        <i class="fas fa-clock"></i>
                                                    </span>
                            </div>
                            <input type="text" class="form-control" id="variationInputDateOnSaleTo"
                                   name="date_on_sale_to"
                                   value="{{ $product->DateOnSaleTo==null ? null : verta($product->DateOnSaleTo)->format('Y-m-d') }}">
                        </div>

                    </div>
                    <div class="col-md-12">
                        <div class="alert alert-info d-flex justify-content-between">
                            <p class="m-0">آیا تمایل دارید این محصول در بخش شمارش معکوس نمایش داده شود ؟</p>
                            <input type="checkbox" name="showOnIndex"
                                   id="showOnIndex" {{ $product->showOnIndex==1 ? 'checked' : '' }}>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="alert alert-dark d-flex justify-content-between">
                            <p class="m-0">آیا تمایل دارید این محصول به صورت همواره تخفیف نمایش داده شود ؟</p>
                            <input type="checkbox" name="has_discount"
                                   id="has_discount" {{ $product->has_discount==1 ? 'checked' : '' }}>
                        </div>
                    </div>
                </div>
                <div id="relatedSelected" class="row mt-2">
                    <div class="col-md-12">
                        <hr>
                    </div>
                    {{-- Sale Section --}}
                    <div class="col-md-12">
                        <p> محصولات مرتبط : </p>
                    </div>
                    @foreach($relatedProducts as $item)
                        <div id="{{ $item->id }}" class="col-6 col-md-2 text-center">
                            <div>
                                <img class="img-thumbnail mb-3"
                                     src="{{ asset(env('PRODUCT_IMAGES_THUMBNAIL_UPLOAD_PATH').$item->primary_image) }}">
                                <span>{{ $item->name }}</span>
                                <input type="hidden" name="relatedProduct[]" value="{{ $item->id }}">
                                <button onclick="deleteFromRelated(this,{{ $item->id }})"
                                        class="btn btn-sm btn-outline-danger mr-2" type="button">حذف
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <hr>
                    </div>
                    {{-- Sale Section --}}
                    <div class="col-md-12">
                        <p> افزودن محصولات : </p>
                    </div>

                    <form id="productSearch"
                          class="d-none d-sm-inline-block form-inline ml-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group d-flex flex-wrap">
                            <button onclick="clearSearchBox()" class="btn btn-danger order-3" type="button">
                                پاکسازی
                            </button>
                            <div class="input-group-append">
                                <button onclick="searchProduct()" class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                            <input id="productSearchInput" type="text"
                                   class="form-control bg-light border-1 small order-2" placeholder="جستجو ..."
                                   aria-label="Search" aria-describedby="basic-addon2">
                        </div>
                    </form>
                </div>
                <div id="productRelatedRow" class="row mt-2">

                </div>

            </form>
        </div>
    </div>
@endsection
