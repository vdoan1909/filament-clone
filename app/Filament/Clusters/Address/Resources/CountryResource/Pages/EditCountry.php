<?php

namespace App\Filament\Clusters\Address\Resources\CountryResource\Pages;

use App\Filament\Clusters\Address\Resources\CountryResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditCountry extends EditRecord
{
    protected static string $resource = CountryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getSavedNotification(): Notification|null
    {
        return Notification::make()
            ->success()
            ->title('Country updated successfully')
            ->body('The country has been updated successfully');
    }
}
