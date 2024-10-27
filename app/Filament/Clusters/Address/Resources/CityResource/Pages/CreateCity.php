<?php

namespace App\Filament\Clusters\Address\Resources\CityResource\Pages;

use App\Filament\Clusters\Address\Resources\CityResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateCity extends CreateRecord
{
    protected static string $resource = CityResource::class;

    protected function getCreatedNotification(): Notification|null
    {
        return Notification::make()
            ->success()
            ->title('City created successfully')
            ->body('The city has been created successfully');
    }
}
