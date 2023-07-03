@extends('home.layouts.index')

@section('title')
    {{ $article->title }}
@endsection


@section('description')

@endsection

@section('keywords')

@endsection

@section('style')
<style>
  img{
   margin:20px 0;
  }
  .sidebar-content{
      padding: 15px;
  }
  .post-details .post-title {
      white-space: inherit !important;
  }

  .main-content{
      padding: 0;
      background-color: white;
  }
  .post-details{
      padding: 25px !important;
  }
  .sticky-sidebar{
      padding: 5px;
  }
  .post-content p {
    overflow: inherit !important;
  }

</style>
@endsection

@section('script')

@endsection

@section('content')
    <!-- Start of Main -->
    <main class="search-page default space-top-30">
            <div class="container">
                <div class="row gutter-lg mb-10">
                    <div class="main-content col-12 col-md-6 col-lg-8">
                        <article class="mb-4">
                            <div class="post-details">
                                <div class="post-cats text-primary">
                                    <a href="{{ route('home.articles.category',['category'=>$article->Category->id]) }}">{{ $article->Category->title }} </a>
                                </div>
                                <div class="post-content">
                                    <p>
                                        {{ $article->shortDescription }}
                                    </p>
                                </div>
                            </div>
                            <figure class="post-media br-sm">
                                <img src="{{ imageExist(env('ARTICLES_IMAGES_UPLOAD_PATH'),$article->image)}}"
                                     width="930"
                                     height="500" alt="blog">
                            </figure>
                            <div class="post-details">
                                <div class="post-content">
                                    {!! $article->description !!}
                                </div>
                                <div class="post-meta">
                                    <span class="post-date">{{ verta($article->updated_at)->format('Y-m-d') }}</span>
                                </div>
                            </div>
                        </article>
                    </div>
                    <aside class="sidebar right-sidebar blog-sidebar sidebar-fixed sticky-sidebar-wrapper col-12 col-md-6 col-lg-4">
                        <div class="sidebar-content">
                            <div class="sticky-sidebar">
                                <!-- End of Widget search form -->
                                <div class="widget widget-categories">
                                    <h3 class="widget-title bb-no mb-0">دسته بندیها </h3>
                                    <ul class="widget-body filter-items search-ul">
                                        @foreach($article_categories as $article_category)
                                            <li>
                                                <a href="{{ route('home.articles.category',['category'=>$article_category->id]) }}">{{ $article_category->title }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <!-- End of Widget categories -->
                                <div class="widget widget-posts">
                                    <h3 class="widget-title bb-no">مقالات مرتبط</h3>
                                    <div class="widget-body">
                                        <div class="swiper">
                                            <div class="swiper-container swiper-theme nav-top" data-swiper-options="{
                                                    'spaceBetween': 20,
                                                    'slidesPerView': 1
                                                }">
                                                <div class="swiper-wrapper row cols-1">
                                                    <div class="swiper-slide widget-col">
                                                        @foreach($articles as $article)
                                                            <div class="post-widget mb-4">
                                                                <figure class="post-media br-sm">
                                                                    <a href="{{ route('home.article',['alias'=>$article->alias]) }}">
                                                                        <img
                                                                            src="{{ imageExist(env('ARTICLES_IMAGES_THUMBNAIL_UPLOAD_PATH'),$article->image)}}"
                                                                            alt="150" height="150"/>
                                                                    </a>
                                                                </figure>
                                                                <div class="post-details">
                                                                    <div class="post-meta">
                                                                        <a href="{{ route('home.article',['alias'=>$article->alias]) }}"
                                                                           class="post-date">{{ verta($article->updated_at)->format('Y-m-d') }}</a>
                                                                    </div>
                                                                    <h4 class="post-title">
                                                                        <a href="{{ route('home.article',['alias'=>$article->alias]) }}">{{ $article->title }}</a>
                                                                    </h4>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        <!-- End of Page Content -->
    </main>
    <!-- End of Main -->

@endsection
