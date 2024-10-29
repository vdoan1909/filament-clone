<?php

namespace App\Filament\Resources\Shop\OrderResource\Pages;

use App\Filament\Resources\Shop\OrderResource;
use Filament\Actions;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

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
            ->title('Order updated successfully')
            ->body('The order has been updated successfully');
    }

    protected function afterSave()
    {
        $product = $this->record;
        Notification::make()
            ->success()
            ->icon('heroicon-o-shopping-bag')
            ->title('Update Order')
            ->body('The order has been updated successfully')
            ->actions([
                Action::make('View')
                    ->url(OrderResource::getUrl('edit', ['record' => $product])),
            ])
            ->sendToDatabase(Auth::user());
    }
}
