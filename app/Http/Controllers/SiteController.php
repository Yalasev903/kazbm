<?php

namespace App\Http\Controllers;

use App\Helpers\Basket;
use App\Models\Article;
use App\Models\Category;
use App\Models\Page;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\City;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

class SiteController extends Controller
{

    # Просмотр страниц
 # Просмотр страниц
    public function getPage(Request $request, $slug = '/')
    {
        $currentCity = app('currentCity');
        $isDefaultCity = $currentCity && $currentCity->is_default;

            // Принудительно устанавливаем город, если он не установлен
        if (!app()->has('currentCity')) {
            $defaultCity = City::where('is_default', true)->first() ?? City::first();
            app()->instance('currentCity', $defaultCity);
            view()->share('currentCity', $defaultCity);
        }

        $currentCity = app('currentCity');
        $isDefaultCity = $currentCity && $currentCity->is_default;

        // Обработка страницы контактов облицовочного кирпича
        if ($slug === 'oblicovochnyy-kirpich/contacts') {
            \Log::debug('Processing oblicovochnyy-kirpich/contacts page', [
                'city' => $currentCity->slug ?? 'none',
                'is_default' => $isDefaultCity
            ]);

            $page = Page::where('slug', 'oblicovochnyy-kirpich/contacts')
                        ->where('status', true)
                        ->first();

            if (!$page) {
                $page = new \stdClass();
                $page->type = 'oblic_contacts';
                $page->title = 'Контакты';
                $page->slug = 'oblicovochnyy-kirpich/contacts';
                $page->seo_title = $page->title;
                $page->meta_keywords = '';
                $page->meta_description = '';
            }

            $seo = $this->buildSeoForPage($currentCity, 'page', $page);
            $mergeData = $this->getParamsByPage($page, $request);

            \Log::debug('Oblic Contacts SEO data', $seo);

            view()->share($seo);
            return view("pages.$page->type", compact('page'), $mergeData);
        }

        // Обработка страницы "О компании" для облицовочного кирпича
        if ($slug === 'oblicovochnyy-kirpich/about') {
            \Log::debug('Processing oblicovochnyy-kirpich/about page', [
                'city' => $currentCity->slug ?? 'none',
                'is_default' => $isDefaultCity
            ]);

            $page = Page::where('slug', 'oblicovochnyy-kirpich/about')
                        ->where('status', true)
                        ->first();

            if (!$page) {
                $page = new \stdClass();
                $page->type = 'oblic_about';
                $page->title = 'О компании';
                $page->slug = 'oblicovochnyy-kirpich/about';
            }

            $seo = $this->buildSeoForPage($currentCity, 'page', $page);
            $mergeData = $this->getParamsByPage($page, $request);

            \Log::debug('Oblic About SEO data', $seo);

            view()->share($seo);
            return view("pages.$page->type", compact('page'), $mergeData);
        }

        if ($slug === 'oblicovochnyy-kirpich/our-products') {
            \Log::debug('Processing oblicovochnyy-kirpich/our-products page', [
                'city' => $currentCity->slug ?? 'none',
                'is_default' => $isDefaultCity
            ]);

            $page = Page::where('slug', 'oblicovochnyy-kirpich/our-products')
                        ->where('status', true)
                        ->first();

            if (!$page) {
                $page = new \stdClass();
                $page->type = 'oblic_our_products';
                $page->title = 'Наша продукция';
                $page->slug = 'oblicovochnyy-kirpich/our-products';
                // Добавьте недостающие свойства
                $page->seo_title = $page->title;
                $page->meta_keywords = '';
                $page->meta_description = '';
            }

            $seo = $this->buildSeoForPage($currentCity, 'page', $page);
            $mergeData = $this->getParamsByPage($page, $request);

            \Log::debug('Oblic Our Products SEO data', $seo);

            view()->share($seo);
            return view("pages.$page->type", compact('page'), $mergeData);
        }

        // Обработка страницы облицовочного кирпича
        if ($slug === 'oblicovochnyy-kirpich') {
            \Log::debug('Processing oblicovochnyy-kirpich page', [
                'city' => $currentCity->slug ?? 'none',
                'is_default' => $isDefaultCity
            ]);

            $page = Page::where('slug', 'oblicovochnyy-kirpich')
                        ->where('status', true)
                        ->first();

            if (!$page) {
                $page = new \stdClass();
                $page->type = 'oblic_home';
                $page->title = 'Облицовочный кирпич';
                $page->slug = 'oblicovochnyy-kirpich';
            }

            $seo = $this->buildSeoForPage($currentCity, 'page', $page);
            $mergeData = $this->getParamsByPage($page, $request);

            \Log::debug('Oblic SEO data', $seo);

            view()->share($seo);
            return view("pages.$page->type", compact('page'), $mergeData);
        }

            // Обработка страницы каталога облицовочного кирпича
            if ($slug === 'oblicovochnyy-kirpich/catalog') {
                $page = Page::where('slug', 'oblicovochnyy-kirpich/catalog')
                            ->where('status', true)
                            ->first();

                if (!$page) {
                    $page = new \stdClass();
                    $page->type = 'oblic_catalog';
                    $page->title = 'Каталог облицовочного кирпича';
                    $page->slug = 'oblicovochnyy-kirpich/catalog';
                    $page->seo_title = $page->title;
                    $page->meta_keywords = '';
                    $page->meta_description = '';
                }

                $seo = $this->buildSeoForPage($currentCity, 'page', $page);
                $mergeData = $this->getParamsByPage($page, $request);

                view()->share($seo);
                return view("pages.$page->type", compact('page'), $mergeData);
            }
        // Обработка страницы облицовочного кирпича
        // if ($slug === 'oblicovochnyy-kirpich') {
        //     $page = Page::where('slug', 'oblicovochnyy-kirpich')
        //                 ->where('status', true)
        //                 ->first();

        //     if (!$page) {
        //         // Если страницы нет в базе, создаем виртуальную
        //         $page = new \stdClass();
        //         $page->type = 'oblic_home';
        //         $page->title = 'Облицовочный кирпич';
        //         $page->slug = 'oblicovochnyy-kirpich';
        //     }

        //     $seo = $this->buildSeoForPage($currentCity, 'page', $page);
        //     $mergeData = $this->getParamsByPage($page, $request);

        //     view()->share($seo);
        //     return view("pages.$page->type", compact('page'), $mergeData);
        // }

        // Обработка главной страницы
        $slugToUse = $slug === '/' ? '/' : $slug;

        // Для главной страницы города по умолчанию используем тип 'home'
        // Для главной страницы других городов также используем тип 'home'
        if ($slugToUse === '/') {
            $page = Page::where('slug', '/')
                        ->where('status', true)
                        ->first();

            if (!$page) {
                $page = new \stdClass();
                $page->type = 'home';
                $page->title = 'Главная';
                $page->slug = '/';
            }
        } else {
            // Для остальных страниц ищем в базе
            $page = Page::where('slug', $slugToUse)
                        ->where('status', true)
                        ->firstOrFail();
        }

        if ($slug === 'catalog') {
            return redirect()->route('category.city.show', [
                'city' => app('currentCity')->slug,
                'slug' => 'giperpressovannyi-kirpic'
            ]);
        }

        $seo = $this->buildSeoForPage($currentCity, 'page', $page);
        $mergeData = $this->getParamsByPage($page, $request);

        view()->share($seo);

        return view("pages.$page->type", compact('page'), $mergeData);
    }

    public function getArticle($city, string $slug)
    {
        $currentCity = app('currentCity');

        $article = Article::query()
            ->where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();

        $articles = Article::query()
            ->select(['small_image', 'title', 'description', 'date', 'slug'])
            ->where('id', '<>', $article->id)
            ->where('status', true)
            ->orderByDesc('date')
            ->where('lang', App::getLocale())
            ->get();

        $seo = $this->buildSeoForPage($currentCity, 'article', $article);

        $seo['h1'] = 'Статьи в ' . ($currentCity->seo_title ?? $currentCity->name);

        view()->share($seo);

        return view("pages.articles.show", compact('article', 'articles'));
    }


    public function getCategory(string $slug, Request $request)
    {
        $currentCity = app('currentCity');

        // Получаем slug из маршрута (как раньше)
        $slug = $request->route('slug');

        // Формируем ключ кэша с учётом города, slug и query string
        $cityId = $currentCity->id ?? 'no_city';
        $cacheKey = "category_{$slug}_{$cityId}_" . md5($request->getQueryString() ?? '');

        $data = Cache::remember($cacheKey, 7200, function() use ($slug, $request, $currentCity) {
            $category = Category::where('slug', $slug)
                        ->where('status', true)
                        ->firstOrFail();

            $colors = ProductColor::query()->select(['id', 'image', 'name'])->get();
            $mergeData = (new Product)->getCatalogData($request, $category->id);

            $seo = $this->buildSeoForPage($currentCity, 'category', $category);

            // Нормализация значений с безопасными fallback
            $seoTitle = $seo['seoTitle'] ?? $category->seo_title ?? $category->name ?? config('app.name');
            $seoDescription = $seo['seoDescription'] ?? $category->meta_description ?? '';
            $seoKeywords = $seo['seoKeywords'] ?? $category->meta_keywords ?? '';
            $h1 = $seo['h1'] ?? $category->title ?? $category->name ?? '';

            $viewVars = [
                'seoTitle' => $seoTitle,
                'seoDescription' => $seoDescription,
                'seoKeywords' => $seoKeywords,
                'h1' => $h1,

                // legacy / snake_case
                'seo_title' => $seoTitle,
                'meta_description' => $seoDescription,
                'meta_keywords' => $seoKeywords,
                'page_title' => $seoTitle,
            ];

            // Возвращаем также оригинальный массив $seo для view()->share
            return array_merge(compact('category', 'colors'), $mergeData, $viewVars, ['seo_raw' => $seo]);
        });

        // Шарим SEO-данные для layout (если в кэше есть)
        if (isset($data['seo_raw'])) {
            view()->share($data['seo_raw']);
            unset($data['seo_raw']);
        }

        return view('pages.catalog.category', $data);
    }
    public function getProduct(Request $request)
    {

        $currentCity = app('currentCity');

        $categorySlug = $request->route('category');
        $productSlug = $request->route('slug');

        $product = Product::with('category')
                    ->where('slug', $productSlug)
                    ->where('status', true)
                    ->firstOrFail();

        if($product->category->slug !== $categorySlug) abort(404);

         $seo = $this->buildSeoForProduct($currentCity, $product);
         view()->share($seo);

        return view('pages.products.show', compact('product'));
    }

    public function setCity(Request $request)
    {
        $citySlug = $request->input('city');
        $city = City::where('slug', $citySlug)->first();

        if (!$city) {
            return response()->json(['error' => 'Город не найден'], 404);
        }

        // Устанавливаем куки на 30 дней (30*24*60 минут)
        $minutes = 30 * 24 * 60;
        Cookie::queue('selected_city', $city->slug, $minutes);

        return response()->json([
            'success' => true,
            'city' => $city->name,
            'is_default' => $city->is_default
        ]);
    }

private function getParamsByPage($page, $request)
{
    switch ($page->slug) {
        case 'checkout':
            return ['cart' => new Basket];
        case 'cart':
            return ['items' => (new Basket)->getContent()];
        case 'search':
            return (new Product)->getCatalogData($request);
        case 'catalog':
            $catalogData = (new Product)->getCatalogData($request);
            return array_merge([
                'colors' => ProductColor::query()->select(['id', 'image', 'name'])->get()
            ], $catalogData);
        case 'articles':
            $articles = (new Article)->getList();
            return compact('articles');
        case 'oblicovochnyy-kirpich':
            $oblicCategory = Category::where('slug', 'oblicovochnyy-kirpich')->first();
            $products = collect();
            if ($oblicCategory) {
                $products = Product::query()
                    ->where('category_id', $oblicCategory->id)
                    ->where('stock', '<>', 0)
                    ->where('status', true)
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
            return [
                'articles' => (new Article)->getPopular(),
                'products' => $products
            ];
        case 'oblicovochnyy-kirpich/contacts':
            return [
                // Можно добавить специфичные данные для контактов облицовочного кирпича
            ];
        case 'oblicovochnyy-kirpich/about':
            // Передаем настройки для страницы "О компании" облицовочного кирпича
            return [
                'bannerSettings' => app(\App\Filament\Settings\OblicAboutBannerSettings::class),
                'productSettings' => app(\App\Filament\Settings\OblicAboutProductSettings::class),
                'advantageSettings' => app(\App\Filament\Settings\OblicAdvantageSettings::class),
            ];
                    case 'oblicovochnyy-kirpich/our-products':
            // Выбираем продукты для раздела "Наша продукция" облицовочного кирпича
            $oblicCategory = Category::where('slug', 'oblicovochnyy-kirpich')->first();
            $products = collect();
            if ($oblicCategory) {
                $products = Product::query()
                    ->select(['title', 'photo', 'price', 'size_id', 'color_id', 'category_id', 'data', 'slug'])
                    ->where('category_id', $oblicCategory->id)
                    ->where('stock', '<>', 0)
                    ->where('status', true)
                    ->orderBy('created_at', 'desc')
                    ->get();
            }

            return [
                'ourProductSettings' => app(\App\Filament\Settings\OurProductSettings::class),
                'products' => $products
            ];
        case 'oblicovochnyy-kirpich/catalog':
            $oblicCategory = Category::where('slug', 'oblicovochnyy-kirpich')->first();
            $oblicCategories = [];
            if ($oblicCategory) {
                $oblicCategories = [$oblicCategory->slug => $oblicCategory->name];
            }

            $catalogData = $this->getOblicCatalogData($request);
            return array_merge([
                'oblicCategories' => $oblicCategories,
                'colors' => ProductColor::query()->select(['id', 'image', 'name'])->get(),
                'sizes' => \App\Models\ProductSize::all(), // Добавьте эту строку
            ], $catalogData);
        case '/':
            // Данные для главной страницы (как было)
            return [
                'articles' => (new Article)->getPopular(),
                'products' => Product::query()
                    ->select(['title', 'photo', 'price', 'size_id', 'color_id', 'category_id', 'data', 'slug'])
                    ->where('stock', '<>', 0)
                    ->where('status', true)
                    ->where('is_home', true)
                    ->get()
            ];
        default:
            return [];
    }
}

private function buildSeoForPage($city, $type, $entity)
{
   \Log::debug('buildSeoForPage called', [
        'city' => $city->slug ?? 'none',
        'type' => $type,
        'entity_slug' => $entity->slug ?? 'none'
    ]);

    // Если это страница облицовочного кирпича или его дочерние страницы, используем комбинированные SEO
    if ($entity->slug === 'oblicovochnyy-kirpich' ||
        str_starts_with($entity->slug, 'oblicovochnyy-kirpich/')) {

        \Log::debug('Using combined SEO for oblic pages', [
            'city' => $city->slug,
            'page_title' => $entity->title ?? $entity->name ?? '',
            'oblic_seo_title' => $city->oblic_seo_title,
            'oblic_meta_description' => $city->oblic_meta_description,
            'oblic_h1' => $city->oblic_h1
        ]);

        // Берем заголовок страницы из таблицы pages
        $pageTitle = $entity->title ?? $entity->name ?? '';

        // Берем SEO данные города для облицовочного кирпича
        $cityOblicSeoTitle = $city->getTranslation('oblic_seo_title', app()->getLocale()) ?? ($city->oblic_seo_title ?? '');
        $cityOblicDescription = $city->getTranslation('oblic_meta_description', app()->getLocale()) ?? ($city->oblic_meta_description ?? '');
        $cityOblicKeywords = $city->getTranslation('oblic_meta_keywords', app()->getLocale()) ?? ($city->oblic_meta_keywords ?? '');
        $cityOblicH1 = $city->getTranslation('oblic_h1', app()->getLocale()) ?? ($city->oblic_h1 ?? '');

        // ВАЖНО: Для H1 используем ТОЛЬКО городскую часть, без дублирования заголовка страницы
        // Для главной страницы облицовочного кирпича - только город
        if ($entity->slug === 'oblicovochnyy-kirpich') {
            $h1 = $cityOblicH1 ?: 'Облицовочный кирпич';
        } else {
            // Для дочерних страниц: заголовок страницы + город
            $h1 = $pageTitle ? $pageTitle . ' ' . $cityOblicH1 : $cityOblicH1;
        }

        // Для SEO title комбинируем как обычно
        $seoTitle = $pageTitle ? $pageTitle . ' ' . $cityOblicSeoTitle : $cityOblicSeoTitle;

        return [
            'seoTitle' => $seoTitle ?: 'Облицовочный кирпич',
            'seoDescription' => $cityOblicDescription ?: '',
            'seoKeywords' => $cityOblicKeywords ?: '',
            'h1' => $h1 ?: 'Облицовочный кирпич',
        ];
    }

    // $type: 'page', 'category', 'article', ...
    // $entity: модель (Page, Category, Article)
    $pageSlug = ($type === 'page') ? $entity->slug : $type;

    // SEO из city_page_seos, если есть
    $cityPageSeo = \App\Models\CityPageSeo::where('city_id', $city->id)
                    ->where('page_slug', $pageSlug)
                    ->first();

    // SEO поля города
    $citySeoTitle = $city->getTranslation('seo_title', 'ru') ?? $city->seo_title ?? '';
    $cityMetaDescription = $city->getTranslation('meta_description', 'ru') ?? $city->meta_description ?? '';
    $cityMetaKeywords = $city->getTranslation('meta_keywords', 'ru') ?? $city->meta_keywords ?? '';

    // H1 города (основной источник)
    $cityH1 = $city->getTranslation('h1', 'ru');
    if (!$cityH1 && $city->h1) {
        $decoded = json_decode($city->h1, true);
        $cityH1 = $decoded['ru'] ?? '';
    }
    if (!$cityH1) {
        // fallback если в h1 пусто — берём name
        $decoded = is_string($city->name) ? json_decode($city->name, true) : $city->name;
        $cityH1 = $decoded['ru'] ?? (is_array($city->name) ? ($city->name['ru'] ?? '') : $city->name);
    }

    if ($cityPageSeo) {
    $seoTitle = $this->renderTemplate($cityPageSeo->seo_title, $entity, $citySeoTitle);
    $metaDescription = $this->renderTemplate($cityPageSeo->meta_description, $entity, $cityMetaDescription);
    $h1 = $this->renderTemplate($cityPageSeo->h1, $entity, $cityH1);
    $seoKeywords = $cityPageSeo->meta_keywords ?? $cityMetaKeywords;
    } else {
        // fallback: entity seo + append city
        $baseTitle = $entity->seo_title ?? ($entity->title ?? $entity->name ?? '');

        if ($entity->slug === '/' || $entity->slug === 'home') {
            // Для главной страницы: только город, без "Главная страница"
            $seoTitle = $citySeoTitle ?: config('app.name');
        } else {
            // Для всех остальных страниц: стандартный формат
            $seoTitle = $baseTitle ? ($baseTitle . ' ' . $citySeoTitle) : config('app.name');
        }

        $metaDescription = $entity->meta_description ?? $cityMetaDescription;
        $seoKeywords = $entity->meta_keywords ?? $cityMetaKeywords;

        // Определяем H1
        if ($entity->slug === '/' || $entity->slug === 'home') {
            $h1 = $cityH1; // для главной — только H1 города
        } elseif ($entity->slug === 'articles') {
            $h1 = $cityH1;
        } else {
            $h1 = $entity->title ?? $entity->name ?? $cityH1;
        }
    }

    return [
        'seoTitle' => $seoTitle,
        'seoDescription' => $metaDescription,
        'seoKeywords' => $seoKeywords,
        'h1' => $h1,
    ];
}


private function buildSeoForProduct($city, $product)
{
    $citySeoTitle = $city->getTranslation('seo_title', 'ru') ?? $city->seo_title ?? '';
    $cityMetaDescription = $city->getTranslation('meta_description', 'ru') ?? $city->meta_description ?? '';
    $cityMetaKeywords = $city->getTranslation('meta_keywords', 'ru') ?? $city->meta_keywords ?? '';
    $cityH1 = $city->getTranslation('h1', 'ru') ?? $city->h1 ?? $city->name;

    $override = \DB::table('product_city_seos')
        ->where('product_id', $product->id)
        ->where('city_id', $city->id)
        ->first();

    if ($override) {
        $seoTitle = $override->seo_title ?? ($product->seo_title ?? $product->title . ' в ' . $citySeoTitle);
        $metaDescription = $override->meta_description ?? ($product->meta_description ?? $cityMetaDescription);
        // H1 для страницы — продукт + H1 города
        $h1 = $override->h1 ?? ($product->title . ' ' . $cityH1);
        $seoKeywords = $override->meta_keywords ?? ($product->meta_keywords ?? $cityMetaKeywords);
    } else {
        $cityPageSeo = \App\Models\CityPageSeo::where('city_id', $city->id)
            ->where('page_slug', 'product')
            ->first();

        if ($cityPageSeo) {
            $seoTitle = $cityPageSeo->seo_title
                ? $this->renderTemplate($cityPageSeo->seo_title, $product, $citySeoTitle)
                : ($product->seo_title ?? $product->title . ' в ' . $citySeoTitle);

            $metaDescription = $cityPageSeo->meta_description
                ? $this->renderTemplate($cityPageSeo->meta_description, $product, $cityMetaDescription)
                : ($product->meta_description ?? $cityMetaDescription);

            // H1 для страницы — продукт + H1 города
            $h1 = $cityPageSeo->h1
                ? $this->renderTemplate($cityPageSeo->h1, $product, $cityH1)
                : ($product->title . ' ' . $cityH1);

            $seoKeywords = $cityPageSeo->meta_keywords
                ? $cityPageSeo->meta_keywords
                : ($product->meta_keywords ?? $cityMetaKeywords);
        } else {
            $seoTitle = $product->seo_title ?? ($product->title . ' в ' . $citySeoTitle);
            $metaDescription = $product->meta_description ?? $cityMetaDescription;
            $h1 = $product->title . ' ' . $cityH1; // здесь без "в"
            $seoKeywords = $product->meta_keywords ?? $cityMetaKeywords;
        }
    }

    return [
        'seoTitle' => $seoTitle,
        'seoDescription' => $metaDescription,
        'seoKeywords' => $seoKeywords,
        'h1' => $h1,
    ];
}

public function getOblicCategory(Request $request, $slug = null)
{
    $currentCity = app('currentCity');

    \Log::debug('getOblicCategory called', [
        'city' => $currentCity->slug,
        'slug' => $slug,
        'full_url' => $request->fullUrl()
    ]);

    // Находим категорию "Облицовочный кирпич"
    $oblicCategory = Category::where('slug', 'oblicovochnyy-kirpich')
                    ->where('status', true)
                    ->first();

    if (!$oblicCategory) {
        abort(404, 'Категория облицовочного кирпича не найдена');
    }

    // Всегда используем корневую категорию
    $category = $oblicCategory;

    // Получаем данные
    $colors = ProductColor::query()->select(['id', 'image', 'name'])->get();
    $sizes = \App\Models\ProductSize::all(); // ДОБАВЬТЕ ЭТУ СТРОКУ
    $mergeData = $this->getOblicCatalogData($request, $category->id);

    $seo = $this->buildSeoForPage($currentCity, 'category', $category);
    view()->share($seo);

    $viewVars = [
        'seoTitle' => $seo['seoTitle'] ?? $category->seo_title ?? $category->name ?? config('app.name'),
        'seoDescription' => $seo['seoDescription'] ?? $category->meta_description ?? '',
        'seoKeywords' => $seo['seoKeywords'] ?? $category->meta_keywords ?? '',
        'h1' => $seo['h1'] ?? $category->title ?? $category->name ?? '',
        'seo_title' => $seo['seoTitle'] ?? $category->seo_title ?? $category->name ?? config('app.name'),
        'meta_description' => $seo['seoDescription'] ?? $category->meta_description ?? '',
        'meta_keywords' => $seo['seoKeywords'] ?? $category->meta_keywords ?? '',
        'page_title' => $seo['seoTitle'] ?? $category->seo_title ?? $category->name ?? config('app.name'),
        'is_oblic_section' => true,
    ];

    // ДОБАВЬТЕ $sizes В compact()
    return view('pages.catalog.oblic_category', array_merge(compact('category', 'colors', 'sizes'), $mergeData, $viewVars));
}
private function getOblicCatalogData(Request $request, ?int $categoryId = null): array
{
    // Находим ID категории облицовочного кирпича
    $oblicCategory = Category::where('slug', 'oblicovochnyy-kirpich')->first();

    if (!$oblicCategory) {
        return ['maxPrice' => 0, 'products' => collect()];
    }

    // Используем только категорию облицовочного кирпича
    $oblicCategoryIds = [$oblicCategory->id];

    $productQuery = Product::query()
        ->with(['pattern'])
        ->select(['id', 'title', 'slug', 'size_id', 'price', 'color_id', 'category_id', 'pattern_id', 'stock', 'galleries'])
        ->where('status', true)
        ->whereIn('category_id', $oblicCategoryIds) // Только товары облицовочного кирпича
        ->filter($request);

    // Если указана конкретная категория, фильтруем по ней
    if ($categoryId) {
        $productQuery->where('category_id', $categoryId);
    }

    $maxPrice = (clone $productQuery)->max('price');
    $products = $productQuery->paginate(12);

    return compact('maxPrice', 'products');
}

    /**
     * Простая подстановка плейсхолдеров:
     * {product_title}, {page_title}, {category}, {city}
     */
    private function renderTemplate($template, $entity, $cityName)
    {
        if (!$template) return null;
        $repl = [
            '{product_title}' => $entity->title ?? '',
            '{page_title}' => $entity->title ?? $entity->name ?? '',
            '{category}' => $entity->name ?? $entity->title ?? '',
            '{city}' => $cityName,
        ];
        return strtr($template, $repl);
    }
}
