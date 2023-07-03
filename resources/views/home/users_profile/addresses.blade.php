@extends('home.users_profile.layout')

@section('title')
    صفحه ای آدرس ها
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            $('select').removeClass('nice-select');
            $('select').show();
            $('div .nice-select').remove();
        })
        $('.province-select').change(function () {
            var provinceID = $(this).val();
            if (provinceID) {
                $.ajax({
                    type: "GET",
                    url: "{{ url('/get-province-cities-list') }}?province_id=" + provinceID,
                    success: function (res) {
                        if (res) {
                            $(".city-select").empty();

                            $.each(res, function (key, city) {
                                console.log(city);
                                $(".city-select").append('<option value="' + city.id + '">' +
                                    city.name + '</option>');
                            });

                        } else {
                            $(".city-select").empty();
                        }
                    }
                });
            } else {
                $(".city-select").empty();
            }
        });

        function collapseAddress() {
            $('#collapseAddAddress').slideToggle(1000);
        }

        function editAddress(address_id) {
            $('#collapse-address-' + address_id).slideToggle(1000);
        }

    </script>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('home/css/profile_panel.css') }}">

    <style>
        li {
            list-style: none !important;
        }

        #myaccountContent {
            margin-top: 0 !important;
            text-align: right !important;
        }

        .float-right {
            float: right !important;
        }

        .show {
            display: block;
        }

    </style>
@endsection

@section('main_content')
    <div class="col-lg-9 col-md-8 order-2">
        @if($user->name==null or $user->national_code==null)
            <div class="alert alert-info text-center">
                برای فعال شدن منو ها از قسمت <a class="ht-btn mx-2" href="{{ route('home.users_profile.index') }}">پروفایل</a> اطلاعات خود را تکمیل نمایید
            </div>
        @else
            <div class="tab-content " id="myaccountContent">

                <div class="myaccount-content address-content">
                    <h3> آدرس ها </h3>

                    @foreach ($addresses as $address)
                        <div>
                            <address>
                                <div class="d-flex justify-content-between">
                                    <p>
                                                        <span class="mr-2"> کاربر : <span> {{ auth()->user()->name }}
                                                            </span> </span>
                                    </p>
                                    <p>
                                                        <span class="mr-2"> عنوان آدرس : <span> {{ $address->title }}
                                                            </span> </span>
                                    </p>
                                    <p>
                                        استان :
                                        {{ province_name($address->province_id) }}
                                    </p>
                                    <p>
                                        شماره موبایل :
                                        {{ $address->cellphone }}
                                    </p>
                                </div>
                                <div class="d-flex justify-content-between">

                                    <p>
                                        شماره ثابت :
                                        {{ $address->tel==null ? '-' : $address->tel }}
                                    </p>
                                    <p>
                                        شهر :
                                        {{ city_name($address->city_id) }}
                                    </p>
                                    <p>
                                        کدپستی :
                                        {{ $address->postal_code==null ? '-' : $address->postal_code }}
                                    </p>
                                </div>
                                <p>
                                    نشانی :
                                    {{ $address->address }}
                                </p>
                            </address>
                            <div class="d-flex justify-content-between">
                                <button onclick="editAddress({{ $address->id }})"
                                        class="btn btn-main-masai" type="button"> ویرایش آدرس
                                </button>
                                <a href="{{ route('home.addresses.delete', ['address' => $address->id]) }}"
                                   class="btn btn-main-masai" type="button">
                                    <i class="fa fa-trash"></i>
                                    حذف
                                </a>
                            </div>
                            <div id="collapse-address-{{ $address->id }}"
                                 class="collapse collapse-address-create-content mt-3"
                                 style="{{ count($errors->addressUpdate) > 0 && $errors->addressUpdate->first('address_id') == $address->id ? 'display:block' : '' }}">
                                <form
                                    action="{{ route('home.addresses.update', ['address' => $address->id]) }}"
                                    method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">

                                        <div class="tax-select col-lg-6 col-md-6">
                                            <label>
                                                عنوان *
                                            </label>
                                            <input class="form-control" type="text" name="title"
                                                   value="{{ $address->title }}">
                                            @error('title', 'addressUpdate')
                                            <p class="input-error-validation">
                                                <strong>{{ $message }}</strong>
                                            </p>
                                            @enderror
                                        </div>
                                        <div class="tax-select col-lg-6 col-md-6">
                                            <label>
                                                موبایل *
                                            </label>
                                            <input class="form-control" type="text"
                                                   name="cellphone"
                                                   value="{{ $address->cellphone }}">
                                            @error('cellphone', 'addressUpdate')
                                            <p class="input-error-validation">
                                                <strong>{{ $message }}</strong>
                                            </p>
                                            @enderror
                                        </div>
                                        <div class="tax-select col-lg-6 col-md-6">
                                            <label>
                                                استان *
                                            </label>
                                            <select
                                                class="form-control email s-email s-wid province-select"
                                                name="province_id">
                                                @foreach ($provinces as $province)
                                                    <option value="{{ $province->id }}"
                                                        {{ $province->id == $address->province_id ? 'selected' : '' }}>
                                                        {{ $province->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('province_id', 'addressStore')
                                            <p class="input-error-validation">
                                                <strong>{{ $message }}</strong>
                                            </p>
                                            @enderror
                                        </div>
                                        <div class="tax-select col-lg-6 col-md-6">
                                            <label>
                                                شهر *
                                            </label>
                                            <select
                                                class="form-control email s-email s-wid city-select"
                                                name="city_id">
                                                <option value="{{ $address->city_id }}"
                                                        selected>
                                                    {{ city_name($address->city_id) }}
                                                </option>
                                            </select>
                                            @error('city_id', 'addressStore')
                                            <p class="input-error-validation">
                                                <strong>{{ $message }}</strong>
                                            </p>
                                            @enderror
                                        </div>
                                        <div class="tax-select col-lg-6 col-md-6">
                                            <label>
                                                کد پستی
                                            </label>
                                            <input class="form-control" type="text"
                                                   name="postal_code"
                                                   value="{{ $address->postal_code }}">
                                            @error('postal_code', 'addressUpdate')
                                            <p class="input-error-validation">
                                                <strong>{{ $message }}</strong>
                                            </p>
                                            @enderror
                                        </div>
                                        <div class="tax-select col-lg-6 col-md-6">
                                            <label>
                                                شماره ثابت
                                            </label>
                                            <input class="form-control" type="text" name="tel"
                                                   value="{{ $address->tel }}">
                                            @error('tel', 'addressUpdate')
                                            <p class="input-error-validation">
                                                <strong>{{ $message }}</strong>
                                            </p>
                                            @enderror
                                        </div>
                                        <div class="tax-select col-lg-12 col-md-12">
                                            <label>
                                                نشانی *
                                            </label>
                                            <textarea class="form-control" type="text"
                                                      name="address"
                                            >{{ $address->address }}</textarea>
                                            @error('address', 'addressUpdate')
                                            <p class="input-error-validation">
                                                <strong>{{ $message }}</strong>
                                            </p>
                                            @enderror
                                        </div>
                                        <div class=" col-lg-12 col-md-12">
                                            <button class="btn btn-main-masai my-2" type="submit"> ثبت
                                                تغییرات
                                            </button>
                                        </div>

                                    </div>

                                </form>

                            </div>

                        </div>

                        <hr>
                    @endforeach

                    <button onclick="collapseAddress()" data-toggle="collapse"
                            data-target="#collapseAddAddress"
                            class="collapse-address-create btn btn-main-masai mt-3" type="submit">
                        ایجاد آدرس
                        جدید
                    </button>
                    <div id="collapseAddAddress"
                         class="collapse collapse-address-create-content mt-3"
                         style="{{ count($errors->addressStore) > 0 ? 'display:block' : '' }}">

                        <form action="{{ route('home.addresses.store') }}" method="POST">
                            @csrf
                            <div class="row">

                                <div class="tax-select col-lg-6 col-md-6">
                                    <label>
                                        عنوان*(منزل,محل کار و ...)
                                    </label>
                                    <input class="form-control" type="text" name="title"
                                           value="{{ old('title') }}">
                                    @error('title', 'addressStore')
                                    <p class="input-error-validation">
                                        <strong>{{ $message }}</strong>
                                    </p>
                                    @enderror
                                </div>
                                <div class="tax-select col-lg-6 col-md-6">
                                    <label>
                                        موبایل *
                                    </label>
                                    <input class="form-control" type="text" name="cellphone"
                                           value="{{ auth()->user()->cellphone }}">
                                    @error('cellphone', 'addressStore')
                                    <p class="input-error-validation">
                                        <strong>{{ $message }}</strong>
                                    </p>
                                    @enderror
                                </div>
                                <div class="tax-select col-lg-6 col-md-6">
                                    <label>
                                        استان *
                                    </label>
                                    <select
                                        class="form-control email s-email s-wid province-select"
                                        name="province_id">
                                        <option value="" selected>انتخاب کنید
                                        </option>
                                        @foreach ($provinces as $province)
                                            <option
                                                value="{{ $province->id }}">{{ $province->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('province_id', 'addressStore')
                                    <p class="input-error-validation">
                                        <strong>{{ $message }}</strong>
                                    </p>
                                    @enderror
                                </div>
                                <div class="tax-select col-lg-6 col-md-6">
                                    <label>
                                        شهر *
                                    </label>
                                    <select class="form-control email s-email s-wid city-select"
                                            name="city_id">
                                        <option value="" selected>انتخاب کنید
                                        </option>
                                    </select>
                                    @error('city_id', 'addressStore')
                                    <p class="input-error-validation">
                                        <strong>{{ $message }}</strong>
                                    </p>
                                    @enderror
                                </div>
                                <div class="tax-select col-lg-6 col-md-6">
                                    <label>
                                        شماره ثابت
                                    </label>
                                    <input class="form-control" type="text" name="tel"
                                           value="{{ old('tel') }}">
                                    @error('tel', 'addressStore')
                                    <p class="input-error-validation">
                                        <strong>{{ $message }}</strong>
                                    </p>
                                    @enderror
                                </div>
                                <div class="tax-select col-lg-6 col-md-6">
                                    <label>
                                        کد پستی
                                    </label>
                                    <input class="form-control" type="text" name="postal_code"
                                           value="{{ old('postal_code') }}">
                                    @error('postal_code', 'addressStore')
                                    <p class="input-error-validation">
                                        <strong>{{ $message }}</strong>
                                    </p>
                                    @enderror
                                </div>

                                <div class="tax-select col-md-12">
                                    <label>
                                        نشانی *
                                    </label>
                                    <textarea class="form-control" type="text"
                                              name="address">{{ old('address') }}</textarea>
                                    @error('address', 'addressStore')
                                    <p class="input-error-validation">
                                        <strong>{{ $message }}</strong>
                                    </p>
                                    @enderror
                                </div>

                                <div class=" col-lg-12 col-md-12">
                                    <button class="btn btn-main-masai mt-3" type="submit"> ثبت
                                        آدرس
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>

            </div>
        @endif
    </div>
@endsection
