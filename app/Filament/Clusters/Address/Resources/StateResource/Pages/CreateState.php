<?php

namespace App\Filament\Clusters\Address\Resources\StateResource\Pages;

use App\Filament\Clusters\Address\Resources\StateResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateState extends CreateRecord
{
    protected static string $resource = StateResource::class;

    protected function getCreatedNotification(): Notification|null
    {
        return Notification::make()
            ->success()
            ->title('State created successfully')
            ->body('The state has been created successfully');
    }
}
