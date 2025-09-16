<?php

namespace App\Filament\Resources;

use App\Enums\ReviewTypeEnum;
use App\Filament\Forms\Components\FileUpload;
use App\Filament\Resources\ProductResource\Pages;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductPattern;
use Filament\Tables\Actions\ReplicateAction;
use App\Models\ProductSize;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class ProductResource extends Resource
{

    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    protected static ?string $navigationGroup = 'Продукты';

    protected static ?string $pluralLabel = 'Товары';

    protected static ?string $modelLabel = 'товар';

    use Translatable;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make('Общие данные')
                    ->schema([
                        TextInput::make('title.ru')
                            ->label('Название (RU)')
                            ->required(),

                        TextInput::make('title.kk')
                            ->label('Атауы (KZ)')
                            ->required(),

                        Select::make('category_id')
                            ->reactive()
                            ->requiredWith(['size_id', 'data.pallet_count', 'data.pallet_weight'])
                            ->label('Категория')
                            ->afterStateUpdated(function (Set $set, ?string $state) {
                                if ($state == 2) $set('size_id', null);
                            })
                            ->options(Category::query()->where('status', true)->pluck('name', 'id')->toArray()),
                        Select::make('size_id')
                            ->label('Размер')
                            ->requiredWith('category_id')
                            ->hidden(fn (Get $get): bool => $get('category_id') == 2)
                            ->options(ProductSize::query()->where('status', true)->pluck('name', 'id')->toArray()),
                        Select::make('color_id')
                            ->required()
                            ->label('Цвет')
                            ->options(ProductColor::query()->pluck('name_m', 'id')->toArray()),
                        Select::make('pattern_id')
                            ->label('Задний фон (в карточке товара)')
                            ->options(ProductPattern::query()->pluck('name', 'id')->toArray()),
                        TinyEditor::make('description.ru')
                            ->fileAttachmentsDirectory('uploads/products')
                            ->label('Описание(RU)'),
                        TinyEditor::make('description,kk')
                            ->fileAttachmentsDirectory('uploads/products')
                            ->label('Описание(KZ)'),
                        TextInput::make('slug')
                            ->maxValue(255)
                            ->required()
                            ->label('Ссылка'),
                        TextInput::make('data.weight')
                            ->required()
                            ->label('Вес продукта'),
//                        Radio::make('per_piece')
//                            ->required()
//                            ->label('Цена за:')
//                            ->options(ProductPriceEnum::labels()),
						
		FileUpload::make('brick_texture_file')
    ->label('Бесшовные текстуры кирпича')
    ->acceptedFileTypes([
        'application/zip',
        'application/x-zip-compressed',
        'application/x-rar-compressed',
        'application/octet-stream',
    ])
    ->preserveFilenames()
    ->maxSize(102400) // 100 MB
    ->directory('brick_textures'),
						
						Section::make()
                            ->columns([
                                'sm' => 2,
                                'xl' => 4,
                            ])
                            ->schema([

                                // штуки
                                TextInput::make('price')
                                    ->type('number')
                                    ->required()
                                    ->label('Цена (шт.)'),
                                TextInput::make('stock')
                                    ->type('number')
                                    ->required()
                                    ->label('Остаток на складе (шт.)'),
                                TextInput::make('data.pallet_count')
                                    ->type('number')
                                    ->requiredWith('category_id')
                                    ->hidden(fn (Get $get): bool => $get('category_id') == 2)
                                    ->label('Количество в 1-м паллете (шт.)'),
                                TextInput::make('data.pallet_weight')
                                    ->requiredWith('category_id')
                                    ->hidden(fn (Get $get): bool => $get('category_id') == 2)
                                    ->label('Вес 1-го паллета (шт.)'),

                                // м2
                                TextInput::make('data.price_m2')
                                    ->type('number')
                                    ->hidden(fn (Get $get): bool => $get('category_id') == 2)
                                    ->label('Цена (м²)'),
                                TextInput::make('data.stock_m2')
                                    ->type('number')
                                    ->requiredWith('data.price_m2')
                                    ->hidden(fn (Get $get): bool => $get('category_id') == 2)
                                    ->label('Остаток на складе (м²)'),
                                TextInput::make('data.pallet_count_m2')
                                    ->type('number')
                                    ->requiredWith('data.price_m2')
                                    ->hidden(fn (Get $get): bool => $get('category_id') == 2)
                                    ->label('Количество в 1-м паллете (м²)'),
                                TextInput::make('data.pallet_weight_m2')
                                    ->requiredWith('data.price_m2')
                                    ->hidden(fn (Get $get): bool => $get('category_id') == 2)
                                    ->label('Вес 1-го паллета (м²)'),

                                // м3
                                TextInput::make('data.price_m3')
                                    ->type('number')
                                    ->hidden(fn (Get $get): bool => $get('category_id') == 2)
                                    ->label('Цена (м³)'),
                                TextInput::make('data.stock_m3')
                                    ->type('number')
                                    ->requiredWith('data.price_m3')
                                    ->hidden(fn (Get $get): bool => $get('category_id') == 2)
                                    ->label('Остаток на складе (м³)'),
                                TextInput::make('data.pallet_count_m3')
                                    ->type('number')
                                    ->requiredWith('data.price_m3')
                                    ->hidden(fn (Get $get): bool => $get('category_id') == 2)
                                    ->label('Количество в 1-м паллете (м³)'),
                                TextInput::make('data.pallet_weight_m3')
                                    ->requiredWith('data.price_m3')
                                    ->hidden(fn (Get $get): bool => $get('category_id') == 2)
                                    ->label('Вес 1-го паллета (м³)'),
                            ]),

                        FileUpload::make('photo')
                            ->directory('products')
                            ->required()
                            ->image()
                            ->label('Фото'),
                        FileUpload::make('galleries')
                            ->directory('products')
                            ->required()
                            ->multiple()
                            ->image()
                            ->label('Галлерея'),
                        TextInput::make('seo_title')
                            ->maxValue(255)
                            ->label('СЕО Заголовок'),
                        Textarea::make('meta_description')
                            ->label('Мета - описание'),
                        Textarea::make('meta_keywords')
                            ->label('Мета - ключевые слова'),
                        Toggle::make('is_home')
                            ->default(0)
                            ->label('Показать на главной?'),
                        Toggle::make('status')
                            ->default(0)
                            ->label('Показать продукт?'),
                ]),
                Card::make('Характеристики(RU)')
                    ->schema([
                        KeyValue::make('parameters.ru')->label('')
                    ]),
                 Card::make('Характеристики(KZ)')
                     ->schema([
                         KeyValue::make('parameters.kk')->label('')
                     ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Наименование'),
                TextColumn::make('category.name')
                    ->label('Категория'),
//                TextColumn::make('slug')
//                    ->label('Ссылка'),
                TextColumn::make('price')
                    ->label('Цена'),
                TextColumn::make('stock')
                    ->label('Остаток на складе'),
                ToggleColumn::make('status')
                    ->label('Статус')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
ReplicateAction::make()
        ->beforeReplicaSaved(function (\App\Models\Product $record, \App\Models\Product $replica) {
            // Получаем базовый slug
            $baseSlug = $record->slug;

            // Ищем уникальный slug с -copy, -copy-2 и т.д.
            $newSlug = $baseSlug . '-copy';
            $counter = 2;

            while (\App\Models\Product::where('slug', $newSlug)->exists()) {
                $newSlug = $baseSlug . '-copy-' . $counter++;
            }

            $replica->slug = $newSlug;

            // Обновим название
            $replica->title = collect($record->title)->map(fn ($val) => $val . ' (копия)');
        })
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
    public static function getTranslatableLocales(): array
    {
        return ['kk', 'ru'];
    }
}
