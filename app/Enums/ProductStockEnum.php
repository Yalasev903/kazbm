<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ProductStockEnum extends Enum
{

    const STOCK_HAVE = 'have';
    const STOCK_HAVENT = 'havent';
    const STOCK_FEW = 'few';

    public static function labels(): array
    {
        return [
            self::STOCK_HAVE => __("Много"),
            self::STOCK_HAVENT => __('Нет в наличии'),
            self::STOCK_FEW => __('Мало'),
        ];
    }

    public static function label(string $stock): string
    {
        return self::labels()[$stock] ?? '';
    }
}
