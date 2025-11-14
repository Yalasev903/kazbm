<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\City;
use App\Models\Page;
use App\Models\Product;
use App\Models\Article;
use App\Models\Category;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate a dynamic sitemap.xml for all cities from DB';

    public function handle(): void
    {
        $this->info('🔄 Генерация sitemap.xml...');

        $baseUrl = config('app.url');

        // Получаем все города из таблицы cities
        $cities = class_exists(City::class) ? City::all() : [];

        // Статические пути сайта
        $staticPaths = ['about', 'contacts', 'calculator'];

        // Пути для облицовочного кирпича
        $oblicPaths = [
            'oblicovochnyy-kirpich',
            'oblicovochnyy-kirpich/about',
            'oblicovochnyy-kirpich/contacts',
            'oblicovochnyy-kirpich/our-products',
            'oblicovochnyy-kirpich/catalog'
        ];

        // Создаём пустой sitemap
        $sitemap = Sitemap::create();

        // Главная страница и языковые переключатели
        $sitemap->add(
            Url::create($baseUrl)->setPriority(1.0)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
        );

        // Для дефолтного города - статические страницы
        $defaultCity = City::where('is_default', true)->first();
        if ($defaultCity) {
            foreach ($staticPaths as $path) {
                $sitemap->add(
                    Url::create("{$baseUrl}/{$path}")
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setPriority(0.7)
                );
            }

            // Страницы облицовочного кирпича для дефолтного города
            foreach ($oblicPaths as $path) {
                $sitemap->add(
                    Url::create("{$baseUrl}/{$path}")
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setPriority(0.8)
                );
            }
        }

        // Если города есть
        foreach ($cities as $city) {
            // Главная страница для города
            $sitemap->add(
                Url::create("{$baseUrl}/{$city->slug}")
                    ->setPriority(1.0)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            );

            // Статические страницы для каждого города
            foreach ($staticPaths as $path) {
                $sitemap->add(
                    Url::create("{$baseUrl}/{$city->slug}/{$path}")
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setPriority(0.7)
                );
            }

            // Страницы облицовочного кирпича для города
            foreach ($oblicPaths as $path) {
                $sitemap->add(
                    Url::create("{$baseUrl}/{$city->slug}/{$path}")
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setPriority(0.8)
                );
            }

            // Категории для каждого города
            if (class_exists(Category::class)) {
                foreach (Category::where('status', true)->get() as $category) {
                    $sitemap->add(
                        Url::create("{$baseUrl}/{$city->slug}/catalog/{$category->slug}")
                            ->setLastModificationDate($category->updated_at ?? now())
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                            ->setPriority(0.6)
                    );
                }
            }

            // Динамические страницы: Products
            if (class_exists(Product::class)) {
                foreach (Product::where('status', true)->get() as $product) {
                    $categorySlug = $product->category->slug ?? 'catalog';
                    $sitemap->add(
                        Url::create("{$baseUrl}/{$city->slug}/catalog/{$categorySlug}/{$product->slug}")
                            ->setLastModificationDate($product->updated_at ?? now())
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                            ->setPriority(0.5)
                    );
                }
            }

            // Динамические страницы: Articles
            if (class_exists(Article::class)) {
                foreach (Article::where('status', true)->get() as $article) {
                    $sitemap->add(
                        Url::create("{$baseUrl}/{$city->slug}/articles/{$article->slug}")
                            ->setLastModificationDate($article->updated_at ?? now())
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                            ->setPriority(0.5)
                    );
                }
            }
        }

        // Глобальные страницы (без города)
        if (class_exists(Page::class)) {
            foreach (Page::where('status', true)->get() as $page) {
                // Пропускаем страницы, которые уже добавлены
                if (!in_array($page->slug, $staticPaths) && !in_array($page->slug, $oblicPaths)) {
                    $sitemap->add(
                        Url::create("{$baseUrl}/{$page->slug}")
                            ->setLastModificationDate($page->updated_at ?? now())
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                            ->setPriority(0.5)
                    );
                }
            }
        }

        // Сохраняем файл
        $path = public_path('sitemap.xml');
        $sitemap->writeToFile($path);

        $this->info("✅ Sitemap успешно создан: {$path}");
    }
}
