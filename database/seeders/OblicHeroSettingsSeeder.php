<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Filament\Settings\OblicHeroSettings;
use Spatie\LaravelSettings\Exceptions\MissingSettings;

class OblicHeroSettingsSeeder extends Seeder
{
    public function run(): void
    {
        try {
            $settings = app(OblicHeroSettings::class);
            // Если мы здесь, значит настройки уже существуют, и мы можем их не трогать.
        } catch (MissingSettings $e) {
            // Если настройки отсутствуют, создадим их напрямую через базу.
            $group = 'oblic_hero';

            $payload = [
                'title' => [
                    'ru' => '<span>Облицовочный</span><b>Кирпич</b>',
                    'kk' => '<span>Облицовкалық</span><b>Кірпіш</b>'
                ],
                'photo' => '',
            ];

            \DB::table('settings')->insert([
                'group' => $group,
                'name' => 'oblic_hero',
                'payload' => json_encode($payload),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
