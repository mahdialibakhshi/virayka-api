@extends('admin.layouts.admin')

@section('title')
    edit products Images
@endsection

@section('script')
    <script>
        // Show File Name
        $('#primary_image').change(function() {
            //get the file name
            var fileName = $(this).val();
            //replace the "Choose a file" label
            $(this).next('.custom-file-label').html(fileName);
        });

        $('#images').change(function() {
            //get the file name
            var fileName = $(this).val();
            //replace the "Choose a file" label
            $(this).next('.custom-file-label').html(fileName);
        });

        function set_as_second_image(image_id) {
            let selector = '#set_as_second_image_' + image_id;
            $.ajax({
                url: "{{ route('admin.products.images.set_as_second_image') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    image_id: image_id,
                },
                dataType: "json",
                type: 'POST',
                beforeSend: function () {
                    $("#overlay").fadeIn();
                },
                success: function (msg) {
                    if (msg) {
                        if (msg[0] === 1) {
                            $(selector).parent().append(msg[1]);
                            $(selector).remove();
                        }
                        if (msg[0] === 0) {
                            swal({
                                title:'ERROR',
                                icon:'error',
                                text:msg[1],
                                buttons:'ok',
                            })
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


    </script>
@endsection

@section('content')

    <!-- Content Row -->
    <div class="row">

        <div class="col-xl-12 col-md-12 mb-4 p-5 bg-white">
            <div class="mb-4 text-center text-md-right">
                <h5 class="font-weight-bold">ویرایش تصاویر محصول : {{ $product->name }}</h5>
            </div>
            <hr>

            @include('admin.sections.errors')

            {{-- Show Primary Image --}}
            <div class="row">
                <div class="col-12 col-md-12 mb-5">
                    <h5>تصویر اصلی : </h5>
                </div>
                <div class="col-12 col-md-3 mb-5">
                    <div class="card">
                        <img class="card-img-top"
                             src="{{ imageExist(env('PRODUCT_IMAGES_UPLOAD_PATH'),$product->primary_image) }}"
                             alt="{{ $product->name }}">
                    </div>
                </div>
            </div>

            <hr>
            <div class="row">
                <div class="col-12 col-md-12 mb-5">
                    <h5>تصاویر : </h5>
                </div>
                @foreach ($product->images as $image)
                    <div class="col-md-3 mb-2">
                        <div class="card">
                            <img class="card-img-top" src="{{ imageExist(env('PRODUCT_IMAGES_UPLOAD_PATH'),$image->image) }}"
                                 alt="{{ $product->name }}">
                            <div class="card-body text-center">
                                <form action="{{ route('admin.products.images.destroy', ['product' => $product->id]) }}" method="post">
                                    @method('DELETE')
                                    @csrf
                                    <input type="hidden" name="image_id" value="{{ $image->id }}">
                                    <button class="btn btn-danger btn-sm mb-3" type="submit">حذف</button>
                                </form>
                                <form action="{{ route('admin.products.images.set_primary', ['product' => $product->id]) }}"
                                      method="post">
                                    @method('PUT')
                                    @csrf
                                    <input type="hidden" name="image_id" value="{{ $image->id }}">
                                    <button class="btn btn-primary btn-sm mb-3" type="submit">انتخاب به عنوان تصویر
                                        اصلی</button>
                                    <button onclick="set_as_second_image({{ $image->id }})"
                                            id="set_as_second_image_{{ $image->id }}"
                                            class="btn {{ $image->set_as_second_image==0?'btn-dark text-gray-500':'btn-success' }} btn-sm mb-3"
                                            type="button">
                                        انتخاب به عنوان تصویر دوم
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <hr>

            <form action="{{ route('admin.products.images.add', ['product' => $product->id]) }}" method="POST"
                  enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="primary_image"> انتخاب تصویر اصلی </label>
                        <div class="custom-file">
                            <input type="file" name="primary_image" class="custom-file-input" id="primary_image">
                            <label class="custom-file-label" for="primary_image"> انتخاب فایل </label>
                        </div>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="images"> انتخاب تصاویر </label>
                        <div class="custom-file">
                            <input type="file" name="images[]" multiple class="custom-file-input" id="images">
                            <label class="custom-file-label" for="images"> انتخاب فایل ها </label>
                        </div>
                    </div>
                </div>

                <button class="btn btn-outline-primary mt-5" type="submit">ویرایش</button>
                <a href="{{ $pre_url }}" class="btn btn-dark mt-5 mr-3">بازگشت</a>
            </form>
        </div>
    </div>

@endsection
