<?php

namespace App\Filament\Clusters\Products\Resources\ProductResource\Pages;

use App\Filament\Clusters\Products\Resources\ProductResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;
    protected static string $resource = ProductResource::class;

    protected function getCreatedNotification(): Notification|null
    {
        return Notification::make()
            ->success()
            ->title('Product created successfully')
            ->body('The product has been created successfully');
    }
}
