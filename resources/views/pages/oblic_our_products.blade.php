@extends('layouts.app')
@section('page_title',(strlen($page->title) > 1 ? $page->title : ''))
@section('seo_title', (strlen($page->seo_title) > 1 ? $page->seo_title : ''))
@section('meta_keywords',(strlen($page->meta_keywords) > 1 ? $page->meta_keywords : ''))
@section('meta_description', (strlen($page->meta_description) > 1 ? $page->meta_description : ''))
@section('schema')
    @php
        $parents = [
            [
                'name' => 'Наша продукция',
                'url' => city_route('oblic.our-products.city')
            ]
        ];
    @endphp
    {!! generate_schema_breadcrumbs('Наша продукция', $parents) !!}
    {!! generate_schema_oblic_business($products ?? []) !!}
@endsection
@section('content')
    <main class="articlePage proKirpichPage">
        <div class="container">
                        @php
                $breadcrumbParents = [
                    [
                        'name' => 'Облицовочный кирпич',
                        'url' => city_route('oblic.city')
                    ]
                ];
            @endphp
            @include('components.breadcrumbs', ['parents' => $breadcrumbParents, 'title' => $page->title])

            @php
                $currentCity = app('currentCity');
                $cityH1 = $currentCity->getTranslation('h1', 'ru') ?? $currentCity->h1 ?? '';
                $mainTitle = $page->sub_title ?: $page->title;

                // Добавляем город к заголовку, если он есть
                if ($cityH1) {
                    $mainTitle .= ' ' . $cityH1;
                }

                // Используем CityContentService для получения контента с учётом города
                $oblicOurProductContent = \App\Services\CityContentService::getOblicOurProductContent();
            @endphp

            <div class="titles">{{ $h1 ?? $page->title  }}</div>

            <div class="banner">
                <x-webp-image
                    src="{{ $oblicOurProductContent['hero_image'] ?? '' }}"
                    alt="hero image"
                    :lazy="true"
                />
            </div>
            <div class="banner_title">{{ $oblicOurProductContent['hero_desc'] ?? '' }}</div>

            <div class="block2">
                <div class="title">{{ $oblicOurProductContent['feature_title'] ?? '' }}</div>
                <div class="block2_box">
                    <div class="left">{!! $oblicOurProductContent['feature_desc'] ?? '' !!}</div>
                    <div class="right">
                        <x-webp-image
                            src="{{ $oblicOurProductContent['feature_photo'] ?? '' }}"
                            alt="feature image"
                            :lazy="true"
                        />
                    </div>
                </div>
            <div class="desc">
                <p>{{ $oblicOurProductContent['process_desc'] ?? 'Отформованные детали просушивают в пропарочном оборудовании на протяжении 10 часов либо в условиях склада, где кирпич сохраняет свои свойства на протяжении пяти дней. Наибольшую прочность материал получает в течение месяца при условии положительной температуры.' }}</p>
            </div>
        </div>

            <div class="block3">
                <div class="title">{{ $oblicOurProductContent['reason_title'] ?? '' }}</div>
                {!! $oblicOurProductContent['reason_desc'] ?? '' !!}
            </div>

            <div class="block4">
                <div class="title">Выводы</div>
                <div class="desc"><p>{{ $oblicOurProductContent['conclusion_text'] ?? '' }}</p></div>
            </div>

            @include('layouts.footer')
        </div>
    </main>
@endsection
