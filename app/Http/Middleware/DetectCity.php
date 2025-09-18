<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\City;
use Illuminate\Http\Request;

class DetectCity
{
    public function handle(Request $request, Closure $next)
    {
        $citySlug = $request->route('city') ?? null;

        if (!$citySlug && $request->path() != '/') {
    $defaultCity = City::where('is_default', true)->first();
    if ($defaultCity) {
        return redirect("/{$defaultCity->slug}/{$request->path()}");
    }
}

        if ($citySlug) {
            $city = City::where('slug', $citySlug)->first();
            if (!$city) {
                abort(404);
            }
        } else {
            $city = City::where('is_default', true)->first();
            if (!$city) {
                $city = City::first(); // fallback
            }
        }

        if (!$citySlug && $request->path() != '/') {
        // Проверяем, не начинается ли путь с исключений (например, profile, ajax и т.д.)
        $exceptions = ['profile', 'ajax', 'forgot-password', 'order/invoice'];
        $path = $request->path();
        $shouldRedirect = true;
        foreach ($exceptions as $ex) {
            if (str_starts_with($path, $ex)) {
                $shouldRedirect = false;
                break;
            }
        }
        if ($shouldRedirect) {
            $defaultCity = City::where('is_default', true)->first();
            if ($defaultCity) {
                return redirect("/{$defaultCity->slug}/{$path}");
            }
        }
    }

        app()->instance('currentCity', $city);
        view()->share('currentCity', $city);

        return $next($request);
    }
}
