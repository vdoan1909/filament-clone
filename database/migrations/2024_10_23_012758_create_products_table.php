<?php
use App\Models\Shop\Brand;
use App\Models\Shop\ShopCategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(ShopCategory::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignIdFor(Brand::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->string('name', 100);
            $table->string('slug', 120)->unique();
            $table->string('sku', 15)->unique();
            $table->string('image');
            $table->text('description')->nullable();
            $table->decimal('old_price', 10, 2)->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->unsignedBigInteger('quantity')->default(0);
            $table->date('published_at')->useCurrent();
            $table->string('seo_title', 100)->nullable();
            $table->text('seo_description')->nullable();
            $table->boolean('is_active')->default(false);
            $table->boolean('is_stock')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
