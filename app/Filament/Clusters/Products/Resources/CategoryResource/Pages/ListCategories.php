<?php

namespace App\Filament\Clusters\Products\Resources\CategoryResource\Pages;

use App\Filament\Clusters\Products\Resources\CategoryResource;
use App\Filament\Imports\ShopCategoryImporter;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ImportAction::make()
                ->label('Import Categories')
                ->importer(ShopCategoryImporter::class)
                ->color('gray'),

            Actions\CreateAction::make(),

            Actions\Action::make('trash')
                ->label('View Trash')
                ->url(route('filament.admin.products.resources.categories.trash'))
                ->icon('heroicon-o-trash')
                ->color('gray'),
        ];
    }
}
