<?php

namespace App\Filament\Clusters\Products\Resources\ProductResource\Pages;

use App\Filament\Clusters\Products\Resources\ProductResource;
use Filament\Actions;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function getCreatedNotification(): Notification|null
    {
        return Notification::make()
            ->success()
            ->title('Product created successfully')
            ->body('The product has been created successfully');
    }

    protected function afterCreate()
    {
        $product = $this->record;
        Notification::make()
            ->success()
            ->icon('heroicon-o-bolt')
            ->title('New Product')
            ->body('The product: ' . $product->name . ' has been created successfully')
            ->actions([
                Action::make('View')
                    ->url(ProductResource::getUrl('edit', ['record' => $product])),
            ])
            ->sendToDatabase(Auth::user());
    }
}
