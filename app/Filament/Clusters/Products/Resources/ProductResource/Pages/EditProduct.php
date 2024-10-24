<?php

namespace App\Filament\Clusters\Products\Resources\ProductResource\Pages;

use App\Filament\Clusters\Products\Resources\ProductResource;
use Filament\Actions;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

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
            ->title('Product updated successfully')
            ->body('The product has been updated successfully');
    }

    protected function afterSave()
    {
        $product = $this->record;
        Notification::make()
            ->warning()
            ->icon('heroicon-o-bolt')
            ->title('Update Product')
            ->body('The product: ' . $product->name . ' has been updated successfully')
            ->actions([
                Action::make('View')
                    ->url(ProductResource::getUrl('edit', ['record' => $product])),
            ])
            ->sendToDatabase(Auth::user());
    }
}
