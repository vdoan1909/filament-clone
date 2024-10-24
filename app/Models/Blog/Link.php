<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Link extends Model
{
    protected $fillable = [
        'url',
        'image',
        'title',
        'description'
    ];

    public static function booted()
    {
        static::updating(function ($category) {
            if ($category->isDirty('image')) {
                // Log::info($category->isDirty('image'));
                if ($category->getOriginal('image')) {
                    // Log::info($category->getOriginal('image'));
                    Storage::delete($category->getOriginal('image'));
                }
            }
        });
    }
}
