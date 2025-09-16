<?php

namespace App\Filament\Resources;

use App\Enums\OrderDeliveryEnum;
use App\Enums\OrderPaymentEnum;
use App\Enums\OrderStatusEnum;
use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OrderResource extends Resource
{

    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';

    protected static ?string $pluralLabel = 'Заказы';

    protected static ?string $modelLabel = 'заказ';

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
                TextColumn::make('phone')
                    ->label('Телефон номера')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable(),
                TextColumn::make('city')
                    ->label('Город'),
                TextColumn::make('street')
                    ->label('Улица'),
                TextColumn::make('house')
                    ->label('Дом'),
                TextColumn::make('delivery')
                    ->formatStateUsing(fn (string $state): string => OrderDeliveryEnum::label($state))
                    ->label('Способ доставки'),
                TextColumn::make('payment')
                    ->formatStateUsing(fn (string $state): string => OrderPaymentEnum::label($state))
                    ->label('Способ оплаты'),
                SelectColumn::make('status')
                    ->options(OrderStatusEnum::labels())
                    ->grow()
                    ->label('Статус заказа'),
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
            'index' => Pages\ListOrders::route('/'),
//            'create' => Pages\CreateOrder::route('/create'),
//            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
