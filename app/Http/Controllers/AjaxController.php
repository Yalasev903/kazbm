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

        $catalogData = (new Product)->getCatalogData($request);

        return response()->json([
            'query' => $request->getQueryString(),
            'html' => view('components.catalog.items', $catalogData)->render(),
            'paginate' => $catalogData['products']->links()->render()
        ]);
    }
}
