@extends('layouts.app')
@section('page_title',(strlen($page->title) > 1 ? $page->title : ''))
@section('seo_title', (strlen($page->seo_title) > 1 ? $page->seo_title : ''))
@section('meta_keywords',(strlen($page->meta_keywords) > 1 ? $page->meta_keywords : ''))
@section('meta_description', (strlen($page->meta_description) > 1 ? $page->meta_description : ''))
@section('content')
    <main class="articlePage proKirpichPage">
        <div class="container">
            @include('components.breadcrumbs')
            <div class="titles">{{ $page->sub_title ?: $page->title }}</div>

            @php $ourProductSettings = app(\App\Filament\Settings\OurProductSettings::class) @endphp
            <picture class="banner">
                @if($heroPhoto = $ourProductSettings->getWebpFormat('hero_image'))
                    <source srcset="{{$heroPhoto}}" type="image/webp">
                    <source srcset="{{$heroPhoto}}" type="image/pjp2">
                @endif
                <img src="{{ $ourProductSettings->getRealFormat('hero_image') }}" alt="hero image">
            </picture>
            <div class="banner_title">{{ $ourProductSettings->hero_desc }}</div>
            <div class="block2">
                <div class="title">{{ $ourProductSettings->feature_title }}</div>
                <div class="block2_box">
                    <div class="left">{!! $ourProductSettings->feature_desc !!}</div>
                    <div class="right">
                        <picture>
                            @if($featurePhoto = $ourProductSettings->getWebpFormat('feature_photo'))
                                <source srcset="{{$featurePhoto}}" type="image/webp">
                                <source srcset="{{$featurePhoto}}" type="image/pjp2">
                            @endif
                            <img src="{{ $ourProductSettings->getRealFormat('feature_photo') }}" alt="feature image">
                        </picture>
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
