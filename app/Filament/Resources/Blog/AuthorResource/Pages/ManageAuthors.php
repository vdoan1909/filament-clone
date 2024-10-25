<?php

namespace App\Filament\Resources\Blog\AuthorResource\Pages;

use App\Filament\Exports\AuthorExporter;
use App\Filament\Resources\Blog\AuthorResource;
use Filament\Actions;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\Auth;

class ManageAuthors extends ManageRecords
{
    protected static string $resource = AuthorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ExportAction::make()
            ->label('Export Authors')
            ->color('gray')
            ->exporter(AuthorExporter::class),

            Actions\CreateAction::make(),
        ];
    }
}
