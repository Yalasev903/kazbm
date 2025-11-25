<?php

namespace App\Helpers;

use App\Models\City;
use Illuminate\Support\Facades\Cache;

class SchemaHelper
{
    public static function generateBreadcrumbs($currentPage, $parents = [])
    {
        $currentCity = app('currentCity');
        $baseUrl = config('app.url');
        $generalSettings = app(\App\Filament\Settings\GeneralSettings::class);

        // Определяем название сайта в зависимости от раздела
        $siteName = $generalSettings->site_name ?? 'Гиперпрессованный кирпич';
        if (self::isOblicSection()) {
            $siteName = 'Облицовочный кирпич';
        }

        $breadcrumbs = [
            [
                'name' => $siteName,
                'url' => $baseUrl
            ]
        ];

        // Добавляем город, если не дефолтный
        if ($currentCity && !$currentCity->is_default) {
            $breadcrumbs[] = [
                'name' => $currentCity->name['ru'] ?? $currentCity->name,
                'url' => $baseUrl . '/' . $currentCity->slug
            ];
        }

        // Для облицовочного раздела добавляем ссылку на главную облицовочного
        if (self::isOblicSection() && $currentCity) {
            $oblicMainUrl = $currentCity->is_default ?
                url('/oblicovochnyy-kirpich') :
                url('/' . $currentCity->slug . '/oblicovochnyy-kirpich');

            $breadcrumbs[] = [
                'name' => 'Облицовочный кирпич',
                'url' => $oblicMainUrl
            ];
        }

        // Добавляем родителей с проверкой URL (только если они не дублируют уже добавленные)
        foreach ($parents as $parent) {
            if (isset($parent['name']) && isset($parent['url'])) {
                // Проверяем, не дублирует ли этот родитель уже существующий элемент
                $isDuplicate = false;
                foreach ($breadcrumbs as $existing) {
                    if ($existing['name'] === $parent['name'] || $existing['url'] === $parent['url']) {
                        $isDuplicate = true;
                        break;
                    }
                }

                if (!$isDuplicate) {
                    $breadcrumbs[] = $parent;
                }
            }
        }

        // Добавляем текущую страницу
        $breadcrumbs[] = [
            'name' => is_string($currentPage) ? $currentPage : 'Страница',
            'url' => url()->current()
        ];

        return $breadcrumbs;
    }

    public static function getProductSchema($product)
    {
        return view('components.schema-product', compact('product'))->render();
    }

    public static function getOrganizationSchema()
    {
        return Cache::remember('schema_organization', 3600, function () {
            return view('components.schema-organization')->render();
        });
    }

    public static function getLocalBusinessSchema()
    {
        return view('components.schema-local-business')->render();
    }

    public static function getOblicBusinessSchema($products = [])
    {
        return view('components.schema-oblic-business', compact('products'))->render();
    }

    public static function getOblicOrganizationSchema()
    {
        $generalSettings = app(\App\Filament\Settings\GeneralSettings::class);
        $currentCity = app('currentCity');

        return view('components.schema-oblic-organization', compact('generalSettings', 'currentCity'))->render();
    }

    public static function isOblicSection()
    {
        return request()->is('*oblicovochnyy-kirpich*') ||
            request()->is('*/oblicovochnyy-kirpich*');
    }
}
