<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $seoTitle ?? config('app.name') }}</title>
    <meta name="city-slug" content="{{ $currentCity->slug ?? '' }}">
    <meta name="description" content="{{ $seoDescription ?? ($page->meta_description ?? '') }}">
    <meta name="keywords" content="{{ $seoKeywords ?? ($page->meta_keywords ?? '') }}">

    @php
        $currentPath = request()->path();
        $segments = explode('/', $currentPath);
        if (count($segments) > 1 && \App\Models\City::where('slug', $segments[0])->exists()) {
            array_shift($segments);
        }
        $cleanPath = implode('/', $segments);
        $canonicalUrl = rtrim($canonicalBase . '/' . $cleanPath, '/');
    @endphp
    <link rel="canonical" href="{{ $canonicalUrl }}" />

    @if(View::hasSection('seo'))
        @yield('seo')
    @endif

    <!-- КРИТИЧЕСКИЙ CSS - встроенный для предотвращения CLS -->
    <style>
        /* Минимальные критические стили для первого экрана */
        .homePage,.container{position:relative}.bg,.bg2{position:absolute;z-index:-1}.bg{top:0;left:0}.bg2{bottom:0;right:0}
        .block1,.block3,.block4,.block5,.block6{margin-bottom:50px}.title{font-size:2rem;text-align:center}

        /* Предотвращение CLS для изображений */
        .optimized-image {
            max-width: 100%;
            height: auto;
            object-fit: cover;
        }

        /* Preloader стили */
        .preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #fff;
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .preloader svg {
            animation: pulse 1.5s ease-in-out infinite both;
        }
        @keyframes pulse {
            0% { transform: scale(0.8); opacity: 0.5; }
            50% { transform: scale(1); opacity: 1; }
            100% { transform: scale(0.8); opacity: 0.5; }
        }

        /* Базовые стили для заголовка чтобы не было CLS */
        .header { position: relative; min-height: 80px; }
        .catalogPage .banner { min-height: 200px; position: relative; }
        .calcPage .titles { min-height: 40px; }

        /* КРИТИЧЕСКИЙ CSS ДЛЯ КАТАЛОГА */
.catalogPage .banner {
    min-height: 200px;
    position: relative;
    overflow: hidden;
}

.catalogPage .block2_prod {
    opacity: 1;
    visibility: visible;
}

.card {
    position: relative;
    min-height: 400px;
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
}

.card .card_slider {
    position: relative;
    min-height: 200px;
}

.optimized-image {
    width: 100%;
    height: auto;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.image-loaded {
    opacity: 1;
}

/* Скелетон-загрузка для изображений */
.card .card_slider:not(.slick-initialized) {
    background: #f0f0f0;
    position: relative;
}

.card .card_slider:not(.slick-initialized)::after {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% { left: -100%; }
    100% { left: 100%; }
}

/* Локальные шрифты для ускорения загрузки */
@font-face {
    font-family: 'Montserrat';
    src: url('{{ asset('fonts/Montserrat-Regular.ttf') }}') format('truetype');
    font-display: swap;
    font-weight: 400;
}

@font-face {
    font-family: 'Montserrat';
    src: url('{{ asset('fonts/Montserrat-Bold.ttf') }}') format('truetype');
    font-display: swap;
    font-weight: 700;
}

@font-face {
    font-family: 'Montserrat';
    src: url('{{ asset('fonts/Montserrat-SemiBold.ttf') }}') format('truetype');
    font-display: swap;
    font-weight: 600;
}

body {
    font-family: 'Montserrat', Arial, sans-serif;
}
</style>
    <!-- Предзагрузка самых важных ресурсов -->
    @php($favicon = $generalSettings->getRealFormat('favicon'))
    <link rel="preload" as="image" href="{{$favicon}}" fetchpriority="high">
    <link rel="shortcut icon" type="image/svg" href="{{$favicon}}">

    <!-- Предзагрузка критических шрифтов -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Предзагрузка критических изображений -->
    @php($heroSettings = app(\App\Filament\Settings\HeroSettings::class))
    @if($heroSettings->title && $heroSettings->photo)
        @if($heroWebp = $heroSettings->getWebpFormat('photo'))
            <link rel="preload" as="image" href="{{$heroWebp}}" type="image/webp" fetchpriority="high">
        @endif
    @endif

    @if(request()->is('oblicovochnyy-kirpich*'))
        @php($oblicHeroSettings = app(\App\Filament\Settings\OblicHeroSettings::class))
        @if($oblicHeroSettings && $oblicHeroSettings->photo)
            @if($webpPath = $oblicHeroSettings->getWebpFormat('photo'))
                <link rel="preload" as="image" href="{{ $webpPath }}" type="image/webp" fetchpriority="high">
            @endif
        @endif
    @endif

    <!-- АСИНХРОННАЯ загрузка CSS с сохранением порядка -->
    <link rel="preload" href="{{ asset('css/style.css?v='. time()) }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="{{ asset('css/dep.min.css?v='. time()) }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="{{ asset('css/animate.min.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="{{ asset('css/slick.min.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">

    <!-- Отложенная загрузка некритичных CSS -->
    <link rel="preload" href="{{ asset('css/baguetteBox.min.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="{{ asset('css/fancybox.min.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="{{ asset('css/jquery-ui.min.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="{{ asset('css/toastr.min.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">

    <!-- Yandex.Metrika - отложенная загрузка с улучшениями -->
    <script>
        // Улучшенная отложенная загрузка метрики
        function loadYandexMetrika() {
            if (document.readyState === 'complete') {
                var script = document.createElement('script');
                script.src = 'https://mc.yandex.ru/metrika/tag.js';
                script.onload = function() {
                    (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
                    m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
                    (window, document, 'script', 'https://mc.yandex.ru/metrika/tag.js', 'ym');
                    ym(103848275, 'init', {ssr:true, webvisor:true, clickmap:true, ecommerce:"dataLayer", accurateTrackBounce:true, trackLinks:true});
                };
                document.head.appendChild(script);
            } else {
                window.addEventListener('load', loadYandexMetrika);
            }
        }

        // Запускаем после полной загрузки страницы
        window.addEventListener('load', function() {
            setTimeout(loadYandexMetrika, 3000); // Задержка 3 секунды после загрузки
        });
    </script>

</head>
<body id="app">
<div class="searchPlatform_bloor" onclick="closeSearch()"></div>
@include('layouts.header')

@yield('content')

<!-- Preloader с улучшенной анимацией -->
<div class="preloader">
    <svg width="95" height="96" viewBox="0 0 95 96" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path opacity="0.3" d="M41.9096 3.09672L2.5322 42.4686C-0.523154 45.5235 -0.523146 50.4765 2.53221 53.5314L41.9096 92.9033C44.965 95.9582 49.9186 95.9582 52.974 92.9033L92.3514 53.5314C95.4068 50.4765 95.4068 45.5235 92.3514 42.4686L52.974 3.09673C49.9187 0.0418036 44.965 0.0417938 41.9096 3.09672Z" fill="#F0EEE9"></path>
    </svg>
    <svg width="101" height="102" viewBox="0 0 101 102" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M44.4752 3.41534L2.74635 45.1383C-0.491456 48.3756 -0.49145 53.6244 2.74636 56.8617L44.4751 98.5847C47.713 101.822 52.9625 101.822 56.2003 98.5847L97.9291 56.8617C101.167 53.6244 101.167 48.3756 97.9291 45.1383L56.2003 3.41534C52.9625 0.177993 47.713 0.177984 44.4752 3.41534Z" fill="#CE2329"></path>
    </svg>
    <svg width="99" height="100" viewBox="0 0 99 100" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M44.6949 4.10308L3.37572 45.4164C0.843937 47.9479 0.84392 52.0521 3.3757 54.5836L44.6949 95.897C47.2267 98.4284 51.3315 98.4284 53.8633 95.8969L95.1825 54.5836C97.7143 52.0522 97.7143 47.9479 95.1825 45.4165L53.8633 4.10306C51.3315 1.57164 47.2267 1.57165 44.6949 4.10308Z" stroke="#F0EEE9" stroke-width="2.75487"></path>
    </svg>
</div>

@include('layouts.modals')
@include('layouts.scripts')

<!-- Fallback для отключенного JavaScript -->
<noscript>
    <link rel="stylesheet" href="{{ asset('css/baguetteBox.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fancybox.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css?v='. time()) }}">
    <link rel="stylesheet" href="{{ asset('css/dep.min.css?v='. time()) }}">
    <link rel="stylesheet" href="{{ asset('css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/slick.min.css') }}">
</noscript>

<!-- Улучшенный скрипт preloader -->
<script>
// Ускоренное скрытие preloader
document.addEventListener('DOMContentLoaded', function() {
    // Минимальная задержка для preloader
    const minDisplayTime = 500;
    const startTime = Date.now();

    function hidePreloader() {
        const elapsedTime = Date.now() - startTime;
        const remainingTime = Math.max(0, minDisplayTime - elapsedTime);

        setTimeout(function() {
            const preloader = document.querySelector('.preloader');
            if (preloader) {
                preloader.style.opacity = '0';
                preloader.style.transition = 'opacity 0.3s ease';
                setTimeout(() => {
                    if (preloader.parentNode) {
                        preloader.parentNode.removeChild(preloader);
                    }
                }, 300);
            }
        }, remainingTime);
    }

    // Пытаемся скрыть preloader как можно раньше
    if (document.readyState === 'complete') {
        hidePreloader();
    } else {
        window.addEventListener('load', hidePreloader);
        // Резервный таймаут на случай если load не сработает
        setTimeout(hidePreloader, 2000);
    }
});

// Register Service Worker
if ('serviceWorker' in navigator) {
  window.addEventListener('load', function() {
    navigator.serviceWorker.register('js/sw.js')
      .then(function(registration) {
        console.log('ServiceWorker registration successful');
      })
      .catch(function(err) {
        console.log('ServiceWorker registration failed: ', err);
      });
  });
}
</script>

</body>
</html>
