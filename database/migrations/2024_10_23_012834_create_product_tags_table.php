<?php

use App\Models\Shop\Product;
use App\Models\Shop\ShopTag;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_tags', function (Blueprint $table) {
            $table->foreignIdFor(Product::class)
            ->constrained()
            ->cascadeOnDelete();

            $table->foreignIdFor(ShopTag::class)
            ->constrained()
            ->cascadeOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_tags');
    }
};
