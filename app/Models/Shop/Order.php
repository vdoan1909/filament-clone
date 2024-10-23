<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const STATUS_ORDERS = [
        'new' => 'new',
        'processing' => 'processing',
        'shipped' => 'shipped',
        'delivered' => 'delivered',
        'cancelled' => 'cancelled'
    ];

    const STATUS_PAYMENTS = [
        'unpaid' => 'unpaid',
        'paid' => 'paid'
    ];
}
