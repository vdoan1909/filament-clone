<?php

namespace App\Filament\Resources\Blog\CategoryResource\Pages;

use App\Filament\Imports\BlogCategoryImporter;
use App\Filament\Resources\Blog\CategoryResource;
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
            ->importer(BlogCategoryImporter::class)
            ->color('gray'),

            Actions\CreateAction::make(),

            Actions\Action::make('trash')
                ->label('View Trash')
                ->icon('heroicon-o-trash')
                ->url(route('filament.admin.resources.blog-categories.trash'))
                ->color('gray'),
        ];
    }
}
