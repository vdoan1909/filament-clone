<?php

namespace App\Filament\Resources\Blog\LinkResource\Pages;

use App\Filament\Resources\Blog\LinkResource;
use Filament\Actions;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditLink extends EditRecord
{
    use EditRecord\Concerns\Translatable;
    protected static string $resource = LinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
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
            ->icon('heroicon-o-tag')
            ->title('Update Blog Category')
            ->body('The blog category: ' . $category->name . ' has been updated successfully')
            ->actions([
                Action::make('View')
                    ->url(LinkResource::getUrl('edit', ['record' => $category])),
            ])
            ->sendToDatabase(Auth::user());
    }
}
