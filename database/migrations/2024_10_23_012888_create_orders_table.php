<?php

use App\Models\Shop\Customer;
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

            $table->foreignIdFor(Customer::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->string('number', 10);
            $table->decimal('total_price', 12, 2)->nullable();
            $table->text('notes')->nullable();
            $table->date('order_date')->useCurrent()->nullable();

            $table->string('status_order', 20);
            $table->string('status_payment', 20);

            $table->string('currency', 10);
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
