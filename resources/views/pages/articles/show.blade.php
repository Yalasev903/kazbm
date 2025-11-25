@extends('layouts.app')
@section('page_title',(strlen($article->title) > 1 ? $article->title : ''))
@section('seo_title', (strlen($article->seo_title) > 1 ? $article->seo_title : ''))
@section('meta_keywords',(strlen($article->meta_keywords) > 1 ? $article->meta_keywords : ''))
@section('meta_description', (strlen($article->meta_description) > 1 ? $article->meta_description : ''))
@section('schema')
    @php
        $schemaParents = [
            [
                'name' => 'Статьи',
                'url' => city_route('pages.city.get', ['slug' => 'articles'])
            ]
        ];
    @endphp
    {!! generate_schema_breadcrumbs($article->title, $schemaParents) !!}

    {{-- Дополнительная schema для статьи --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Article",
        "headline": "{{ $article->title }}",
        "description": "{{ strip_tags($article->description ?? $article->body) }}",
        "image": "{{ $article->getRealFormat('image') }}",
        "datePublished": "{{ $article->getPublishedAt() }}",
        "dateModified": "{{ $article->updated_at }}",
        "author": {
            "@type": "Organization",
            "name": "{{ $generalSettings->site_name }}"
        },
        "publisher": {
            "@type": "Organization",
            "name": "{{ $generalSettings->site_name }}",
            "logo": {
                "@type": "ImageObject",
                "url": "{{ Storage::url($generalSettings->logo) }}"
            }
        },
        "mainEntityOfPage": {
            "@type": "WebPage",
            "@id": "{{ url()->current() }}"
        }
    }
    </script>
@endsection
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
