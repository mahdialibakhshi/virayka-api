@extends('admin.layouts.admin')

{{-- ===========  meta Title  =================== --}}
@section('title')
    لیست درخواست های کاربران
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
    </style>
@endsection
{{-- ===========  My JavaScript  =================== --}}

@section('script')
    <script>
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
                            if (msg[0]==1){
                                swal({
                                    title:'با تشکر',
                                    text:'درخواست کاربر مورد نظر رد شد',
                                    icon:'success',
                                    buttons:'متوجه شدم',
                                })
                                window.location.href="{{ route('admin.user.change_role.index') }}";
                            }
                            if (msg[0]==0){
                                swal({
                                    title:'خطا',
                                    text:msg[1],
                                    icon:'error',
                                    buttons:'ok',
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
            <div class="row d-sm-flex align-items-center justify-content-between mb-4">
                <div class="col-3">
                    <a title="لیست کاربران" href="{{ route('admin.user.index') }}" class="btn btn-dark btn-sm">بازگشت</a>
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
                                    <th>جزئیات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $key=>$item)
                                    <tr class="{{ $item->is_active==0 ? 'bg-danger text-white' : ''  }}">
                                        <td>
                                            {{ $key+1 }}
                                        </td>
                                        <td>
                                            <a class="{{ $item->is_active==0 ? 'text-white' : ''  }} href="{{ route('admin.user.edit',['user'=>$item->id]) }}">
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
                                            <a title="مشاهده مدارک و جزئیات" href="{{ route('admin.user.change_role.edit',['user'=>$item->id]) }}"
                                                class="btn btn-primary btn-sm">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <button onclick="Deny({{ $item->id }})" title="رد درخواست کاربر" type="button" class="btn btn-danger btn-sm">
                                                رد
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </form>
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
