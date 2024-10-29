<?php

namespace App\Filament\Widgets;

use App\Models\Shop\Customer;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class CustomerChart extends ChartWidget
{
    protected static ?string $heading = 'Total customers';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $data = Trend::model(Customer::class)
            ->between(
                start: Customer::oldest('created_at')->first()->created_at ?? now()->subYear(),
                end: now(),
            )
            ->perMonth()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn(TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
