<?php

namespace App\Filament\Pages;

use App\Filament\Forms\Components\FileUpload;
use App\Filament\Settings\OurProductSettings;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class OurProductSettingsPage extends SettingsPage
{

    protected static ?string $navigationIcon = 'heroicon-o-lifebuoy';

    protected static ?string $title = 'Наша продукция';

    protected static ?string $slug = 'blocks/our-products';

    protected static string $settings = OurProductSettings::class;

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
