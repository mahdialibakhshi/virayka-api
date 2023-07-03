@extends('admin.layouts.admin')

{{-- ===========  meta Title  =================== --}}
@section('title')
    لیست مقالات
@endsection
{{-- ===========  My Css Style  =================== --}}
@section('style')
    <style>
        .img-profile{
            max-width: 150px;
            height: auto;
        }
        td{
            vertical-align: middle !important;
        }
        .img-profile{
            width: 200px;
            height: auto;
        }
    </style>
@endsection
{{-- ===========  My JavaScript  =================== --}}
@section('script')
    <script>
        function categoryChange(tag) {
            let catId = tag.value;
            window.location.href = "/admin-panel/management/articles/index/" + catId;
        }
        function removeModal(id){
            let remove_modal=$('#remove_modal');
            $('#id').val(id);
            remove_modal.modal('show');
        }
    </script>
@endsection

{{-- ===========      CONTENT      =================== --}}
@section('content')

    <!-- Content Row -->
    <div class="row">

        <div class="col-xl-12 col-md-12 mb-4 p-4 bg-white">
            <div class="d-flex flex-column text-center flex-md-row justify-content-md-between mb-4">
                <h5 class="font-weight-bold mb-3 mb-md-0">لیست مقالات ({{ $articles->total() }})</h5>

                <div>
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.articles.create') }}">
                        <i class="fa fa-plus"></i>
                        ایجاد مقاله
                    </a>
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.articles.categories.index') }}">
                        <i class="fa fa-plus"></i>
                        دسته‌بندی مقالات
                    </a>
                </div>
            </div>
            <div class="form-group col-12">
                <hr>
            </div>
            <div class="form-group col-12">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <label for="name">دسته‌بندی</label>
                        <select onchange="categoryChange(this)" class="form-control" name="category_id" id="category_id">
                            <option value="">نمایش همه</option>
                        @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $category->id==$cat ? 'selected' : '' }}>{{ $category->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center">

                    <thead>
                    <tr>
                        <th>ردیف</th>
                        <th>نام</th>
                        <th>alias</th>
                        <th>دسته‌بندی</th>
                        <th>تصویر</th>
                        <th>عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($articles as $key => $item)
                        <tr>
                            <td>
                                {{ $articles->firstItem() + $key }}
                            </td>
                            <td>
                                {{ $item->title }}
                            </td>
                            <td>
                                {{ $item->alias }}
                            </td>
                            <td>
                                {{ $item->Category->title }}
                            </td>
                            <td>
                                <img  class="img-profile" src="{{ asset(env('ARTICLES_IMAGES_UPLOAD_PATH').$item->image) }}">
                            </td>

                            <td>
                                <a  class="btn btn-sm btn-primary"
                                    aria-haspopup="true" aria-expanded="false" href="{{ route('admin.articles.edit',['article'=>$item->id]) }}">
                                    <i class="fa fa-pen"></i>
                                </a>
                                <button onclick="removeModal({{ $item->id }})"  class="btn btn-sm btn-danger"
                                    aria-haspopup="true" aria-expanded="false" >
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-5">
                {{ $articles->render() }}
            </div>
        </div>
    </div>
    @include('admin.articles.modal')
@endsection
