<?php

use App\Models\Blog\BlogAuthor;
use App\Models\Blog\BlogCategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(BlogAuthor::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignIdFor(BlogCategory::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->string('title', 100);
            $table->string('slug', 130);
            $table->string('image')->nullable();
            $table->text('content');
            $table->date('published_at')->nullable();
            $table->string('seo_title', 100)->nullable();
            $table->text('seo_description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
