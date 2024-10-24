<?php

namespace App\Filament\Clusters\Products\Resources\CategoryResource\Pages;

use App\Filament\Clusters\Products\Resources\CategoryResource;
use Filament\Actions;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getCreatedNotification(): Notification|null
    {
        return Notification::make()
            ->success()
            ->title('Category created successfully')
            ->body('The category has been created successfully');
    }

    protected function afterCreate()
    {
        $category = $this->record;
        Notification::make()
            ->success()
            ->icon('heroicon-o-tag')
            ->title('New Shop Category')
            ->body('The category: ' . $category->name . ' has been created successfully')
            ->actions([
                Action::make('View')
                    ->url(CategoryResource::getUrl('edit', ['record' => $category])),
            ])
            ->sendToDatabase(Auth::user());
    }
}
