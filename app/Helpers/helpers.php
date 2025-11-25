<?php

if (! function_exists('city_route')) {
    function city_route($name, $params = [])
    {
        $city = app('currentCity') ?? null;

        // Если это Filament/админка, используем обычный route()
        if (request()->is('filament*') || request()->is('admin*')) {
            return route($name, $params);
        }

        // Обработка маршрута 'city'
        if ($name === 'city') {
            if ($city && $city->is_default) {
                return url('/');
            }
            return url('/' . $city->slug);
        }

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

        // Для страницы "О компании" гиперпрессованного кирпича
        if ($name === 'about.city') {
            if ($city && $city->is_default) {
                return url('/about');
            }
            return url('/' . $city->slug . '/about');
        }

        // Для страницы контактов гиперпрессованного кирпича
        if ($name === 'contacts.city') {
            if ($city && $city->is_default) {
                return url('/contacts');
            }
            return url('/' . $city->slug . '/contacts');
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

        try {
            return route($name, $params);
        } catch (\Exception $e) {
            // Fallback на базовый URL
            return url('/');
        }
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

if (! function_exists('webp_image')) {
    function webp_image($originalPath, $alt = '', $class = '', $attributes = [], $lazy = true)
    {
        // Если путь уже абсолютный URL
        if (str_starts_with($originalPath, 'http')) {
            return $originalPath;
        }

        // Получаем абсолютный путь к файлу
        $absolutePath = public_path($originalPath);

        // Генерируем WebP путь
        $webpPath = getWebpPath($originalPath);
        $absoluteWebpPath = public_path($webpPath);

        $attributesString = '';
        foreach ($attributes as $key => $value) {
            $attributesString .= " {$key}=\"{$value}\"";
        }

        if ($lazy) {
            $attributesString .= ' loading="lazy"';
        }

        // Если WebP существует - используем picture с fallback
        if (file_exists($absoluteWebpPath)) {
            return <<<HTML
<picture>
    <source srcset="{$webpPath}" type="image/webp">
    <source srcset="{$originalPath}" type="image/jpeg">
    <img src="{$originalPath}" alt="{$alt}" class="{$class}"{$attributesString}>
</picture>
HTML;
        }

        // Иначе обычный img
        return "<img src=\"{$originalPath}\" alt=\"{$alt}\" class=\"{$class}\"{$attributesString}>";
    }
}

if (! function_exists('getWebpPath')) {
    function getWebpPath($originalPath)
    {
        $pathInfo = pathinfo($originalPath);
        return $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.webp';
    }
}

if (! function_exists('generate_schema_organization')) {
    function generate_schema_organization()
    {
        return App\Helpers\SchemaHelper::getOrganizationSchema();
    }
}

if (! function_exists('generate_schema_product')) {
    function generate_schema_product($product)
    {
        return App\Helpers\SchemaHelper::getProductSchema($product);
    }
}

if (! function_exists('generate_schema_breadcrumbs')) {
    function generate_schema_breadcrumbs($currentPage)
    {
        $breadcrumbs = App\Helpers\SchemaHelper::generateBreadcrumbs($currentPage);
        return view('components.schema-breadcrumb', compact('breadcrumbs'))->render();
    }
}

if (! function_exists('generate_schema_local_business')) {
    function generate_schema_local_business()
    {
        return App\Helpers\SchemaHelper::getLocalBusinessSchema();
    }
}

if (! function_exists('generate_schema_oblic_business')) {
    function generate_schema_oblic_business($products = [])
    {
        return App\Helpers\SchemaHelper::getOblicBusinessSchema($products);
    }
}

if (! function_exists('generate_schema_oblic_organization')) {
    function generate_schema_oblic_organization()
    {
        return App\Helpers\SchemaHelper::getOblicOrganizationSchema();
    }
}
