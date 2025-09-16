<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\SiteUser;
use Filament\Widgets\ChartWidget;

class UsersChart extends ChartWidget
{

    protected static ?string $heading = 'Пользователи';
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {

        $user_count1 = SiteUser::query()->where('status', 0)->count();
        $user_count2 = SiteUser::query()->where('status', 1)->count();

        return [
            'datasets' => [
                [
                    'label' => 'Общее количество',
                    'data' => [$user_count1, $user_count2],
                    'hoverOffset' => 2,
                    'backgroundColor' => [
                        'rgb(54, 162, 235)',
                        'rgb(255, 99, 132)',
                    ]
                ],
            ],
            'labels' => ['Покупатель', 'Оптовый поставщик'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
