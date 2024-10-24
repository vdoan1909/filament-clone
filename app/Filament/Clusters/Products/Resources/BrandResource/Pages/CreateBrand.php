<?php

namespace App\Filament\Clusters\Products\Resources\BrandResource\Pages;

use App\Filament\Clusters\Products\Resources\BrandResource;
use Filament\Actions;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateBrand extends CreateRecord
{
    protected static string $resource = BrandResource::class;

    protected function getCreatedNotification(): Notification|null
    {
        return Notification::make()
            ->success()
            ->title('Brand created successfully')
            ->body('The brand has been created successfully');
    }

    protected function afterCreate()
    {
        $brand = $this->record;
        Notification::make()
            ->success()
            ->icon('heroicon-o-bookmark-square')
            ->title('New Brand')
            ->body('The brand: ' . $brand->name . ' has been created successfully')
            ->actions([
                Action::make('View')
                    ->url(BrandResource::getUrl('edit', ['record' => $brand])),
            ])
            ->sendToDatabase(Auth::user());
    }
}
