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

        // Устанавливаем город в контейнер приложения
        app()->instance('currentCity', $city);
        view()->share('currentCity', $city);

        // Также устанавливаем footerCity для консистентности
        $footerCity = City::where('slug', 'pavlodar')->first() ?? $city;
        app()->instance('footerCity', $footerCity);
        view()->share('footerCity', $footerCity);

        // Получаем данные каталога
        $catalogData = (new Product)->getCatalogData($request);

        return response()->json([
            'query' => $request->getQueryString(),
            'html' => view('components.catalog.items', $catalogData)->render(),
            'paginate' => $catalogData['products']->links()->render()
        ]);
    }
}
