@extends('admin.layouts.admin')

{{-- ===========  meta Title  =================== --}}
@section('title')
    مشاهده درخواست کابر
@endsection
{{-- ===========  My Css Style  =================== --}}
@section('style')
    <style>
        .profile {
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
        function Confirm(user_id) {
            let role = 3;
            if (confirm('آیا از تغییر سطح کاربری این کاربر اطمینان دارید؟')) {
                $.ajax({
                    url: "{{ route('admin.user.change_role.confirm') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        user_id: user_id,
                        role: role,
                    },
                    dataType: "json",
                    type: 'POST',
                    beforeSend: function () {
                        $("#overlay").fadeIn();
                    },
                    success: function (msg) {
                        if (msg) {
                            if (msg[0] == 1) {
                                swal({
                                    title: 'با تشکر',
                                    text: msg[1],
                                    icon: 'success',
                                    buttons: 'متوجه شدم',
                                })
                                setTimeout(function (){
                                    window.location.href = "{{ route('admin.user.change_role.index') }}";
                                },3000)
                            }
                            if (msg[0] == 0) {
                                swal({
                                    title: 'خطا',
                                    text: msg[1],
                                    icon: 'error',
                                    buttons: 'ok',
                                })
                            }
                        }
                        $("#overlay").fadeOut();
                    },
                    fail: function (error) {
                        console.log(error);
                        $("#overlay").fadeOut();
                    }
                })
            }
        }

        function Deny(user_id) {
            if (confirm('آیا از رد درخواست این کاربر اطمینان دارید؟')) {
                $.ajax({
                    url: "{{ route('admin.user.change_role.deny') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        user_id: user_id,
                    },
                    dataType: "json",
                    type: 'POST',
                    beforeSend: function () {
                        $("#overlay").fadeIn();
                    },
                    success: function (msg) {
                        if (msg) {
                            if (msg[0] == 1) {
                                swal({
                                    title: 'با تشکر',
                                    text: 'درخواست کاربر مورد نظر رد شد',
                                    icon: 'success',
                                    buttons: 'متوجه شدم',
                                })
                                window.location.href = "{{ route('admin.user.change_role.index') }}";
                            }
                            if (msg[0] == 0) {
                                swal({
                                    title: 'خطا',
                                    text: msg[1],
                                    icon: 'error',
                                    buttons: 'ok',
                                })
                            }
                        }
                        $("#overlay").fadeOut();

                    },
                    fail: function (error) {
                        console.log(error);
                        $("#overlay").fadeOut();
                    }
                })
            }
        }

    </script>
@endsection
{{-- ===========      CONTENT      =================== --}}
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12 d-flex justify-content-between">
                    <h5>{{ $user->name.' - '.$user->Role->display_name }}</h5>
                    <div>
                        <button onclick="Deny({{ $user->id }})" title="رد درخواست کاربر" type="button"
                                class="btn btn-danger btn-sm">
                            رد
                        </button>
                        <a title="بازگشت به لیست درخواست ها" href="{{ route('admin.user.change_role.index') }}"
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
                <div class="row">
                    <div class="col-2">
                        <img class="profile" src="{{ imageExist(env('USER_IMAGES_UPLOAD_PATH'),$user->avatar) }}">
                    </div>
                    <div class="col-10">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group row">
                                    <div class="col-sm-4 mb-3">
                                        <label>نام و نام خانودگی</label>
                                        <input disabled class="form-control mb-2 border"
                                               value="{{ $user->name }}">
                                    </div>
                                    <div class="col-sm-4 mb-4">
                                        <label>نوع کاربری</label>
                                        <input disabled class="form-control mb-2 border"
                                               value="{{ $user->Role->display_name }}">
                                    </div>
                                    <div class="col-sm-4 mb-3">
                                        <label>شماره همراه</label>
                                        <input disabled class="form-control mb-2 border"
                                               value="{{ $user->cellphone }}">
                                    </div>
                                    <div class="col-sm-4">
                                        <label>ایمیل</label>
                                        <input disabled class="form-control mb-2 border"
                                               value="{{ $user->email }}">
                                    </div>
                                    <div class="col-sm-4">
                                        <label>شماره ثابت</label>
                                        <input disabled class="form-control mb-2 border"
                                               value="{{ $user->tel }}">
                                    </div>
                                </div>
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
                    <div class="col-lg-6 col-12">
                        <div class="d-flex align-items-center justify-content-between company_label mb-2">
                            <label for="company_type" class="required d-block w-100 h-100">
                                فروشگاه
                            </label>
                            <input disabled {{ $user->company_type==1 ? 'checked' : '' }} type="radio" value="1"
                                   id="company_type" name="company_type" data-company-type="1" class="radio_btn">
                        </div>
                    </div>
                    <div class="col-lg-6 col-12">
                        <div class="d-flex align-items-center justify-content-between company_label mb-2">
                            <label for="company_type_2" class="required d-block w-100 h-100">
                                شرکت
                            </label>
                            <input disabled {{ $user->company_type==2 ? 'checked' : '' }} type="radio" value="2"
                                   id="company_type_2" data-company-type="2" name="company_type" class="radio_btn">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-12">
                        <div class="single-input-item mb-2">
                            <label for="company_name" class="required">
                                نام موسسه/شرکت *
                            </label>
                            <input disabled id="company_name" name="company_name" value="{{ $user->company_name }}"
                                   class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="col-lg-6 col-12">
                        <div class="single-input-item mb-2">
                            <label for="economic_code" class="required">
                                کد اقتصادی *
                            </label>
                            <input disabled id="economic_code" name="economic_code" value="{{ $user->economic_code }}"
                                   class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="col-lg-6 col-12">
                        <div class="single-input-item mb-2">
                            <label for="naghsh_code" class="required">
                                کد نقش *
                            </label>
                            <input disabled id="naghsh_code" name="naghsh_code" value="{{ $user->naghsh_code }}"
                                   class="form-control form-control-sm">
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
                               href="{{ imageExist(env('USER_ROLE_IMAGES_UPLOAD_PATH'),$user->image_atach_5) }}">
                                <img class="img-thumbnail"
                                     src="{{ imageExist(env('USER_ROLE_IMAGES_UPLOAD_PATH'),$user->image_atach_5) }}">
                            </a>
                            <label class="d-block mt-3">تصویر کارت ملی مدیر عامل</label>
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
                <div class="row">
                    <div class="col-12">
                        <hr>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <h3>
                            <button type="button" onclick="Confirm({{ $user->id }})"
                                    class="btn btn-success btn-sm float-left">تایید درخواست
                            </button>
                            تغییر کاربری به حقوقی</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
