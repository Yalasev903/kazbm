<?php

namespace App\Filament\Resources\ArticleResource\Widgets;

use App\Models\Article;
use App\Models\Page;
use App\Models\Product;
use App\Models\SiteUser;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{

    protected function getStats(): array
    {

        $product_count = Product::query()->where('status', true)->count();
        $article_count = Article::query()->where('status', true)->count();
        $page_count = Page::query()->where('status', true)->count();

        return [
            Stat::make('Товары', $product_count)
                ->description('общее количество')
                ->color('warning'),
            Stat::make('Статьи', $article_count)
                ->description('общее количество')
                ->color('warning'),
            Stat::make('Страницы', $page_count)
                ->description('общее количество')
                ->color('warning'),
        ];
    }
}
