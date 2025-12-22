@extends('layouts.app')
@section('page_title',(strlen($page->title) > 1 ? $page->title : ''))
@section('seo_title', (strlen($page->seo_title) > 1 ? $page->seo_title : ''))
@section('meta_keywords',(strlen($page->meta_keywords) > 1 ? $page->meta_keywords : ''))
@section('meta_description', (strlen($page->meta_description) > 1 ? $page->meta_description : ''))

@section('schema')
    @php
        $parents = [
            [
                'name' => 'Облицовочный кирпич',
                'url' => city_route('oblic.city')
            ],
            [
                'name' => 'О компании',
                'url' => city_route('oblic.about.city')
            ]
        ];
    @endphp
    {!! generate_schema_breadcrumbs('О компании', $parents) !!}
    {!! generate_schema_oblic_business([]) !!}
@endsection

@section('content')
    <main class="aboutPage">
        <div id="left"><img src="{{ asset('images/icons/about_p.svg') }}"></div>
        <div class="container">
            @php
                $breadcrumbParents = [
                    [
                        'name' => 'Облицовочный кирпич',
                        'url' => city_route('oblic.city')
                    ]
                ];
                $bannerContent = \App\Services\CityContentService::getOblicAboutBannerContent();
                $productContent = \App\Services\CityContentService::getOblicAboutProductContent();
            @endphp
            @include('components.breadcrumbs', ['parents' => $breadcrumbParents, 'title' => $page->title])

            @if($bannerContent['title_' . App::getLocale()] ?? null)
                <div class="banner">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="1380" height="387" viewBox="0 0 1380 387" fill="none">
                        <path d="M0 20C0 8.9543 8.95431 0 20 0H1360C1371.05 0 1380 8.95431 1380 20V367C1380 378.046 1371.05 387 1360 387H471C459.954 387 451 378.046 451 367V208C451 196.954 442.046 188 431 188H20C8.95431 188 0 179.046 0 168V20Z" fill="url(#pattern0_279_22625)"></path>
                        <defs>
                            <pattern id="pattern0_279_22625" patternContentUnits="objectBoundingBox" width="1" height="1">
                                <use xlink:href="#image0_279_22625" transform="matrix(0.000769231 0 0 0.00274299 0 -0.423703)"></use>
                            </pattern>
                            <image id="image0_279_22625" width="1300" height="650" xlink:href="{{ $bannerContent['bg_image'] ?? '' }}"></image>
                        </defs>
                    </svg>
                    <div class="title">{!! $bannerContent['title_' . App::getLocale()] ?? '' !!}</div>
                </div>
            @endif

            <div class="min-container">
                @if($bannerContent['sub_title'] ?? null)
                    <div class="block2">
                        <div class="left">
                            <x-webp-image
                                src="{{ $bannerContent['photo'] ?? '' }}"
                                alt="{{ $bannerContent['desc_' . App::getLocale()] ?? '' }}"
                                :lazy="true"
                            />
                        </div>
                        <div class="right">
                            <div class="titles">{{ $bannerContent['sub_title'] ?? '' }}</div>
                            <div class="desc">{{ $bannerContent['desc_' . App::getLocale()] ?? '' }}</div>
                        </div>
                    </div>
                @endif

                @if($productContent['title'] ?? null)
                    <div class="block4">
                        <div class="left">
                            <x-webp-image
                                src="{{ $productContent['photo'] ?? '' }}"
                                alt="{{ $productContent['title'] ?? '' }}"
                                :lazy="true"
                            />
                        </div>
                        <div class="right">
                            <div class="title">{{ $productContent['title'] ?? __("Наша Продукция") }}</div>
                            <div class="desc">{{ $productContent['description'] ?? __('Облицовочный кирпич от ТОО "KAZBM" — это высококачественный, долговечный и эстетически привлекательный строительный материал, идеально подходящий для современного строительства.') }}</div>
                            <a class="btn" href="{{ city_route('pages.city.get', [
                                'city' => app()->get('currentCity')->slug ?? session('current_city_slug'),
                                'slug' => 'oblicovochnyy-kirpich/our-products'
                            ]) }}">{{__("Подробнее")}}</a>
                        </div>
                    </div>
                @endif

                @if($productItems = $productContent['items'] ?? null)
                <div class="block5">
                    <div class="left">
                        @foreach($productItems as $item)
                            <div class="item">
                                <div class="item_title">{{ __($item['name'] ?? '') }}</div>
                                <div class="item_desc">{{ __($item['desc'] ?? '') }}</div>
                            </div>
                        @endforeach
                    </div>
                    <div class="right">
                        <x-webp-image
                            src="{{ $productContent['item_photo'] ?? '' }}"
                            alt="item photo"
                            :lazy="true"
                        />
                    </div>
                </div>
            @endif
            </div>
            @include('layouts.footer')
        </div>
    </main>
@endsection
