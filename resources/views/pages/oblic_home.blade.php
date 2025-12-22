@extends('layouts.app')
@section('oblic_page_title', $seo['h1'] ?? 'Облицовочный кирпич')
@section('oblic_seo_title', $seo['seoTitle'] ?? 'Облицовочный кирпич')
@section('oblic_meta_keywords', $seo['seoKeywords'] ?? '')
@section('oblic_meta_description', $seo['seoDescription'] ?? '')

@section('schema')
    @php
        $parents = [
            [
                'name' => 'Облицовочный кирпич',
                'url' => city_route('oblic.city')
            ]
        ];
    @endphp
    {!! generate_schema_breadcrumbs('Главная', $parents) !!}
    {!! generate_schema_oblic_business($products ?? []) !!}
@endsection
@section('content')
    <main class="homePage">
        <img class="bg" src="{{ asset('images/icons/hpb2_bg.svg') }}" alt="hpb2 icon">
        <img class="bg2" src="{{ asset('images/icons/hpb5_bg.svg') }}" alt="hpb5 icon">
        <div class="container">
            @include('components.blocks.oblic_hero')
            @include('components.blocks.oblic_catalog')
            @include('components.blocks.calculator', ['icon_name' => 'home_w'])
            @include('components.blocks.oblic_advantage')
            <div class="block5">

                @php $productContent = \App\Services\CityContentService::getOblicAboutProductContent(); @endphp
                @if($productContent['title'] ?? null)
                    <div class="block5_row1">
                        @php
    $testPath = 'images/hpb5_1.png';
    $testWebp = getWebpPath($testPath);
    $webpExists = file_exists(public_path($testWebp));
@endphp
                            <x-webp-image
    src="{{ $productContent['photo'] ?? 'images/hpb5_1.png' }}"
    alt="hpb5_1 image"
    class="optimized-image"
    :width="542"
    :height="368"
    :lazy="true"
/>

                        <div class="right">
                            <div class="titles">{{ $productContent['title'] ?? __("О компании kazbm") }}</div>
                            <div class="subTitle">{{ __("Наша Продукция")}}</div>
                            <div class="desc">{{ $productContent['description'] ?? __("Облицовочный кирпич от ТОО “KAZBM” — это высококачественный, долговечный и эстетически привлекательный строительный материал, идеально подходящий для современного строительства.") }}</div>
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
                            $advantageContent = \App\Services\CityContentService::getOblicAdvantageContent();
                            $items = $advantageContent['items'] ?? [];
                        @endphp
                        @foreach($items as $item)
                            <div class="item">
                                <div class="title">{{ app()->getLocale() == 'kk' ? ($item['title_kk'] ?? $item['title_ru']) : $item['title_ru'] }}</div>
                                <div class="desc">{{ app()->getLocale() == 'kk' ? ($item['desc_kk'] ?? $item['desc_ru']) : $item['desc_ru'] }}</div>
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
                        <a class="btn" href="{{ city_route('pages.city.get', ['city' => app()->get('currentCity')->slug ?? session('current_city_slug'), 'slug' => 'oblicovochnyy-kirpich/about']) }}">{{ __("Узнать больше") }}</a>
                    </div>
                </div>
            </div>
            @include('components.articles.popular', ['title' =>__("Популярные статьи")])
            @include('layouts.footer')
        </div>
    </main>
@endsection
