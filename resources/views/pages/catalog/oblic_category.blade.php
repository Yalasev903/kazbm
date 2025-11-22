@php(\Log::debug('category view vars', ['seoTitle' => $seoTitle ?? null, 'seo_title' => $seo_title ?? null]))
@extends('layouts.app')

{{-- @section('page_title', $page_title)
@section('seo_title', $seo_title)
@section('meta_description', $meta_description)
@section('meta_keywords', $meta_keywords) --}}

@section('content')
    <main class="catalogPage">
        <div class="container">
    <main class="catalogPage">
        <div class="container">
            @include('.components.breadcrumbs', [
                'title' => __($category->name),
                'parents' => ['Каталог' => city_route('pages.city.get', ['slug' => 'catalog'])]
            ])
            <div class="banner">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="1380" height="339" viewBox="0 0 1380 339" fill="none">
                    <path d="M0 20C0 8.95431 8.95431 0 20 0H1360C1371.05 0 1380 8.95431 1380 20V319C1380 330.046 1371.05 339 1360 339H477.5C466.454 339 457.5 330.046 457.5 319V251.5C457.5 240.454 448.546 231.5 437.5 231.5H20C8.9543 231.5 0 222.546 0 211.5V20Z" fill="url(#pattern0_279_22100)"></path>
                    <defs>
                        <pattern id="pattern0_279_22100" patternContentUnits="objectBoundingBox" width="1" height="1">
                            <use xlink:href="#image0_279_22100" transform="matrix(0.000555556 0 0 0.00226155 0 -1.24244)"></use>
                        </pattern>
                        <image id="image0_279_22100" width="1800" height="1201" xlink:href="{{ asset('images/catalog.png') }}"></image>
                    </defs>
                </svg>
                <img class="mobileBanner" src="{{ asset('images/catalog.png') }}" alt="catalog banner">
                <div class="title"><b>{{__("Каталог")}}</b></div>
            </div>
            <div class="block2">
                <div class="title mainer">{{ $category->name }}</div>
                @include('components.catalog.oblic_filter')
                <div class="block2_prod">
                    <div class="head_box">
                        <div class="head"></div>
                        <div class="head_icon" onclick="openFilter()">
                            <svg width="22" height="20" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M21 1H1L9 9.96484V16.1625L13 18.0578V9.96484L21 1Z" stroke="#3B3535" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="body catalogItems">
                        @include('components.catalog.oblic_items')
                    </div>
                    <div class="pogination_block">{{ $products->links() }}</div>
                </div>
            </div>
            <div class="block3"></div>
            @include('layouts.footer')
        </div>
    </main>
@endsection
@section('scripts')
<script type="text/javascript">
// 🔴 ЖДЕМ, ПОКА JQUERY ЗАГРУЗИТСЯ
function initCatalog() {
    // Catalog scripts initialized

    // 🔴 ГЛОБАЛЬНАЯ ФУНКЦИЯ AJAX REQUEST
    window.ajaxRequest = function(queryString = '', page = 1) {
    // AJAX request

        const citySlug = $('meta[name="city-slug"]').attr('content');
        let fullQueryString = queryString;

        if (citySlug && !fullQueryString.includes('city=')) {
            fullQueryString += (fullQueryString ? '&' : '') + 'city=' + citySlug;
        }

        let url = '/ajax/filter/products?page=' + page;
        if (fullQueryString) {
            if (fullQueryString.startsWith('&')) {
                fullQueryString = fullQueryString.substring(1);
            }
            url += '&' + fullQueryString;
        }

    // ajax URL built

        // Показываем индикатор загрузки
        $('.catalogItems').addClass('loading');

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: url,
            type: "GET",
            success: function(response){
                // response received
                $(".catalogItems").html(response.html);
                $(".catalogPage .pogination_block").html(response.paginate);
                localStorage.setItem('productQueryString', response.query || '');

                // Переинициализируем пагинацию после загрузки контента
                initializePagination();

                // Инициализируем слайдеры
                setTimeout(initProductSliders, 100);

                // Скрываем индикатор загрузки
                $('.catalogItems').removeClass('loading');

                // Прокручиваем к верху товаров
                $('html, body').animate({
                    scrollTop: $(".block2_prod").offset().top - 100
                }, 500);
            },
            error: function(xhr, status, error) {
                // ajax error
                $('.catalogItems').removeClass('loading');

                if (typeof toastr !== 'undefined') {
                    toastr.error('Произошла ошибка при загрузке товаров');
                }
            }
        });
    };

    // 🔴 БАЗОВАЯ ФУНКЦИЯ ДЛЯ ИНИЦИАЛИЗАЦИИ СЛАЙДЕРОВ
    function initProductSliders() {
    // initialize product sliders

        // Сначала уничтожаем все существующие слайдеры
        if ($.fn.slick) {
            $('.card .card_slider.slick-initialized').slick('unslick');

            // Затем инициализируем заново
            $('.card .card_slider').not('.slick-initialized').slick({
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
                            arrows: false,
                            dots: true
                        },
                    },
                ],
            });
        }
    }

    // 🔴 ФУНКЦИЯ ИНИЦИАЛИЗАЦИИ ПАГИНАЦИИ
 function initializePagination() {
    // initialize pagination

    // Удаляем старые обработчики
    $(document).off('click', '.pogination_block a, .pagination a');

    // Добавляем рабочий обработчик для всех ссылок пагинации
    $(document).on('click', '.pogination_block a, .pagination a', function(e) {
        e.preventDefault();
        e.stopPropagation();
    // pagination clicked

        // Пробуем получить data-href, если нет - берем обычный href
        let href = $(this).data('href') || $(this).attr('href');

        if (!href) {
            console.error('No href found in pagination link');
            return;
        }

    // pagination href

        // Извлекаем номер страницы из URL
        const url = new URL(href, window.location.origin);
        const page = url.searchParams.get('page') || 1;

    // loading page

        // Вызываем функцию напрямую
        if (typeof window.ajaxRequest === 'function') {
            window.ajaxRequest('', page);
        } else {
            console.error('ajaxRequest not found');
        }
    });

    // pagination initialized
}

    // 🔴 ОСНОВНОЙ КОД ПРИ ЗАГРУЗКЕ ДОКУМЕНТА
    $(document).ready(function () {
    // catalog scripts loaded
    localStorage.setItem('productQueryString', '');

        // 🔴 ИНИЦИАЛИЗАЦИЯ ПРИ ЗАГРУЗКЕ
        initializePagination();

    // Инициализируем слайдеры при загрузке
    requestAnimationFrame(initProductSliders);

        // 🔴 ОБРАБОТЧИКИ ФИЛЬТРОВ
        $(".clearFilterBtn").on('click', function (e) {
            e.preventDefault();
            // clear filter clicked
            window.ajaxRequest();
        });

        // Инициализация слайдера цены
        if ($("#slider-range").length && $.fn.slider) {
            $("#slider-range").slider({
                range: true,
                min: parseInt($("#slider-range").data('min')),
                max: parseInt($("#slider-range").data('max')),
                values: [parseInt($(".minVal").val()), parseInt($(".maxVal").val())],
                slide: function(event, ui) {
                    $(".minVal").val(ui.values[0]);
                    $(".maxVal").val(ui.values[1]);
                    updatePrice(ui.values[0], ui.values[1]);
                }
            });
        }

        // Обработчики для полей ввода цены
        $(".minVal, .maxVal").on('input', function (e) {
            if ($("#slider-range").length && $.fn.slider) {
                $("#slider-range").slider("values", 0, $(".minVal").val());
                $("#slider-range").slider("values", 1, $(".maxVal").val());
            }
            updatePrice($(".minVal").val(), $(".maxVal").val());
        });

        // Обработчик для чекбоксов размеров
        $(document).on('change', ".filter .checkbox input[type='checkbox']", function (e) {
            e.preventDefault();
            console.log('Size filter changed');

            let sizes = [];
            $(".checkbox input[type='checkbox']:checked").each(function (index, item) {
                sizes.push(item.value);
            });

            let query = localStorage.getItem('productQueryString');
            let queryString = '';

            if (query) {
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
            } else {
                if (sizes.length > 0) {
                    queryString += '&size=' + sizes.join(',');
                }
            }

            // Получаем город и добавляем его
            const citySlug = $('meta[name="city-slug"]').attr('content');
            if (citySlug) {
                queryString += '&city=' + citySlug;
            }

            console.log('Size filter query:', queryString);
            window.ajaxRequest(queryString, 1);
        });

        // Обработчик для цветов
        $(document).on('click', ".filter .elmt", function (e) {
            e.preventDefault();
            console.log('Color filter clicked');

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

                var color = query.has('color') ? parseInt(query.get('color')) : 0;
                var currentValue = color === 0 ? $(this).data('id') : (color === $(this).data('id') ? 0 : $(this).data('id'));

                if (currentValue !== 0) {
                    queryString += '&color=' + currentValue;
                }
            } else {
                var currentValue = $(this).data('id');
                if (currentValue !== 0) {
                    queryString += '&color=' + currentValue;
                }
            }

            // Получаем город и добавляем его
            const citySlug = $('meta[name="city-slug"]').attr('content');
            if (citySlug) {
                queryString += '&city=' + citySlug;
            }

            console.log('Color filter query:', queryString);
            window.ajaxRequest(queryString, 1);
        });

        function updatePrice(fromPrice, toPrice) {
            let query = localStorage.getItem('productQueryString');
            let queryString = '';

            if (query) {
                query = new URLSearchParams(query);

                if (query.has('color') && query.get('color')) {
                    queryString += '&color=' + query.get('color');
                }

                if (query.has('size') && query.get('size')) {
                    queryString += '&size=' + query.get('size');
                }
            }

            queryString += '&price=' + fromPrice + ',' + toPrice;

            // Получаем город и добавляем его
            const citySlug = $('meta[name="city-slug"]').attr('content');
            if (citySlug) {
                queryString += '&city=' + citySlug;
            }

            console.log('Price update query:', queryString);
            window.ajaxRequest(queryString, 1);
        }

        // Дополнительная проверка
        setTimeout(function() {
                // final check removed in production
        }, 2000);
    });
    $(document).on('click', '.pagination_item', function(e) {
    e.preventDefault();
    const href = $(this).data('href') || $(this).attr('href');
    if (!href) return;
    const url = new URL(href, window.location.origin);
    const page = url.searchParams.get('page') || 1;
    if (typeof window.ajaxRequest === 'function') {
        window.ajaxRequest('', page);
    }
});
}

// 🔴 ИСПРАВЛЕННЫЕ ОБРАБОТЧИКИ ФИЛЬТРОВ
function initializeFilters() {
    // initialize filters

    // Обработчик очистки фильтров
    $(".clearFilterBtn").off('click').on('click', function (e) {
        e.preventDefault();
    // clear filter clicked
        window.ajaxRequest();
    });

    // Инициализация слайдера цены
    if ($("#slider-range").length && $.fn.slider) {
        $("#slider-range").slider({
            range: true,
            min: parseInt($("#slider-range").data('min')),
            max: parseInt($("#slider-range").data('max')),
            values: [parseInt($(".minVal").val()), parseInt($(".maxVal").val())],
            slide: function(event, ui) {
                $(".minVal").val(ui.values[0]);
                $(".maxVal").val(ui.values[1]);
                updatePrice(ui.values[0], ui.values[1]);
            }
        });
    }

    // Обработчики для полей ввода цены
    $(".minVal, .maxVal").off('input').on('input', function (e) {
        if ($("#slider-range").length && $.fn.slider) {
            $("#slider-range").slider("values", 0, $(".minVal").val());
            $("#slider-range").slider("values", 1, $(".maxVal").val());
        }
        updatePrice($(".minVal").val(), $(".maxVal").val());
    });

    // Обработчик для чекбоксов размеров
    $(document).off('change', ".filter .checkbox input[type='checkbox']").on('change', ".filter .checkbox input[type='checkbox']", function (e) {
        e.preventDefault();
    // size filter changed

        let sizes = [];
        $(".checkbox input[type='checkbox']:checked").each(function (index, item) {
            sizes.push(item.value);
        });

        let query = localStorage.getItem('productQueryString');
        let queryString = '';

        if (query) {
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
        } else {
            if (sizes.length > 0) {
                queryString += '&size=' + sizes.join(',');
            }
        }

        // Получаем город и добавляем его
        const citySlug = $('meta[name="city-slug"]').attr('content');
        if (citySlug) {
            queryString += '&city=' + citySlug;
        }

            // size filter query ready
        window.ajaxRequest(queryString, 1);
    });

    // Обработчик для цветов
    $(document).off('click', ".filter .elmt").on('click', ".filter .elmt", function (e) {
        e.preventDefault();
            // color filter clicked

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

            var color = query.has('color') ? parseInt(query.get('color')) : 0;
            var currentValue = color === 0 ? $(this).data('id') : (color === $(this).data('id') ? 0 : $(this).data('id'));

            if (currentValue !== 0) {
                queryString += '&color=' + currentValue;
            }
        } else {
            var currentValue = $(this).data('id');
            if (currentValue !== 0) {
                queryString += '&color=' + currentValue;
            }
        }

        // Получаем город и добавляем его
        const citySlug = $('meta[name="city-slug"]').attr('content');
        if (citySlug) {
            queryString += '&city=' + citySlug;
        }

            // color filter query ready
        window.ajaxRequest(queryString, 1);
    });

    // filters initialized
}

// 🔴 ЗАПУСКАЕМ КОД ТОЛЬКО КОГДА JQUERY ГОТОВ
if (typeof jQuery === 'undefined') {
    // Ждем пока jQuery загрузится
    var checkJquery = setInterval(function() {
        if (typeof jQuery !== 'undefined') {
            clearInterval(checkJquery);
            initCatalog();
        }
    }, 100);
} else {
    initCatalog();
}
</script>
@endsection
