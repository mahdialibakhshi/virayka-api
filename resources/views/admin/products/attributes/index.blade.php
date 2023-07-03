@extends('admin.layouts.admin')

@section('title')
    product attribute index
@endsection

@section('style')
    <style>
        .input-error-validation {
            color: red;
            font-size: 9pt;
        }
    </style>
@endsection

@section('script')
    <script>
        $('#attribute_product_input').selectpicker({
            'title': 'انتخاب مقدار'
        });

        $('.btn-outline-primary').click(function () {
            $('#modal_error').html('');
            $('#short_text').val('');
            $('#priority').val(1);
            $('#title').text('مشخصه‌ی فنی / افزودن');
            $('#add_update_product_attributes_button').text('افزودن');
        })
        $('#attribute_id').change(function () {
            let option = '';
            $('#attribute_product_input').html('')
            $('#modal_error').html('');
            let attribute_id = $(this).val();
            if (attribute_id == '') {
                alert('موردی را انتخاب نکرده اید!')
            } else {
                $('#attribute_input_value').html('');
                let attribute_values =@json( $attribute_values );
                let values = [];
                $.each(attribute_values, function (i, attribute_value) {
                    if (attribute_value.attribute_id == attribute_id) {
                        values.push(attribute_value);
                    }
                })
                if (values.length > 0) {
                    let select = '<label class="mt-2">مقدار :</label><select class="form-control' +
                        ' form-control-sm"' +
                        ' id="attribute_product_input" ' +
                        'name="attribute_product_input[]"' +
                        'data-live-search="true" ' +
                        'multiple>';
                    $('#attribute_input_value').append(select);
                    $.each(values, function (i, value) {
                        option = `<option value="${value.id}">${value.name}</option>`;
                        $('#attribute_product_input').append(option)
                    })
                    $('#attribute_product_input').selectpicker({
                        'title': 'انتخاب مقدار'
                    });
                } else {
                    $('#attribute_product_input').remove();
                    let textarea='<label class="mt-2">مقدار :</label>' +
                        '<textarea rows="10" id="attribute_product_input" ' +
                        ' class="form-control form-control-sm">' +
                        '</textarea>';
                    $('#attribute_input_value').append(textarea);
                }
            }
        })

        $('#add_update_product_attributes_button').click(function () {
            $('#modal_error').html('');
            let product_id = {{ $product->id }};
            let attribute_product_val = $('#attribute_product_input').val();
            let attribute_id = $('#attribute_id').val();
            let short_text = $('#short_text').val();
            let priority = $('#priority').val();
            if (attribute_id == '') {
                alert('موردی را انتخاب نکرده اید!')
            } else {
                $.ajax({
                    url: "{{ route('admin.product.attributes.addOrUpdate') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        product_id: product_id,
                        attribute_id: attribute_id,
                        attribute_product_val: attribute_product_val,
                        priority: priority,
                        short_text: short_text,
                    },
                    type: "POST",
                    dataType: "json",
                    success: function (msg) {
                        $('#modal_error').html('');
                        if (msg) {
                            if (msg[0] == 'success') {
                                swal({
                                    icon: 'success',
                                    title: 'با تشکر',
                                    text: msg[1],
                                    timer: 1500,
                                })
                            }
                            setTimeout(function () {
                                location.reload();
                            }, 1500)
                        }
                    },
                    error: function (response) {
                        $.each(response.responseJSON.errors, function (i, v) {
                            let p = `<p class="input-error-validation">${v[0]}</p>`;
                            $('#modal_error').append(p);
                        })
                    }
                })
            }
        })

        function update_attribute_value(attribute_id, attribute_value_id, integer,short_text,priority) {
            if (integer == 1) {
                $('#attribute_input_value').html('')
                let select = '<label class="mt-2">مقدار :</label><select class="form-control' +
                    ' form-control-sm"' +
                    ' id="attribute_product_input" ' +
                    'name="attribute_product_input[]"' +
                    'data-live-search="true" ' +
                    'multiple>';
                $('#attribute_input_value').append(select);
                let attribute_values =@json( $attribute_values );
                let values = [];
                let option = '';
                $.each(attribute_values, function (i, attribute_value) {
                    if (attribute_value.attribute_id == attribute_id) {
                        values.push(attribute_value);
                    }
                })
                $.each(values, function (i, value) {
                    let selected = '';
                    console.log([value.id, attribute_value_id]);
                    if (value.id == attribute_value_id) {
                        selected = 'selected';
                    }
                    option = `<option value="${value.id}" ${selected}>${value.name}</option>`;
                    $('#attribute_product_input').append(option)
                })
                $('#attribute_product_input').find('option[value="' + attribute_value_id + '"]').attr('selected', 'selected');
                $('#attribute_product_input').selectpicker({
                    'title': 'انتخاب مقدار'
                });
            } else {
                $('#attribute_input_value').html('')
                let label = '<label class="mt-2">مقدار :</label>';
                let textarea = $('<textarea>', {
                    rows: 10,
                    id: 'attribute_product_input',
                    type: 'text',
                    class: 'form-control form-control-sm',
                    text: attribute_value_id,
                })
                $('#attribute_input_value').append(label);
                $('#attribute_input_value').append(textarea);
            }
            $('#short_text').val(short_text);
            $('#priority').val(priority);
            $('#add_product_attribute').modal('show');
            $('#title').text('مشخصه‌ی فنی / ویرایش');
            $('#add_update_product_attributes_button').text('ویرایش');
            $('#attribute_id').find('option[value="' + attribute_id + '"]').attr('selected', 'selected');
        }

        $('#attribute_ids').selectpicker({
            'title': 'انتخاب ویژگی'
        });

        function change_active(id, is_active, tag) {
            $.ajax({
                url: "{{ route('admin.product.attributes.change_active') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    is_active: is_active,
                    id: id,
                },
                type: "POST",
                dataType: "json",
                success: function (msg) {
                    if (msg) {
                        if (msg[0] == 0) {
                            swal({
                                icon: 'error',
                                title: 'ERROR',
                                text: msg[1],
                                buttons: 'ok',
                            })
                        }
                        if (msg[0] == 1) {
                            $(tag).parent().html(msg[1]);
                        }
                    }
                },
                error: function (response) {
                    $.each(response.responseJSON.errors, function (i, v) {
                        let p = `<p class="input-error-validation">${v[0]}</p>`;
                        $('#modal_error').append(p);
                    })
                }
            })
        }

        function change_original(id, is_original, tag) {
            $.ajax({
                url: "{{ route('admin.product.attributes.change_original') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    is_original: is_original,
                    id: id,
                },
                type: "POST",
                dataType: "json",
                success: function (msg) {
                    if (msg) {
                        if (msg[0] == 0) {
                            swal({
                                icon: 'error',
                                title: 'ERROR',
                                text: msg[1],
                                buttons: 'ok',
                            })
                        }
                        if (msg[0] == 1) {
                            $(tag).parent().html(msg[1]);
                        }
                    }
                },
                error: function (response) {
                    $.each(response.responseJSON.errors, function (i, v) {
                        let p = `<p class="input-error-validation">${v[0]}</p>`;
                        $('#modal_error').append(p);
                    })
                }
            })
        }
    </script>
@endsection

@section('content')

    <!-- Content Row -->
    <div class="row">
        <div class="col-xl-12 col-md-12 mb-4 p-4 bg-white">
            <div class="d-flex flex-column text-center flex-md-row justify-content-md-between mb-4">
                <h5 class="font-weight-bold mb-3 mb-md-0">لیست مشخصات فنی ({{ $product->name }})</h5>
                <div>
                    <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal"
                            data-target="#add_product_attribute">
                        <i class="fa fa-plus"></i>
                        افزودن
                    </button>
                    <a class="btn btn-sm btn-outline-dark" href="{{ $pre_url }}">
                        بازگشت
                        <i class="fa fa-arrow-left mr-1"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center">
                    <thead>
                    <tr>
                        <th>نام</th>
                        <th>مقدار</th>
                        <th>اختصار</th>
                        <th>اولویت نمایش</th>
                        <th>نمایش در لیست محصولات</th>
                        <th>نمایش در جزئیات محصول</th>
                        <th>عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($product_attributes as $key => $attribute)
                        <tr>
                            <th>
                                {{ $attribute->attribute->name }}
                            </th>
                            <th>
                                @php
                                    $attribute_values=$attribute->attributeValues($attribute->value,$attribute->attribute_id);
                                @endphp
                                @if($attribute_values==null)
                                    {{ $attribute->value }}
                                @else
                                    {{ $attribute_values->name }}
                                @endif
                            </th>
                            <th>
                                {{ $attribute->short_text }}
                            </th>
                            <th>
                                {{ $attribute->priority }}
                            </th>
                            <th>
                                <button title="نمایش زیر تصویر محصول"
                                        onclick="change_original({{ $attribute->id }},{{ $attribute->is_original }},this)"
                                        class="btn btn-sm {{ $attribute->is_original==1?'btn-success':'btn-danger'}}">{{ $attribute->is_original==1?'فعال':'غیرفعال'}}</button>
                            </th>
                            <th>
                                <button title="نمایش به عنوان ویژگی اصلی در جزئیات محصول"
                                        onclick="change_active({{ $attribute->id }},{{ $attribute->is_active }},this)"
                                        class="btn btn-sm {{ $attribute->is_active==1?'btn-success':'btn-danger'}}">{{ $attribute->is_active==1?'فعال':'غیرفعال'}}</button>
                            </th>
                            <th class="d-flex justify-content-center">
                                <button onclick="update_attribute_value({{ $attribute->attribute_id }},
                                '{{ $attribute->attributeValues($attribute->value,$attribute->attribute_id)==null ? $attribute->value : $attribute->attributeValues($attribute->value,$attribute->attribute_id)->id }}',
                                '{{ $attribute->attributeValues($attribute->value,$attribute->attribute_id)==null ? 0 : 1 }}','{{ $attribute->short_text }}','{{ $attribute->priority }}')"
                                        class="btn btn-primary btn-sm">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <a href="{{ route('admin.product.attributes.remove',['attribute'=>$attribute->id]) }}"
                                   class="btn btn-danger btn-sm mr-2">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </th>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @include('admin.products.attributes.modal')
@endsection
