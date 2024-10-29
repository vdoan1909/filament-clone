<?php

namespace App\Filament\Widgets;

use App\Models\Shop\Customer;
use App\Models\Shop\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $thisDay = now();
        $aWeekAgo = (clone $thisDay)->subWeek();
        $aMonthAgo = (clone $thisDay)->subMonth();
        // \Illuminate\Support\Facades\Log::info($thisDay);

        $formatNumber = function (int $number): string {
            if ($number < 1000) {
                return (string) Number::format($number, 0);
            }

            if ($number < 1000000) {
                return Number::format($number / 1000, 2) . 'k';
            }

            return Number::format($number / 1000000, 2) . 'm';
        };

        $revenue = Order::where('status_payment', 'Paid')
            ->sum('total_price');

        $newCustomer = Customer::where('created_at', '>=', $aWeekAgo)
            ->where('created_at', '<=', $thisDay)
            ->count();

        $newOrder = Order::where('status_order', 'New')
            ->where('created_at', '>=', $aMonthAgo)
            ->where('created_at', '<=', $thisDay)
            ->sum('total_price');

        $dataOrder = Trend::model(Order::class)
            ->between(
                start: now()->subYear(),
                end: now(),
            )
            ->perMonth()
            ->count();

        $dataCustomer = Trend::model(Customer::class)
            ->between(
                start: now()->subYear(),
                end: now(),
            )
            ->perMonth()
            ->count();

        $dataNewOrder = Trend::model(Order::class)
            ->between(
                start: now()->subYear(),
                end: now(),
            )
            ->perMonth()
            ->count();

        return [
            Stat::make('Revenue', $formatNumber($revenue))
                ->chart(
                    $dataOrder->map(fn(TrendValue $value) => $value->aggregate)
                        ->toArray()
                )
                ->description('32k increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('New customers', $formatNumber($newCustomer))
                ->chart(
                    $dataCustomer->map(fn(TrendValue $value) => $value->aggregate)
                        ->toArray()
                )
                ->description('3% decrease')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger'),

            Stat::make('New orders', $formatNumber($newOrder))
                ->chart(
                    $dataNewOrder->map(fn(TrendValue $value) => $value->aggregate)
                        ->toArray()
                )
                ->description('7% increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
        ];
    }
}
