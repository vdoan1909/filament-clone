<?php

namespace App\Filament\Clusters\Products\Resources\ProductResource\Pages;

use App\Filament\Clusters\Products\Resources\ProductResource;
use App\Filament\Clusters\Products\Resources\ProductResource\Widgets\ProductStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('trash')
                ->label('View Trash')
                ->url(route('filament.admin.products.resources.products.trash'))
                ->icon('heroicon-o-trash')
                ->color('gray'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ProductStats::class,
        ];
    }
}
