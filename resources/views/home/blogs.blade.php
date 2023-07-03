@extends('home.layouts.index')

@section('title')
    مقالات
@endsection

@section('description')

@endsection

@section('keywords')

@endsection

@section('style')
    <style>
        .blog{
            padding: 20px;
        }
        .blog article{
            box-shadow: 0px 0px 4px black;
            padding: 14px;
        }
        .blog img{
            height: auto !important;
        }
    </style>
@endsection

@section('script')

@endsection

@section('content')
    <!-- Start of Main -->
    <main class="search-page default space-top-30">
        <div class="container">
                <div class="row mb-2">
                    @foreach($articles as $article)
                    <div class="blog col-12 col-md-4 col-lg-3">
                        <article class="post post-mask overlay-zoom br-sm">
                            <figure class="post-media">
                                <a href="{{ route('home.article',['alias'=>$article->alias]) }}">
                                    <img src="{{ imageExist(env('ARTICLES_IMAGES_THUMBNAIL_UPLOAD_PATH'),$article->image)}}" width="600"
                                         height="420" alt="blog">
                                </a>
                            </figure>
                            <div class="post-details">
                                <div class="post-details-visible">
                                    <div class="post-cats">
                                        <a href="#">{{ $article->Category->title }}</a>
                                    </div>
                                    <h4 class="post-title text-white">
                                        <a href="{{ route('home.article',['alias'=>$article->alias]) }}">{{ $article->title }}</a>
                                    </h4>
                                </div>
                                <div class="post-meta">
                                     <a href="{{ route('home.article',['alias'=>$article->alias]) }}" class="post-date">{{ verta($article->created_at)->format('Y-m-d') }}</a>
                                </div>
                            </div>
                        </article>
                    </div>
                    @endforeach
                </div>
                {{ $articles->render() }}
            </div>
        <!-- End of Page Content -->
    </main>
    <!-- End of Main -->
@endsection
