@extends('admin.layouts.admin')

@section('title')
    محصولات چندتایی
@endsection
@section('style')
    <style>
        .img-thumbnail {
            width: 150px;
            height: auto;
        }

        th {
            vertical-align: middle !important;
        }

        #overlay {
            display: none;
        }

        .sale_img {
            position: absolute;
            width: 40px;
            height: auto;
            left: 0;
            top: 0;
        }

        #success_alert {
            width: 300px;
            height: 100px;
            background-color: green;
            color: white;
            position: fixed;
            bottom: 50px;
            left: 50px;
            text-align: center;
            border-radius: 5%;
            display: none;
        }

        .text-inherit {
            color: inherit !important;
        }

        input {
            text-align: center !important;
        }
    </style>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            //get localStorage
            let multi_products = JSON.parse(localStorage.getItem("multi_products"));
            let multi_products_brand=localStorage.getItem('multi_products_brand');
            let multi_products_category=localStorage.getItem('multi_products_category');
            let multi_products_search_input=localStorage.getItem('multi_products_search_input');
            if (multi_products !=null){
                $('#SearchInput').val(multi_products_search_input);
                $('#category option[value="'+multi_products_category+'"]').prop('selected',true);
                $('#brand option[value="'+multi_products_brand+'"]').prop('selected',true);
                filter();
            }else {
                $('#category option:first').prop('selected',true);
                $('#brand option:first').prop('selected',true);
            }
        });
        function update_multi_product_quantity(variation_id, tag) {
            let quantity = $(tag).val();
            console.log(variation_id,quantity);
            $.ajax({
                url: "{{ route('admin.products.multi.update.quantity') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    variation_id: variation_id,
                    quantity: quantity,
                },
                dataType: "json",
                type: 'POST',
                beforeSend: function () {

                },
                success: function (msg) {
                    if (msg) {
                        if (msg[0] == 1) {
                            $('#success_alert').show(500);
                            setTimeout(function () {
                                $('#success_alert').hide(500);
                            }, 3000);
                        }
                    }

                },
                fail: function (error) {
                    console.log(error);
                }
            })
        }

        function update_multi_price(variation_id, tag) {
            let price = $(tag).val();
            $.ajax({
                url: "{{ route('admin.products.multi.update.update_multi_price') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    variation_id: variation_id,
                    price: price,
                },
                dataType: "json",
                type: 'POST',
                beforeSend: function () {

                },
                success: function (msg) {
                    if (msg) {
                        if (msg[0] == 1) {
                            $('#success_alert').show(500);
                            setTimeout(function () {
                                $('#success_alert').hide(500);
                            }, 3000);
                        }
                    }

                },
                fail: function (error) {
                    console.log(error);
                }
            })
        }

        function price_format(tag) {
            let price = $(tag).val();
            $(tag).val(number_format(price));
        }

        function filter() {
            let brand = $('#brand').val();
            let category = $('#category').val();
            let name = $('#SearchInput').val();
            let multi_products_ids =@json($multi_products_ids);
            $.ajax({
                url: "{{ route('admin.products.multi.search') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    name: name,
                    brand: brand,
                    category: category,
                    multi_products_ids: multi_products_ids,
                },
                dataType: "json",
                type: "POST",
                beforeSend: function () {
                    $("#overlay").fadeIn();
                },
                success: function (msg) {
                    if (msg[0] == 1) {
                        //clear localStorage
                        localStorage.removeItem('multi_products');
                        localStorage.removeItem('multi_products_brand');
                        localStorage.removeItem('multi_products_category');
                        localStorage.removeItem('multi_products_search_input');
                        //save in localStorage
                        localStorage.setItem("multi_products", JSON.stringify(msg[1]));
                        localStorage.setItem('multi_products_brand',brand);
                        localStorage.setItem('multi_products_category',category);
                        localStorage.setItem('multi_products_search_input',name);
                        //
                        $('#insertRow').html(msg[1]);
                        $('.paginate').hide();
                        $("#overlay").fadeOut();
                    } else {
                        console.error(msg);
                    }
                },
                fail: function (error) {
                    console.log(error);
                }
            })
        }
        $('#clearFactorBtn').click(function () {
            //clear localStorage
            localStorage.removeItem('multi_products');
            localStorage.removeItem('multi_products_brand');
            localStorage.removeItem('multi_products_category');
            localStorage.removeItem('multi_products_search_input');
            window.location.reload();
        })

        function custom_pagination(tag){
            let per_page=$(tag).val();
            let url='{{ route('admin.products.multi.update.pagination',['show_per_page'=>':per_page']) }}';
            url=url.replace(':per_page',per_page);
            window.location.href=url;
        }

    </script>
@endsection

@section('content')
    <!-- Content Row -->
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4 p-4 bg-white">
            <div class="d-flex flex-column text-center flex-md-row justify-content-md-between mb-4">
                <h5 class="font-weight-bold mb-3 mb-md-0">لیست محصولات چندتایی ({{ $variations->total() }})</h5>
            </div>
            <div class="row d-lg-flex justify-content-between align-items-center">
                <div class="col-md-8 col-12 d-flex align-items-center">
                    <div class="form-group">
                        <label> جست و جو : </label>
                        <div class="input-group input-group-md d-flex flex-row-reverse border-radius">
                            <input type="text" class="form-control form-control-sm"
                                   aria-label="Sizing example input"
                                   aria-describedby="inputGroup-sizing-lg" placeholder="جست و جو..."
                                   id="SearchInput" autocomplete="off">
                            <div class="input-group-prepend border-radius">
                    <span class="input-group-text" id="basic-addon2"><i class="fa fa-search"
                                                                        aria-hidden="true"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mr-2">
                        <label> دسته بندی : </label>
                        <select id="category" class="form-control form-control-sm">
                            <option value="0">نمایش همه</option>
                            @foreach($categories as $cat)
                                <option style="font-size: 13pt;font-weight: bold" value="{{ $cat->id }}">{{ $cat->name }}</option>
                                <?php
                                $children = \App\Models\Category::where('parent_id', $cat->id)->get();
                                ?>
                                @foreach($children as $child)
                                    <option value="{{ $child->id }}">{{ $child->name }}</option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mr-2">
                        <label> برند ها : </label>
                        <div class="d-flex">
                            <select id="brand" class="form-control form-control-sm">
                                <option value="0">نمایش همه</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                            <button type="button" onclick="filter()" class="btn btn-sm btn-danger mr-3">فیلتر</button>
                            <button type="button" id="clearFactorBtn" class="btn btn-sm btn-primary mr-3">پاکسازی
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-12 d-flex justify-content-end align-items-center">
                    <form id="paginate_form" method="get">
                        <select onchange="custom_pagination(this)" name="show_per_page"
                                class="form-control form-control-sm">
                            <option value="default" {{$show_per_page==1?'selected':''}}>پیش فرض</option>
                            <option value="50" {{$show_per_page==50?'selected':''}}> نمایش 50 تایی</option>
                            <option value="100" {{$show_per_page==100?'selected':''}}> نمایش 100 تایی</option>
                            <option value="200" {{$show_per_page==200?'selected':''}}> نمایش 200 تایی</option>
                            <option value="all" {{$show_per_page==0?'selected':''}}> نمایش همه</option>
                        </select>
                    </form>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center">

                    <thead>
                    <tr>
                        <th>#</th>
                        <th>نام</th>
                        <th>رنگ</th>
                        <th>متغیر</th>
                        <th>نام دسته بندی</th>
                        <th>برند</th>
                        <th>تصویر اصلی</th>
                        <th>تعداد</th>
                        <th>قیمت (تومان)</th>
                    </tr>
                    </thead>
                    <tbody id="insertRow">
                    @foreach ($variations as $key => $variation)
                        <tr>
                            <th>
                                {{ $variations->firstItem() + $key }}
                            </th>
                            <th class="position-relative">
                                <a href="{{ route('admin.products.show', ['product' => $variation->Product->id]) }}">
                                    {{ $variation->Product->name }}
                                </a>
                            </th>
                            <th class="position-relative">
                                {{ $variation->Color->name }}
                            </th>

                            <th class="position-relative">
                                {{ $variation->AttributeValue->name }}
                            </th>
                            <th>
                                {{ $variation->category_name($variation->product_id) }}
                            </th>
                            <th>
                                {{ isset($variation->Product->brand_id->name) ?? '-' }}
                            </th>
                            <th>
                                <img class="img-thumbnail"
                                     src="{{ imageExist(env('PRODUCT_IMAGES_UPLOAD_PATH'),$variation->Product->primary_image) }}">
                            </th>
                            <th>
                                <input onchange="update_multi_product_quantity({{ $variation->id }},this)"
                                       class="form-control form-control-sm" value="{{ $variation->quantity }}">
                            </th>
                            <th>
                                <input onchange="update_multi_price({{ $variation->id }},this)"
                                       class="form-control form-control-sm"
                                       value="{{ number_format($variation->price) }}">
                            </th>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row paginate">
                <div class="col-12">
                    <div class="row justify-content-center">
                        {{ $variations->render() }}
                    </div>
                </div>
            </div>
            <div id="overlay">
                <div class="spinner-border text-danger" style="width: 3rem; height: 3rem;"></div>
                <br/>
                Loading...
            </div>
        </div>
    </div>
    <div id="success_alert">
        <div class="d-flex justify-content-center align-items-center h-100">تغییرات مورد نظر با موفقیت اعمال شد</div>
    </div>
@endsection
