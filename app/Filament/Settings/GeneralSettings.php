<?php

namespace App\Filament\Settings;

use App\Helpers\Common;
use App\Traits\ImageUpload;
use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    use ImageUpload;

    public ?string $site_name = null;
    public ?string $desc = null;
    public ?string $phone = null;
    public ?string $email = null;

    public ?string $address_ru = null;
    public ?string $address_kk = null;
    public ?string $logo = null;
    public ?string $favicon = null;
    public ?string $logo_fixed = null;
    public ?string $map_link = null;
    public ?array $socials = [];

    public static function group(): string
    {
        return 'general';
    }

    public function getPhone(): string
    {
        return Common::getPhone($this->phone) ?: '';
    }
}
