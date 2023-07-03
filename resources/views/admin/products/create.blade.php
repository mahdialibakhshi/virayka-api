@extends('admin.layouts.admin')

@section('title')
    create products
@endsection

@section('script')
    <script>
        $('#brandSelect').selectpicker({
            'title': 'انتخاب برند'
        });
        $('#tagSelect').selectpicker({
            'title': 'انتخاب تگ'
        });

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

        $('#categorySelect').selectpicker({
            'title': 'انتخاب دسته بندی'
        });
        $('#functionalTypesSelect').selectpicker({
            'title': 'انتخاب براساس عملکرد'
        });



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
        //remove style in text copied to editor
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
                <h5 class="font-weight-bold">ایجاد محصول</h5>
                <div>
                    <button id="save" class="btn btn-sm btn-success">save</button>
                    <button id="saveClose" class="btn btn-sm btn-success">save and close</button>
                    <a href="{{ url()->previous() }}" class="btn btn-sm btn-dark">بازگشت</a>
                </div>
            </div>
            <hr>

            @include('admin.sections.errors')

            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="name">نام *</label>
                        <input class="form-control" id="name" name="name" type="text" value="{{ old('name') }}">
                    </div>

                    <div class="form-group col-md-3">
                        <label for="name">alias</label>
                        <input class="form-control" id="alias" name="alias" type="text" value="{{ old('alias') }}">
                    </div>

                    <div class="form-group col-md-3">
                        <label for="name">کلمات مشابه</label>
                        <input class="form-control" id="similarWords" name="similarWords" type="text" value="{{ old('similarWords') }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="name">کد کالا</label>
                        <input class="form-control" id="product_code" name="product_code" type="text" value="{{ old('product_code') }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="category_id">دسته بندی</label>
                        <select id="categorySelect" name="category_id[]" class="form-control" data-live-search="true" multiple>
                            @foreach ($categories as $category)
                                <option
                                    {{ old('category_id')==$category->id ? 'selected' : ' ' }} value="{{ $category->id }}">
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
                                    {{ old('functional_type_id')==$functionalType->id ? 'selected' : ' ' }} value="{{ $functionalType->id }}">
                                    {{ $functionalType->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="is_active">وضعیت نمایش</label>
                        <select class="form-control" id="is_active" name="is_active">
                            <option value="1" selected>فعال</option>
                            <option value="0">غیرفعال</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="name">موجودی انبار</label>
                        <input class="form-control" id="quantity" name="quantity" type="text"
                               value="{{ old('quantity') }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="is_active">برچسب</label>
                        <select class="form-control" id="label" name="label">
                            <option {{ old('label')==0 ? 'selected' : ' ' }} selected value="0">بدون برچسب</option>
                            @foreach($labels as $label)
                                <option
                                    {{ old('label')==$label->id ? 'selected' : ' ' }} value="{{ $label->id }}">{{ $label->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="brand">انتخاب برند</label>
                        <select class="form-control" id="brand" name="brand">
                            <option {{ old('label')==0 ? 'selected' : ' ' }} selected value="0">بدون برند</option>
                            @foreach($brands as $brand)
                                <option {{ old('brand')==$brand->id ? 'selected' : ' ' }} value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="name">قیمت (تومان)</label>
                        <input onkeyup="NumberFormat(this)" class="form-control" id="price" name="price" type="text"
                               value="{{ old('price') }}">
                    </div>
                    <div class="form-group col-md-12">
                        <label for="shortDescription">توضیحات مختصر</label>
                        <textarea class="form-control" id="shortDescription"
                                  name="shortDescription">{{ old('shortDescription') }}</textarea>
                    </div>


                    <div class="form-group col-md-12">
                        <label for="description">توضیحات</label>
                        <textarea class="form-control" id="description"
                                  name="description">{{ old('description') }}</textarea>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="description">ویدئو(لینک آپارات)</label>
                        <textarea class="form-control" id="video" name="video"
                                  rows="4"></textarea>
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

                    <div class="form-group col-md-3">
                        <label for="images"> انتخاب تصاویر </label>
                        <div class="custom-file">
                            <input type="file" name="images[]" multiple class="custom-file-input" id="images">
                            <label class="custom-file-label" for="images"> انتخاب فایل ها </label>
                        </div>
                    </div>

                </div>
                <input type="hidden" name="close" id="close" value="">
            </form>
        </div>

    </div>

@endsection
