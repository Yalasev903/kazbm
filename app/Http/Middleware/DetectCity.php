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

        app()->instance('currentCity', $city);
        view()->share('currentCity', $city);

        return $next($request);
    }
}
