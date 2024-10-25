<?php

namespace App\Filament\Resources\Blog\PostResource\Pages;

use App\Filament\Resources\Blog\PostResource;
use Filament\Actions;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    protected function getCreatedNotification(): Notification|null
    {
        return Notification::make()
            ->success()
            ->title('Post created successfully')
            ->body('The post has been created successfully');
    }

    protected function afterCreate()
    {
        $post = $this->record;
        Notification::make()
            ->success()
            ->icon('heroicon-o-document-text')
            ->title('New Post')
            ->body('The post: ' . $post->title . ' has been created successfully')
            ->actions([
                Action::make('View')
                    ->url(PostResource::getUrl('edit', ['record' => $post])),
            ])
            ->sendToDatabase(Auth::user());
    }
}
