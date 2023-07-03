@extends('admin.layouts.admin')

{{-- ===========  meta Title  =================== --}}
@section('title')
    لیست صفحات
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
        .banner_page{
            width: 200px;
            height: auto;
        }
    </style>
@endsection
{{-- ===========  My JavaScript  =================== --}}
@section('script')
<script>
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
                <h5 class="font-weight-bold mb-3 mb-md-0">لیست صفحات ({{ $pages->total() }})</h5>

                <div>
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.pages.create') }}">
                        <i class="fa fa-plus"></i>
                        ایجاد صفحه
                    </a>
                </div>
            </div>
            <div class="form-group col-12">
                <hr>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center">

                    <thead>
                    <tr>
                        <th>ردیف</th>
                        <th>عنوان صفحه</th>
                        <th>اولویت نمایش</th>
                        <th>بنر بالای صفحه</th>
                        <th>عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($pages as $key => $item)
                        <tr>
                            <td>
                                {{ $pages->firstItem() + $key }}
                            </td>
                            <td>
                                {{ $item->title }}
                            </td>
                            <td>
                                {{ $item->priority }}
                            </td>
                            <td>
                                <img class="img-thumbnail banner_page" src="{{ imageExist(env('BANNER_PAGES_UPLOAD_PATH'),$item->image) }}">
                            </td>
                            <td>
                                <a  class="btn btn-sm btn-primary"
                                    aria-haspopup="true" aria-expanded="false" href="{{ route('admin.pages.edit',['page'=>$item->id]) }}">
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
                {{ $pages->render() }}
            </div>
        </div>
    </div>
    @include('admin.pages.modal')
@endsection
