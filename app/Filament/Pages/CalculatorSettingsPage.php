<?php

namespace App\Filament\Pages;

use App\Filament\Forms\Components\FileUpload;
use App\Filament\Settings\CalculatorSettings;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class CalculatorSettingsPage extends SettingsPage
{

    protected static ?string $navigationIcon = 'heroicon-o-calculator';

    protected static ?string $title = 'Калькулятор';

    protected static ?string $slug = 'blocks/calculator';

    protected static string $settings = CalculatorSettings::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make('№1 - Типы проектов')
                    ->schema([
                        Repeater::make('types')
                            ->maxItems(2)
                            ->label('Типы')
                            ->collapsed()
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->label('Наименование'),
                        ])
                    ]),

                Card::make('№2 - Способы кладки')
                    ->schema([
                        Repeater::make('methods')
                            ->maxItems(10)
                            ->label('Способы')
                            ->collapsed()
                            ->schema([
                                TextInput::make('text')
                                    ->required()
                                    ->label('Текст'),
                                TextInput::make('value')
                                    ->required()
                                    ->label('Значение'),
                                FileUpload::make('icon')
                                    ->directory('settings')
                                    ->required()
                                    ->image()
                                    ->label('Иконка'),
                            ])
                    ]),
            ]);
    }
}
