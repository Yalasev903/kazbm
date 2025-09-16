@extends('layouts.app')
@section('page_title',(strlen($page->title) > 1 ? $page->title : ''))
@section('seo_title', (strlen($page->seo_title) > 1 ? $page->seo_title : ''))
@section('meta_keywords',(strlen($page->meta_keywords) > 1 ? $page->meta_keywords : ''))
@section('meta_description', (strlen($page->meta_description) > 1 ? $page->meta_description : ''))
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
                @php $productSettings = app(\App\Filament\Settings\About\ProductSettings::class) @endphp
                @if($productSettings->title)
                    <div class="block5_row1">
                        <picture>
                            <source srcset="{{ asset('images/hpb5_1.webp') }}" type="image/webp">
                            <source srcset="{{ asset('images/hpb5_1.webp') }}" type="image/pjp2">
                            <img src="{{ asset('images/hpb5_1.png') }}" alt="hpb5_1 image" loading="lazy">
                        </picture>
                        <div class="right">
                            <div class="titles">{{__("О компании kazbm")}}</div>
                            <div class="subTitle">{{ __("Наша Продукция")}}</div>
                            <div class="desc">{{__("Гиперпрессованный облицовочный кирпич от ТОО “KAZBM” — это высококачественный, долговечный и эстетически привлекательный строительный материал, идеально подходящий для современного строительства.") }}</div>
                        </div>
                    </div>
                @endif
                <picture class="full">
                    <source srcset="{{ asset('images/hpb5_2.webp') }}" type="image/webp">
                    <source srcset="{{ asset('images/hpb5_2.webp') }}" type="image/pjp2">
                    <img src="{{ asset('images/hpb5_2.png') }}" alt="hpb5_2 image" loading="lazy">
                </picture>
                <div class="block5_row2">
                    <div class="left">
                        @php $advantageSettings = app(\App\Filament\Settings\About\AdvantageSettings::class) @endphp
                        <div class="item">
                            <div class="title">{{ __("Высочайшая прочность")}} </div>
                            <div class="desc">{{ __("За счет наличия цемента и высокого давления при производстве. При таких условиях, по сути, происходит склеивание частиц, а сам процесс нередко называют холодной сваркой. Прочность колеблется в пределах М100-М400.")}} </div>
                        </div>

                        <div class="item">
                            <div class="title">{{ __("Низкое влагопоглащение")}} </div>
                            <div class="desc">{{ __("Высокая плотность обеспечивает этот вид кирпича не только высокой термостойкостью, но и отличной устойчивостью к внешним факторам, таким как дождь и различные химические растворы, что делает из него превосходный вариант для использования вне помещений;")}} </div>
                        </div>

                        <div class="item">
                            <div class="title">{{ __("Морозостойкость")}} </div>
                            <div class="desc">{{ __("Показатель морозостойкости – F-150. Это означает способность выдерживать 150 циклов замораживания-оттаивания. В средней полосе России это означает примерно 30 лет без признаков разрушения. То есть, такой кирпич в буквальном смысле слова будет как новый в течение 30 лет, и только по прошествии этого времени начнут появляться первые признаки разрушения.")}} </div>
                        </div>
                        <div class="item">
                            <div class="title">{{ __("Широкий ассортимент цветов")}} </div>
                            <div class="desc">{{ __("В настоящее время мы предлагаем четыре цветовые палитры для гиперпрессованного кирпича, однако мы готовы напечатать кирпич в любом цвете по Вашему запросу.")}} </div>
                        </div>
                    </div>
                    <div class="right">
                        <picture>
                            <source srcset="{{ asset('images/hpb5_3.webp') }}" type="image/webp">
                            <source srcset="{{ asset('images/hpb5_3.webp') }}" type="image/pjp2">
                            <img src="{{ asset('images/hpb5_3.png') }}" alt="hpb5_3 image" loading="lazy">
                        </picture>
                        <a class="btn" href="{{ route('pages.get', 'about') }}">{{__("Узнать больше")}}</a>
                    </div>
                </div>
            </div>
            @include('components.articles.popular', ['title' =>__("Популярные статьи")])
            @include('layouts.footer')
        </div>
    </main>
@endsection
