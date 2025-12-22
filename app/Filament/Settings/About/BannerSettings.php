<?php

namespace App\Filament\Settings\About;

use App\Traits\ImageUpload;
use Spatie\LaravelSettings\Settings;

class BannerSettings extends Settings
{
    use ImageUpload;

    public ?string $title_ru = null;
    public ?string $title_kk = null;
    public ?string $sub_title = null;
    public ?string $desc_ru = null;
    public ?string $desc_kk = null;
    public ?string $photo = null;
    public ?string $bg_image = null;

    public static function group(): string
    {
        return 'about_banner';
    }
}
