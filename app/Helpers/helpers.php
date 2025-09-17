<?php

if (! function_exists('city_route')) {
    function city_route($name, $params = [])
    {
        $city = app('currentCity') ?? null;
        if ($city && !isset($params['city'])) {
            $params = array_merge(['city' => $city->slug], $params);
        }
        return route($name, $params);
    }
}
