<div>
    <!-- Walk as if you are kissing the Earth with your feet. - Thich Nhat Hanh -->
@props([
    'src',
    'alt' => '',
    'class' => '',
    'lazy' => true,
    'width' => null,
    'height' => null,
    'fetchpriority' => 'auto',
])

@php
    // Оптимизация: проверяем WebP только в production
    if (app()->environment('production')) {
        $webpPath = getWebpPath($src);
        $hasWebp = file_exists(public_path($webpPath));
    } else {
        // В development всегда показываем WebP для тестирования
        $webpPath = getWebpPath($src);
        $hasWebp = true;
    }

    $extension = strtolower(pathinfo($src, PATHINFO_EXTENSION));
    $mimeTypes = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'svg' => 'image/svg+xml',
    ];
    $mimeType = $mimeTypes[$extension] ?? 'image/jpeg';

    // Формируем атрибуты
    $imgAttributes = [
        'alt' => $alt,
        'class' => $class . ' optimized-image',
        'decoding' => 'async'
    ];

    if ($width) $imgAttributes['width'] = $width;
    if ($height) $imgAttributes['height'] = $height;
    if ($lazy && $fetchpriority !== 'high') {
        $imgAttributes['loading'] = 'lazy';
    }
    if ($fetchpriority === 'high') {
        $imgAttributes['fetchpriority'] = 'high';
    }
@endphp

@if($hasWebp)
<picture>
    <source srcset="{{ asset($webpPath) }}" type="image/webp">
    <source srcset="{{ asset($src) }}" type="{{ $mimeType }}">
    <img src="{{ asset($src) }}"
         @foreach($imgAttributes as $key => $value)
         {{ $key }}="{{ $value }}"
         @endforeach
         onload="this.classList.add('image-loaded')">
</picture>
@else
<img src="{{ asset($src) }}"
     @foreach($imgAttributes as $key => $value)
     {{ $key }}="{{ $value }}"
     @endforeach
     onload="this.classList.add('image-loaded')">
@endif
</div>
