@extends('admin.layouts.admin')

@section('title')
    index paymentMethods
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('admin/css/home/deliverytime.css') }}">
    <style>
        #error {
            font-size: 9pt;
            color: red;
        }

        .shadow {
            box-shadow: 0 16px 24px 2px rgba(0, 0, 0, .14), 0 6px 30px 5px rgba(0, 0, 0, .12), 0 8px 10px -5px rgba(0, 0, 0, .2) !important;
            padding: 26px;
        }
        .input-error-validation{
            font-size: 9pt;
            color: red;
            text-align: right;
            padding: 10px;
        }
    </style>
@endsection

@section('script')
    <script>
        function appendInput() {
            var html = `<li class="my-1">
                        <input type="text" class="inputbox wide input-time" name="sent_times[]" value="">
                        <button onclick="removeInput(this)" type="button" class="btn btn-danger remove-time">-</button>
                    </li>`;
            $('.times-rows').append(html);
        }

        function removeInput(tag) {
            $(tag).closest('li').remove();
        }

        function updateDescription(methodId) {
            $('#methodId').val(methodId);
            $.ajax({
                url: "{{ route('admin.delivery_method.info') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    methodId: methodId,
                },
                type: "POST",
                dataType: "json",
                success: function (method) {
                    $('#title').text(method.name);
                    $('#description').val(method.description);
                }
            })
        }

        $('#updateDewscriptionButton').click(function () {
            $('#error').text('');
            let methodId = $('#methodId').val();
            let description = $('#description').val();
            $.ajax({
                url: "{{ route('admin.delivery_method.update') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    methodId: methodId,
                    description: description,
                },
                type: "POST",
                dataType: "json",
                success: function (msg) {
                    if (msg) {
                        if (msg[0] == 'error') {
                            let error = msg[1];
                            $('#error').text(error);
                        } else {
                            swal({
                                title: 'با تشکر',
                                text: 'توضیحات با موفقیت ویرایش شد',
                                icon: "success",
                                timer: 3000,
                            })
                            $('#updateModal').modal('hide');
                        }
                    }
                }
            })
        })
    </script>
@endsection

@section('content')

    <!-- Content Row -->
    <div class="row">

        <div class="col-xl-12 col-md-12 mb-4 p-4 bg-white">
            <div class="text-center mb-4 bg-dark p-3">
                <h5 class="font-weight-bold mb-3 mb-md-0 text-white">وضعیت روش های ارسال</h5>
            </div>
            <div class="table-responsive ">
                <table class="table table-bordered table-striped text-center shadow">
                    <thead>
                    <tr>
                        <th>روش ارسال</th>
                        <th>وضعیت</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($deliveryMethods as $key=>$item)
                        <tr>
                            <th>
                                {{ $item->name }}
                            </th>
                            <th>
                                <a href="{{ route('admin.delivery_method.changeStatus',['method'=>$item->id,'status'=>$item->getRawOriginal('is_active')]) }}"
                                   class="text-white btn btn-sm {{ $item->getRawOriginal('is_active') ? 'btn-success' : 'btn-danger'  }}">
                                    {{ $item->is_active }}
                                </a>
                                <button onclick="updateDescription({{ $item->id }})" data-toggle="modal"
                                        data-target="#updateModal" type="button" href="#"
                                        class="text-white btn btn-sm btn-info">
                                    <i class="fa fa-edit"></i>
                                </button>

                            </th>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <hr>
            <div class="text-center mb-4 bg-dark p-3">
                <h5 class="font-weight-bold mb-3 mb-md-0 text-white">تنظیمات زمان ارسال کالا </h5>
            </div>
            <form action="{{ route('admin.delivery_method.config') }}" method="POST">
                @csrf
            <div class="d-flex flex-column text-center flex-md-row justify-content-md-between mb-4">
                <table class="admintable">

                    <tbody class="d-block">
                        <tr>
                            <td class="key">
                                انتخاب روزهای تعطیل :
                            </td>
                            <td class="checkbox-container">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="holidays[]" value="0" {{ str_contains($delivery_config->holidays,'0') ? 'checked' : '' }}>
                                        <span class="checkmark">شنبه</span>
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="holidays[]" value="1" {{ str_contains($delivery_config->holidays,'1') ? 'checked' : '' }}>
                                        <span class="checkmark">یکشنبه</span>
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="holidays[]" value="2" {{ str_contains($delivery_config->holidays,'2') ? 'checked' : '' }}>
                                        <span class="checkmark">دوشنبه</span>
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="holidays[]" value="3" {{ str_contains($delivery_config->holidays,'3') ? 'checked' : '' }}>
                                        <span class="checkmark">سه شنبه</span>
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="holidays[]" value="4" {{ str_contains($delivery_config->holidays,'4') ? 'checked' : '' }}>
                                        <span class="checkmark">چهارشنبه</span>
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="holidays[]" value="5" {{ str_contains($delivery_config->holidays,'5') ? 'checked' : '' }}>
                                        <span class="checkmark">پنج شنبه</span>
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="holidays[]" value="6" {{ str_contains($delivery_config->holidays,'6') ? 'checked' : '' }}>
                                        <span class="checkmark">جمعه</span>
                                    </label>
                                </div>
                                @error('holidays')
                                <p class="input-error-validation">
                                    <strong>{{ $message }}</strong>
                                </p>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <td class="key">
                                انتخاب زمان برای کدام روش ها فعال شود؟
                            </td>
                            <td class="checkbox-container methods">
                                @foreach($deliveryMethods as $item)
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="shipping[]" value="{{ $item->id }}" {{ str_contains($delivery_config->methods_id,$item->id) ? 'checked' : '' }}>
                                            <span class="checkmark">{{ $item->name }}</span>
                                        </label>
                                    </div>
                                @endforeach
                                <p class="info">هر کدام از روش های بالا را که فعال نمایید، کاربر به محض کلیک روی
                                    آنها با یک پاپاپ مواجه خواهد شد، که از وی زمان دریافت مرسوله از پیک فروشگاه را
                                    درخواست میکند</p>
                                    @error('shipping')
                                    <p class="input-error-validation">
                                        <strong>{{ $message }}</strong>
                                    </p>
                                    @enderror
                            </td>

                        </tr>
                        <tr class="d-flex">
                            <td class="key">
                                ساعات ارسال :
                            </td>
                            <td class="text-right">
                                <button onclick="appendInput()" class="btn btn-success add-time float-right"
                                        type="button">
                                    +
                                </button>
                                <ul class="times-rows">
                                    @foreach($sent_times as $sent_time)
                                        <li class="mb-2">
                                            <input type="text" class="inputbox wide input-time" name="sent_times[]"
                                                   value="{{ $sent_time }}">
                                            <button onclick="removeInput(this)" type="button" class="btn btn-danger remove-time">-</button>
                                        </li>
                                    @endforeach
                                </ul>
                                @error('sent_times')
                                <p class="input-error-validation">
                                    <strong>{{ $message }}</strong>
                                </p>
                                @enderror
                            </td>
                        </tr>

                        <tr class="d-flex">
                            <td class="key">
                                چندروز بعد از خرید ارسال انجام میشود :
                            </td>
                            <td class="text-right">
                                <input type="number" min="1" class="inputbox wide" name="order_send_after" value="{{ $delivery_config->order_send_after }}">
                                <p>
                                    مشخص کنید تایم هایی که مشتری برای دریافت کالا انتخاب میکند، از چند روز بعد نمایش
                                    داده
                                    شود؟
                                </p>
                            </td>
                        </tr>

                        <tr class="d-flex">
                            <td class="key">
                                چند روز در بخش سفارشات نمایش داده شود :
                            </td>
                            <td class="text-right">
                                <input type="number" min="1" class="inputbox wide" name="days_count" value="{{ $delivery_config->days_count }}">
                                <p>
                                    مشخص کنید چند تاریخ برای انتخاب به کاربر نمایش داده شود
                                </p>
                            </td>
                        </tr>
                        <tr class="d-flex">
                            <td class="key">
                                از چه قیمتی به بعد، هزینه ی ارسال رایگان شود؟  :
                            </td>
                            <td class="text-right">
                                <input name="free_delivery" value="{{ $delivery_config->free_delivery }}">
                            </td>
                        </tr>
                        <tr>
                            <td class="key">
                                قانون حذف هزینه ی ارسال برای کدام روش های ارسال اعمال شود؟
                            </td>
                            <td class="checkbox-container methods">
                                @foreach($deliveryMethods as $item)
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="free_delivery_for[]" value="{{ $item->id }}" {{ str_contains($delivery_config->free_delivery_for,$item->id) ? 'checked' : '' }}>
                                            <span class="checkmark">{{ $item->name }}</span>
                                        </label>
                                    </div>
                                @endforeach

                                @error('free_delivery_for')
                                <p class="input-error-validation">
                                    <strong>{{ $message }}</strong>
                                </p>
                                @enderror
                            </td>

                        </tr>
                        <tr>
                            <td>
                                <button class="btn btn-success" type="submit">ذخیره
                                </button>
                            </td>
                        </tr>

                    </tbody>

                </table>
            </div>
            </form>
        </div>
    </div>

    @include('admin.deliveryMethods.modal')
@endsection
