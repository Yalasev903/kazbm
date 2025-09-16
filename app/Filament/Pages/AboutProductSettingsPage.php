<?php

namespace App\Filament\Pages;

use App\Filament\Forms\Components\FileUpload;
use App\Filament\Settings\About\ProductSettings;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class AboutProductSettingsPage extends SettingsPage
{

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $title = 'Продукция';

    protected static ?string $navigationGroup = 'О компании';

    protected static ?string $slug = 'blocks/about/product';

    protected static string $settings = ProductSettings::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make('Блок №1')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->label('Заголовок'),
                        Textarea::make('description')
                            ->required()
                            ->label('Описание'),
                        FileUpload::make('photo')
                            ->directory('settings')
                            ->required()
                            ->image()
                            ->label('Фото'),
                    ]),

                Card::make('Блок №2')
                    ->schema([
                        FileUpload::make('item_photo')
                            ->directory('settings')
                            ->required()
                            ->image()
                            ->label('Фото'),
                        Repeater::make('items')
                            ->maxItems(2)
                            ->label('Элементы')
                            ->collapsed()
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->label('Наименование'),
                                Textarea::make('desc')
                                    ->required()
                                    ->label('Краткое описание'),
                            ])
                    ]),
            ]);
    }
}
