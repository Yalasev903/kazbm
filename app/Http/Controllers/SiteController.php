<?php

namespace App\Http\Controllers;

use App\Helpers\Basket;
use App\Models\Article;
use App\Models\Category;
use App\Models\Page;
use App\Models\Product;
use App\Models\ProductColor;
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

    public function getArticle(string $slug)
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
        view()->share($seo);

        return view('pages.catalog.category', compact('category', 'colors'), $mergeData);
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
        // $entity: model (Page, Category, Article)
        $pageSlug = ($type == 'page') ? $entity->slug : $type; // или другое логическое определение

        // 1. попытка взять override product_city_seos / city_page_seos
        $cityPageSeo = \App\Models\CityPageSeo::where('city_id', $city->id)
                        ->where('page_slug', $pageSlug)
                        ->first();

        $cityName = $city->getTranslation('name', 'ru') ?? $city->name;

        if ($cityPageSeo) {
            $seoTitle = $this->renderTemplate($cityPageSeo->seo_title, $entity, $cityName);
            $metaDescription = $this->renderTemplate($cityPageSeo->meta_description, $entity, $cityName);
            $h1 = $this->renderTemplate($cityPageSeo->h1, $entity, $cityName);
        } else {
            // fallback: entity seo + append city
            $baseTitle = $entity->seo_title ?? ($entity->title ?? $entity->name ?? '');
            $seoTitle = $baseTitle ? ($baseTitle . ' в ' . $cityName) : config('app.name');
            $metaDescription = $entity->meta_description ?? '';
            $h1 = $entity->title ?? ($entity->name ?? '');
        }

        return [
            'seoTitle' => $seoTitle,
            'seoDescription' => $metaDescription,
            'seoKeywords' => $cityPageSeo->meta_keywords ?? ($entity->meta_keywords ?? ''),
            'h1' => $h1,
        ];
    }

    private function buildSeoForProduct($city, $product)
    {
        $cityName = $city->getTranslation('name', 'ru') ?? $city->name;

        // 1. product_city_seos
        $override = \DB::table('product_city_seos')
            ->where('product_id', $product->id)
            ->where('city_id', $city->id)
            ->first();

        if ($override && $override->seo_title) {
            $seoTitle = $override->seo_title;
            $metaDescription = $override->meta_description ?? '';
            $h1 = $override->h1 ?? ($product->title . ' в ' . $cityName);
        } else {
            // 2. city page template for 'product'
            $cityPageSeo = \App\Models\CityPageSeo::where('city_id', $city->id)
                            ->where('page_slug', 'product')
                            ->first();
            if ($cityPageSeo && $cityPageSeo->seo_title) {
                $seoTitle = $this->renderTemplate($cityPageSeo->seo_title, $product, $cityName);
                $metaDescription = $this->renderTemplate($cityPageSeo->meta_description, $product, $cityName);
                $h1 = $this->renderTemplate($cityPageSeo->h1, $product, $cityName);
            } else {
                // 3. fallback product seo + city
                $base = $product->seo_title ?? $product->title;
                $seoTitle = $base . ' в ' . $cityName;
                $metaDescription = $product->meta_description ?? '';
                $h1 = $product->title . ' в ' . $cityName;
            }
        }

        return [
            'seoTitle' => $seoTitle,
            'seoDescription' => $metaDescription,
            'seoKeywords' => $override->meta_keywords ?? $product->meta_keywords ?? '',
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
