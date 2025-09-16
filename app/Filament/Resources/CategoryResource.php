<?php

namespace App\Filament\Resources;

use App\Filament\Forms\Components\FileUpload;
use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class CategoryResource extends Resource
{

    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-swatch';

    protected static ?string $navigationGroup = 'Продукты';

    protected static ?string $pluralLabel = 'Категории';

    protected static ?string $modelLabel = 'категория';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    TextInput::make('name')
                        ->maxValue(255)
                        ->required()
//                        ->reactive()
                        ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                        ->label('Наименование'),
                    TextInput::make('slug')
                        ->maxValue(255)
                        ->required()
                        ->label('Ссылка'),
                    TinyEditor::make('description')
                        ->fileAttachmentsDirectory('uploads/categories')
                        ->label('Описание'),
                    TextInput::make('seo_title')
                        ->maxValue(255)
                        ->label('СЕО Заголовок'),
                    Textarea::make('meta_description')
                        ->label('Мета - описание'),
                    Textarea::make('meta_keywords')
                        ->label('Мета - ключевые слова'),
                    Toggle::make('status')
                        ->default(0)
                        ->label('Показать категорию?'),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Наименование'),
                TextColumn::make('slug')
                    ->label('Ссылка'),
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
