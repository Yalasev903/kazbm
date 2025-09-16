<?php

namespace App\Filament\Pages;

use App\Filament\Forms\Components\FileUpload;
use App\Filament\Settings\GeneralSettings;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class GeneralSettingsPage extends SettingsPage
{

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $title = 'Настройки';

    protected static ?string $slug = 'settings';

    protected static ?int $navigationSort = 1;

    protected static string $settings = GeneralSettings::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    TextInput::make('site_name')
                        ->required()
                        ->label('Название сайта'),
                    TextInput::make('desc')
                        ->label('Описание'),
                    TextInput::make('phone')
                        ->required()
                        ->label('Номер телефона'),
                    TextInput::make('address_kk')
                        ->required()
                        ->label('Адрес компании на казахском языке'),
                    TextInput::make('address_ru')
                        ->required()
                        ->label('Адрес компании на русском языке'),

                    TextInput::make('email')
                        ->email()
                        ->required()
                        ->label('Электронная почта'),
                    TextInput::make('map_link')
                        ->label('Ссылка на карту'),
                    FileUpload::make('favicon')
                        ->directory('settings')
                        ->required()
                        ->image()
                        ->label('Favicon сайта'),
                    FileUpload::make('logo')
                        ->directory('settings')
                        ->required()
                        ->image()
                        ->label('Логотип сайта (основной)'),
                    FileUpload::make('logo_fixed')
                        ->directory('settings')
                        ->required()
                        ->image()
                        ->label('Логотип сайта (в шапке)'),
                ]),

                Section::make('Данные')
                    ->schema([
                        Repeater::make('socials')
                            ->maxItems(4)
                            ->label('Социальные сети')
                            ->collapsed()
                            ->schema([
                                TextInput::make('link')
                                    ->required()
                                    ->label('Ссылка'),
                                FileUpload::make('photo')
                                    ->label('Фото')
                                    ->directory('settings')
                                    ->image(),
                            ])
                    ])
            ]);
    }
}
