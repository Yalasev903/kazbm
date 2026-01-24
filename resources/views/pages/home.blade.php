@extends('layouts.app')
@section('page_title',(strlen($page->title) > 1 ? $page->title : ''))
@section('seo_title', (strlen($page->seo_title) > 1 ? $page->seo_title : ''))
@section('meta_keywords',(strlen($page->meta_keywords) > 1 ? $page->meta_keywords : ''))
@section('meta_description', (strlen($page->meta_description) > 1 ? $page->meta_description : ''))
@section('schema')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebSite",
    "name": "{{ $generalSettings->site_name }}",
    "url": "{{ config('app.url') }}",
    "potentialAction": {
        "@type": "SearchAction",
        "target": "{{ config('app.url') }}/search?q={search_term_string}",
        "query-input": "required name=search_term_string"
    }
}
</script>
@endsection
@section('content')
    <main class="homePage">
        <img class="bg" src="{{ asset('images/icons/hpb2_bg.svg') }}" alt="hpb2 icon">
        <img class="bg2" src="{{ asset('images/icons/hpb5_bg.svg') }}" alt="hpb5 icon">
        <div class="container">
            @include('components.blocks.hero')
            @include('components.blocks.catalog')
            @include('components.blocks.calculator', ['icon_name' => 'home_w'])
            @include('components.blocks.advantage')
            <div class="block5">

                @php $productContent = \App\Services\CityContentService::getAboutProductContent(); @endphp
                @if($productContent['title'] ?? null)
                    <div class="block5_row1">
                            <x-webp-image
                                src="{{ $productContent['photo'] ?? 'images/hpb5_1.png' }}"
                                alt="hpb5_1 image"
                                class="optimized-image"
                                :width="542"
                                :height="368"
                                :lazy="true"
                            />
                        <div class="right">
                            <div class="titles">{{ $productContent['title'] ?? __("О компании Kazbm") }}</div>
                            <!-- <div class="subTitle">{{ __("Наша Продукция")}}</div> -->
                            <div class="desc">{{ $productContent['description'] ?? __("Гиперпрессованный облицовочный кирпич от ТОО “KAZBM” — это высококачественный, долговечный и эстетически привлекательный строительный материал, идеально подходящий для современного строительства.") }}</div>
                        </div>
                    </div>
                @endif
                <x-webp-image
                    src="images/hpb5_2.png"
                    alt="hpb5_2 image"
                    class="full optimized-image"
                    :lazy="true"
                />
                <div class="block5_row2">
                    <div class="left">
                        @php
                            $advantageContent = \App\Services\CityContentService::getAdvantageContent();
                            $items = $advantageContent['items'] ?? [];
                        @endphp
                        @foreach($items as $item)
                            <div class="item">
                                <div class="title">{{ __($item['title']) }}</div>
                                <div class="desc">{{ $item['desc'] }}</div>
                            </div>
                        @endforeach
                    </div>
                    <div class="right">
                        <x-webp-image
                            src="images/hpb5_3.png"
                            alt="hpb5_3 image"
                            class="optimized-image"
                            :width="736"
                            :height="173"
                            :lazy="true"
                        />
                        <a class="btn" href="{{ route('pages.city.get', ['city' => app()->get('currentCity')->slug ?? session('current_city_slug'), 'slug' => 'about']) }}">{{ __("Узнать больше") }}</a>
                    </div>
                </div>
            </div>
            @include('components.articles.popular', ['title' =>__("Популярные статьи")])
            @include('layouts.footer')
        </div>
    </main>
@endsection
