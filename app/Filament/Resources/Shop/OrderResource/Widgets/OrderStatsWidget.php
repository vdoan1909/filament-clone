<?php

namespace App\Filament\Resources\Shop\OrderResource\Widgets;

use App\Filament\Resources\Shop\OrderResource\Pages\ListOrders;
use App\Models\Shop\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class OrderStatsWidget extends BaseWidget
{
    protected function getTablePage(): string
    {
        return ListOrders::class;
    }

    protected function getStats(): array
    {
        $data = Trend::model(Order::class)
            ->between(
                start: now()->subYear(),
                end: now(),
            )
            ->perMonth()
            ->count();

        return [
            Stat::make('Orders', Order::count())
                ->chart(
                    $data->map(fn(TrendValue $value) => $value->aggregate)
                        ->toArray()
                ),
            Stat::make('Open Order', Order::whereIn('status_order', ['Processing', 'Delivered'])->count())
            ->description('Order Processing and Delivered'),
            Stat::make('Average Price', number_format(Order::avg('total_price'), 2, '.', '.'))
        ];
    }
}
