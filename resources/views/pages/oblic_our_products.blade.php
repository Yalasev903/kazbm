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

                // Используем правильные настройки для облицовочного кирпича
                $oblicOurProductSettings = app(\App\Filament\Settings\OblicOurProductSettings::class);
            @endphp

            <div class="titles">{{ $h1 ?? $page->title  }}</div>

            <x-webp-image
                src="{{ $oblicOurProductSettings->getRealFormat('hero_image') }}"
                alt="hero image"
                class="banner"
                :lazy="true"
            />
            <div class="banner_title">{{ $oblicOurProductSettings->hero_desc }}</div>

            <div class="block2">
                <div class="title">{{ $oblicOurProductSettings->feature_title }}</div>
                <div class="block2_box">
                    <div class="left">{!! $oblicOurProductSettings->feature_desc !!}</div>
                    <div class="right">
                        <x-webp-image
                            src="{{ $oblicOurProductSettings->getRealFormat('feature_photo') }}"
                            alt="feature image"
                            :lazy="true"
                        />
                    </div>
                </div>
                <div class="desc">
                    <p>Отформованные детали просушивают в пропарочном оборудовании на протяжении 10 часов либо в условиях склада, где кирпич сохраняет свои свойства на протяжении пяти дней. Наибольшую прочность материал получает в течение месяца при условии положительной температуры.</p>
                </div>
            </div>

            <div class="block3">
                <div class="title">{{ $oblicOurProductSettings->reason_title }}</div>
                {!! $oblicOurProductSettings->reason_desc !!}
            </div>

            <div class="block4">
                <div class="title">Выводы</div>
                <div class="desc"><p>{{ $oblicOurProductSettings->conclusion_text }}</p></div>
            </div>

            @include('layouts.footer')
        </div>
    </main>
@endsection
