<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ProductPriceEnum extends Enum
{

    const PER_PIECE = 0;
    const PER_M2 = 1;
    const PER_M3 = 2;

    public static function labels(): array
    {
        return [
            self::PER_PIECE => 'шт.',
            self::PER_M2 => 'м²',
            self::PER_M3 => 'м³',
        ];
    }
}
