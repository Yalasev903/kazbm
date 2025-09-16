<?php

namespace App\Filament\Settings\About;

use App\Traits\ImageUpload;
use Spatie\LaravelSettings\Settings;

class BannerSettings extends Settings
{
    use ImageUpload;

    public string $title_ru;
    public string $title_kk;
    public string $sub_title;
    public string $desc_ru;
    public string $desc_kk;
    public string $photo;
    public string $bg_image;

    public static function group(): string
    {
        return 'about_banner';
    }
}
