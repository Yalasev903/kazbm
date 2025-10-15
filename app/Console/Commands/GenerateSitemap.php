<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\City;
use App\Models\Page;
use App\Models\Product;
use App\Models\Article;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate a dynamic sitemap.xml for all cities from DB';

    public function handle(): void
    {
        $this->info('🔄 Генерация sitemap.xml...');

        $baseUrl = config('app.url');

        // Получаем все города из таблицы cities
        $cities = class_exists(City::class) ? City::pluck('slug')->toArray() : [];

        // Статические пути сайта
        $staticPaths = ['catalog', 'cart', 'articles', 'about', 'contacts', 'checkout', 'our-products'];

        // Создаём пустой sitemap
        $sitemap = Sitemap::create();

        // Главная страница и языковые переключатели
        $sitemap->add(
            Url::create($baseUrl)->setPriority(1.0)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
        );
        $sitemap->add(
            Url::create("{$baseUrl}/set-lang/kk")->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
        );
        $sitemap->add(
            Url::create("{$baseUrl}/set-lang/ru")->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
        );

        // Если города есть
        foreach ($cities as $city) {

            // Статические страницы для каждого города
            foreach ($staticPaths as $path) {
                $sitemap->add(
                    Url::create("{$baseUrl}/{$city}/{$path}")
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                        ->setPriority(0.8)
                );
            }

            // Динамические страницы: Products
            if (class_exists(Product::class)) {
                foreach (Product::all() as $product) {
                    $sitemap->add(
                        Url::create("{$baseUrl}/{$city}/catalog/{$product->slug}")
                            ->setLastModificationDate($product->updated_at ?? now())
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                            ->setPriority(0.7)
                    );
                }
            }

            // Динамические страницы: Articles
            if (class_exists(Article::class)) {
                foreach (Article::all() as $article) {
                    $sitemap->add(
                        Url::create("{$baseUrl}/{$city}/articles/{$article->slug}")
                            ->setLastModificationDate($article->updated_at ?? now())
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                            ->setPriority(0.7)
                    );
                }
            }

            // Динамические страницы: Pages
            if (class_exists(Page::class)) {
                foreach (Page::all() as $page) {
                    $sitemap->add(
                        Url::create("{$baseUrl}/{$city}/{$page->slug}")
                            ->setLastModificationDate($page->updated_at ?? now())
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                            ->setPriority(0.7)
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
