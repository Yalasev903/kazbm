<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class OrderTypeEnum extends Enum
{

    const LEGAL_TYPE_1 = 0; // физ.
    const LEGAL_TYPE_2 = 1; // юр.

    public static function labels(): array
    {
        return [
            self::LEGAL_TYPE_1 => 'Физическое лицо',
            self::LEGAL_TYPE_2 => 'Юридическое лицо',
        ];
    }
}
