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

        // Для страницы "О компании" облицовочного кирпича
        if ($name === 'oblic.about.city') {
            if ($city && $city->is_default) {
                return url('/oblicovochnyy-kirpich/about');
            }
            return url('/' . $city->slug . '/oblicovochnyy-kirpich/about');
        }

        // Для страницы "Наша продукция" облицовочного кирпича
        if ($name === 'oblic.our-products.city') {
            if ($city && $city->is_default) {
                return url('/oblicovochnyy-kirpich/our-products');
            }
            return url('/' . $city->slug . '/oblicovochnyy-kirpich/our-products');
        }

        // Для страницы контактов облицовочного кирпича
        if ($name === 'oblic.contacts.city') {
            if ($city && $city->is_default) {
                return url('/oblicovochnyy-kirpich/contacts');
            }
            return url('/' . $city->slug . '/oblicovochnyy-kirpich/contacts');
        }

        // Для калькулятора
        if ($name === 'calculator.city') {
            if ($city && $city->is_default) {
                return url('/calculator');
            }
            return url('/' . $city->slug . '/calculator');
        }

        // Для остальных страниц
        if ($city && !isset($params['city'])) {
            $params = array_merge(['city' => $city->slug], $params);
        }
        return route($name, $params);
    }
}

if (! function_exists('oblic_contacts_route')) {
    function oblic_contacts_route()
    {
        $city = app('currentCity') ?? null;

        if ($city && $city->is_default) {
            return url('/oblicovochnyy-kirpich/contacts');
        }
        return url('/' . $city->slug . '/oblicovochnyy-kirpich/contacts');
    }
}

// Добавьте эту новую функцию
if (! function_exists('oblic_about_route')) {
    function oblic_about_route()
    {
        $city = app('currentCity') ?? null;

        if ($city && $city->is_default) {
            return url('/oblicovochnyy-kirpich/about');
        }
        return url('/' . $city->slug . '/oblicovochnyy-kirpich/about');
    }
}

if (! function_exists('oblic_our_products_route')) {
    function oblic_our_products_route()
    {
        $city = app('currentCity') ?? null;

        if ($city && $city->is_default) {
            return url('/oblicovochnyy-kirpich/our-products');
        }
        return url('/' . $city->slug . '/oblicovochnyy-kirpich/our-products');
    }
}
