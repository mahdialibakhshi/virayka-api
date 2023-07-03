@extends('admin.layouts.admin')

@section('title')
    index categories
@endsection

@section('style')
    <style>
        .img-thumbnail{
            width: 75px;
            height: auto;
        }

        th{
            vertical-align: middle !important;
        }

    </style>
@endsection

@section('script')
<script>
    function RemoveModal(category_id){
        let modal=$('#remove_category_modal');
        modal.modal('show');
        $('#category_id').val(category_id);
    }
    function RemoveCategory(){
        let category_id=$('#category_id').val();
        let category_modal_alert=$('#category_modal_alert');
        $.ajax({
            url:"{{ route('admin.articles.categories.remove') }}",
            data:{
                _token:"{{ csrf_token() }}",
                category_id:category_id,
            },
            dataType:"json",
            type:'POST',
            beforeSend:function (){

            },
            success:function (msg){
                if (msg){
                    if (msg[0]==0){
                        let error=msg[1];
                        let items=msg[2];
                        let row='';
                        console.log(items);
                        $.each(items,function (i,item){
                            row+=`<div><a href="${item.link}">${item.name}</a></div>`;
                        })
                        $('#category_modal_text').html(error+row);
                        category_modal_alert.modal('show');
                    }
                    if (msg[0]==1){
                        let message=msg[1];
                        swal({
                            title:'باتشکر',
                            text:message,
                            icon:'success',
                            timer:3000,
                        })
                        window.location.reload();
                    }
                }
            },
        })
    }
</script>
@endsection

@section('content')

    <!-- Content Row -->
    <div class="row">

        <div class="col-xl-12 col-md-12 mb-4 p-4 bg-white">
            <div class="d-flex flex-column text-center flex-md-row justify-content-md-between mb-4">
                <h5 class="font-weight-bold mb-3 mb-md-0">لیست دسته بندی‌های مقالات ({{ $categories->total() }})</h5>
                <div>
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.articles.categories.create') }}">
                        <i class="fa fa-plus"></i>
                        ایجاد دسته بندی
                    </a>
                    <a class="btn btn-sm btn-outline-dark" href="{{ route('admin.articles.index') }}">
                        <i class="fa fa-arrow-left"></i>
                        لیست مقالات
                    </a>
                </div>

            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>نام</th>
                            <th>alias</th>
                            <th>ویرایش</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $key => $category)
                            <tr>
                                <th>
                                    {{ $categories->firstItem() + $key }}
                                </th>
                                <th>
                                    {{ $category->title }}
                                </th>
                                <th>
                                    {{ $category->alias }}
                                </th>
                                <th>
                                    <a class="btn btn-sm btn-info mr-3"
                                        href="{{ route('admin.articles.categories.edit', ['category' => $category->id]) }}">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <button onclick="RemoveModal({{ $category->id }})" type="button" class="btn btn-sm btn-danger mr-3">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </th>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-5">
                {{ $categories->render() }}
            </div>
        </div>
    </div>
    @include('admin.articles.categories.modal')
    @include('admin.articles.categories.alertModal')
@endsection
