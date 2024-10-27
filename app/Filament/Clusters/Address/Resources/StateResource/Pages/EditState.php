<?php

namespace App\Filament\Clusters\Address\Resources\StateResource\Pages;

use App\Filament\Clusters\Address\Resources\StateResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditState extends EditRecord
{
    protected static string $resource = StateResource::class;

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
            ->title('State updated successfully')
            ->body('The state has been updated successfully');
    }
}
