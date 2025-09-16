<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@if(strlen($__env->yieldContent('seo_title')) > 2) @yield('seo_title') @else @yield('page_title') @endif</title>
    <meta name="description" content="@yield('meta_description')">
    <meta name="keywords" content="@yield('meta_keywords')">
    <meta name="title" content="@yield('seo_title')">
    @if(View::hasSection('seo'))
        @yield('seo')
    @endif
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function(m,e,t,r,i,k,a){
        m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
        m[i].l=1*new Date();
        for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
        k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)
    })(window, document,'script','https://mc.yandex.ru/metrika/tag.js?id=103848275', 'ym');

    ym(103848275, 'init', {ssr:true, webvisor:true, clickmap:true, ecommerce:"dataLayer", accurateTrackBounce:true, trackLinks:true});
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/103848275" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
    @php($favicon = $generalSettings->getRealFormat('favicon'))
    <link rel="preload" as="image" href="{{$favicon}}">
    <link rel="shortcut icon" type="image/svg" href="{{$favicon}}">
    
    {{-- Preload critical hero images --}}
    @php($heroSettings = app(\App\Filament\Settings\HeroSettings::class))
    @if($heroSettings->title && $heroSettings->photo)
        @if($heroWebp = $heroSettings->getWebpFormat('photo'))
            <link rel="preload" as="image" href="{{$heroWebp}}" type="image/webp">
        @endif
        <link rel="preload" as="image" href="{{ $heroSettings->getRealFormat('photo') }}">
    @endif
    
    {{-- Preload critical background SVGs --}}
    <link rel="preload" as="image" href="{{ asset('images/icons/hpb2_bg.svg') }}" type="image/svg+xml">
    <link rel="preload" as="image" href="{{ asset('images/icons/hpb5_bg.svg') }}" type="image/svg+xml">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link rel="stylesheet" href="{{ asset('css/style.css?v='. time()) }}">
    <link rel="stylesheet" href="{{ asset('css/dep.min.css?v='. time()) }}">
    <link rel="stylesheet" href="{{ asset('css/image-optimization.css?v='. time()) }}">
    <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="{{ asset('css/animate.min.css') }}" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="{{ asset('css/slick.min.css') }}" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="{{ asset('css/baguetteBox.min.css') }}" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="{{ asset('css/fancybox.min.css') }}" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.min.css') }}" media="print" onload="this.media='all'">
    @yield('styles')

<!-- Fallback that only gets inserted when JavaScript is disabled, in which case we can't load CSS asynchronously. -->
    <noscript>
        <link rel="stylesheet" href="{{ asset('css/dep.min.css') }}" media="all">
        <link rel="stylesheet" href="{{ asset('css/animate.min.css') }}" media="all">
        <link rel="stylesheet" href="{{ asset('css/slick.min.css') }}" media="all">
        <link rel="stylesheet" href="{{ asset('css/baguetteBox.min.css') }}" media="all">
        <link rel="stylesheet" href="{{ asset('css/fancybox.min.css') }}" media="all">
        <link rel="stylesheet" href="{{ asset('css/jquery-ui.min.css') }}" media="all">
        @yield('styles')
    </noscript>

</head>
<body id="app">
<div class="searchPlatform_bloor" onclick="closeSearch()"></div>
@include('layouts.header')

@yield('content')

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
</body>
</html>
