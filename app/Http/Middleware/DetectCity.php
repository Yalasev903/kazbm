<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\City;
use Illuminate\Http\Request;

class DetectCity
{
    public function handle(Request $request, Closure $next)
    {
        // 🔴 ДОБАВЬТЕ ЭТУ ПРОВЕРКУ В САМОЕ НАЧАЛО
        if ($request->is('ajax/*') || $request->is('api/*')) {
            $city = City::where('is_default', true)->first() ?? City::first();
            app()->instance('currentCity', $city);
            view()->share('currentCity', $city);

            $footerCity = City::where('slug', 'pavlodar')->first() ?? $city;
            app()->instance('footerCity', $footerCity);
            view()->share('footerCity', $footerCity);

            return $next($request);
        }
        // Игнорируем админку, filament и служебные маршруты
        if ($request->is('admin*') ||
            $request->is('filament*') ||
            $request->is('_debugbar*') ||
            $request->is('ajax*') ||
            (class_exists(\Filament\Facades\Filament::class) && \Filament\Facades\Filament::isServing())) {
            return $next($request);
        }

        if ($request->isMethod('post') ||
            $request->header('X-Livewire') ||
            $request->header('X-Filament')) {
            return $next($request);
        }

        $adminPaths = [
            'admin',
            'filament',
            '_debugbar',
            'livewire',
            'livewire-ui',
            'livewire-ui-modal',
            'api',
            'graphql',
            'horizon',
            'telescope',
            'vendor',
            'storage',
            'uploads'
        ];
        $currentPath = $request->path();

        foreach ($adminPaths as $adminPath) {
            if (str_starts_with($currentPath, $adminPath)) {
                return $next($request);
            }
        }

        if (str_contains($request->url(), '/admin/') ||
            str_contains($request->url(), '/filament/')) {
            return $next($request);
        }

        // Исключения, где город не нужен
        $path = $request->path();
        $exceptions = ['profile', 'ajax', 'forgot-password', 'order/invoice'];
        foreach ($exceptions as $ex) {
            if (str_starts_with($path, $ex)) {
                $city = City::where('is_default', true)->first() ?? City::first();
                app()->instance('currentCity', $city);
                view()->share('currentCity', $city);

                $footerCity = City::where('slug', 'pavlodar')->first() ?? $city;
                app()->instance('footerCity', $footerCity);
                view()->share('footerCity', $footerCity);

                return $next($request);
            }
        }

        // 🔑 ОСНОВНАЯ ЛОГИКА: Определяем город
        $citySlug = $request->route('city')
            ?? $request->cookie('selected_city')
            ?? null;

        // Если это главная страница (/) и есть город в куки - используем его
        if ($path === '/' && $request->cookie('selected_city')) {
            $citySlug = $request->cookie('selected_city');
        }

        // Если город не указан и путь не корень → редирект на дефолтный город
        if (!$citySlug && $request->path() != '/' && !empty($request->path())) {
            $defaultCity = City::where('is_default', true)->first();
            if ($defaultCity) {
                return redirect("/{$defaultCity->slug}/{$request->path()}");
            }
        }

        if ($citySlug) {
            $city = City::where('slug', $citySlug)->first();
            if (!$city) {
                // Если город не найден, используем дефолтный
                $city = City::where('is_default', true)->first() ?? City::first();
            }
        } else {
            $city = City::where('is_default', true)->first() ?? City::first();
        }

        // 🔑 ВАЖНО: Для главной страницы проверяем, нужно ли добавлять город в URL
        if ($path === '/' && $city && !$city->is_default) {
            // Если на главной странице выбран не дефолтный город - редиректим на URL с городом
            return redirect("/{$city->slug}");
        }

        if ($path === '/oblicovochnyy-kirpich' && $city && !$city->is_default) {
            // Если на странице облицовочного кирпича выбран не дефолтный город - редиректим на URL с городом
            return redirect("/{$city->slug}/oblicovochnyy-kirpich");
        }

        if ($path === '/oblicovochnyy-kirpich/contacts' && $city && !$city->is_default) {
            return redirect("/{$city->slug}/oblicovochnyy-kirpich/contacts");
        }
        // Шарим текущий город
        app()->instance('currentCity', $city);
        view()->share('currentCity', $city);

        // Для футера — всегда "Павлодар", если нет города в URL
        $footerCity = $city;
        $route = $request->route();
        if ($route && !$route->parameter('city')) {
            $footerCity = City::where('slug', 'pavlodar')->first() ?? $city;
        }

        app()->instance('footerCity', $footerCity);
        view()->share('footerCity', $footerCity);

        return $next($request);
    }
}
