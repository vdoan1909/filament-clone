<?php

use App\Models\Blog\BlogCategory;
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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(User::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignIdFor(BlogCategory::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->string('title', 100);
            $table->string('image');
            $table->string('slug', 130);
            $table->text('content');
            $table->date('published_at')->useCurrent();
            $table->string('seo_title', 100);
            $table->text('seo_description');
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
