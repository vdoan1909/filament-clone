<?php

namespace App\Filament\Clusters\Address\Resources\StateResource\Pages;

use App\Filament\Clusters\Address\Resources\StateResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewState extends ViewRecord
{
    protected static string $resource = StateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
