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

        if ($slug == 'catalog')
            return redirect('catalog/giperpressovannyi-kirpic');

        $page = Page::query()
            ->where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();
        $mergeData = $this->getParamsByPage($page, $request);

        return view("pages.$page->type", compact('page'), $mergeData);
    }

    public function getArticle(string $slug)
    {

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

        return view("pages.articles.show", compact('article', 'articles'));
    }

    public function getCategory(string $slug, Request $request)
    {

        $category = Category::query()
            ->where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();

        $colors = ProductColor::query()->select(['id', 'image', 'name'])->get();
        $mergeData = (new Product)->getCatalogData($request, $category->id);

        return view('pages.catalog.category', compact('category', 'colors'), $mergeData);
    }

    public function getProduct(Request $request)
    {

        $product = Product::query()
            ->with(['pattern'])
            ->where('slug', $request->slug)
            ->where('status', true)
            ->firstOrFail();

        if ($product->category->slug != $request->category)
            abort(404);

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
}
