<?php

namespace App\Filament\Resources;

use App\Filament\Forms\Components\FileUpload;
use App\Filament\Resources\ProductSizeResource\Pages;
use App\Models\ProductSize;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class ProductSizeResource extends Resource
{

    protected static ?string $model = ProductSize::class;

    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';

    protected static ?string $navigationGroup = 'Продукты';

    protected static ?string $pluralLabel = 'Размеры';

    protected static ?string $modelLabel = 'размер';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                        TextInput::make('name')
                            ->required()
                            ->label('Текст'),
                        TextInput::make('value')
                            ->required()
                            ->label('Значение'),
                        TextInput::make('width')
                            ->numeric()
                            ->step(0.001)
                            ->required()
                            ->label('Ширина'),

                        TextInput::make('height')
                            ->numeric()
                            ->step(0.001)
                            ->required()
                            ->label('Высота'),

                        TextInput::make('depth')
                            ->numeric()
                            ->step(0.001)
                            ->required()
                            ->label('Глубина'),

                        TextInput::make('pallet_count')
                            ->numeric()
                            ->default(300)
                            ->required()
                            ->label('Кол-во на паллете'),
                        TextInput::make('weight')
                            ->required()
                            ->label('Вес'),
                        FileUpload::make('image')
                            ->directory('sizes')
                            ->required()
                            ->image()
                            ->label('Иконка'),
                        Toggle::make('status')
                            ->default(0)
                            ->label('Показать размер?'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Наименование'),
                TextColumn::make('weight')
                    ->label('Вес'),
                ImageColumn::make('image')
                    ->label('Иконка'),
                ToggleColumn::make('status')
                    ->label('Статус')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListProductSizes::route('/'),
            'create' => Pages\CreateProductSize::route('/create'),
            'edit' => Pages\EditProductSize::route('/{record}/edit'),
        ];
    }
}
