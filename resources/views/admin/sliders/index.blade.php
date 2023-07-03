@extends('admin.layouts.admin')

@section('title')
    index slider
@endsection

@section('style')
    <style>
        .img-thumbnail{
            max-width: 200px;
            height: auto;
        }
        .border-none{
            border: none !important;
        }
    </style>
@endsection

@section('content')

    <!-- Content Row -->
    <div class="row">

        <div class="col-xl-12 col-md-12 mb-4 p-4 bg-white">
            <div class="d-flex flex-column text-center flex-md-row justify-content-md-between mb-4">
                <h5 class="font-weight-bold mb-3 mb-md-0">لیست اسلایدر ها ({{ $sliders->total() }})</h5>
                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.sliders.create') }}">
                    <i class="fa fa-plus"></i>
                    ایجاد اسلایدر
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>تصویر</th>
{{--                            <th>thumbnail</th>--}}
                            <th>عنوان</th>
                            <th>وضعیت</th>
                            <th>اولویت نمایش</th>
                            <th>متن</th>
                            <th>لینک دکمه</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sliders as $key => $slider)
                            <tr>
                                <th>
                                    {{ $sliders->firstItem() + $key }}
                                </th>
                                <th>
                                    <a target="_blank" href="{{ url( env('SLIDER_IMAGES_UPLOAD_PATH').$slider->image ) }}">
                                        <img class="img-thumbnail" src="{{ imageExist( env('SLIDER_IMAGES_UPLOAD_PATH'),$slider->image ) }}">
                                    </a>
                                </th>
{{--                                <th>--}}
{{--                                    <a target="_blank" href="{{ url( env('SLIDER_IMAGES_UPLOAD_PATH').$slider->image ) }}">--}}
{{--                                        <img class="img-thumbnail" src="{{ imageExist( env('SLIDER_IMAGES_UPLOAD_PATH'),$slider->thumbnail ) }}">--}}
{{--                                    </a>--}}
{{--                                </th>--}}
                                <th>
                                    {{ $slider->title }}
                                </th>
                                <th>
                                    {{ $slider->is_active==1 ? 'فعال' : 'غیرفعال' }}
                                </th>
                                <th>
                                    {{ $slider->priority }}
                                </th>
                                <th>
                                    {{ $slider->text }}
                                </th>
                                <th>
                                    {{ $slider->button_link }}
                                </th>
                                <th class="d-flex border-none">
                                    <form action="{{ route('admin.sliders.destroy', ['slider' => $slider->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-sm btn-danger" type="submit">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                    <a class="btn btn-sm btn-info mr-3"
                                        href="{{ route('admin.sliders.edit', ['slider' => $slider->id]) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                </th>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-5">
                {{ $sliders->render() }}
            </div>
        </div>
    </div>
@endsection
