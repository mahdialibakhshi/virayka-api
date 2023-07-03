@extends('admin.layouts.admin')

{{-- ===========  meta Title  =================== --}}
@section('title')
    تیکت ها
@endsection
{{-- ===========  My Css Style  =================== --}}
@section('style')
    <style>
        #form {
            display: none;
        }
        .CustomBG{
            background-color: #d2ecff !important;
        }
    </style>
@endsection
{{-- ===========  My JavaScript  =================== --}}

@section('script')
    <script>
        $('#button').click(function () {
            $('#form').slideDown();
        })

        $('#UpdateStatus').click(function (){
            let status_id=$('#status').val();
            let ticket_id={{ $ticket->id }};
            let url="{{ route('admin.ticket.changeStatusAjax') }}";
            $.post(url,{
                status_id : status_id,
                ticket_id : ticket_id,
                _token : "{{ csrf_token() }}"
            },function (msg){
                console.log(msg);
                if (msg[0]==1){
                    swal({
                        icon : 'success',
                        text : 'وضعیت با موفقیت تغییر پیدا کرد',
                        timer : 2000
                    });
                }
            },'json')
        })
    </script>
@endsection
{{-- ===========      CONTENT      =================== --}}
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row d-sm-flex align-items-center justify-content-between mb-4">
                <div class="col-12">
                    لیست تیک ها
                    <a href="{{ URL::previous() }}" class="btn btn-secondary float-left">
                        <i class=" fa fa-arrow-left"></i>
                    </a>
                    <hr>
                    <div class="col-12">
                        @include('admin.sections.errors')
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="form-group row">
                        <div class="col-sm-3">
                            <label>کاربر</label>
                            <a href="#">
                                <input disabled class="form-control mb-2 border"
                                       value="{{ $ticket->User->name }}">
                            </a>
                        </div>
                        <div class="col-sm-3">
                            <label>عنوان</label>
                            <input disabled class="form-control mb-2 border"
                                   value="{{ $ticket->title }}">
                        </div>
                        <div class="col-sm-2">
                            <label>تاریخ</label>
                            <input disabled class="form-control mb-2 border"
                                   value="{{ verta($ticket->created_at)->format('d - %B - Y') }}">
                        </div>                        <div class="col-sm-2">
                            <label>وضعیت</label>
                            <select class="form form-control" id="status">
                                @foreach($status as $row)
                                <option value="{{ $row->id }}" {{ $row->id==$ticket->status_id ? 'selected' : '' }}>{{ $row->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-12">
                            <textarea disabled rows="10"
                                      class="form-control mb-2 border">{{ $ticket->description }}</textarea>
                        </div>
                        <div class="col-sm-12">
                            <div class="hr"></div>
                             @if($ticket->file!=null)
                                <div class="col-sm-12 text-right">
                                    <a class="btn btn-danger text-decoration-none text-white" target="_blank"
                                       href="{{ url(env('UPLOAD_FILE_Ticket').$ticket->file) }}">
                                        فایل ضمیمه
                                        <i class="fa fa-download"></i>
                                    </a>
                                </div>
                            @endif
                            <button type="button" id="button" class="btn btn-info float-left">پاسخ
                                <i class="fa fa-reply"></i>
                            </button>
                            <button type="button" id="UpdateStatus" class="btn btn-outline-dark float-left ml-2">به روز رسانی وضعیت

                            </button>
                        </div>

                    </div>
                </div>
                <form
                    id="form"
                    class="col-12"
                    action="{{ route('admin.ticket.replay') }}"
                    method="post"
                >
                    @csrf
                    پاسخ خود را ارسال نمایید:
                    <hr>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <textarea name="description" rows="10" class="form-control mb-2 border"></textarea>
                        </div>
                        <input name="ticket_id" type="hidden" value="{{ $ticket->id }}">
                        <input name="user_id" type="hidden" value="{{ $ticket->User->id }}">
                        <div class="col-sm-12">
                            <button class="btn btn-success float-left">
                              ارسال پاسخ
                            </button>
                        </div>
                    </div>
                </form>
                @if(count($conversation)>0)
                <div class="col-12">
                    <div class="form-group row">
                        @foreach($conversation as $item)
                            <div class="col-sm-12 mb-3">
                                <label class="float-left">{{ verta($item->created_at)->format('d - %B - Y') }}</label>
                                <label class="float-right">{{ $item->user_id=='admin' ? 'admin' : $item->User->name }} : </label>
                                <textarea disabled
                                          class="form-control mb-2 border {{ $item->user_id=='admin' ? 'CustomBG' : '' }}"
                                          rows="10"
                                >{{ $item->description }}</textarea>
                            </div>
                            @if($item->file!=null)
                                <div class="col-sm-12 text-right">
                                    <a class="btn btn-danger text-decoration-none text-white" target="_blank"
                                       href="{{ url(env('UPLOAD_FILE_Ticket').$item->file) }}">
                                        فایل ضمیمه
                                        <i class="fa fa-download"></i>
                                    </a>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection
