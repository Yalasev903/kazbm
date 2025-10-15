<?php

namespace App\Providers;

use App\Filament\Settings\GeneralSettings;
use App\Helpers\Basket;
use App\Models\Category;
use App\Models\ProductSize;
use Darryldecode\Cart\CartServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use App\Models\City;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(CartServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(GeneralSettings $generalSettings): void
    {

        Paginator::useBootstrap();
        Paginator::defaultView('components.pagination');

        $sizes = (new ProductSize)->getList();
        $categories = Category::query()
            ->where('status', true)
            ->pluck('name', 'slug')
            ->toArray();

        View::share('generalSettings', $generalSettings);
        View::composer(['layouts.header'], function ($view) {
            $view->with('cartCount', (new Basket)->getContent()->count());
        });
        View::composer([
            'layouts.header',
            'components.catalog.filter'
        ], function ($view) use ($categories) {
            $view->with('categories', $categories);
        });
        View::composer([
            'pages.calculator',
            'components.blocks.calculator',
            'components.catalog.filter'
        ], function ($view) use ($sizes) {
            $view->with('sizes', $sizes);
        });
        View::composer('layouts.header', function ($view) {
            $cities = City::all();
            $view->with('cities', $cities);
        });

        // Автоматически пинговать при изменении контента
        $models = [\App\Models\City::class, \App\Models\Page::class, \App\Models\Category::class, \App\Models\Article::class];

        foreach ($models as $model) {
            $model::saved(function () {
                \Artisan::call('sitemap:generate');
            });

            $model::deleted(function () {
                \Artisan::call('sitemap:generate');
            });
        }
         View::composer('*', function ($view) {
            $defaultCity = City::where('is_default', true)->first();

            if ($defaultCity) {
                $canonicalBase = url('/' . $defaultCity->slug);
            } else {
                $canonicalBase = url('/');
            }

            $view->with('canonicalBase', $canonicalBase);
        });
    }
}
