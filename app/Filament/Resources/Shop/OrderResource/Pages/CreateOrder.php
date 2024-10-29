<?php

namespace App\Filament\Resources\Shop\OrderResource\Pages;

use App\Filament\Resources\Shop\OrderResource;
use Filament\Actions;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    // xử lý dữ liệu trước khi thêm vào bảng order_items
    // dùng cho việc thêm vào nhiều bảng 1 lúc

    protected function handleRecordCreation(array $data): Model
    {
        $totalPrice = array_reduce($data['items'], function ($sum, $item) {
            return $sum + $item['total_price'];
        }, 0);
        $data['total_price'] = $totalPrice;

        $order = static::getModel()::create($data);

        foreach ($data['items'] as $item) {
            // Log::info($item);

            $order->items()->create(
                [
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                ]
            );
        }

        return $order;
    }

    protected function getCreatedNotification(): Notification|null
    {
        return Notification::make()
            ->success()
            ->title('Order created successfully')
            ->body('The order has been created successfully');
    }

    protected function afterCreate()
    {
        $product = $this->record;
        Notification::make()
            ->success()
            ->icon('heroicon-o-shopping-bag')
            ->title('New Order')
            ->body('A new order has been created successfully')
            ->actions([
                Action::make('View')
                    ->url(OrderResource::getUrl('edit', ['record' => $product])),
            ])
            ->sendToDatabase(Auth::user());
    }
}
