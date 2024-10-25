<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Link extends Model
{
    use HasTranslations;

    protected $fillable = [
        'url',
        'image',
        'title',
        'slug',
        'color',
        'description'
    ];

    protected $translatable = [
        'title',
        'slug',
        'description'
    ];

    public static function booted()
    {
        static::creating(function ($link) {
            $link->slug = Str::slug($link->title);
        });

        static::updating(function ($link) {
            $link->slug = Str::slug($link->title);

            if ($link->isDirty('image')) {
                // Log::info($link->isDirty('image'));
                if ($link->getOriginal('image')) {
                    // Log::info($link->getOriginal('image'));
                    Storage::delete($link->getOriginal('image'));
                }
            }
        });

        static::deleting(function ($link) {
            if ($link->isForceDeleting()) {
                if ($link->image) {
                    Storage::delete($link->image);
                }
            }
        });
    }
}
