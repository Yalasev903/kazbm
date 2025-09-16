<?php

namespace App\Helpers;

use App\Enums\MonthEnum;
use App\Traits\Resizable;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class Common
{
    use Resizable;

    /**
     * От количество поставить на разные падежи
     *
     * @param $value
     * @param array $words
     * @return mixed
     */
    public static function multiplier($value, $words = array())
    {
        if ($value % 10 == 1 && ($value<10 || $value>20)) {
            return $words[0];
        } else if ($value % 10 > 1 && $value % 10 < 5 && ($value<10 || $value>20)) {
            return $words[1];
        } else {
            return $words[2];
        }
    }

    /**
     * По ссылке Youtube получить ID
     *
     * @param string $link
     * @return string
     */
    public static function getYoutubeId(string $link): string
    {

        $parts = parse_url($link);

        if (!isset($parts['query']))
            return '';

        parse_str($parts['query'], $query);
        return $query['v'] ?? '';
    }

    public static function getImage(?string $path): string
    {
        return $path ? Storage::url($path) : '';
    }

    public static function getVideoPoster(?string $path): string
    {
        $webpImage = self::getWebpByImage($path);
        return $webpImage ? self::getImage($webpImage) : self::getImage($path);
    }

    public static function getWebpByImage(string $path): string
    {
        $webpImage = (strpos($path,'svg') === false) ? str_replace('.' . pathinfo($path, PATHINFO_EXTENSION), '.webp', $path) : '';
        return file_exists(asset('storage/'. $webpImage)) ? $webpImage : '';
    }

    public static function getDateToString(string $date): string
    {
        $date = Carbon::make($date);
        return $date->day .' '. MonthEnum::getInTheGenetiveCase($date->month) .', '. $date->year;
    }

    public static function getPhone(string $phone): string
    {
        return preg_replace("/[^,.0-9]/", '', $phone);
    }

    public static function getWebpFormat($model, string $attribute)
    {
        $attrValue = is_array($model)
            ? $model[$attribute] ?? ''
            : $model->$attribute ?? '';
        $webpImage = Common::getWebpByImage($attrValue);
        return self::getImage($webpImage) ?: '';
    }

    public static function getWebpSmallFormat(string $image)
    {
        $smallImage = self::getThumbnail($image, 'small');
        return $smallImage ? $smallImage : '';
    }

}
