@extends('admin.layouts.admin')


@section('title')
    محصولات  وابسته به رنگ
@endsection

@section('style')
    <style>
        .img-thumbnail {
            width: 80px;
            height: auto;
        }

        th {
            vertical-align: middle !important;
        }

        .percent {
            width: 50px !important;
        }

        input {
            width: 100%;
        }

        .mb-0 {
            margin-bottom: 0 !important;
        }

        input {
            text-align: center;
        }
    </style>
@endsection

@section('script')
    <script>
        $('.tab-title').click(function () {
            $('.tab-title').removeClass('tab-active');
            $(this).addClass('tab-active');
            let target = $(this).data('id');
            $('.tab-content').removeClass('d-flex');
            $('#' + target).addClass('d-flex');
        })
        $('#btn-submit').click(function () {
            $('#form').submit();
        })
        // Show File Name
        $('#image').change(function () {
            //get the file name
            var fileName = $(this).val();
            //replace the "Choose a file" label
            $(this).next('.custom-file-label').html(fileName);
        });
        // Show File Name
        $('#images').change(function () {
            //get the file name
            var fileName = $(this).val();
            //replace the "Choose a file" label
            $(this).next('.custom-file-label').html(fileName);
        });

        //remove
        function RemoveModal(id) {
            let modal = $('#remove_modal');
            modal.modal('show');
            $('#id').val(id);
        }

        function Remove() {
            let id = $('#id').val();
            let product_id="{{ $product->id }}";
            $.ajax({
                url: "{{ route('admin.product.variations.attribute.colors.remove') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    product_id: product_id,
                },
                dataType: "json",
                type: 'POST',
                beforeSend: function () {

                },
                success: function (msg) {
                    console.log(msg);
                    if (msg) {
                        if (msg[0] == 0) {
                            let message = msg[1];
                            swal({
                                title: 'متاسفیم',
                                text: message,
                                icon: 'error',
                                timer: 3000,
                            })
                        }
                        if (msg[0] == 1) {
                            let message = msg[1];
                            swal({
                                title: 'باتشکر',
                                text: message,
                                icon: 'success',
                                timer: 3000,
                            })
                            window.location.reload();
                        }
                    }
                },
            })
        }
        //remove
        function RemoveAttrValue(id) {
            let modal = $('#remove_modal_attr');
            modal.modal('show');
            $('#attr_value').val(id);
        }

        function RemoveAttr() {
            let attr_value = $('#attr_value').val();
            let product_id="{{ $product->id }}";
            $.ajax({
                url: "{{ route('admin.product.variations.attribute.attr_remove') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    attr_value: attr_value,
                    product_id: product_id,
                },
                dataType: "json",
                type: 'POST',
                beforeSend: function () {

                },
                success: function (msg) {
                    console.log(msg);
                    if (msg) {
                        if (msg[0] == 0) {
                            let message = msg[1];
                            swal({
                                title: 'متاسفیم',
                                text: message,
                                icon: 'error',
                                timer: 3000,
                            })
                        }
                        if (msg[0] == 1) {
                            let message = msg[1];
                            swal({
                                title: 'باتشکر',
                                text: message,
                                icon: 'success',
                                timer: 3000,
                            })
                            window.location.reload();
                        }
                    }
                },
            })
        }

        //price separator
        function price_separator(tag) {
            let price = $(tag).val();
            price = price.replaceAll(',', '');
            price = number_format(price);
            $(tag).val(price);
        }

        $('#attribute_id').change(function () {
            let option = '';
            $('#attribute_product_input').html('')
            $('#modal_error').html('');
            let attribute_id = $(this).val();
            if (attribute_id == '') {
                alert('موردی را انتخاب نکرده اید!')
            } else {
                let attribute_values =@json( $attribute_values );
                let values = [];
                $.each(attribute_values, function (i, attribute_value) {
                    if (attribute_value.attribute_id == attribute_id) {
                        values.push(attribute_value);
                    }
                })
                if (values.length > 0) {
                    $('#attribute_product_input').remove();
                    let select = $('<select>', {
                        id: 'attribute_product_input',
                        class: 'form-control form-control-sm',
                        name: 'attribute_value',
                    });
                    $('#attribute_input_value').append(select);
                    $.each(values, function (i, value) {
                        option = `<option value="${value.id}">${value.name}</option>`;
                        $('#attribute_product_input').append(option)
                    })
                } else {
                    $('#attribute_product_input').remove();
                    let input = $('<input>', {
                        id: 'attribute_product_input',
                        type: 'text',
                        class: 'form-control form-control-sm',
                    })
                    $('#attribute_input_value').append(input);
                }
            }
        })

        $('#update_btn').click(function () {
            $('.update_type').val('update');
            $('#update_form').submit();
        })

        $('#update_and_close_btn').click(function () {
            $('.update_type').val('update_and_close');
            $('#update_form').submit();
        })

        $('.selectpicker').selectpicker();

        //calculate Discount
        function calculateDiscount(tag,id){
            let mainPrice = $('#price_'+id).val();
            mainPrice=mainPrice.replaceAll(',','',mainPrice);
            let discount = $(tag).val();
            discount=discount.replaceAll(',','',mainPrice);
            if (parseInt(discount) > parseInt(mainPrice)) {
                alert('قیمت پس از تخفیف باید کمتر از قیمت اصلی باشد');
                $('#sale_price_'+id).val(number_format(mainPrice));
                $('#percent_sale_price_'+id).val(0);
            } else {
                let percentDiscount = calculatePercentDiscount(discount, mainPrice);
                percentDiscount=parseFloat(percentDiscount).toFixed(4);
                $('#percent_sale_price_'+id).val(percentDiscount);
            }
        }


        function calculatePercentDiscount(discount, mainPrice) {
            let percentDiscount = ((mainPrice - discount) / mainPrice) * 100;
            return percentDiscount;
        }

    </script>
@endsection

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $product->name }}</h1>
        <div>
            <button id="update_and_close_btn" type="button" class="btn btn-success btn-sm">به‌روزرسانی و بستن</button>
            <button id="update_btn" type="button" class="btn btn-success btn-sm">به‌روزرسانی</button>
            <a title="بازگشت" href="{{ $pre_url }}"
               class="d-none d-sm-inline-block btn btn-sm btn-dark shadow-sm">
                <i class="fas fa-arrow-left fa-sm "></i>
            </a>
        </div>
    </div>
    <!-- Content Row -->
    <div class="row card shadow mb-4">
        <!-- Card Body -->
        <div class="card-body">
            <div class="row card">
                <form id="update_form" action="{{ route('admin.product.variations.attribute.update') }}" method="post">
                    <input type="hidden" name="previous_rout" value="{{ url()->previous() }}">
                    @csrf
                    <input type="hidden" id="product_id" name="product_id" value="{{ $product->id }}">
                    @foreach($product_attr_variations as $key=>$product_attr_variation)
                        <div class="my-3">
                            <div class="col-12 d-flex justify-content-between align-items-center">
                                <h3 class="p-2">
                                    {{ $attrs[$key]['attr_id_name'] }}
                                    -
                                    {{ $attrs[$key]['attr_value_name'] }}
                                </h3>
                                <button type="button" onclick="RemoveAttrValue({{ $attrs[$key]['attr_value'] }})"
                                        title="حذف"
                                        class="btn btn-sm btn-danger">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped text-center">
                                        <thead>
                                        <tr class="bg-dark text-white">
                                            <th>#</th>
                                            <th>مقدار</th>
                                            <th class="percent">موجودی</th>
                                            <th>قیمت(تومان)</th>
                                            <th>قیمت بعد از تخفیف(تومان)</th>
                                            <th class="percent">تخفیف(%)</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($product_attr_variation as $item)
                                            <tr>
                                                <th>#</th>
                                                <th>{{ $item->Color->name }}</th>
                                                <th><input onkeyup="price_separator(this)"
                                                           name="quantity[{{ $item->id }}]"
                                                           value="{{ $item->quantity }}"></th>
                                                <th>
                                                    <input id="price_{{ $item->id }}" name="price_[{{ $item->id }}]"
                                                           value="{{ number_format($item->price) }}">
                                                </th>
                                                <th>
                                                    <input onkeyup="calculateDiscount(this,{{ $item->id }})" id="sale_price_{{ $item->id }}" name="sale_price_[{{ $item->id }}]"
                                                           value="{{ number_format($item->sale_price) }}">
                                                </th>
                                                <th>
                                                    <input id="percent_sale_price_{{ $item->id }}" name="percent_sale_price_[{{ $item->id }}]"
                                                           value="{{ $item->percent_sale_price==null ? 0 : $item->percent_sale_price }}">
                                                </th>

                                                <input  type="hidden" name="ids[]" value="{{ $item->id }}">
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <input type="hidden" name="update_type" class="update_type">
                        </div>
                    @endforeach
                </form>
            </div>
            <div class="row">
                <div class="col-12">
                    <hr>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <p class="p-3 bg-gradient-light text-dark text-center">رنگ بندی</p>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <form id="form"
                          action="{{ route('admin.product.variations.attribute.save_colors',['product'=>$product->id]) }}"
                          method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row my-3">
                            <div class="form-group col-md-3 col-12">
                                <label for="title_fa">انتخاب رنگ :</label>
                                <select name="attr_value" class="form-control form-control-sm selectpicker" data-live-search="true">
                                    <option value="" selected>انتخاب کنید</option>
                                    @foreach($colors as $color)
                                        <option value="{{ $color->id }}">{{ $color->name }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('attr_value'))
                                    <div class="input-error-validation">{{ $errors->first('attr_value') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-7 col-12">
                                <label for="primary_image">تصویر اصلی<span class="mr-3"
                                                                           style="font-size: 9pt;color: #0099ff">(ابعاد پیشنهادی :300*299 پیکسل)</span>
                                </label>
                                <div class="custom-file cursor-pointer height-32">
                                    <input type="file" name="image" class="custom-file-input" id="image"
                                           value="{{ old('image') }}">
                                    <label class="custom-file-label form-control form-control-sm mb-0"
                                           for="image"></label>
                                </div>
                                @if($errors->has('image'))
                                    <div class="input-error-validation">{{ $errors->first('image') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-2 d-flex align-items-end">
                                <button class="btn btn-sm btn-success">add</button>
                                <?php
                                $color_id = \App\Models\Attribute::where('name', 'رنگ')->first()->id;
                                ?>
                                <a href="{{ route('admin.attributes.values.index',['attribute' => $color_id]) }}"
                                   class="btn btn-sm btn-primary mr-3">افزودن رنگ</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped text-center">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>رنگ</th>
                                <th>تصویر اصلی</th>
                                <th>عملیات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($product_variation_colors as $product_variation_color)
                                <tr>
                                    <th>#</th>
                                    <th>{{ $product_variation_color->Color->name }}</th>
                                    <th>
                                        <img class="img-thumbnail"
                                             src="{{ imageExist(env('PRODUCT_VARIATION_COLOR_UPLOAD_PATH'),$product_variation_color->image) }}">
                                    </th>
                                    <th>
                                        <button onclick="RemoveModal({{ $product_variation_color->id }})"
                                                class="btn btn-sm btn-danger">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </th>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <p class="p-3 bg-gradient-light text-dark text-center">ویژگی های وابسته</p>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <form id="form"
                          action="{{ route('admin.product.variations.attribute.add_product',['product'=>$product->id]) }}"
                          method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row my-3">
                            <div class="form-group col-md-4">
                                <label>ویژگی:</label>
                                <select name="attribute_id" id="attribute_id" class="form-control form-control-sm">
                                    <option value="" selected>انتخاب کنید</option>
                                    @foreach($attributes as $attribute)
                                        <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('attribute_id'))
                                    <div class="input-error-validation">{{ $errors->first('attribute_id') }}</div>
                                @endif
                            </div>
                            <div id="attribute_input_value" class="form-group col-md-4">
                                <label>مقدار :</label>
                                <select name="attribute_value" class="form-control form-control-sm"
                                        id="attribute_product_input">
                                    <option value="">
                                        انتخاب کنید
                                    </option>
                                </select>
                                @if($errors->has('attribute_value'))
                                    <div class="input-error-validation">{{ $errors->first('attribute_value') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-4 d-flex align-items-end">
                                <button class="btn btn-sm btn-success">add</button>
                                <a href="{{ route('admin.attributes.index') }}" class="btn btn-sm btn-primary mr-3">افزودن
                                    وابستگی</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <hr>
                </div>
            </div>
        </div>
    </div>
    @include('admin.products.variations.attributes.modal')
@endsection
