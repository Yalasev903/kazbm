<?php

if (! function_exists('city_route')) {
    function city_route($name, $params = [])
    {
        $city = app('currentCity') ?? null;

        // Для главной страницы
        if ($name === 'home.city') {
            if ($city && $city->is_default) {
                return url('/');
            }
            return url('/' . $city->slug);
        }

        // Для страницы облицовочного кирпича
        if ($name === 'oblic.city') {
            if ($city && $city->is_default) {
                return url('/oblicovochnyy-kirpich');
            }
            return url('/' . $city->slug . '/oblicovochnyy-kirpich');
        }

        // Для остальных страниц
        if ($city && !isset($params['city'])) {
            $params = array_merge(['city' => $city->slug], $params);
        }
        return route($name, $params);
    }
}
