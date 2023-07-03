@extends('admin.layouts.admin')

{{-- ===========  meta Title  =================== --}}
@section('title')
    ویرایش مقاله
@endsection
{{-- ===========  My Css Style  =================== --}}
@section('style')
    <style>
        .modal-header .close {
            margin: -1rem;
        }

        .modal-body > p, .form-group {
            padding: 0.25rem 0.5rem;
        }ro

         .noneDisplay {
             display: none !important;
         }

        .redBorder {
            border: 1px solid red;
        }

        #image{
            max-width: 200px;
            height: auto;
        }

    </style>
@endsection
{{-- ===========  My JavaScript  =================== --}}

@section('script')
    <script>
        // Show File Name
        $('#primary_image').change(function() {
            //get the file name
            var fileName = $(this).val();
            //replace the "Choose a file" label
            $(this).next('.custom-file-label').html(fileName);
        });
        CKEDITOR.replace( 'description' ,{
            language: 'fa',
            filebrowserUploadUrl: "{{route('upload', ['_token' => csrf_token() ])}}",
            filebrowserUploadMethod: 'form'
        });
    </script>
@endsection

@section('content')

    <!-- Content Row -->
    <div class="row">

        <div class="col-xl-12 col-md-12 mb-4 p-4 bg-white">
            <div class="mb-4 text-center text-md-right">
                <h5 class="font-weight-bold">ویرایش - {{ $article->title }}</h5>
            </div>
            <hr>

            @include('admin.sections.errors')

            <form action="{{ route('admin.articles.update', ['article' => $article->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="form-row">
                    <div class="form-group col-md-12 text-center">
                        <img id="image" class="img-profile" src="{{ asset(env('ARTICLES_IMAGES_UPLOAD_PATH').$article->image) }}">
                    </div>
                    <div class="form-group col-md-12 text-center">
                        <hr>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="name">نام</label>
                        <input class="form-control" id="name" name="name" type="text" value="{{ $article->title }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="name">alias</label>
                        <input class="form-control" id="alias" name="alias" type="text" value="{{ $article->alias }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="name">دسته‌بندی</label>
                        <select class="form-control" name="category_id" id="category_id">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $category->id==$article->category_id ? 'selected' : '' }}>{{ $category->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="primary_image"> ویرایش تصویر اصلی </label>
                        <div class="custom-file">
                            <input type="file" name="primary_image" class="custom-file-input" id="primary_image">
                            <label class="custom-file-label" for="primary_image"> انتخاب فایل </label>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="shortDescription">توضیحات کوتاه</label>
                        <textarea class="form-control" id="shortDescription" name="shortDescription" type="text">{{ $article->shortDescription }}</textarea>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="description">توضیحات</label>
                        <textarea class="form-control" id="description" name="description"
                                  rows="4">{!! $article->description !!}</textarea>
                    </div>
                </div>

                <button class="btn btn-outline-primary mt-5" type="submit">ویرایش</button>
                <a href="{{ route('admin.articles.index',['cat'=>0]) }}" class="btn btn-dark mt-5 mr-3">بازگشت</a>
            </form>
        </div>

    </div>

@endsection
