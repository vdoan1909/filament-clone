<?php

namespace App\Filament\Resources\Shop\OrderResource\Pages;

use App\Filament\Resources\Shop\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

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
}
