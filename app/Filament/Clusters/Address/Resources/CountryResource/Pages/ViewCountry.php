<?php

namespace App\Filament\Clusters\Address\Resources\CountryResource\Pages;

use App\Filament\Clusters\Address\Resources\CountryResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCountry extends ViewRecord
{
    protected static string $resource = CountryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
