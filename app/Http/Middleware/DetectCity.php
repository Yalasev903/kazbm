<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\City;
use Illuminate\Http\Request;

class DetectCity
{
    public function handle(Request $request, Closure $next)
    {
        $path = $request->path();

        $exceptions = ['profile', 'ajax', 'forgot-password', 'order/invoice'];
        foreach ($exceptions as $ex) {
            if (str_starts_with($path, $ex)) {
                $city = City::where('is_default', true)->first() ?? City::first();
                app()->instance('currentCity', $city);
                view()->share('currentCity', $city);

                // Установка города для футера
                $footerCity = City::where('slug', 'pavlodar')->first() ?? $city;
                app()->instance('footerCity', $footerCity);
                view()->share('footerCity', $footerCity);

                return $next($request);
            }
        }

        $citySlug = $request->route('city') ?? null;

        if (!$citySlug && $request->path() != '/') {
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

        app()->instance('currentCity', $city);
        view()->share('currentCity', $city);

        // Определение города для футера
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
