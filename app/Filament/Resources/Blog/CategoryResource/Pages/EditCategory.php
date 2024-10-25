<?php

namespace App\Filament\Resources\Blog\CategoryResource\Pages;

use App\Filament\Resources\Blog\CategoryResource;
use Filament\Actions;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

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
            ->title('Blog Category updated successfully')
            ->body('The blog category has been updated successfully');
    }

    protected function afterSave()
    {
        $category = $this->record;
        Notification::make()
            ->warning()
            ->icon('heroicon-o-rectangle-stack')
            ->title('Update Blog Category')
            ->body('The blog category: ' . $category->name . ' has been updated successfully')
            ->actions([
                Action::make('View')
                    ->url(CategoryResource::getUrl('edit', ['record' => $category])),
            ])
            ->sendToDatabase(Auth::user());
    }
}
