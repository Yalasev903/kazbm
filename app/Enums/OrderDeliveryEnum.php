<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class OrderDeliveryEnum extends Enum
{

    const DELIVERY_SELF = 'self';
    const DELIVERY_BRAND = 'brand';
    const DELIVERY_YANDEX = 'yandex';

    public static function labels(): array
    {
        return [
            self::DELIVERY_SELF => [
                'title' => 'Самовывоз',
                'desc' => 'Самовывоз с нашего завода по адресу г. Павлодар, Специальная Экономическая Зона (СЭЗ), Северная промышленная зона 361',
                'price' => 0
            ],
//            self::DELIVERY_BRAND => [
//                'title' => 'Фирменная доставка',
//                'desc' => 'Lörem ipsum nöfärade osade presm innan dinat plaligt det autosm teponade polänar om onat att maledes, såsom minösk fävugt os.',
//                'price' => 12300
//            ],
//            self::DELIVERY_YANDEX => [
//                'title' => 'Яндекс доставка',
//                'desc' => 'Lörem ipsum nöfärade osade presm innan dinat plaligt det autosm teponade polänar om onat att maledes, såsom minösk fävugt os.',
//                'price' => 12300
//            ]
        ];
    }

    public static function label(string $key): string
    {
        return self::labels()[$key]['title'] ?? '';
    }
}
