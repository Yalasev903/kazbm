<?php

namespace App\Filament\Settings;

use Spatie\LaravelSettings\Settings;
use App\Traits\ImageUpload;

class OblicAboutProductSettings extends Settings
{
    use ImageUpload;

    public ?string $title = null;
    public ?string $description = null;
    public ?string $photo = null;
    public ?string $item_photo = null;
    public ?array $items = [];
    

    public static function group(): string
    {
        return 'oblic_about_product';
    }
}
