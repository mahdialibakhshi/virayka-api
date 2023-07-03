@extends('admin.layouts.admin')

@section('title')
    create coupon
@endsection

@section('style')
   <style>
       #percentageParent,#max_percentage_amount_parent{
           display: none;
       }
       #users {
           border: 1px solid #eeeeee;
           height: auto;
           max-height: 120px;
           overflow: auto;
           display: none;
       }
   </style>
@endsection

@section('script')
    <script>
         $('#expireDate').MdPersianDateTimePicker({
            targetTextSelector: '#expireInput',
            englishNumber: true,
            enableTimePicker: true,
            textFormat: 'yyyy-MM-dd HH:mm:ss',
        });

         $('#type').change(function (){
             let type=$(this).val();
             if (type=='percentage'){
                 $('#percentage').parent().show();
                 $('#max_percentage_amount').parent().show();
                 $('#amount').parent().hide();
             }else {
                 $('#percentage').parent().hide();
                 $('#max_percentage_amount').parent().hide();
                 $('#amount').parent().show();
             }

         })

         //getUserInfo
         //get Users
         $('#selectUserInput').focus(function () {
             getUserAjax();
         });
         function getUserAjax() {
             $('#users').html('');
             $.ajax({
                 url: "{{ route('admin.user.AjaxGet') }}",
                 dataType: "json",
                 type: "POST",
                 data: {
                     _token: "{{ csrf_token() }}",
                 },
                 success: function (rows) {
                     if (rows) {
                         let rowsList = '';
                         $.each(rows, function (key, row) {
                             rowsList += appendUserRow(row);
                         });
                         $('#users').append(rowsList);
                         $('#users').slideDown();
                     }
                 },
                 fail: function (fail) {
                     console.log(fail);
                 }
             })
         }
         $('#selectUserInput').on('keyup', function () {
             $('#users').html('');
             let input = $(this).val();
             $.ajax({
                 url: "{{ route('admin.user.searchUser') }}",
                 dataType: "json",
                 type: "POST",
                 data: {
                     _token: "{{ csrf_token() }}",
                     name: input,
                 },
                 success: function (rows) {
                     if (rows) {
                         if (rows.length > 0) {
                             let rowsList = '';
                             $.each(rows, function (key, row) {
                                 rowsList += appendUserRow(row);
                             });
                             $('#users').append(rowsList);
                         } else {
                             $('#users').html(`<p class="alert alert-danger text-center">کاربر مورد نظر یافت نشد</p>`);
                         }
                         $('#users').slideDown();
                     }
                 },
                 fail: function (fail) {
                     console.log(fail);
                 }
             })
         });
         function appendUserRow(row) {
             return `<p onclick="appToInput('${row.id}','${row.name}')" class="user" data-id='${row.id}'>${row.name}</p>`;
         }
         function appToInput(id, name) {
             $('#selectUserInput').val(name);
             $('#userId').val(id);
             $('#users').slideUp();
             $('#users').html('');
         }

    </script>
@endsection
@section('content')

    <!-- Content Row -->
    <div class="row">

        <div class="col-xl-12 col-md-12 mb-4 p-4 bg-white">
            <div class="mb-4 text-center text-md-right">
                <h5 class="font-weight-bold">ایجاد کوپن</h5>
            </div>
            <hr>

            @include('admin.sections.errors')

            <form action="{{ route('admin.coupons.store') }}" method="POST">
                @csrf

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="code">کد</label>
                        <input class="form-control" id="code" name="code" type="text" {{ old('code') }}>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="type">نوع</label>
                        <select class="form-control" id="type" name="type">
                            <option value="amount" selected>مبلغی</option>
                            <option value="percentage">درصدی</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="amount">مبلغ (تومان)</label>
                        <input class="form-control" id="amount" name="amount" type="text" {{ old('amount') }}>
                    </div>
                    <div id="percentageParent" class="form-group col-md-3">
                        <label for="percentage">درصد</label>
                        <input class="form-control" id="percentage" name="percentage" type="text" {{ old('percentage') }}>
                    </div>
                    <div id="max_percentage_amount_parent" class="form-group col-md-3">
                        <label for="max_percentage_amount">حداکثر مبلغ برای نوع درصدی (تومان)</label>
                        <input class="form-control" id="max_percentage_amount" name="max_percentage_amount" type="text" {{ old('max_percentage_amount') }}>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="max_percentage_amount">دفعات قابل استفاده</label>
                        <input class="form-control" id="times" name="times" type="number" value="1">
                    </div>
                    <div class="form-group col-md-3">
                        <label> تاریخ انقضا  </label>
                        <div class="input-group">
                            <div class="input-group-prepend order-2">
                                <span class="input-group-text" id="expireDate">
                                    <i class="fas fa-clock"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" id="expireInput"
                                name="expired_at">
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <label for="description"> کاربر </label>
                        <input class="form-control" id="selectUserInput"  type="text">
                        <input class="form-control" id="userId" name="user_id" value="" type="hidden">
                        <div id="users">

                        </div>
                    </div>
                </div>

                <button class="btn btn-outline-primary mt-5" type="submit">ثبت</button>
                <a href="{{ route('admin.coupons.index') }}" class="btn btn-dark mt-5 mr-3">بازگشت</a>
            </form>
        </div>

    </div>

@endsection
