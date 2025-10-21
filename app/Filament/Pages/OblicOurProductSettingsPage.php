<?php
// app/Filament/Pages/OblicOurProductSettingsPage.php

namespace App\Filament\Pages;

use App\Filament\Settings\OblicOurProductSettings;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class OblicOurProductSettingsPage extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationLabel = 'Наша продукция (Облицовочный кирпич)';
    protected static ?string $navigationGroup = 'Настройки сайта (Облицовочный кирпич)';
    protected static ?int $navigationSort = 3;

    protected static string $settings = OblicOurProductSettings::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make('Блок №1 - Баннер')
                    ->schema([
                        Textarea::make('hero_desc')
                            ->required()
                            ->label('Краткое описание'),
                        FileUpload::make('hero_image')
                            ->directory('settings')
                            ->required()
                            ->image()
                            ->label('Фото'),
                    ]),

                Card::make('Блок №2 - Особенности')
                    ->schema([
                        TextInput::make('feature_title')
                            ->required()
                            ->label('Заголовок'),
                        TinyEditor::make('feature_desc')
                            ->fileAttachmentsDirectory('uploads/settings')
                            ->label('Описание'),
                        FileUpload::make('feature_photo')
                            ->directory('settings')
                            ->required()
                            ->image()
                            ->label('Фото'),
                    ]),

                Card::make('Блок №3 - Причины')
                    ->schema([
                        TextInput::make('reason_title')
                            ->required()
                            ->label('Заголовок'),
                        TinyEditor::make('reason_desc')
                            ->fileAttachmentsDirectory('uploads/settings')
                            ->label('Описание'),
                    ]),

                Card::make('Блок №4 - Выводы')
                    ->schema([
                        Textarea::make('conclusion_text')
                            ->required()
                            ->label('Описание'),
                    ]),
            ]);
    }
}
