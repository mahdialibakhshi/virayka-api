<div class="row">

    <div class="col-12">
        <div class="brand-slider card border_all bglight">
            <header class="card-header">
                <h3 class="card-title"><span>دسته بندی های برتر</span></h3>
            </header>
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        @foreach($active_categories as $category)
                            <div class="col-6 col-md-2 contact-bigicon">

                                <a href="{{ route('home.product_categories',['category'=>$category->id]) }}"
                                   target="_blank">
                                    <img class="img-responsive imgpad"
                                         src="{{ imageExist(env('CATEGORY_IMAGES_UPLOAD_PATH'),$category->header_image) }}"
                                         alt="{{ $category->name }}"/>
                                    <b class="title-3 light-black">{{ $category->name }}</b>
                                </a>

                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
