@extends('layouts.app')
@section('page_title',(strlen($article->title) > 1 ? $article->title : ''))
@section('seo_title', (strlen($article->seo_title) > 1 ? $article->seo_title : ''))
@section('meta_keywords',(strlen($article->meta_keywords) > 1 ? $article->meta_keywords : ''))
@section('meta_description', (strlen($article->meta_description) > 1 ? $article->meta_description : ''))
@section('content')
    <main class="articlePage">
        <div class="container">
            @include('.components.breadcrumbs', [
                'title' => $article->title,
                'parents' => ['Статьи' => route('pages.get', 'articles')]
            ])
            <div class="titles">{{ $article->title }}</div>
            @if($article->image)
                <picture class="banner">
                    @if($photo = $article->getWebpFormat('image'))
                        <source srcset="{{$photo}}" type="image/webp">
                        <source srcset="{{$photo}}" type="image/pjp2">
                    @endif
                    <img src="{{ $article->getRealFormat('image') }}" alt="{{ $article->title }}">
                </picture>
            @endif
            <div class="block1">
                <div class="date">{{ $article->getPublishedAt() }}</div>
                <div class="desc">{!! $article->body !!}</div>
            </div>
            <div class="homePage">
                @include('components.articles.popular', ['title' => __("Похожие статьи")])
            </div>
            @include('layouts.footer')
        </div>
    </main>
@endsection
