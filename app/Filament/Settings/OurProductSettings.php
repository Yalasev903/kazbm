<?php

namespace App\Filament\Settings;

use App\Traits\ImageUpload;
use Spatie\LaravelSettings\Settings;

class OurProductSettings extends Settings
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
        return 'our_product';
    }
}
