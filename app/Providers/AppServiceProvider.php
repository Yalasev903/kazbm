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
use Illuminate\Support\Facades\Blade;
use Intervention\Image\ImageManagerStatic as Image;

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
        if (!function_exists('getWebpPath')) {
            function getWebpPath($originalPath) {
                // Если путь уже абсолютный URL
                if (str_starts_with($originalPath, 'http')) {
                    return $originalPath;
                }

                // Определяем базовый путь в зависимости от источника
                if (str_starts_with($originalPath, '/storage/')) {
                    // Для storage путей
                    $relativePath = str_replace('/storage/', '', $originalPath);
                    $storagePath = storage_path('app/public/' . $relativePath);
                    $webpPath = dirname($storagePath) . '/' . pathinfo($storagePath, PATHINFO_FILENAME) . '.webp';

                    // Проверяем существование WebP в storage
                    if (file_exists($webpPath)) {
                        return '/storage/' . dirname($relativePath) . '/' . pathinfo($relativePath, PATHINFO_FILENAME) . '.webp';
                    }
                } else {
                    // Для public путей
                    $publicPath = public_path($originalPath);
                    $webpPath = dirname($publicPath) . '/' . pathinfo($publicPath, PATHINFO_FILENAME) . '.webp';

                    if (file_exists($webpPath)) {
                        return dirname($originalPath) . '/' . pathinfo($originalPath, PATHINFO_FILENAME) . '.webp';
                    }
                }

                return $originalPath; // Fallback на оригинал если WebP нет
            }
        }

        Paginator::useBootstrap();
        Paginator::defaultView('components.pagination');

        $sizes = (new ProductSize)->getList();

        // Для каталога - все категории
        $categories = Category::query()
            ->where('status', true)
            ->pluck('name', 'slug')
            ->toArray();

        // Для шапки - исключаем категорию "Облицовочный кирпич" если она будет
        $headerCategories = Category::query()
            ->where('status', true)
            ->where('slug', '!=', 'oblicovochnyy-kirpich') // исключаем облицовочный кирпич
            ->pluck('name', 'slug')
            ->toArray();

        View::share('generalSettings', $generalSettings);

        View::share('canonicalBase', config('app.url'));

        View::composer(['layouts.header'], function ($view) {
            $view->with('cartCount', (new Basket)->getContent()->count());
        });

        // Для шапки - категории без облицовочного кирпича
        View::composer(['layouts.header'], function ($view) use ($headerCategories) {
            $view->with('categories', $headerCategories);
        });

        // Для фильтра в каталоге - все категории
        View::composer(['components.catalog.filter'], function ($view) use ($categories) {
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
        // $models = [\App\Models\City::class, \App\Models\Page::class, \App\Models\Category::class, \App\Models\Article::class];

        // foreach ($models as $model) {
        //     $model::saved(function () {
        //         \Artisan::call('sitemap:generate');
        //     });

        //     $model::deleted(function () {
        //         \Artisan::call('sitemap:generate');
        //     });
        // }

        View::composer('*', function ($view) {
            $defaultCity = City::where('is_default', true)->first();

            if ($defaultCity) {
                $canonicalBase = url('/' . $defaultCity->slug);
            } else {
                $canonicalBase = url('/');
            }

            $view->with('canonicalBase', $canonicalBase);
        });
        // Оптимизация конкретных проблемных изображений
        $this->optimizeProblematicImages();
        View::composer('*', function ($view) {
        // Не выполняем для админских путей
        if (request()->is('admin*') ||
            request()->is('filament*') ||
            (class_exists(\Filament\Facades\Filament::class) && \Filament\Facades\Filament::isServing())) {
            return;
        }

        $currentCity = app('currentCity');
        $isOblicSection = request()->is('*oblicovochnyy-kirpich*');

        $categories = Category::where('status', true)
            ->where('slug', '!=', 'oblicovochnyy-kirpich')
            ->pluck('name', 'slug')
            ->toArray();

        $oblicCategories = Category::where('status', true)
            ->where('slug', 'oblicovochnyy-kirpich')
            ->pluck('name', 'slug')
            ->toArray();

        $view->with(compact('isOblicSection', 'categories', 'oblicCategories'));
    });
  }

    /**
     * Оптимизация конкретных больших изображений (приведение к нужным размерам и сжатие).
     */
    private function optimizeProblematicImages()
    {
        $problemImages = [
            public_path('images/block4_2.webp') => [537, 302], // Привести к реальному размеру отображения
            public_path('images/catalog.png') => [800, 400],
            // Добавьте другие большие файлы из отчета
        ];

        foreach ($problemImages as $path => $dimensions) {
            if (file_exists($path)) {
                try {
                    $image = Image::make($path);
                    if ($image->width() > $dimensions[0]) {
                        $image->resize($dimensions[0], $dimensions[1], function ($constraint) {
                            $constraint->aspectRatio();
                        });
                        $image->save($path, 65);
                    }
                } catch (\Exception $e) {
                    \Log::error("Failed to optimize: {$path} - " . $e->getMessage());
                }
            }
        }
    }
}
