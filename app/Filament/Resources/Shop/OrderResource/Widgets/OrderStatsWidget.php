<?php

namespace App\Filament\Resources\Shop\OrderResource\Widgets;

use App\Filament\Resources\Shop\OrderResource\Pages\ListOrders;
use App\Models\Shop\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderStatsWidget extends BaseWidget
{
    protected function getTablePage(): string
    {
        return ListOrders::class;
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Orders', Order::count()),
            Stat::make('Open Order', Order::whereIn('status_order', ['Processing', 'Delivered'])->count()),
            Stat::make('Average Price', number_format(Order::avg('total_price'), 2, '.', '.')),
        ];
    }
}
