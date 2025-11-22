@php(\Log::debug('category view vars', ['seoTitle' => $seoTitle ?? null, 'seo_title' => $seo_title ?? null]))
@extends('layouts.app')

@section('content')
    <main class="catalogPage">
        <div class="container">
            @include('.components.breadcrumbs', [
                'title' => __($category->name),
                'parents' => ['Каталог' => city_route('pages.city.get', ['slug' => 'catalog'])]
            ])
            <div class="banner">
                <!-- Оптимизированный SVG с предзагрузкой изображения -->
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="1380" height="339" viewBox="0 0 1380 339" fill="none" aria-hidden="true">
                    <path d="M0 20C0 8.95431 8.95431 0 20 0H1360C1371.05 0 1380 8.95431 1380 20V319C1380 330.046 1371.05 339 1360 339H477.5C466.454 339 457.5 330.046 457.5 319V251.5C457.5 240.454 448.546 231.5 437.5 231.5H20C8.9543 231.5 0 222.546 0 211.5V20Z" fill="url(#pattern0_279_22100)"></path>
                    <defs>
                        <pattern id="pattern0_279_22100" patternContentUnits="objectBoundingBox" width="1" height="1">
                            <use xlink:href="#image0_279_22100" transform="matrix(0.000555556 0 0 0.00226155 0 -1.24244)"></use>
                        </pattern>
                        <!-- WebP с fallback -->
                        <image id="image0_279_22100" width="1800" height="1201" xlink:href="{{ asset('images/catalog.png') }}">
                            <source srcset="{{ asset('images/catalog.webp') }}" type="image/webp">
                        </image>
                    </defs>
                </svg>
                <!-- Оптимизированное мобильное изображение -->
                <x-webp-image
                    src="images/catalog.png"
                    alt="catalog banner"
                    class="mobileBanner"
                    :lazy="true"
                    :width="352"
                    :height="235"
                    containerWidth="352"
                  containerHeight="235"
                />
                <div class="title"><b>{{__("Каталог")}}</b></div>
            </div>
            <div class="block2">
                <div class="title mainer">{{ $category->name }}</div>
                @include('components.catalog.filter')
                <div class="block2_prod">
                    <div class="head_box">
                        <div class="head"></div>
                        <div class="head_icon" onclick="openFilter()">
                            <svg width="22" height="20" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path d="M21 1H1L9 9.96484V16.1625L13 18.0578V9.96484L21 1Z" stroke="#3B3535" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="body catalogItems">
                        @include('components.catalog.items')
                    </div>
                    <div class="pogination_block">{{ $products->links() }}</div>
                </div>
            </div>
            <div class="block3"></div>
            @include('layouts.footer')
        </div>
    </main>
@endsection

{{-- @section('scripts')
<script type="text/javascript" defer>
// Улучшенная инициализация с предотвращением forced reflow
function initCatalogScripts() {
    localStorage.setItem('productQueryString', '');

    // Оптимизированная инициализация слайдера
    function initProductSliders() {
        const $sliders = $('.card .card_slider');

        // Используем requestAnimationFrame для предотвращения forced reflow
        requestAnimationFrame(() => {
            $sliders.slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                dots: true,
                arrows: false,
                infinite: false,
                focusOnSelect: false,
                variableWidth: false,
                lazyLoad: 'ondemand',
                waitForAnimate: false, // Добавляем для оптимизации
                responsive: [
                    {
                        breakpoint: 576,
                        settings: {
                            arrows: false,
                            waitForAnimate: false
                        },
                    },
                ],
            }).on('init afterChange', function(event, slick) {
                // Оптимизированная ленивая загрузка
                const $slider = $(this);
                const $activeSlide = $slider.find('.slick-active');

                // Загружаем только активное изображение сразу
                $activeSlide.find('img[data-lazy]').each(function() {
                    const $img = $(this);
                    const src = $img.attr('data-lazy');
                    if (src && !$img.attr('src').includes('data:image')) {
                        // Используем Image для предзагрузки
                        const preloadImage = new Image();
                        preloadImage.src = src;
                        preloadImage.onload = function() {
                            $img.attr('src', src).removeAttr('data-lazy');
                        };
                    }
                });
            });
        });
    }

    // Отложенная инициализация слайдеров
    function initSlidersWhenIdle() {
        if ('requestIdleCallback' in window) {
            requestIdleCallback(() => {
                initProductSliders();
            }, { timeout: 2000 });
        } else {
            // Fallback для старых браузеров
            setTimeout(initProductSliders, 1000);
        }
    }

    // Остальной код остается таким же...
    $(".clearFilterBtn").on('click', function (e) {
        e.preventDefault();
        ajaxRequest();
    });

    // Оптимизированная инициализация слайдера цены
    function initPriceSlider() {
        const $slider = $("#slider-range");
        if ($slider.length) {
            requestAnimationFrame(() => {
                $slider.slider({
                    range: true,
                    min: parseInt($slider.data('min')),
                    max: parseInt($slider.data('max')),
                    values: [parseInt($(".minVal").val()), parseInt($(".maxVal").val())],
                    slide: function(event, ui) {
                        $(".minVal").val(ui.values[0]);
                        $(".maxVal").val(ui.values[1]);
                        // Дебаунс для предотвращения частых запросов
                        clearTimeout(window.priceUpdateTimeout);
                        window.priceUpdateTimeout = setTimeout(() => {
                            updatePrice(ui.values[0], ui.values[1]);
                        }, 300);
                    }
                });
            });
        }
    }

    // Инициализируем слайдер цены после загрузки DOM
    initPriceSlider();

    // Остальные обработчики...
    $(".minVal, .maxVal").on('input', function (e) {
        $("#slider-range").slider("values", 0, $(".minVal").val());
        $("#slider-range").slider("values", 1, $(".maxVal").val());

        clearTimeout(window.priceUpdateTimeout);
        window.priceUpdateTimeout = setTimeout(() => {
            updatePrice($(".minVal").val(), $(".maxVal").val());
        }, 300);
    });

    // Инициализация слайдеров товаров после небольшой задержки
    setTimeout(initSlidersWhenIdle, 500);
}

// Запускаем когда DOM готов
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initCatalogScripts);
} else {
    setTimeout(initCatalogScripts, 100);
}
</script>
@endsection --}}
@section('scripts')
    <script type="text/javascript">
        $(function () {
            localStorage.setItem('productQueryString', '');

            // $(".applyFilterBtn").on('click', function (e) {
            //     e.preventDefault();
            //     let query = localStorage.getItem('productQueryString');
            //     ajaxRequest(query, 1)
            // })

            $(".clearFilterBtn").on('click', function (e) {
                e.preventDefault(); ajaxRequest();
            })

            $("#slider-range").slider({
                range: true,
                min: parseInt($( "#slider-range" ).data('min')),
                max: parseInt($( "#slider-range" ).data('max')),
                values: [ parseInt($( ".minVal" ).val()), parseInt($( ".maxVal" ).val()) ],
                slide: function( event, ui ) {
                    $( ".minVal" ).val(ui.values[0]);
                    $( ".maxVal" ).val(ui.values[1]);

                    updatePrice(ui.values[0], ui.values[1])
                }
            });
            $(".minVal, .maxVal").on('input', function (e) {
                $( "#slider-range" ).slider( "values", 0, $( ".minVal" ).val());
                $( "#slider-range" ).slider( "values", 1, $( ".maxVal" ).val());

                updatePrice($( ".minVal" ).val(), $( ".maxVal" ).val())
            });

$(".filter .checkbox input[type='checkbox']").on('change', function (e) {
    e.preventDefault();

    let sizes = [];
    $(".checkbox input[type='checkbox']:checked").each(function (index, item) {
        sizes.push(item.value);
    })

    let query = localStorage.getItem('productQueryString');
    let queryString = '';

    query = new URLSearchParams(query);

    if (query.has('price') && query.get('price')) {
        queryString += '&price=' + query.get('price');
    }

    if (query.has('color') && query.get('color')) {
        queryString += '&color=' + query.get('color');
    }

    if (sizes.length > 0) {
        queryString += '&size=' + sizes.join(',');
    }

    // Получаем город и добавляем его
    const citySlug = $('meta[name="city-slug"]').attr('content');
    if (citySlug) {
        queryString += '&city=' + citySlug;
    }

    ajaxRequest(queryString, 1)
})

$(".filter .elmt").on('click', function (e) {
    e.preventDefault();

    let query = localStorage.getItem('productQueryString');
    let queryString = '';

    query = new URLSearchParams(query);

    if (query.has('price') && query.get('price')) {
        queryString += '&price=' + query.get('price');
    }

    if (query.has('size') && query.get('size')) {
        queryString += '&size=' + query.get('size');
    }

    var color = query.has('color') ? parseInt(query.get('color')) : 0,
        currentValue = color === 0 ? $(this).data('id') : (color === $(this).data('id') ? 0 : $(this).data('id'));
    if (currentValue !== 0) {
        queryString += '&color=' + currentValue
    }

    // Получаем город и добавляем его
    const citySlug = $('meta[name="city-slug"]').attr('content');
    if (citySlug) {
        queryString += '&city=' + citySlug;
    }

    ajaxRequest(queryString, 1)
})

function updatePrice(fromPrice, toPrice) {
    let query = localStorage.getItem('productQueryString');
    let queryString = '';

    query = new URLSearchParams(query);

    if (query.has('color') && query.get('color')) {
        queryString += '&color=' + query.get('color');
    }

    if (query.has('size') && query.get('size')) {
        queryString += '&size=' + query.get('size');
    }

    queryString += '&price=' + fromPrice + ',' + toPrice;

    // Получаем город и добавляем его
    const citySlug = $('meta[name="city-slug"]').attr('content');
    if (citySlug) {
        queryString += '&city=' + citySlug;
    }

    ajaxRequest(queryString, 1)
}

            getPaginations();

function getPaginations() {
    let items = $(".pogination .pagination_item:not('.active')")
    if (items.length > 0) {
        items.each(function () {
            $(this).on('click', function (e) {
                e.preventDefault();

                // Получаем URL из data-href
                let fullUrl = $(this).data('href');
                let url = new URL(fullUrl);

                // Извлекаем номер страницы из параметра 'page'
                let page = url.searchParams.get('page') || 1;

                let query = localStorage.getItem('productQueryString');
                let queryString = '';

                if (query) {
                    query = new URLSearchParams(query);

                    if (query.has('price') && query.get('price')) {
                        queryString += '&price=' + query.get('price');
                    }

                    if (query.has('size') && query.get('size')) {
                        queryString += '&size=' + query.get('size');
                    }

                    if (query.has('color') && query.get('color')) {
                        queryString += '&color=' + query.get('color');
                    }
                }

                // 🔑 ДОБАВЛЯЕМ ГОРОД К ЗАПРОСУ
                const citySlug = $('meta[name="city-slug"]').attr('content');
                if (citySlug) {
                    queryString += '&city=' + citySlug;
                }

                ajaxRequest(queryString, page);
            });
        });
    }
}

            function updatePrice(fromPrice, toPrice) {
                let query = localStorage.getItem('productQueryString');
                let queryString = '';

                query = new URLSearchParams(query);

                if (query.has('color') && query.get('color')) {
                    queryString += '&color=' + query.get('color');
                }

                if (query.has('size') && query.get('size')) {
                    queryString += '&size=' + query.get('size');
                }

                queryString += '&price=' + fromPrice + ',' + toPrice;
                ajaxRequest(queryString, 1)
            }

            function ajaxRequest(queryString = '', page = 1) {
                // Получаем текущий город из мета-тега
                const citySlug = $('meta[name="city-slug"]').attr('content');

                // Добавляем город к queryString
                let fullQueryString = queryString;
                if (citySlug) {
                    fullQueryString += '&city=' + citySlug;
                }

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/ajax/filter/products?page=' + page + fullQueryString,
                    type: "GET",
                    success: function(response){
                        $(".catalogItems").html(response.html)
                        $(".catalogPage .pogination_block").html(response.paginate)
                        localStorage.setItem('productQueryString', response.query)
                        getPaginations()

                        $('.card .card_slider').slick({
                            slidesToShow: 1,
                            slidesToScroll: 1,
                            dots: true,
                            arrows: false,
                            infinite: false,
                            focusOnSelect: false,
                            variableWidth: false,
                            lazyLoad: 'ondemand',
                            responsive: [
                                {
                                    breakpoint: 576,
                                    settings: {
                                        arrows: false
                                    },
                                },
                            ],
                        }).on('init afterChange', function(event, slick) {
                            const $slider = $(this);
                            const $activeSlides = $slider.find('.slick-active');
                            const $nextSlides = $slider.find('.slick-active').next();
                            const $prevSlides = $slider.find('.slick-active').prev();

                            $activeSlides.add($nextSlides).add($prevSlides).find('img[data-lazy]').each(function() {
                                const $img = $(this);
                                const src = $img.attr('data-lazy');
                                if (src && !$img.attr('src').includes('data:image')) {
                                    $img.attr('src', src).removeAttr('data-lazy');
                                }
                            });
                        });
                    },
                    error: function(data) {
                        console.log(data)
                    }
                });
            }
        })
    </script>
@endsection
