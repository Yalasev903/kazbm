<?php

namespace App\Filament\Resources;

use App\Filament\Forms\Components\FileUpload;
use App\Filament\Resources\ArticleResource\Pages;
use App\Models\Article;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $pluralLabel = 'Статьи';

    protected static ?string $modelLabel = 'статья';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    TextInput::make('title')
                        ->maxValue(255)
                        ->required()
//                        ->reactive()
                        ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                        ->label('Заголовок'),
                    Textarea::make('description')
                        ->label('Краткое описание'),
                    TinyEditor::make('body')
                        ->required()
                        ->fileAttachmentsDirectory('uploads/articles')
                        ->label('Описание'),
                    TextInput::make('slug')
                        ->maxValue(255)
                        ->required()
                        ->unique(null, null, fn ($record) => $record)
                        ->label('Ссылка'),
                    Select::make("lang")
                        ->label("Язык")
                        ->options([
                            'ru' => 'Русский',
                            'kk' => 'Қазақша',
                        ]),
                    FileUpload::make('small_image')
                        ->directory('articles')
                        ->image()
 ->disk('public')
    ->visibility('public') // очень важно!                        ->required()
                        ->label('Фото (в карточке)'),
                    FileUpload::make('image')
                        ->directory('articles')
                        ->image()
                        ->label('Фото на странице просмотра'),
                    TextInput::make('seo_title')
                        ->maxValue(255)
                        ->label('СЕО Заголовок'),
                    Textarea::make('meta_description')
                        ->label('Мета - описание'),
                    Textarea::make('meta_keywords')
                        ->label('Мета - ключевые слова'),
                    DatePicker::make('date')
                        ->required()
                        ->label('Дата публикации'),
                    Toggle::make('is_popular')
                        ->default(0)
                        ->label('Отобразить на главной?'),
                    Toggle::make('status')
                        ->default(0)
                        ->label('Опубликовать?'),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->words(15)
                    ->wrap(15)
                    ->label('Заголовок'),
                TextColumn::make('date')
                    ->label('Дата публикации'),
                ImageColumn::make('small_image')
                    ->label('Фото'),

                ToggleColumn::make('is_popular')
                    ->label('На главной'),
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
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }
}
