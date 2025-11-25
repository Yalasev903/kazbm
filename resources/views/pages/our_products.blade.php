@extends('layouts.app')
@section('page_title',(strlen($page->title) > 1 ? $page->title : ''))
@section('seo_title', (strlen($page->seo_title) > 1 ? $page->seo_title : ''))
@section('meta_keywords',(strlen($page->meta_keywords) > 1 ? $page->meta_keywords : ''))
@section('meta_description', (strlen($page->meta_description) > 1 ? $page->meta_description : ''))
@section('schema')
    @php
        $parents = [
            [
                'name' => 'Гиперпрессованный кирпич',
                'url' => city_route('city')
            ]
        ];
    @endphp
    {!! generate_schema_breadcrumbs('Наша продукция', $parents) !!}
@section('content')
    <main class="articlePage proKirpichPage">
        <div class="container">
            @include('components.breadcrumbs')

            @php
                $currentCity = app('currentCity');
                $cityH1 = $currentCity->getTranslation('h1', 'ru') ?? $currentCity->h1 ?? '';
                $mainTitle = $page->sub_title ?: $page->title;

                // Добавляем город к заголовку, если он есть
                if ($cityH1) {
                    $mainTitle .= ' ' . $cityH1;
                }
            @endphp

            {{-- <div class="titles">{{ $page->sub_title ?: $page->title }}</div> --}}
            <div class="titles">{{ $mainTitle }}</div>
            @php $ourProductSettings = app(\App\Filament\Settings\OurProductSettings::class) @endphp
                <x-webp-image
                    src="{{ $ourProductSettings->getRealFormat('hero_image') }}"
                    class="banner"
                    alt="hero image"
                    :lazy="true"
                />
            <div class="banner_title">{{ $ourProductSettings->hero_desc }}</div>
            <div class="block2">
                <div class="title">{{ $ourProductSettings->feature_title }}</div>
                <div class="block2_box">
                    <div class="left">{!! $ourProductSettings->feature_desc !!}</div>
                    <div class="right">
                 <x-webp-image
                    src="{{ $ourProductSettings->getRealFormat('feature_photo') }}"
                    alt="feature image"
                    :lazy="true"
                />
                    </div>
                </div>
                <div class="desc">
                    <p>Отформованные детали просушивают в  пропарочном оборудовании на протяжении 10 часов либо в условиях склада,  где кирпич сохраняет свои свойства на протяжении пяти дней. Наибольшую  прочность материал получает в течение месяца при условии положительной  температуры.</p>
                </div>
            </div>
            <div class="block3">
                <div class="title">{{ $ourProductSettings->reason_title }}</div>
                {!! $ourProductSettings->reason_desc !!}
            </div>
            <div class="block4">
                <div class="title">Выводы</div>
                <div class="desc"><p>{{ $ourProductSettings->conclusion_text }}</p></div>
            </div>
            @include('layouts.footer')
        </div>
    </main>
@endsection
