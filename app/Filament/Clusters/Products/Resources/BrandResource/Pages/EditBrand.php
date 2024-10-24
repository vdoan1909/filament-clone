<?php

namespace App\Filament\Clusters\Products\Resources\BrandResource\Pages;

use App\Filament\Clusters\Products\Resources\BrandResource;
use Filament\Actions;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditBrand extends EditRecord
{
    protected static string $resource = BrandResource::class;

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
            ->title('Brand updated successfully')
            ->body('The brand has been updated successfully');
    }

    protected function afterSave()
    {
        $brand = $this->record;
        Notification::make()
            ->warning()
            ->icon('heroicon-o-bookmark-square')
            ->title('Update Brand')
            ->body('The brand: ' . $brand->name . ' has been created successfully')
            ->actions([
                Action::make('View')
                    ->url(BrandResource::getUrl('edit', ['record' => $brand])),
            ])
            ->sendToDatabase(Auth::user());
    }
}
