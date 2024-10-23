<?php

namespace App\Filament\Clusters\Products\Resources\BrandResource\Pages;

use App\Filament\Clusters\Products\Resources\BrandResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBrands extends ListRecords
{
    protected static string $resource = BrandResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

            Actions\Action::make('trash')
                ->label('View Trash')
                ->url(route('filament.admin.products.resources.brands.trash'))
                ->icon('heroicon-o-trash')
                ->color('gray'),
        ];
    }
}
