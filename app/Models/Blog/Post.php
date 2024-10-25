<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Spatie\Tags\HasTags;
use Illuminate\Support\Str;

class Post extends Model
{
    use SoftDeletes, HasTags;

    protected $fillable = [
        'blog_author_id',
        'blog_category_id',
        'title',
        'slug',
        'image',
        'content',
        'published_at',
        'seo_title',
        'seo_description'
    ];

    public static function booted()
    {
        static::creating(function ($post) {
            $post->slug = Str::slug($post->title);
        });

        static::updating(function ($post) {
            $post->slug = Str::slug($post->title);

            if ($post->isDirty('image')) {
                // Log::info($post->isDirty('image'));
                if ($post->getOriginal('image')) {
                    // Log::info($post->getOriginal('image'));
                    Storage::delete($post->getOriginal('image'));
                }
            }
        });
    }

    public function blog_author()
    {
        return $this->belongsTo(BlogAuthor::class);
    }

    public function blog_category()
    {
        return $this->belongsTo(BlogCategory::class);
    }
}
