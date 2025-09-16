<?php

namespace App\Filament\Resources;

use App\Enums\UserStatusEnum;
use App\Filament\Resources\SiteUserResource\Pages;
use App\Models\SiteUser;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SiteUserResource extends Resource
{

    protected static ?string $model = SiteUser::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $pluralLabel = 'Пользователи';

    protected static ?string $modelLabel = 'пользователь';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Имя клиента')
                    ->searchable(true, function (Builder $query, string $search): Builder {
                        return $query->where('name', 'like', "%{$search}%");
                    }),
                TextColumn::make('surname')
                    ->label('Фамилия'),
                TextColumn::make('patronymic')
                    ->label('Отчество'),
                TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('Телефон номера'),
                SelectColumn::make('status')
                    ->options(UserStatusEnum::labels())
                    ->label('Статус'),
            ])
            ->filters([
                //
            ])
            ->actions([
//                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListSiteUsers::route('/'),
//            'create' => Pages\CreateSiteUser::route('/create'),
//            'edit' => Pages\EditSiteUser::route('/{record}/edit'),
        ];
    }
}
