@extends('layouts.app')
@section('page_title',(strlen($page->title) > 1 ? $page->title : ''))
@section('seo_title', (strlen($page->seo_title) > 1 ? $page->seo_title : ''))
@section('meta_keywords',(strlen($page->meta_keywords) > 1 ? $page->meta_keywords : ''))
@section('meta_description', (strlen($page->meta_description) > 1 ? $page->meta_description : ''))
@section('content')
    <main class="articlePage">
        <div class="container">
            @include('.components.breadcrumbs', [
                'title' => $page->title,
                'parents' => ['Статьи' => route('pages.get', 'articles')]
            ])
            <div class="titles">{{ $page->sub_title ?: $page->title }}</div>
            <div class="block1">
{{--                <div class="date">{{ $page->getPublishedAt() }}</div>--}}
                <div class="desc">{!! $page->description !!}</div>
            </div>
            @include('layouts.footer')
        </div>
    </main>
@endsection
