@extends('admin.layouts.admin')

@section('title')
    دسته بندی ها
@endsection

@section('style')
    <style>
        .img-thumbnail {
            width: 75px;
            height: auto;
        }

        th {
            vertical-align: middle !important;
        }

        #overlay {
            display: none;
        }
    </style>
@endsection

@section('script')
    <script>
        function RemoveModal(category_id) {
            let modal = $('#remove_category_modal');
            modal.modal('show');
            $('#category_id').val(category_id);
        }

        function RemoveCategory() {
            let category_id = $('#category_id').val();
            let category_modal_alert = $('#category_modal_alert');
            $.ajax({
                url: "{{ route('admin.category.remove') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    category_id: category_id,
                },
                dataType: "json",
                type: 'POST',
                beforeSend: function () {

                },
                success: function (msg) {
                    if (msg) {
                        if (msg[0] == 0) {
                            let error = msg[1];
                            let items = msg[2];
                            let row = '';
                            console.log(items);
                            $.each(items, function (i, item) {
                                row += `<div><a href="${item.link}">${item.name}</a></div>`;
                            })
                            $('#category_modal_text').html(error + row);
                            category_modal_alert.modal('show');
                        }
                        if (msg[0] == 1) {
                            let message = msg[1];
                            swal({
                                title: 'باتشکر',
                                text: message,
                                icon: 'success',
                                timer: 3000,
                            })
                            window.location.reload();
                        }
                    }
                },
            })
        }

        function showOnIndex(category_id) {
            let selector = '#category_' + category_id;
            $.ajax({
                url: "{{ route('admin.category.showOnIndex') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    category_id: category_id,
                },
                dataType: "json",
                type: 'POST',
                beforeSend: function () {
                    $("#overlay").fadeIn();
                },
                success: function (msg) {
                    if (msg) {
                        if (msg[1] === 1) {
                            $(selector).removeClass('btn-dark');
                            $(selector).addClass('btn-success text-white');
                            $(selector).text('فعال');
                        }
                        if (msg[1] === 0) {
                            $(selector).removeClass('btn-success text-white');
                            $(selector).addClass('btn-dark');
                            $(selector).text('غیر فعال');
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

        $('#SearchInput').keyup(function (){
            let name=$(this).val();
            $.ajax({
                url: "{{ route('admin.categories.get') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    name:name,
                },
                dataType: "json",
                type: "POST",
                success: function (msg) {
                    if (msg[0]==1){
                        $('#insertRow').html(msg[1]);
                        $('.paginate').hide();
                    }else {
                        console.error(msg);
                    }
                },
                fail: function (error) {
                    console.log(error);
                }
            })
        })

    </script>
@endsection

@section('content')

    <!-- Content Row -->
    <div class="row">

        <div class="col-xl-12 col-md-12 mb-4 p-4 bg-white">
            <div class="d-flex flex-column text-center flex-md-row justify-content-md-between mb-4">
                <h5 class="font-weight-bold mb-3 mb-md-0">لیست دسته بندی ها ({{ $categories->total() }})</h5>
            </div>
            <div class="row d-lg-flex justify-content-between align-items-center">
                <div class="col-md-10 col-12 d-flex align-items-center">
                    <div class="form-group">
                        <label> جست و جو : </label>
                        <div class="input-group input-group-md d-flex flex-row-reverse border-radius">
                            <input type="text" class="form-control form-control-sm"
                                   aria-label="Sizing example input"
                                   aria-describedby="inputGroup-sizing-lg" placeholder="جست و جو..."
                                   id="SearchInput" autocomplete="off">
                            <div class="input-group-prepend border-radius">
                    <span class="input-group-text" id="basic-addon2"><i class="fa fa-search"
                                                                        aria-hidden="true"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-12">
                    <div class="d-lg-flex justify-content-end align-items-center">
                        <div>
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.categories.create') }}">
                                <i class="fa fa-plus"></i>
                                ایجاد دسته بندی
                            </a>
                        </div>
                    </div>
                </div>
            </div>


            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center">

                    <thead>
                    <tr>
                        <th>#</th>
                        <th>نام</th>
                        <th>اولویت</th>
                        <th>آیکن</th>
                        <th>تصویر</th>
                        <th>تصویر header</th>
                        <th>والد</th>
                        <th>وضعیت</th>
                        <th>نمایش در صفحه اصلی</th>
                        <th>عملیات</th>
                    </tr>
                    </thead>
                    <tbody id="insertRow">
                    @foreach ($categories as $key => $category)
                        <tr>
                            <th>
                                {{ $categories->firstItem() + $key }}
                            </th>
                            <th>
                                {{ $category->name }}
                            </th>
                            <th>
                                {{ $category->priority }}
                            </th>
                            <th>
                                <img class="img-thumbnail"
                                     src="{{ imageExist(env('CATEGORY_IMAGES_UPLOAD_PATH'),$category->image) }}">
                            </th>
                            <th>
                                <img class="img-thumbnail"
                                     src="{{ imageExist(env('CATEGORY_IMAGES_UPLOAD_PATH'),$category->banner_image) }}">
                            </th>
                            <th>
                                <img class="img-thumbnail"
                                     src="{{ imageExist(env('CATEGORY_IMAGES_UPLOAD_PATH'),$category->header_image) }}">
                            </th>
                            <th>
                                @if ($category->parent_id == 0)
                                    بدون والد
                                @else
                                    {{ $category->parent->name }}
                                @endif
                            </th>

                            <th>
                                    <span
                                        class="{{ $category->getRawOriginal('is_active') ? 'text-success' : 'text-danger' }}">
                                        {{ $category->is_active }}
                                    </span>
                            </th>
                            <th>
                                <a title="نمایش دسته‌بندی در صفحه اصلی" id="category_{{ $category->id }}" onclick="showOnIndex({{ $category->id }})"
                                   class="btn btn-sm {{ $category->showOnIndex==1 ? 'btn-success text-white' : 'btn-dark' }}">
                                    {{ $category->showOnIndex==1 ? 'فعال' : 'غیرفعال' }}
                                </a>
                            </th>
                            <th>
                                <a title="مشاهده" class="btn btn-sm btn-success"
                                   href="{{ route('admin.categories.show', ['category' => $category->id]) }}"><i
                                        class="fa fa-eye"></i></a>
                                <a title="ویرایش" class="btn btn-sm btn-info mr-3"
                                   href="{{ route('admin.categories.edit', ['category' => $category->id]) }}"><i
                                        class="fa fa-edit"></i></a>
{{--                                <button title="حذف" type="button" onclick="RemoveModal({{ $category->id }})"--}}
{{--                                        class="btn btn-sm btn-danger mr-3"--}}
{{--                                        href=""><i class="fa fa-trash"></i></button>--}}
                            </th>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row paginate">
                <div class="col-12">
                    <div class="row justify-content-center">
                        {{ $categories->render() }}
                    </div>
                </div>
            </div>
            <div id="overlay">
                <div class="spinner-border text-danger" style="width: 3rem; height: 3rem;"></div>
                <br/>
                Loading...
            </div>
        </div>
    </div>

    @include('admin.categories.modal')
    @include('admin.categories.alertModal')
@endsection
