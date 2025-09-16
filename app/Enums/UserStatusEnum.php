<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class UserStatusEnum extends Enum
{

    const STATUS_BUYER = 0;
    const STATUS_WHOLESALER = 1;

    public static function labels(): array
    {
        return [
            self::STATUS_BUYER => 'покупатель',
            self::STATUS_WHOLESALER => 'оптовый поставщик',
        ];
    }
}
