<?php

namespace App\Filament\Resources;

use App\Filament\Forms\Components\FileUpload;
use App\Filament\Resources\ProductColorResource\Pages;
use App\Models\ProductColor;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductColorResource extends Resource
{

    protected static ?string $model = ProductColor::class;

    protected static ?string $navigationIcon = 'heroicon-o-beaker';

    protected static ?string $navigationGroup = 'Продукты';

    protected static ?string $pluralLabel = 'Цвета';

    protected static ?string $modelLabel = 'цвет';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    TextInput::make('name')
                        ->required()
                        ->label('Наименование (во множественном ч.)'),
                    TextInput::make('name_w')
                        ->required()
                        ->label('Наименование (в женском р.)'),
                    TextInput::make('name_m')
                        ->required()
                        ->label('Наименование (в мужском р.)'),
                    FileUpload::make('image')
                        ->directory('colors')
                        ->required()
                        ->image()
                        ->label('Фото'),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name_m')
                    ->label('Наименование'),
                ImageColumn::make('image')
                    ->label('Фото'),
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
            'index' => Pages\ListProductColors::route('/'),
            'create' => Pages\CreateProductColor::route('/create'),
            'edit' => Pages\EditProductColor::route('/{record}/edit'),
        ];
    }
}
