@extends('admin.layouts.admin')

{{-- ===========  meta Title  =================== --}}
@section('title')
    کاربران
@endsection
{{-- ===========  My Css Style  =================== --}}
@section('style')
    <style>
        #users {
            border: 1px solid #eeeeee;
            height: auto;
            max-height: 120px;
            overflow: auto;
            display: none;
            position: absolute;
            z-index: 999;
            width: 100%;
            top: 48px;
            background-color: white;
        }


        .user {
            margin-bottom: 0;
            padding: 0.5rem;
        }

        .user:hover {
            background-color: #afb6ff;
            color: white;
            cursor: pointer;
        }
        .bg-white{
            background-color: white;
            border: 1px solid #cccccc;
            color: #737272;
        }
    </style>
@endsection
{{-- ===========  My JavaScript  =================== --}}

@section('script')
    <script>
        $(document).ready(function () {
            //get localStorage
            let users = JSON.parse(localStorage.getItem("users"));
            let userNameSearch = localStorage.getItem('userNameSearch');
            let role_id = localStorage.getItem('role_id');
            if (users != null) {
                $('#userNameSearch').val(userNameSearch);
                $('#role option').prop('selected', false);
                $('#role option[value="' + role_id + '"]').prop('selected', true);
                filter();
            }
        });

        function removeModal(user_id) {
            let modal = $('#remove_modal');
            modal.modal('show');
            $('#user_id').val(user_id);
        }

        function RemoveUser() {
            let user_id = $('#user_id').val();
            let modal_alert = $('#modal_alert');
            $.ajax({
                url: "{{ route('admin.user.destroy') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    user_id: user_id,
                },
                dataType: "json",
                type: 'POST',
                beforeSend: function () {

                },
                success: function (msg) {
                    if (msg) {
                        let message = msg[1];
                        if (msg[0] === 0) {
                            swal({
                                title: 'خطا',
                                text: message,
                                icon: 'error',
                                buttons: 'ok',
                            })
                        }
                        if (msg[0] === 1) {
                            swal({
                                title: 'باتشکر',
                                text: message,
                                icon: 'success',
                                timer: 3000,
                            })
                            let users = JSON.parse(localStorage.getItem("users"));
                            if (users != null) {
                                filter();
                            } else {
                                setInterval(function () {
                                    window.location.reload();
                                }, 3000)
                            }
                        }
                    }
                },
            })
        }

        function filter() {
            let name = $('#userNameSearch').val();
            let role_id = $('#role').val();
            $.ajax({
                url: "{{ route('admin.users.get') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    name: name,
                    role_id: role_id,
                },
                dataType: "json",
                type: "POST",
                beforeSend: function () {
                    $("#overlay").fadeIn();
                },
                success: function (msg) {
                    if (msg[0] == 1) {
                        $('#insertRow').html(msg[1]);
                        $('.paginate').hide();
                        //clear localStorage
                        localStorage.removeItem('users');
                        localStorage.removeItem('userNameSearch');
                        localStorage.removeItem('role_id');
                        //save in localStorage
                        localStorage.setItem("users", JSON.stringify(msg[1]));
                        localStorage.setItem("userNameSearch", name);
                        localStorage.setItem("role_id", role_id);
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
            localStorage.removeItem('users');
            localStorage.removeItem('userNameSearch');
            window.location.reload();
        })

        function custom_pagination(tag) {
            let per_page = $(tag).val();
            let url = '{{ route('admin.users.pagination',['show_per_page'=>':per_page']) }}';
            url = url.replace(':per_page', per_page);
            window.location.href = url;
        }
    </script>
@endsection
{{-- ===========      CONTENT      =================== --}}
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12 d-lg-flex justify-content-between align-items-center">
                    <h4>کاربران ( {{ $users->total() }} )</h4>
                </div>
                <div class="col-12">
                    <hr>
                </div>
            </div>
            <div class="row d-lg-flex justify-content-between align-items-center mb-3">
                <div class="col-md-8 col-12 d-flex align-items-center">
                    <div>
                        <div class="input-group input-group-md d-flex flex-row-reverse border-radius">
                            <input type="text" class="form-control form-control-sm"
                                   aria-label="Sizing example input"
                                   aria-describedby="inputGroup-sizing-lg" placeholder="جست و جوی کاربر..."
                                   id="userNameSearch" autocomplete="off">
                            <div class="input-group-prepend border-radius">
                    <span class="input-group-text" id="basic-addon2"><i class="fa fa-search"
                                                                        aria-hidden="true"></i></span>
                            </div>
                        </div>
                    </div>
                    <select id="role" class="form mr-3 bg-white">
                        <option value="0">همه</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                        @endforeach
                    </select>
                    <button type="button" onclick="filter()" class="btn btn-sm btn-danger mr-3">فیلتر</button>
                    <button type="button" id="clearFactorBtn" class="btn btn-sm btn-primary mr-3">پاکسازی</button>
                </div>
                <div class="col-md-4 col-12">
                    <div class="d-lg-flex justify-content-end align-items-center">
                        <form id="paginate_form" method="get" class="ml-2">
                            <select onchange="custom_pagination(this)" name="show_per_page"
                                    class="form-control form-control-sm">
                                <option value="default" {{$show_per_page==1?'selected':''}}>پیش فرض</option>
                                <option value="50" {{$show_per_page==50?'selected':''}}> نمایش 50 تایی</option>
                                <option value="100" {{$show_per_page==100?'selected':''}}> نمایش 100 تایی</option>
                                <option value="200" {{$show_per_page==200?'selected':''}}> نمایش 200 تایی</option>
                                <option value="all" {{$show_per_page==0?'selected':''}}> نمایش همه</option>
                            </select>
                        </form>
                        <div>
                            <a title="افزودن کاربر جدید" href="{{ route('admin.user.create') }}"
                               class="btn btn-info btn-sm">افزودن</a>
                            @if($has_request_change_role)
                                <a title="برای تغییر کاربری درخواست جدید دارید"
                                   href="{{ route('admin.user.change_role.index') }}" class="btn btn-danger btn-sm">درخواست
                                    جدید</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @if(count($users)>0)
                <div class="row">
                    <div class="col-12">
                        <form
                            id="groupDelete"
                            action="#"
                            method="POST">
                            @method('delete')
                            @csrf
                            <table class="table table-bordered text-center table-striped">
                                <thead>
                                <tr>
                                    <th>ردیف</th>
                                    <th>نام و نام خانوادگی</th>
                                    <th>شماره همراه</th>
                                    <th>سطح کاربری</th>
                                    <th>وضعیت</th>
                                    <th>جزئیات</th>
                                </tr>
                                </thead>
                                <tbody id="insertRow">
                                @foreach($users as $key=>$item)
                                    <tr class="{{ $item->is_active==0 ? 'bg-danger text-white' : ''  }}">
                                        <td>
                                            {{ $users->firstItem()+$key }}
                                        </td>
                                        <td>
                                            <a class="{{ $item->is_active==0 ? 'text-white' : ''  }}"
                                               href="{{ route('admin.user.edit',['user'=>$item->id]) }}">
                                                {{ $item->name }}
                                            </a>
                                        </td>
                                        <td>
                                            {{ $item->cellphone }}
                                        </td>
                                        <td>
                                            {{ $item->Role->display_name }}
                                        </td>
                                        <td>
                                            {{ $item->is_active==1 ? 'فعال' : 'غیر فعال' }}
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.user.edit',['user'=>$item->id]) }}"
                                               class="btn btn-info btn-sm">
                                                <i class="fa fa-user"></i>
                                            </a>
                                            <button type="button" onclick="removeModal({{ $item->id }})"
                                                    class="btn btn-danger btn-sm">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
                <div class="row paginate">
                    <div class="col-12">
                        <div class="row justify-content-center">
                            {{ $users->render() }}
                        </div>
                    </div>
                </div>
            @else
                <div class="row d-sm-flex align-items-center justify-content-between mt-4 noneDisplay">
                    <div class="col-12">
                        <hr>
                    </div>
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            کاربری برای نمایش موجود نیست
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    @include('admin.users.modal')
@endsection
