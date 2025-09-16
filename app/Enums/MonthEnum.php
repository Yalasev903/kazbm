<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class MonthEnum extends Enum
{

    const MONTH_1 = 1;
    const MONTH_2 = 2;
    const MONTH_3 = 3;
    const MONTH_4 = 4;
    const MONTH_5 = 5;
    const MONTH_6 = 6;
    const MONTH_7 = 7;
    const MONTH_8 = 8;
    const MONTH_9 = 9;
    const MONTH_10 = 10;
    const MONTH_11 = 11;
    const MONTH_12 = 12;

    public static function getInTheGenetiveCase(int $month): string
    {

        $months = [
            self::MONTH_1 => 'января',
            self::MONTH_2 => 'февраля',
            self::MONTH_3 => 'марта',
            self::MONTH_4 => 'апреля',
            self::MONTH_5 => 'мая',
            self::MONTH_6 => 'июня',
            self::MONTH_7 => 'июля',
            self::MONTH_8 => 'августа',
            self::MONTH_9 => 'сентября',
            self::MONTH_10 => 'октября',
            self::MONTH_11 => 'ноября',
            self::MONTH_12 => 'декабря',
        ];

        return $months[$month] ?? '';
    }

}
