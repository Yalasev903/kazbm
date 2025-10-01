<?php

if (! function_exists('city_route')) {
    function city_route($name, $params = [])
    {
        $city = app('currentCity') ?? null;

        // Для главной страницы
        if ($name === 'home.city') {
            // Если город дефолтный - используем URL без города
            if ($city && $city->is_default) {
                return route('home');
            }
            // Если город не дефолтный - используем URL с городом
            return route('home.city', ['city' => $city->slug]);
        }

        // Для остальных страниц
        if ($city && !isset($params['city'])) {
            $params = array_merge(['city' => $city->slug], $params);
        }
        return route($name, $params);
    }
}
