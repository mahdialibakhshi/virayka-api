@extends('admin.layouts.admin')

{{-- ===========  meta Title  =================== --}}
@section('title')
    اطلاعات کاربر
@endsection
{{-- ===========  My Css Style  =================== --}}
@section('style')
    <style>
        #profile {
            width: 100%;
            height: auto;
            border: 1px solid #cccccc;
            border-radius: 50%;
        }
    </style>
@endsection
{{-- ===========  My JavaScript  =================== --}}

@section('script')
    <script>
        function changeLabel(tag) {
            //get the file name
            var fileName = tag.value;
            if (fileName.length > 0) {
                //replace the "Choose a file" label
                $('.custom-file-label').html(fileName);
            } else {
                $('.custom-file-label').html('فایلی را انتخاب نکرده اید');
            }

        }
        function submitForm(close) {
            $('#close').val(close);
            $('#user_form_info').submit();
        }
        function editAddress(address_id) {
            $('#collapse-address-' + address_id).slideToggle(1000);
        }
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
    </script>
@endsection
{{-- ===========      CONTENT      =================== --}}
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12 d-flex justify-content-between">
                    <h5>{{ $user->name.' - '.$user->Role->display_name }} ID : {{ $user->id }} </h5>
                    <div>
                        <a title="سفارشات کاربر" href="{{ route('admin.user.order',['user'=>$user->id]) }}"
                           class="btn btn-sm btn-danger">
                            سفارشات کاربر
                        </a>
                        <a title="کیف پول" href="{{ route('admin.wallet.index',['user'=>$user->id]) }}"
                           class="btn btn-sm btn-primary">
                            <i class="fa fa-wallet"></i>
                        </a>
                        <a title="بازگشت" href="{{ route('admin.user.index') }}"
                           class="btn btn-sm btn-secondary">
                            <i class=" fa fa-arrow-left"></i>
                        </a>
                    </div>
                </div>
                <div class="col-12">
                    <hr>
                </div>
            </div>
            <div class="col-xl-12 col-md-12 mb-1 p-4 bg-white">
                @include('admin.sections.errors')
                <div class="row">
                    <div class="col-2">
                        <img id="profile" src="{{ imageExist(env('USER_IMAGES_UPLOAD_PATH'),$user->avatar) }}">
                    </div>
                    <form id="user_form_info"
                          action="{{ route('admin.user.update',['user'=>$user->id]) }}"
                          method="POST"
                          enctype="multipart/form-data"
                          class="col-10">
                        <input type="hidden" id="close" name="close" value="0">
                        <div class="row">
                            <div class="col-12">
                                @csrf
                                @method('put')
                                <div class="form-group row">
                                    <div class="col-sm-4 mb-3">
                                        <label>نام و نام خانودگی</label>
                                        <input name="name" class="form-control mb-2 border"
                                               value="{{ $user->name }}">
                                    </div>
                                    <div class="col-sm-4 mb-3">
                                        <label>کد ملی</label>
                                        <input name="national_code" class="form-control mb-2 border"
                                               value="{{ $user->national_code }}">
                                    </div>
                                    <div class="col-sm-4 mb-3">
                                        <label>وضعیت</label>
                                        <select name="is_active" class="form-control mb-2 border">
                                            <option value="1" {{ $user->is_active==1 ? 'selected' : ''  }}>فعال</option>
                                            <option value="0" {{ $user->is_active==0 ? 'selected' : ''  }}>غیر فعال
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4 mb-4">
                                        <label>نوع کاربری</label>
                                        <select name="user_role" class="form-control mb-2 border">
                                            @foreach($user_roles as $role)
                                                <option @if($role->id==1) style="font-size: 20px;color: red;font-weight: bolder" @endif value="{{ $role->id }}" {{ $user->role==$role->id ? 'selected' : '' }}>{{ $role->display_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-4 mb-3">
                                        <label>شماره همراه</label>
                                        <input name="cellphone" class="form-control mb-2 border"
                                               value="{{ $user->cellphone }}">
                                    </div>
                                    <div class="col-sm-4">
                                        <label>ایمیل</label>
                                        <input name="email" class="form-control mb-2 border"
                                               value="{{ $user->email }}">
                                    </div>
                                    <div class="col-sm-4">
                                        <label>شماره ثابت</label>
                                        <input name="tel" class="form-control mb-2 border"
                                               value="{{ $user->tel }}">
                                    </div>
                                    <div class="col-sm-4 custom-file marginTop31">
                                        <label style="margin-top: 32px" class="custom-file-label mt-4" for="file">ویرایش عکس کاربر</label>
                                        <input onchange="changeLabel(this)" type="file" class="custom-file-input"
                                               name="avatar" id="avatar">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <hr>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-primary text-center">
                                    تعیین سطح کاربری برای ادمین
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="role">نقش ادمین</label>
                                <select class="form-control" name="role" id="role">
                                    <option value="">بدون سطح دسترسی</option>

                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}" {{ in_array($role->id , $user->roles->pluck('id')->toArray()) ? 'selected' : '' }}>{{ $role->display_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="accordion col-md-12 mt-3" id="accordionPermission">
                                <div class="card">
                                    <div class="card-header p-1" id="headingOne">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link btn-block text-right" type="button" data-toggle="collapse"
                                                    data-target="#collapsePermission" aria-expanded="true" aria-controls="collapseOne">
                                                مجوز های دسترسی
                                            </button>
                                        </h2>
                                    </div>

                                    <div id="collapsePermission" class="collapse" aria-labelledby="headingOne"
                                         data-parent="#accordionPermission">
                                        <div class="card-body row">
                                            @foreach ($permissions as $permission)
                                                <div class="form-group form-check col-md-3">
                                                    <input type="checkbox" class="form-check-input"
                                                           id="permission_{{ $permission->id }}" name="{{ $permission->name }}"
                                                           value="{{ $permission->name }}"
                                                        {{ in_array( $permission->id , $user->permissions->pluck('id')->toArray() ) ? 'checked' : '' }}
                                                    >
                                                    <label class="form-check-label mr-3"
                                                           for="permission_{{ $permission->id }}">{{ $permission->display_name }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            @if($user->company_type==1)
                                <div class="col-lg-6 col-12 text-center mb-2">
                                    <a target="_blank"
                                       href="{{ imageExist(env('USER_ROLE_IMAGES_UPLOAD_PATH'),$user->image_atach_1) }}">
                                        <img class="img-thumbnail"
                                             src="{{ imageExist(env('USER_ROLE_IMAGES_UPLOAD_PATH'),$user->image_atach_1) }}">
                                    </a>
                                    <label class="d-block mt-3"> جواز کسب </label>
                                </div>
                                <div class="col-lg-6 col-12 text-center mb-2">
                                    <a target="_blank"
                                       href="{{ imageExist(env('USER_ROLE_IMAGES_UPLOAD_PATH'),$user->image_atach_2) }}">
                                        <img class="img-thumbnail"
                                             src="{{ imageExist(env('USER_ROLE_IMAGES_UPLOAD_PATH'),$user->image_atach_2) }}">
                                    </a>
                                    <label class="d-block mt-3">تصویر کارت ملی صاحب جواز </label>
                                </div>
                            @endif
                            @if($user->company_type==2)
                                <div class="col-lg-6 col-12 text-center">
                                    <a target="_blank"
                                       href="{{ imageExist(env('USER_ROLE_IMAGES_UPLOAD_PATH'),$user->image_atach_3) }}">
                                        <img class="img-thumbnail"
                                             src="{{ imageExist(env('USER_ROLE_IMAGES_UPLOAD_PATH'),$user->image_atach_3) }}">
                                    </a>
                                    <label class="d-block mt-3">آگهی آخرین تغییرات</label>
                                </div>
                                <div class="col-lg-6 col-12 text-center">
                                    <a target="_blank"
                                       href="{{ imageExist(env('USER_ROLE_IMAGES_UPLOAD_PATH'),$user->image_atach_4) }}">
                                        <img class="img-thumbnail"
                                             src="{{ imageExist(env('USER_ROLE_IMAGES_UPLOAD_PATH'),$user->image_atach_4) }}">
                                    </a>
                                    <label class="d-block mt-3">اساس نامه</label>
                                </div>
                                <div class="col-lg-6 col-12 text-center">
                                    <a target="_blank"
                                       href="{{ imageExist(env('USER_ROLE_IMAGES_UPLOAD_PATH'),$user->image_atach_6) }}">
                                        <img class="img-thumbnail"
                                             src="{{ imageExist(env('USER_ROLE_IMAGES_UPLOAD_PATH'),$user->image_atach_6) }}">
                                    </a>
                                    <label class="d-block mt-3">روزنامه رسمی کشور</label>
                                </div>
                            @endif
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="button" onclick="submitForm(1)" class="btn btn-danger btn-sm float-left mr-2">ویرایش و بستن</button>
                                <button type="button" onclick="submitForm(0)" id="edit" class="btn btn-info btn-sm float-left">ویرایش</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card mt-3 mb-3">
            <div class="row">
                <div class="col-12 d-flex justify-content-between">
                    <h5 class="p-3">آدرس های کاربر:</h5>
                </div>
                <div class="col-12">
                    <hr>
                </div>
            </div>
            <div class="card-body">
                <div class="col-xl-12 col-md-12 mb-1 p-1">
                    @foreach($user->Addresses as $address)
                        <div>
                            <address class="row my-3">
                                <div class="col-lg-4 col-12">
                                    <div>
                                        <span>گیرنده:</span>

                                        <span> {{ $address->User->name }}</span>
                                    </div>
                                    <div>
                                        <span>عنوان آدرس</span>
                                        <span>:</span>
                                        <span> {{ $address->title }}</span>
                                    </div>
                                </div>
                                <div class="col-lg-5 col-12">
                                    <div>
                                        <span>شماره تماس:</span>

                                        <span>{{ $address->cellphone }}</span>
                                    </div>
                                    <div>
                                        <span>شماره تلفن ضروری:</span>

                                        <span>{{ $address->tel==null ? '-' : $address->tel }}</span>
                                    </div>
                                    <div>
                                        <span>استان / شهر</span>
                                        <span>:</span>
                                        <span>{{ province_name($address->province_id).'/'.city_name($address->city_id) }}</span>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div>
                                        <span>آدرس :</span>

                                        <span> {{ $address->address }}</span>
                                    </div>
                                </div>
                            </address>
                            <div class="d-flex justify-content-between">
                                <button onclick="editAddress({{ $address->id }})"
                                        class="btn btn-info btn-sm" type="button"> ویرایش آدرس
                                </button>
                            </div>
                            <div id="collapse-address-{{ $address->id }}"
                                 class="collapse collapse-address-create-content mt-3"
                                 style=" {{ count($errors->addressUpdate) > 0 && $errors->addressUpdate->first('address_id') == $address->id ? 'display:block' : 'display: none' }}">
                                <form
                                    action="{{ route('home.addresses.update', ['address' => $address->id]) }}"
                                    method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">

                                        <div class="tax-select col-lg-6 col-md-6">
                                            <label>
                                                عنوان آدرس :
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
                                                استان :
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
                                                شهر :
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
                                                شماره تلفن ضروری :
                                            </label>
                                            <input class="form-control" type="text" name="tel"
                                                   value="{{ $address->tel }}">
                                            @error('tel', 'addressUpdate')
                                            <p class="input-error-validation">
                                                <strong>{{ $message }}</strong>
                                            </p>
                                            @enderror
                                        </div>
                                        <div class="tax-select col-lg-6 col-md-6">
                                            <label>
                                                کد پستی :
                                            </label>
                                            <input class="form-control" type="text" name="postal_code"
                                                   value="{{ $address->postal_code }}">
                                            @error('postal_code', 'addressUpdate')
                                            <p class="input-error-validation">
                                                <strong>{{ $message }}</strong>
                                            </p>
                                            @enderror
                                        </div>
                                        <div class="tax-select col-lg-12 col-md-12">
                                            <label>
                                                : آدرس
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
                                            <button class="btn btn-info btn-sm my-2" type="submit">
                                                ثبت
                                                تغییرات
                                            </button>
                                        </div>

                                    </div>

                                </form>

                            </div>

                        </div>

                        <hr>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
