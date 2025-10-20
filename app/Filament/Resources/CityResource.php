<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CityResource\Pages;
use App\Models\City;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CityResource extends Resource
{
    protected static ?string $model = City::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    protected static ?string $pluralLabel = 'Города';
    protected static ?string $modelLabel = 'город';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Город')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Основная информация')
                            ->schema([
                                Forms\Components\TextInput::make('name.ru')
                                    ->label("Название города (ru)")
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('name.kk')
                                    ->label("Название города (kk)")
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('slug')
                                    ->label("Slug (латинскими буквами)")
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('region')
                                    ->label("Регион (если есть)")
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('delivery_cost')
                                    ->label("Стоимость доставки")
                                    ->numeric()
                                    ->required(),
                                Forms\Components\Toggle::make('is_default')
                                    ->label("Город по умолчанию"),
                            ]),
                        Forms\Components\Tabs\Tab::make('SEO настройки')
                            ->schema([
                                Forms\Components\TextInput::make('seo_title.ru')
                                    ->label("SEO Title (ru)")
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('seo_title.kk')
                                    ->label("SEO Title (kk)")
                                    ->maxLength(255),
                                Forms\Components\Textarea::make('meta_description.ru')
                                    ->label("Meta Description (ru)")
                                    ->rows(3),
                                Forms\Components\Textarea::make('meta_description.kk')
                                    ->label("Meta Description (kk)")
                                    ->rows(3),
                                Forms\Components\Textarea::make('meta_keywords.ru')
                                    ->label("Meta Keywords (ru)")
                                    ->rows(2),
                                Forms\Components\Textarea::make('meta_keywords.kk')
                                    ->label("Meta Keywords (kk)")
                                    ->rows(2),
                                Forms\Components\TextInput::make('h1.ru')
                                    ->label("H1 (ru)")
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('h1.kk')
                                    ->label("H1 (kk)")
                                    ->maxLength(255),
                            ]),
                        Forms\Components\Tabs\Tab::make('SEO для облицовочного кирпича')
                            ->schema([
                                Forms\Components\TextInput::make('oblic_seo_title.ru')
                                    ->label("SEO Title для облицовочного (ru)")
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('oblic_seo_title.kk')
                                    ->label("SEO Title для облицовочного (kk)")
                                    ->maxLength(255),
                                Forms\Components\Textarea::make('oblic_meta_description.ru')
                                    ->label("Meta Description для облицовочного (ru)")
                                    ->rows(3),
                                Forms\Components\Textarea::make('oblic_meta_description.kk')
                                    ->label("Meta Description для облицовочного (kk)")
                                    ->rows(3),
                                Forms\Components\Textarea::make('oblic_meta_keywords.ru')
                                    ->label("Meta Keywords для облицовочного (ru)")
                                    ->rows(2),
                                Forms\Components\Textarea::make('oblic_meta_keywords.kk')
                                    ->label("Meta Keywords для облицовочного (kk)")
                                    ->rows(2),
                                Forms\Components\TextInput::make('oblic_h1.ru')
                                    ->label("H1 для облицовочного (ru)")
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('oblic_h1.kk')
                                    ->label("H1 для облицовочного (kk)")
                                    ->maxLength(255),
                            ]),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Название города')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('region')
                    ->label('Регион')
                    ->sortable(),
                Tables\Columns\TextColumn::make('delivery_cost')
                    ->label('Стоимость доставки')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_default')
                    ->label('По умолчанию')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCities::route('/'),
            'create' => Pages\CreateCity::route('/create'),
            'edit' => Pages\EditCity::route('/{record}/edit'),
        ];
    }
}
