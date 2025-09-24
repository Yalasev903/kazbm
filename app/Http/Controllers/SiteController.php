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

class SiteController extends Controller
{

    # Просмотр страниц
    public function getPage(Request $request, $slug = '/')
    {

        $currentCity = app('currentCity');

       if ($slug === 'catalog') {
            return redirect()->route('category.city.show', [
        'city' => app('currentCity')->slug,
        'slug' => 'giperpressovannyi-kirpic'
            ]);
        }
        $slugToUse = $slug === '/' ? '/' : $slug;

        $page = Page::where('slug', $slugToUse)
                    ->where('status', true)
                    ->firstOrFail();

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

        $slug = $request->route('slug');
        $category = Category::where('slug', $slug)
                    ->where('status', true)
                    ->firstOrFail();

        $colors = ProductColor::query()->select(['id', 'image', 'name'])->get();
        $mergeData = (new Product)->getCatalogData($request, $category->id);

        $seo = $this->buildSeoForPage($currentCity, 'category', $category);

        // Добавляем шаринг SEO-данных для использования в layout
        view()->share($seo);

        // Нормализация значений с безопасными fallback
        $seoTitle = $seo['seoTitle'] ?? $category->seo_title ?? $category->name ?? config('app.name');
        $seoDescription = $seo['seoDescription'] ?? $category->meta_description ?? '';
        $seoKeywords = $seo['seoKeywords'] ?? $category->meta_keywords ?? '';
        $h1 = $seo['h1'] ?? $category->title ?? $category->name ?? '';

        // Передаём в view — явные имена, чтобы layout/шаблон мог использовать любое
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

        return view('pages.catalog.category', array_merge(compact('category', 'colors'), $mergeData, $viewVars));
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

        return response()->json(['success' => true, 'city' => $city->name]);
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
            case '/':
                return [
                    'articles' => (new Article)->getPopular(),
                    'products' => Product::query()
                        ->select(['title', 'photo', 'price', 'size_id', 'color_id', 'category_id', 'data', 'slug'])
                        ->where('stock', '<>', 0)
                        ->where('status', true)
                        ->where('is_home', true)
//                        ->limit(4)
                        ->get()
                ];
            default:
                return [];
        }
    }

private function buildSeoForPage($city, $type, $entity)
{
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
