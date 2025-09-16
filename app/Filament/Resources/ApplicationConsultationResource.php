<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApplicationConsultationResource\Pages;
use App\Models\Application;
use App\Models\Applications\ApplicationConsultation;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ApplicationConsultationResource extends Resource
{

    protected static ?string $model = ApplicationConsultation::class;

    protected static ?string $navigationIcon = 'heroicon-o-information-circle';

    protected static ?string $navigationGroup = 'Заявки';

    protected static ?string $pluralLabel = 'на консультацию';

    protected static ?string $modelLabel = 'на консультацию';

    public static function getEloquentQuery(): Builder
    {
        /** @var Application $model */
        $model = app(self::getModel());
        return parent::getEloquentQuery()
            ->where('type', $model->getType())
            ->orderByDesc('id');
    }

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
                TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable(),
                TextColumn::make('message')
                    ->label('Сообщение'),
                TextColumn::make('created_at')
                    ->label('Дата создания')
                    ->dateTime('Y-m-d H:i:s', 'Asia/Almaty'),
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
            'index' => Pages\ListApplicationConsultations::route('/'),
            'create' => Pages\CreateApplicationConsultation::route('/create'),
            'edit' => Pages\EditApplicationConsultation::route('/{record}/edit'),
        ];
    }
}
