<?php

namespace App\Filament\Resources\Blog\LinkResource\Pages;

use App\Filament\Resources\Blog\LinkResource;
use Filament\Actions;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateLink extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;
    protected static string $resource = LinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }

    protected function getCreatedNotification(): Notification|null
    {
        return Notification::make()
            ->success()
            ->title('Link created successfully')
            ->body('The link has been created successfully');
    }

    protected function afterCreate()
    {
        $category = $this->record;
        Notification::make()
            ->success()
            ->icon('heroicon-o-link')
            ->title('New Link')
            ->body('The link: ' . $category->name . ' has been created successfully')
            ->actions([
                Action::make('View')
                    ->url(LinkResource::getUrl('edit', ['record' => $category])),
            ])
            ->sendToDatabase(Auth::user());
    }
}
