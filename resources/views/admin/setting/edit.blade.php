@extends('admin.layouts.admin')

@section('title')
    Edit Store Info
@endsection

@section('style')
    <style>
        .img-thumbnail {
            max-width: 200px;
            height: auto;
        }

        p {
            padding: 10px;
        }
    </style>
@endsection

@section('script')
    <script>
        // Show File Name
        $('#image').change(function() {
            //get the file name
            var fileName = $(this).val();
            //replace the "Choose a file" label
            $(this).next('.custom-file-label').html(fileName);
        });
        // Show File Name
        $('#special_page_banner').change(function() {
            //get the file name
            var fileName = $(this).val();
            //replace the "Choose a file" label
            $(this).next('.custom-file-label').html(fileName);
        });
        // Show File Name
        $('#newest_page_banner').change(function() {
            //get the file name
            var fileName = $(this).val();
            //replace the "Choose a file" label
            $(this).next('.custom-file-label').html(fileName);
        });

        // Show File Name
        $('#favicon').change(function() {
            //get the file name
            var fileName = $(this).val();
            //replace the "Choose a file" label
            $(this).next('.custom-file-label').html(fileName);
        });
        // Show File Name
        $('#top_page_banner').change(function() {
            //get the file name
            var fileName = $(this).val();
            //replace the "Choose a file" label
            $(this).next('.custom-file-label').html(fileName);
        });

        $(`#order_start`).MdPersianDateTimePicker({
            targetTextSelector: `#order_start_input`,
            englishNumber: true,
            enableTimePicker: true,
            textFormat: 'yyyy-MM-dd HH:mm:ss',
        });
    </script>
@endsection

@section('content')

    <!-- Content Row -->
    <div class="row">

        <div class="col-xl-12 col-md-12 mb-4 p-4 bg-white">
            <div class="mb-4 text-center text-md-right">
                @if($amazing_sale==null)
                <h5 class="font-weight-bold">اطلاعات فروشگاه</h5>
                @else
                    <h5 class="font-weight-bold">تنظیمات زمان برای محصولات شگفت انگیز</h5>
                @endif
            </div>
            <hr>
            @if($amazing_sale==null)
            <div class="text-center">
                <img class="img-thumbnail" src="{{ asset(env('LOGO_UPLOAD_PATH').$setting->image) }}">
                <p>
                    {{ $setting->name }}
                </p>
            </div>
            @endif
            @include('admin.sections.errors')
            <form action="{{ route('admin.setting.update',['setting'=>1]) }}" method="POST"
                  enctype="multipart/form-data">
                @csrf
                <div class="form-row">
                    @if($amazing_sale==null)
                    <div class="form-group col-md-4">
                        <label for="name">نام فروشنده</label>
                        <input class="form-control" id="name" name="name" type="text" value="{{ $setting->name }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="name">پست الکترونیک</label>
                        <input class="form-control" id="email" name="email" type="email" value="{{ $setting->email }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="name">آدرس</label>
                        <input class="form-control" id="address" name="address" type="text"
                               value="{{ $setting->address }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="name">تلفن تماس</label>
                        <input class="form-control" id="tel" name="tel" type="text" value="{{ $setting->tel }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="name">تلفن تماس 2</label>
                        <input class="form-control" id="tel2" name="tel2" type="text" value="{{ $setting->tel2 }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="name">تلفن تماس 3</label>
                        <input class="form-control" id="tel3" name="tel3" type="text" value="{{ $setting->tel3 }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="name">تلفن تماس 4</label>
                        <input class="form-control" id="tel4" name="tel4" type="text" value="{{ $setting->tel4 }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="name">شماره همراه</label>
                        <input class="form-control" id="cellphone" name="cellphone" type="text"
                               value="{{ $setting->cellphone }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="name">پشتیبانی واتساپ</label>
                        <input class="form-control" id="whatsapp" name="whatsapp" type="text"
                               value="{{ $setting->whatsapp }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="name">ساعت کار فروشگاه</label>
                        <input class="form-control" id="workTime" name="workTime" type="text"
                               value="{{ $setting->workTime }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="name">کد اقتصادی</label>
                        <input class="form-control" id="EconomicCode" name="EconomicCode" type="text"
                               value="{{ $setting->EconomicCode }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="name">شماره همراه جهت دریافت ثبت سفارش </label>
                        <input class="form-control" id="delivery_order_numbers" name="delivery_order_numbers"
                               type="text" value="{{ $setting->delivery_order_numbers }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="name">شماره ثبت شرکت </label>
                        <input class="form-control" id="shomare_sabt" name="shomare_sabt" type="text"
                               value="{{ $setting->shomare_sabt }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="name">کد پستی </label>
                        <input class="form-control" id="postalCode" name="postalCode" type="text"
                               value="{{ $setting->postalCode }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="name">پیشوند کد کالا </label>
                        <input class="form-control" id="productCode" name="productCode" type="text"
                               value="{{ $setting->productCode }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="name">پیج اینستاگرام </label>
                        <input class="form-control" id="instagram" name="instagram" type="text"
                               value="{{ $setting->instagram }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="name">لینک تلگرام </label>
                        <input class="form-control" id="instagram" name="telegram" type="text"
                               value="{{ $setting->telegram }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="name">پیام بالای صفحه</label>
                        <input class="form-control" id="message" name="message" type="text"
                               value="{{ $setting->message }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="name">لوگو شرکت </label>
                        <div class="input-group mb-3">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="image" name="image">
                                <label class="custom-file-label" for="image">انتخاب کنید</label>
                            </div>
                        </div>
                        @error('image')
                        <p style="color: red">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label for="name">نمادک(favicon)</label>
                        <div class="input-group mb-3">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="favicon" name="favicon">
                                <label class="custom-file-label" for="favicon">انتخاب کنید</label>
                            </div>
                        </div>
                        @error('favicon')
                        <p style="color: red">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label for="name">بنر صفحه محصولات ویژه</label>
                        <div class="input-group mb-3">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="special_page_banner" name="special_page_banner">
                                <label class="custom-file-label" for="special_page_banner">انتخاب کنید</label>
                            </div>
                        </div>
                        @error('special_page_banner')
                        <p style="color: red">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label for="name">بنر صفحه محصولات جدید</label>
                        <div class="input-group mb-3">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="newest_page_banner" name="newest_page_banner">
                                <label class="custom-file-label" for="newest_page_banner">انتخاب کنید</label>
                            </div>
                        </div>
                        @error('newest_page_banner')
                        <p style="color: red">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group col-md-4">
                        <label for="name">بنر اعلانات بالای صفحه</label>
                        <div class="input-group mb-3">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="top_page_banner" name="top_page_banner">
                                <label class="custom-file-label" for="top_page_banner">انتخاب کنید</label>
                            </div>
                        </div>
                        @error('top_page_banner')
                        <p style="color: red">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label for="name">بنر اعلانات بالای صفحه فعال باشد؟</label>
                        <select class="form-control" name="top_page_banner_active">
                            <option {{ $setting->top_page_banner_active==1 ? 'selected' : '' }} value="1">بله</option>
                            <option {{ $setting->top_page_banner_active==0 ? 'selected' : '' }} value="0">خیر</option>
                        </select>
                    </div>
                        <div class="form-group col-12">
                            <label for="name">اعلانات محصولات(نمایش در صفحه محصول)</label>
                            <textarea rows="5" class="form-control" id="product_message" name="product_message" type="text">{{ $setting->product_message }}</textarea>
                        </div>
                        <div class="form-group col-12">
                            <label for="name">درباره ما(فوتر)</label>
                            <textarea rows="5" class="form-control" id="about_us" name="about_us" type="text">{{ $setting->about_us }}</textarea>
                        </div>
                    @else
                        <div class="form-group mb-2">
                            <label> تاریخ شروع : </label>
                            <div class="input-group">
                                <div class="input-group-prepend order-2">
                                                    <span class="input-group-text" id="order_start">
                                                        <i class="fas fa-clock"></i>
                                                    </span>
                                </div>
                                <input type="text" class="form-control" id="order_start_input"
                                       name="date_on_sale_from"
                                       value="{{ $setting->expire_amazing_product==null ? null : verta($setting->expire_amazing_product)->format('Y-m-d') }}">
                            </div>
                        </div>
                    @endif

                </div>

                <button class="btn btn-outline-primary mt-5" type="submit">ثبت</button>
            </form>
        </div>

    </div>

@endsection
