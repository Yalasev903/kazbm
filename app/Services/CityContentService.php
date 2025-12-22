<?php

namespace App\Services;

use App\Models\City;
use App\Models\CitySectionContent;

class CityContentService
{
    public static function getContent(string $section, ?int $cityId = null): ?array
    {
        if (!$cityId) {
            $city = app('currentCity');
            $cityId = $city?->id;
        }

        if (!$cityId) {
            return null;
        }

        return CitySectionContent::getContent($cityId, $section);
    }

    public static function getOurProductContent(?int $cityId = null): array
    {
        $cityContent = static::getContent('our_product', $cityId);
        if ($cityContent) {
            return static::processImages($cityContent, ['hero_image', 'feature_photo']);
        }

        $settings = app(\App\Filament\Settings\OurProductSettings::class);
        return [
            'hero_desc' => $settings->hero_desc,
            'hero_image' => \App\Helpers\Common::getImage($settings->hero_image),
            'feature_title' => $settings->feature_title,
            'feature_desc' => $settings->feature_desc,
            'feature_photo' => \App\Helpers\Common::getImage($settings->feature_photo),
            'reason_title' => $settings->reason_title,
            'reason_desc' => $settings->reason_desc,
            'conclusion_text' => $settings->conclusion_text,
        ];
    }

    public static function getOblicOurProductContent(?int $cityId = null): array
    {
        $cityContent = static::getContent('oblic_our_product', $cityId);
        if ($cityContent) {
            return static::processImages($cityContent, ['hero_image', 'feature_photo']);
        }

        $settings = app(\App\Filament\Settings\OblicOurProductSettings::class);
        return [
            'hero_desc' => $settings->hero_desc,
            'hero_image' => \App\Helpers\Common::getImage($settings->hero_image),
            'feature_title' => $settings->feature_title,
            'feature_desc' => $settings->feature_desc,
            'feature_photo' => \App\Helpers\Common::getImage($settings->feature_photo),
            'reason_title' => $settings->reason_title,
            'reason_desc' => $settings->reason_desc,
            'conclusion_text' => $settings->conclusion_text,
            'process_desc' => null, // process_desc removed from settings class to avoid 500 error
        ];
    }

    public static function getAdvantageContent(?int $cityId = null): array
    {
        $cityContent = static::getContent('advantage', $cityId);
        if ($cityContent && isset($cityContent['items'])) {
            $cityContent['items'] = static::processItemsImages($cityContent['items'], ['image', 'small_image']);
            return $cityContent;
        }

        $settings = app(\App\Filament\Settings\AdvantageSettings::class);
        return ['items' => static::processItemsImages($settings->items, ['image', 'small_image'])];
    }

    public static function getOblicAdvantageContent(?int $cityId = null): array
    {
        $cityContent = static::getContent('oblic_advantage', $cityId);
        if ($cityContent && isset($cityContent['items'])) {
            $cityContent['items'] = static::processItemsImages($cityContent['items'], ['image', 'small_image']);
            return $cityContent;
        }

        $settings = app(\App\Filament\Settings\OblicAdvantageSettings::class);
        return ['items' => static::processItemsImages($settings->items, ['image', 'small_image'])];
    }

    public static function getAboutAdvantageContent(?int $cityId = null): array
    {
        $cityContent = static::getContent('about_advantage', $cityId);
        if ($cityContent && isset($cityContent['items'])) {
            $cityContent['items'] = static::processItemsImages($cityContent['items'], ['image', 'small_image']);
            return $cityContent;
        }

        $settings = app(\App\Filament\Settings\About\AdvantageSettings::class);
        return ['items' => static::processItemsImages($settings->items, ['image', 'small_image'])];
    }

    public static function getAboutProductContent(?int $cityId = null): array
    {
        $cityContent = static::getContent('about_product', $cityId);
        if ($cityContent) {
            return static::processImages($cityContent, ['photo', 'item_photo']);
        }

        $settings = app(\App\Filament\Settings\About\ProductSettings::class);
        return [
            'title' => $settings->title,
            'description' => $settings->description,
            'photo' => \App\Helpers\Common::getImage($settings->photo),
            'item_photo' => \App\Helpers\Common::getImage($settings->item_photo),
            'items' => $settings->items,
        ];
    }

    public static function getOblicAboutProductContent(?int $cityId = null): array
    {
        $cityContent = static::getContent('oblic_about_product', $cityId);
        if ($cityContent) {
            return static::processImages($cityContent, ['photo', 'item_photo']);
        }

        $settings = app(\App\Filament\Settings\OblicAboutProductSettings::class);
        return [
            'title' => $settings->title,
            'description' => $settings->description,
            'photo' => \App\Helpers\Common::getImage($settings->photo),
            'item_photo' => \App\Helpers\Common::getImage($settings->item_photo),
            'items' => $settings->items,
        ];
    }

    public static function getAboutBannerContent(?int $cityId = null): array
    {
        $cityContent = static::getContent('about_banner', $cityId);
        if ($cityContent) {
            return static::processImages($cityContent, ['photo', 'bg_image']);
        }

        $settings = app(\App\Filament\Settings\About\BannerSettings::class);
        return [
            'title_ru' => $settings->title_ru,
            'title_kk' => $settings->title_kk,
            'sub_title' => $settings->sub_title,
            'desc_ru' => $settings->desc_ru,
            'desc_kk' => $settings->desc_kk,
            'photo' => \App\Helpers\Common::getImage($settings->photo),
            'bg_image' => \App\Helpers\Common::getImage($settings->bg_image),
        ];
    }

    public static function getOblicAboutBannerContent(?int $cityId = null): array
    {
        $cityContent = static::getContent('oblic_about_banner', $cityId);
        if ($cityContent) {
            return static::processImages($cityContent, ['photo', 'bg_image']);
        }

        $settings = app(\App\Filament\Settings\OblicAboutBannerSettings::class);
        return [
            'title_ru' => $settings->title_ru,
            'title_kk' => $settings->title_kk,
            'photo' => \App\Helpers\Common::getImage($settings->photo),
            'sub_title' => $settings->sub_title,
            'desc_ru' => $settings->desc_ru,
            'desc_kk' => $settings->desc_kk,
            'bg_image' => \App\Helpers\Common::getImage($settings->bg_image),
        ];
    }

    private static function processImages(array $content, array $fields): array
    {
        foreach ($fields as $field) {
            if (isset($content[$field])) {
                $content[$field] = \App\Helpers\Common::getImage($content[$field]);
            }
        }
        return $content;
    }

    private static function processItemsImages(array $items, array $fields): array
    {
        foreach ($items as &$item) {
            foreach ($fields as $field) {
                if (isset($item[$field])) {
                    $item[$field] = \App\Helpers\Common::getImage($item[$field]);
                }
            }
        }
        return $items;
    }
}
