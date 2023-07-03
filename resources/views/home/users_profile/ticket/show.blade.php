@extends('home.users_profile.layout')

@section('title')
    جزئیات تیکت
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('home/css/profile_panel.css') }}">

    <style>
        #form {
            display: none;
        }

        .active {
            color: #0B94F7;
        }

        .UserImage {
            width: 100px;
            height: 100px;
            border-radius: 50%;
        }

        .CustomBG {
            background-color: #d2ecff !important;
        }
        .float-left{
            float: left !important;
        }
    </style>
@endsection

@section('script')
    <script>
        let $ = jQuery;
        $('#button').click(function () {
            $('#form').slideDown();
        });
    </script>
@endsection

@section('main_content')
    <div class="col-lg-9 col-md-8">
        @if($user->name==null)
            <div class="alert alert-info text-center">
                برای فعال شدن منو ها از قسمت <a class="ht-btn mx-2" href="{{ route('home.users_profile.index') }}">پروفایل</a> اطلاعات خود را تکمیل نمایید
            </div>
        @else
            <div class="tab-content" id="myaccountContent">
                <div class="myaccount-content">
                    <div class="row">
                        <div class="hr"></div>
                    </div>
                    <div class="row">
                        <form
                            action="{{ route('home.ticket.store') }}"
                            method="POST"
                            enctype="multipart/form-data"
                            class="col-12"
                        >
                            @csrf
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <input disabled class="form-control mb-2 border"
                                           value="{{ $ticket->title }}" placeholder="عنوان">
                                </div>
                                <div class="col-sm-12">
                                                            <textarea disabled
                                                                      class="form-control mb-2 border">{{ $ticket->description }}</textarea>
                                </div>
                                @if($ticket->file!=null)
                                    <div class="col-sm-12 text-right">
                                        <a class="btn btn-sm btn-danger text-decoration-none text-white" target="_blank"
                                           href="{{ url(env('UPLOAD_FILE_Ticket').$ticket->file) }}">
                                            فایل ضمیمه
                                            <i class="fa fa-download"></i>
                                        </a>
                                    </div>
                                @endif
                                <div class="col-sm-12">
                                    <div class="hr"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="row mt-3">
                        @foreach($conversation as $item)
                            <div class="col-sm-12 mb-3">
                                <label>{{ $item->user_id=='admin' ? 'admin' : $item->User->name }}
                                    : </label>
                                <label class="float-left">{{ verta($item->created_at)->format('d - %B - Y') }}</label>

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
                            <div class="col-sm-12">
                                <div class="hr"></div>
                            </div>
                        @endforeach
                        <div class="col-sm-12 mb-3">
                            <button type="button" id="button" class="btn btn-sm btn-info float-left">
                                پاسخ
                                <i class="fa fa-reply"></i>
                            </button>
                            <a href="{{ route('home.ticket.index') }}"
                               class="btn btn-dark btn-sm text-decoration-none text-white">بازگشت</a>
                        </div>
                        <form
                            id="form"
                            class="col-12 text-right form"
                            action="{{ route('home.ticket.replay') }}"
                            method="post"
                            enctype="multipart/form-data"
                        >
                            @csrf
                            پاسخ خود را ارسال نمایید:
                            <hr>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                                            <textarea name="description" rows="10"
                                                                      class="form-control mb-2 border"></textarea>
                                </div>
                                <div class="col-sm-12">
                                    <p>
                                        افزودن فایل ضمیمه(عکس.فایل ورد و pdf ) :
                                    </p>
                                    <input class="mt-2" type="file" name="file">
                                </div>
                                <input name="ticket_id" type="hidden" value="{{ $ticket->id }}">
                                <div class="col-sm-12 mt-2">
                                    <button type="submit" class="btn btn-sm btn-success float-left">
                                        ارسال
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



