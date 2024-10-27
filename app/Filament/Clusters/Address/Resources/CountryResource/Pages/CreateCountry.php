<?php

namespace App\Filament\Clusters\Address\Resources\CountryResource\Pages;

use App\Filament\Clusters\Address\Resources\CountryResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateCountry extends CreateRecord
{
    protected static string $resource = CountryResource::class;

    protected function getCreatedNotification(): Notification|null
    {
        return Notification::make()
            ->success()
            ->title('Country created successfully')
            ->body('The country has been created successfully');
    }
}
