<?php

namespace App\Filament\Resources\Blog\CategoryResource\Pages;

use App\Filament\Resources\Blog\CategoryResource;
use App\Models\Blog\BlogCategory;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class TrashCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;

    protected function getTableQuery(): Builder
    {
        return BlogCategory::onlyTrashed();
    }
}
