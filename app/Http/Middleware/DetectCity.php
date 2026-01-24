<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\City;
use Illuminate\Http\Request;

class DetectCity
{
    public function handle(Request $request, Closure $next)
    {
        // 1. Игнорируем служебные маршруты, но устанавливаем дефолтный город для контейнера
        if ($request->is('_debugbar*') ||
            $request->is('vendor*') ||
            $request->is('storage*') ||
            $request->is('uploads*')) {
            $this->shareDefaultCity();
            return $next($request);
        }

        // 2. Для админки Filament
        if ($request->is('admin*') ||
            $request->is('filament*') ||
            (class_exists(\Filament\Facades\Filament::class) && \Filament\Facades\Filament::isServing())) {
            $this->shareDefaultCity();
            return $next($request);
        }

        // 3. Определяем город из разных источников
        $citySlug = $request->route('city');

        // Если в роуте нет города, пробуем достать из первого сегмента пути вручную
        if (!$citySlug) {
            $path = $request->path();
            $segments = explode('/', $path);
            if (count($segments) > 0 && !empty($segments[0])) {
                $potentialSlug = $segments[0];
                $systemPaths = ['oblicovochnyy-kirpich', 'about', 'our-products', 'contacts', 'catalog', 'articles', 'calculator', 'profile', 'ajax', 'set-city', 'set-lang'];
                if (!in_array($potentialSlug, $systemPaths)) {
                    if (City::where('slug', $potentialSlug)->exists()) {
                        $citySlug = $potentialSlug;
                    }
                }
            }
        }

        // Если города все еще нет, пробуем куки
        if (!$citySlug) {
            $citySlug = $request->cookie('selected_city');
        }

        // Если это AJAX/Livewire/POST и города все еще нет, пробуем Referer
        if (!$citySlug && ($request->ajax() || $request->header('X-Livewire') || $request->isMethod('post'))) {
            $referer = $request->header('referer');
            if ($referer) {
                $refererPath = parse_url($referer, PHP_URL_PATH);
                $refererSegments = explode('/', trim($refererPath, '/'));
                if (count($refererSegments) > 0 && !empty($refererSegments[0])) {
                    $potentialSlug = $refererSegments[0];
                    $systemPaths = ['oblicovochnyy-kirpich', 'about', 'our-products', 'contacts', 'catalog', 'articles', 'calculator', 'profile', 'ajax'];
                    if (!in_array($potentialSlug, $systemPaths)) {
                        if (City::where('slug', $potentialSlug)->exists()) {
                            $citySlug = $potentialSlug;
                        }
                    }
                }
            }
        }

        // 4. Находим модель города
        if ($citySlug) {
            $city = City::where('slug', $citySlug)->first();
        }

        if (!isset($city) || !$city) {
            $city = City::where('is_default', true)->first() ?? City::first();
        }

        // 5. Шарим город в контейнер и вьюхи
        app()->instance('currentCity', $city);
        view()->share('currentCity', $city);

        // Футер город (Павлодар по умолчанию для определенных условий)
        $footerCity = $city;
        // Если город не указан в URL, используем Павлодар для футера (как было в оригинале)
        if (!$request->route('city')) {
            $footerCity = City::where('slug', 'pavlodar')->first() ?? $city;
        }
        app()->instance('footerCity', $footerCity);
        view()->share('footerCity', $footerCity);

        // 6. Логика редиректов (ТОЛЬКО для обычных GET запросов)
        $isAjax = $request->ajax() || $request->header('X-Livewire') || $request->header('X-Requested-With') === 'XMLHttpRequest';
        $isGet = $request->isMethod('get');
        $path = $request->path();

        if ($isGet && !$isAjax) {
            // Исключения для редиректов
            $exceptions = ['profile', 'ajax', 'api', 'forgot-password', 'order/invoice', 'set-city', 'set-lang'];
            foreach ($exceptions as $ex) {
                if (str_starts_with($path, $ex)) {
                    return $next($request);
                }
            }

            // Если это корень (/) и выбран не дефолтный город - редирект на /slug
            if ($path === '/' && !$city->is_default) {
                return redirect("/{$city->slug}");
            }

            // Если город не в URL, но должен там быть (не дефолтный город)
            if (!$request->route('city') && !$city->is_default && $path !== '/') {
                // Проверяем, не является ли путь уже путем с городом (на всякий случай)
                $segments = explode('/', $path);
                if (!City::where('slug', $segments[0])->exists()) {
                    \Log::debug('DetectCity: Redirecting to city-prefixed path', ['city' => $city->slug, 'path' => $path]);
                    return redirect("/{$city->slug}/" . ltrim($path, '/'));
                }
            }
        }

        return $next($request);
    }

    private function shareDefaultCity()
    {
        $city = City::where('is_default', true)->first() ?? City::first();
        app()->instance('currentCity', $city);
        view()->share('currentCity', $city);

        $footerCity = City::where('slug', 'pavlodar')->first() ?? $city;
        app()->instance('footerCity', $footerCity);
        view()->share('footerCity', $footerCity);
    }
}
