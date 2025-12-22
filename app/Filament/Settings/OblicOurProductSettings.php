<?php

namespace App\Filament\Settings;

use Spatie\LaravelSettings\Settings;
use App\Traits\ImageUpload;

class OblicOurProductSettings extends Settings
{
    use ImageUpload;

    public ?string $hero_image = null;
    public ?string $hero_desc = null;
    public ?string $feature_title = null;
    public ?string $feature_desc = null;
    public ?string $feature_photo = null;
    public ?string $reason_title = null;
    public ?string $reason_desc = null;
    public ?string $conclusion_text = null;

    public static function group(): string
    {
        return 'oblic_our_product';
    }
}
