<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Shop\OrderResource;
use App\Models\Shop\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestOrder extends BaseWidget
{
    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(OrderResource::getEloquentQuery())
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Customer'),

                Tables\Columns\TextColumn::make('status_order')
                    ->label('Order Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'New' => 'info',
                        'Processing' => 'primary',
                        'Shipped' => 'success',
                        'Delivered' => 'success',
                        'Cancelled' => 'danger',
                    }),

                Tables\Columns\TextColumn::make('currency')
                    ->label('Currency'),

                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total Price')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn($state) => number_format($state, 2, '.', '.')),

                Tables\Columns\TextColumn::make('status_payment')
                    ->label('Payment Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Paid' => 'success',
                        'Unpaid' => 'danger'
                    })
            ])
            ->actions([
                Tables\Actions\ViewAction::make('Open')
                    ->url(fn(Order $record): string => OrderResource::getUrl('view', ['record' => $record])),
            ]);
    }
}
