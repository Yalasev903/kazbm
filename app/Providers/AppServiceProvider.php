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

    }
}
