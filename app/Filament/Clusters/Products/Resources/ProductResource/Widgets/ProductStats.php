<?php

namespace App\Filament\Clusters\Products\Resources\ProductResource\Widgets;

use App\Models\Shop\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProductStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Products', Product::count())
                ->description('Total number of products in the shop'),

            Stat::make('Product Inventory', Product::sum('quantity'))
                ->description('Total available stock in the inventory'),

            Stat::make('Average price', '$' . number_format(Product::avg('price'), 2))
                ->description('Average product price'),
        ];
    }
}
