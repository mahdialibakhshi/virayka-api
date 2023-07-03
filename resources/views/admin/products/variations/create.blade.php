@extends('admin.layouts.admin')

@section('title')
    create products attribute
@endsection

@section('script')
    <script>
        // Show File Name
        $('#primary_image').change(function () {
            //get the file name
            var fileName = $(this).val();
            //replace the "Choose a file" label
            $(this).next('.custom-file-label').html(fileName);
        });
        $('#images').change(function () {
            //get the file name
            var fileName = $(this).val();
            //replace the "Choose a file" label
            $(this).next('.custom-file-label').html(fileName);
        });
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
                if (values.length>0){
                    $('#attribute_product_input').remove();
                    let select=$('<select>',{
                        id:'attribute_product_input',
                        name:'attribute_input_value',
                        class:'form-control',
                    });
                    $('#attribute_input_value').append(select);
                    $.each(values, function (i, value) {
                        option=`<option value="${value.id}">${value.name}</option>`;
                        $('#attribute_product_input').append(option)
                    })
                }else {
                    $('#attribute_product_input').remove();
                    let input=$('<input>',{
                        id:'attribute_product_input',
                        name:'attribute_input_value',
                        type:'text',
                        class:'form-control',
                    })
                    $('#attribute_input_value').append(input);
                }
            }
        })
        //calculate Discount
        $('#salePrice').keyup(function () {
            let mainPrice = $('#price').val();
            let discount = $(this).val();
            if (parseInt(discount) > parseInt(mainPrice)) {
                alert('قیمت پس از تخفیف باید کمتر از قیمت اصلی باشد');
                $('#salePrice').val(mainPrice);
                $('#percentSalePrice').val(0);

            } else {
                let percentDiscount = calculatePercentDiscount(discount, mainPrice);
                console.log('ok');
                console.log(percentDiscount);
                $('#percentSalePrice').val(percentDiscount);
            }
        })
        function calculatePercentDiscount(discount, mainPrice) {
            let percentDiscount = ((mainPrice - discount) / mainPrice) * 100;
            return percentDiscount;
        }
        $('#percentSalePrice').keyup(function () {
            let percentDiscount = $(this).val();
            let mainPrice = $('#price').val();
            if (parseInt(mainPrice) < 0) {
                alert('ابتدا برای کالا قیمت اصلی را وارد کنید');
            } else {
                if (percentDiscount > 100) {
                    $('#percentSalePrice').val(100);
                    percentDiscount = 100;
                }
                if (percentDiscount < 0) {
                    $('#percentSalePrice').val(0);
                    percentDiscount = 0;
                }
                let Discount = calculateDiscount(percentDiscount, mainPrice);
                $('#salePrice').val(Discount);
            }
        })
        $('#price').keyup(function () {
            let price=$(this).val();
            $('#salePrice').val(price);
            $('#percentSalePrice').val(0);
            $('#variationInputDateOnSaleFrom').val('');
            $('#variationInputDateOnSaleTo').val('');
        })
        function calculateDiscount(percentDiscount, mainPrice) {
            let Discount = ((100 - percentDiscount) * mainPrice) / 100;
            return Discount;
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
    </script>
    {{--    //ckEditor--}}
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script>
        CKEDITOR.replace('description', {
            language: 'fa',
            filebrowserUploadUrl: "{{route('upload', ['_token' => csrf_token() ])}}",
            filebrowserUploadMethod: 'form'
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
            <div class="mb-4 text-center text-md-right">
                <h5 class="font-weight-bold mb-3 mb-md-0">{{ $product->name }} - ایجاد محصول </h5>
            </div>
            <hr>

            @include('admin.sections.errors')

            <form action="{{ route('admin.product.variations.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input name="product_id" type="hidden" value="{{ $product->id }}">
                <div class="form-row">
                    <div class="form-group col-12 col-md-3">
                        <label for="name">ویژگی</label>
                        <select id="attribute_id" name="attribute_id" class="form-control">
                            <option value="">انتخاب کنید</option>
                            @foreach($attributes as $attribute)
                                <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="attribute_input_value" class="form-group col-12 col-md-3">
                        <label>مقدار :</label>
                        <select class="form-control" id="attribute_product_input" name="attribute_input_value">
                            <option value="">
                                انتخاب کنید
                            </option>
                        </select>
                    </div>
                    <div class="form-group col-12 col-md-3">
                        <label for="name">قیمت اصلی ( تومان )</label>
                        <input autocomplete="off" class="form-control" id="price" name="price" type="text" value="{{ old('price') }}">
                    </div>
                    <div class="form-group col-12 col-md-3">
                        <label for="name">موجودی انبار</label>
                        <input class="form-control" id="quantity" name="quantity" type="text"
                               value="{{ old('quantity') }}">
                    </div>

                    {{-- Product Image Section --}}
                    <div class="col-md-12">
                        <hr>
                        <p>تصاویر محصول : </p>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="primary_image"> انتخاب تصویر اصلی </label>
                        <div class="custom-file">
                            <input type="file" name="primary_image" class="custom-file-input" id="primary_image">
                            <label class="custom-file-label" for="primary_image"> انتخاب فایل </label>
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
                                   value="{{ $product->salePrice }}">
                        </div>
                        <div class="form-group col-md-3">
                            <label> تخفیف برحسب درصد ( % )</label>
                            <input id="percentSalePrice" type="number" name="percentSalePrice"
                                   value="{{ $product->percentSalePrice }}" class="form-control">
                        </div>
                        <div class="form-group col-md-3">
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
                        <div class="form-group col-md-3">
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
                                       id="has_discount">
                            </div>
                        </div>
                    </div>
                </div>

                <button class="btn btn-outline-primary mt-5" type="submit">ثبت</button>
                <a href="{{ route('admin.product.variations.index',['product'=>$product->id]) }}" class="btn btn-dark mt-5 mr-3">بازگشت</a>
            </form>
        </div>

    </div>

@endsection
