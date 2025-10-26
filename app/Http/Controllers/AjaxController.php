<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\City;
use App\Models\Product;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function search(Request $request)
    {
        $search = $request->input('query');

        // 🔑 ДОБАВЛЯЕМ ОБРАБОТКУ ГОРОДА ДЛЯ ПОИСКА
        $citySlug = $request->get('city') ?? $request->cookie('selected_city') ?? null;

        if ($citySlug) {
            $city = City::where('slug', $citySlug)->first();
            if (!$city) {
                $city = City::where('is_default', true)->first() ?? City::first();
            }
        } else {
            $city = City::where('is_default', true)->first() ?? City::first();
        }

        // Устанавливаем город в контейнер приложения
        app()->instance('currentCity', $city);
        view()->share('currentCity', $city);

        $products = Product::query()
            ->select(['title', 'slug', 'category_id'])
            ->where('status', true)
            ->filter($request)
            ->get();

        return response()->json([
            'html' => view('components.search_result', compact('products', 'search'))->render()
        ]);
    }

    public function getDeliveryCosts()
    {
        $cities = City::all()->pluck('delivery_cost', 'name');
        return response()->json($cities);
    }

    public function articles()
    {
        $articles = (new Article)->getList();

        return response()->json([
            'html' => view('components.articles.items', compact('articles'))->render()
        ]);
    }

public function products(Request $request)
{
    // 🔑 УСТАНАВЛИВАЕМ ГОРОД ДЛЯ AJAX ЗАПРОСОВ
    $citySlug = $request->get('city') ?? $request->cookie('selected_city') ?? null;

    if ($citySlug) {
        $city = City::where('slug', $citySlug)->first();
        if (!$city) {
            $city = City::where('is_default', true)->first() ?? City::first();
        }
    } else {
        $city = City::where('is_default', true)->first() ?? City::first();
    }

    app()->instance('currentCity', $city);
    view()->share('currentCity', $city);

    $footerCity = City::where('slug', 'pavlodar')->first() ?? $city;
    app()->instance('footerCity', $footerCity);
    view()->share('footerCity', $footerCity);

    // 🔑 ПРОВЕРЯЕМ, ЕСЛИ ЭТО ЗАПРОС ДЛЯ ОБЛИЦОВОЧНОГО КИРПИЧА
    $isOblic = $request->get('is_oblic') || $request->is('ajax/filter/oblic-products');

    if ($isOblic) {
        // Используем отдельный метод для облицовочного кирпича
        $catalogData = $this->getOblicCatalogData($request);
    } else {
        // Стандартный каталог
        $catalogData = (new Product)->getCatalogData($request);
    }

    return response()->json([
        'query' => $request->getQueryString(),
        'html' => view($isOblic ? 'components.catalog.oblic_items' : 'components.catalog.items', $catalogData)->render(),
        'paginate' => $catalogData['products']->links()->render()
    ]);
}

    private function getOblicCatalogData(Request $request): array
    {
        // Находим категорию облицовочного кирпича и все её подкатегории
        $oblicCategory = Category::where('slug', 'oblicovochnyy-kirpich')->first();

        if (!$oblicCategory) {
            return ['maxPrice' => 0, 'products' => collect()];
        }

        $oblicCategoryIds = [$oblicCategory->id];
        $subcategories = Category::where('parent_id', $oblicCategory->id)
                            ->where('status', true)
                            ->pluck('id')
                            ->toArray();

        $oblicCategoryIds = array_merge($oblicCategoryIds, $subcategories);

        $productQuery = Product::query()
            ->with(['pattern'])
            ->select(['id', 'title', 'slug', 'size_id', 'price', 'color_id', 'category_id', 'pattern_id', 'stock', 'galleries'])
            ->where('status', true)
            ->whereIn('category_id', $oblicCategoryIds) // Только облицовочный кирпич
            ->filter($request);

        $maxPrice = (clone $productQuery)->max('price');
        $products = $productQuery->paginate(12);

        return compact('maxPrice', 'products');
    }

    // Добавьте отдельный маршрут для облицовочного кирпича
    public function oblicProducts(Request $request)
    {
        return $this->products($request);
    }
}
