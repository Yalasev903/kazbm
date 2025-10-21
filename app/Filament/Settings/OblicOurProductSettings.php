<?php

namespace App\Filament\Settings;

use Spatie\LaravelSettings\Settings;
use App\Traits\ImageUpload;

class OblicOurProductSettings extends Settings
{
    use ImageUpload;

    public string $hero_image;
    public string $hero_desc;
    public string $feature_title;
    public string $feature_desc;
    public string $feature_photo;
    public string $reason_title;
    public string $reason_desc;
    public string $conclusion_text;

    public static function group(): string
    {
        return 'oblic_our_product';
    }
}
