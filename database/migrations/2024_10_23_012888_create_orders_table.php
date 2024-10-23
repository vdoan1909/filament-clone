<?php

use App\Models\Shop\Order;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(User::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->unsignedBigInteger('number');
            $table->decimal('total_price', 12, 2)->nullable();
            $table->text('notes')->nullable();

            $table->enum(
                'status_order',
                [
                    Order::STATUS_ORDERS['new'],
                    Order::STATUS_ORDERS['processing'],
                    Order::STATUS_ORDERS['shipped'],
                    Order::STATUS_ORDERS['delivered'],
                    Order::STATUS_ORDERS['cancelled']
                ]
            )->default(Order::STATUS_ORDERS['new']);

            $table->enum(
                'status_payment',
                [
                    Order::STATUS_PAYMENTS['unpaid'],
                    Order::STATUS_PAYMENTS['paid']
                ]
            )->default(Order::STATUS_PAYMENTS['unpaid']);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
