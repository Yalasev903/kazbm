<?php

namespace App\Filament\Pages;

use App\Filament\Forms\Components\FileUpload;
use App\Filament\Settings\OblicAboutProductSettings;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class OblicAboutProductSettingsPage extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $title = 'Продукция';

    protected static ?string $navigationGroup = 'О компании (облицовочный кирпич)';

    protected static ?string $slug = 'blocks/oblic-company/product';

    protected static string $settings = OblicAboutProductSettings::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    TextInput::make('title')
                        ->required()
                        ->label('Заголовок'),
                    Textarea::make('description')
                        ->required()
                        ->label('Описание'),
                    FileUpload::make('photo')
                        ->directory('oblic_about')
                        ->required()
                        ->image()
                        ->label('Фото'),
                    FileUpload::make('item_photo')
                        ->directory('oblic_about')
                        ->required()
                        ->image()
                        ->label('Дополнительное фото'),
                    Repeater::make('items')
                        ->label('Элементы')
                        ->collapsed()
                        ->schema([
                            TextInput::make('name')
                                ->required()
                                ->label('Название'),
                            Textarea::make('desc')
                                ->required()
                                ->label('Описание'),
                        ])
                ]),
            ]);
    }
}
