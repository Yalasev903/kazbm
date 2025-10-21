<?php

namespace App\Filament\Settings;

use Spatie\LaravelSettings\Settings;
use App\Traits\ImageUpload;

class OblicAboutBannerSettings extends Settings
{
    use ImageUpload;

    public string $title_ru;
    public string $title_kk;
    public string $photo;
    public string $sub_title;
    public string $desc_ru;
    public string $desc_kk;
    public string $bg_image;

    public static function group(): string
    {
        return 'oblic_about_banner';
    }
}
