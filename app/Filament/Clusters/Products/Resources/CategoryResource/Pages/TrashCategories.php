<?php

namespace App\Filament\Clusters\Products\Resources\CategoryResource\Pages;

use App\Filament\Clusters\Products\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Shop\ShopCategory;

class TrashCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;

    protected function getTableQuery(): Builder
    {
        return ShopCategory::onlyTrashed();
    }
}
