<?php

namespace App\Filament\Clusters\Address\Resources\CityResource\Pages;

use App\Filament\Clusters\Address\Resources\CityResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditCity extends EditRecord
{
    protected static string $resource = CityResource::class;

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
            ->title('City updated successfully')
            ->body('The city has been updated successfully');
    }
}
