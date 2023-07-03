@extends('admin.layouts.admin')

{{-- ===========  meta Title  =================== --}}
@section('title')
    تیکت های کاربر
@endsection
{{-- ===========  My Css Style  =================== --}}
@section('style')
    <style>

    </style>
@endsection
{{-- ===========  My JavaScript  =================== --}}

@section('script')
    <script>


    </script>
@endsection
{{-- ===========      CONTENT      =================== --}}
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row d-sm-flex align-items-center justify-content-between mb-4">
                <div class="col-12">
                    تیکت های کاربر
                    <a href="{{ URL::previous() }}" class="btn btn-secondary float-left">
                        <i class=" fa fa-arrow-left"></i>
                    </a>
                    <hr>
                </div>
            </div>
            @if(count($tickets)>0)
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
                                    <th>کاربر</th>
                                    <th>عنوان</th>
                                    <th>دسته بندی</th>
                                    <th>وضعیت</th>
                                    <th>مشاهده</th>
                                    <th>تاریخ</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($tickets as $key=>$item)
                                    <tr>
                                        <td>
                                            {{ $tickets->firstItem()+$key }}
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.user.edit',['user'=>$item->user_id]) }}">
                                                {{ $item->User->name }}
                                            </a>
                                        </td>
                                        <td>{{ $item->title }}</td>
                                        <td>
                                            {{ $item->Category->title }}
                                        </td>
                                        <td>
                                            {{ $item->Status->title }}
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.ticket.show',['id'=>$item->id]) }}"
                                               class="btn btn-primary">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                        <td class="text-center">{{ verta($item->created_at)->format('d - %B - Y') }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="row justify-content-center">
                            {{ $tickets->render() }}
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
                            تیکتی ای برای نمایش موجود نیست
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
