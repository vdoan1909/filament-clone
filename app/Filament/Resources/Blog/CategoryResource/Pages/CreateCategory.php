<?php

namespace App\Filament\Resources\Blog\CategoryResource\Pages;

use App\Filament\Resources\Blog\CategoryResource;
use Filament\Actions;
use Filament\Notifications\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getCreatedNotification(): Notification|null
    {
        return Notification::make()
            ->success()
            ->title('Blog Category created successfully')
            ->body('The blog category has been created successfully');
    }

    protected function afterCreate()
    {
        $category = $this->record;
        Notification::make()
            ->success()
            ->icon('heroicon-o-tag')
            ->title('New Blog Category')
            ->body('The blog category: ' . $category->name . ' has been created successfully')
            ->actions([
                Action::make('View')
                    ->url(CategoryResource::getUrl('edit', ['record' => $category])),
            ])
            ->sendToDatabase(Auth::user());
    }
}
