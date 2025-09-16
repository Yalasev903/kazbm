<?php

namespace App\Filament\Resources;

use App\Filament\Forms\Components\FileUpload;
use App\Filament\Resources\PatternResource\Pages;
use App\Models\ProductPattern;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductPatternResource extends Resource
{

    protected static ?string $model = ProductPattern::class;

    protected static ?string $navigationIcon = 'heroicon-o-square-3-stack-3d';

    protected static ?string $navigationGroup = 'Продукты';

    protected static ?string $pluralLabel = 'Паттерны';

    protected static ?string $modelLabel = 'паттерн';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    TextInput::make('name')
                        ->required()
                        ->label('Наименование'),
                    FileUpload::make('photo')
                        ->directory('patterns')
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
                TextColumn::make('name')
                    ->label('Наименование'),
                ImageColumn::make('photo')
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
            'index' => Pages\ListPatterns::route('/'),
            'create' => Pages\CreatePattern::route('/create'),
            'edit' => Pages\EditPattern::route('/{record}/edit'),
        ];
    }
}
