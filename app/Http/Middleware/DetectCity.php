<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\City;
use Illuminate\Http\Request;

class DetectCity
{
    public function handle(Request $request, Closure $next)
    {
        // Игнорируем админку, filament и служебные маршруты
        if ($request->is('admin*') ||
            $request->is('filament*') ||
            $request->is('_debugbar*') ||
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

        // 🔑 Основное: ищем город в URL или в куки
        $citySlug = $request->route('city')
            ?? $request->cookie('selected_city')
            ?? null;

        // Если город не указан и путь не корень → редирект на дефолтный город
        if (!$citySlug && $request->path() != '/' && !empty($request->path())) {
            $defaultCity = City::where('is_default', true)->first();
            if ($defaultCity) {
                return redirect("/{$defaultCity->slug}/{$request->path()}");
            }
        }

        if ($citySlug) {
            $city = City::where('slug', $citySlug)->first();
            if (!$city) abort(404);
        } else {
            $city = City::where('is_default', true)->first() ?? City::first();
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
