<?php

namespace App\Filament\Resources\Blog\PostResource\Pages;

use App\Filament\Resources\Blog\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

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
            ->title('Post updated successfully')
            ->body('The post has been updated successfully');
    }

    protected function afterSave()
    {
        $post = $this->record;
        Notification::make()
            ->success()
            ->icon('heroicon-o-document-text')
            ->title('Update Post')
            ->body('The post: ' . $post->title . ' has been updated successfully')
            ->actions([
                Action::make('View')
                    ->url(PostResource::getUrl('edit', ['record' => $post])),
            ])
            ->sendToDatabase(Auth::user());
    }
}
