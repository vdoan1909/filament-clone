<?php

namespace App\Filament\Resources\Shop\OrderResource\Pages;

use App\Filament\Resources\Shop\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Shop\OrderResource\Widgets\OrderStatsWidget;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            OrderStatsWidget::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            'All' => Tab::make(),

            'New' => Tab::make()
                ->modifyQueryUsing(
                    fn(Builder $query)
                    => $query->where('status_order', 'New')
                ),

            'Processing' => Tab::make()
                ->modifyQueryUsing(
                    fn(Builder $query)
                    => $query->where('status_order', 'Processing')
                ),

            'Shipped' => Tab::make()
                ->modifyQueryUsing(
                    fn(Builder $query)
                    => $query->where('status_order', 'Shipped')
                ),

            'Delivered' => Tab::make()
                ->modifyQueryUsing(
                    fn(Builder $query)
                    => $query->where('status_order', 'Delivered')
                ),

            'Cancelled' => Tab::make()
                ->modifyQueryUsing(
                    fn(Builder $query)
                    => $query->where('status_order', 'Cancelled')
                ),
        ];
    }
}
