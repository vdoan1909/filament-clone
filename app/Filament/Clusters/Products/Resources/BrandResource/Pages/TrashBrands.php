<?php

namespace App\Filament\Clusters\Products\Resources\BrandResource\Pages;

use App\Filament\Clusters\Products\Resources\BrandResource;
use App\Models\Shop\Brand;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class TrashBrands extends ListRecords
{
    protected static string $resource = BrandResource::class;

    protected function getTableQuery(): Builder
    {
        return Brand::onlyTrashed();
    }
}
