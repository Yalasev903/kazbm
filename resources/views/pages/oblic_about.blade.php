@extends('layouts.app')
@section('page_title',(strlen($page->title) > 1 ? $page->title : ''))
@section('seo_title', (strlen($page->seo_title) > 1 ? $page->seo_title : ''))
@section('meta_keywords',(strlen($page->meta_keywords) > 1 ? $page->meta_keywords : ''))
@section('meta_description', (strlen($page->meta_description) > 1 ? $page->meta_description : ''))
@section('content')
    <main class="aboutPage">
        <div id="left"><img src="{{ asset('images/icons/about_p.svg') }}"></div>
        <div class="container">
            @include('components.breadcrumbs')

            @if($bannerSettings->{'title_' . App::getLocale()})
                <div class="banner">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="1380" height="387" viewBox="0 0 1380 387" fill="none">
                        <path d="M0 20C0 8.9543 8.95431 0 20 0H1360C1371.05 0 1380 8.95431 1380 20V367C1380 378.046 1371.05 387 1360 387H471C459.954 387 451 378.046 451 367V208C451 196.954 442.046 188 431 188H20C8.95431 188 0 179.046 0 168V20Z" fill="url(#pattern0_279_22625)"></path>
                        <defs>
                            <pattern id="pattern0_279_22625" patternContentUnits="objectBoundingBox" width="1" height="1">
                                <use xlink:href="#image0_279_22625" transform="matrix(0.000769231 0 0 0.00274299 0 -0.423703)"></use>
                            </pattern>
                            <image id="image0_279_22625" width="1300" height="650" xlink:href="{{ $bannerSettings->getRealFormat('bg_image') }}"></image>
                        </defs>
                    </svg>
                    <div class="title">{!! $bannerSettings->{'title_' . App::getLocale()} !!}</div>
                </div>
            @endif

            <div class="min-container">
                @if($bannerSettings->sub_title)
                    <div class="block2">
                        <picture class="left">
                            @if($photo = $bannerSettings->getWebpFormat('photo'))
                                <source srcset="{{$photo}}" type="image/webp">
                                <source srcset="{{$photo}}" type="image/pjp2">
                            @endif
                            <img src="{{ $bannerSettings->getRealFormat('photo') }}" alt="{{ $bannerSettings->{'desc_' . App::getLocale()} }}">
                        </picture>
                        <div class="right">
                            <div class="titles">{{ $bannerSettings->sub_title }}</div>
                            <div class="desc">{{ $bannerSettings->{'desc_' . App::getLocale()} }}</div>
                        </div>
                    </div>
                @endif

                @if($productSettings->title)
                    <div class="block4">
                        <picture class="left">
                            @if($photo = $productSettings->getWebpFormat('photo'))
                                <source srcset="{{$photo}}" type="image/webp">
                                <source srcset="{{$photo}}" type="image/pjp2">
                            @endif
                            <img src="{{ $productSettings->getRealFormat('photo') }}" alt="{{ $productSettings->title }}" loading="lazy">
                        </picture>
                        <div class="right">
                            <div class="title">{{__("Наша Продукция")}}</div>
                            <div class="desc">{{__("Облицовочный кирпич от ТОО “KAZBM” — это высококачественный, долговечный и эстетически привлекательный строительный материал, идеально подходящий для современного строительства.") }}</div>
                            <a class="btn" href="{{ city_route('pages.city.get', [
                                'city' => app()->get('currentCity')->slug ?? session('current_city_slug'),
                                'slug' => 'oblicovochnyy-kirpich/our-products'
                            ]) }}">{{__("Подробнее")}}
                            </a>
                        </div>
                    </div>
                @endif

                @if($productItems = $productSettings->items)
                <div class="block5">
                    <div class="left">
                        @foreach($productItems as $item)
                            <div class="item">
                                {{-- Используем перевод через __() --}}
                                <div class="item_title">{{ __($item['name']) }}</div>
                                <div class="item_desc">{{ __($item['desc']) }}</div>
                            </div>
                        @endforeach
                    </div>
                    <picture class="right">
                        @if($photo = $productSettings->getWebpFormat('item_photo'))
                            <source srcset="{{ $photo }}" type="image/webp">
                            <source srcset="{{ $photo }}" type="image/pjp2">
                        @endif
                        <img src="{{ $productSettings->getRealFormat('item_photo') }}" alt="item photo" loading="lazy">
                    </picture>
                </div>
            @endif
            </div>
            @include('layouts.footer')
        </div>
    </main>
@endsection
