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

    // Генерация srcset для адаптивности
    $srcset = null;
    if (isset($sizes) && $width && $height) {
        if (! function_exists('__webp_generate_srcset')) {
            function __webp_generate_srcset($original, $webp, $baseWidth, $baseHeight, $hasWebp) {
                $sizesArr = [400, 800, 1200];
                $originalSrcset = [];
                $webpSrcset = [];
                foreach ($sizesArr as $size) {
                    if ($size <= $baseWidth) {
                        $ratio = $size / $baseWidth;
                        $newHeight = (int)($baseHeight * $ratio);
                        $resizedOriginal = __webp_get_resized_path($original, $size);
                        $resizedWebp = __webp_get_resized_path($webp, $size);
                        $originalSrcset[] = asset($resizedOriginal) . " {$size}w";
                        if ($hasWebp) {
                            $webpSrcset[] = asset($resizedWebp) . " {$size}w";
                        }
                    }
                }
                return [
                    'original' => implode(', ', $originalSrcset),
                    'webp' => implode(', ', $webpSrcset)
                ];
            }
        }
        if (! function_exists('__webp_get_resized_path')) {
            function __webp_get_resized_path($path, $size) {
                if (filter_var($path, FILTER_VALIDATE_URL)) {
                    return $path;
                }
                $rel = ltrim($path, '/');
                $info = pathinfo($rel);
                $dir = $info['dirname'] === '.' ? '' : $info['dirname'];
                $filename = $info['filename'];
                $ext = $info['extension'] ?? '';
                $resized = ($dir ? $dir . '/' : '') . $filename . '-' . $size . ($ext ? '.' . $ext : '');
                return $resized;
            }
        }
        $srcset = __webp_generate_srcset($src, $webpPath, $width, $height, $hasWebp);
    }
@endphp

@if($hasWebp && is_array($srcset) && !empty($srcset['webp']))
    <picture>
        <source srcset="{{ $srcset['webp'] }}" type="image/webp" sizes="{{ $sizes }}">
        <source srcset="{{ $srcset['original'] }}" type="{{ $mimeType }}" sizes="{{ $sizes }}">
        <img src="{{ asset($src) }}"
             @foreach($imgAttributes as $key => $value)
             {{ $key }}="{{ $value }}"
             @endforeach
             onload="this.classList.add('image-loaded')">
    </picture>
@elseif($hasWebp && empty($srcset))
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
