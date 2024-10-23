<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Brand extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'slug',
        'website',
        'description',
        'is_active',
        'seo_title',
        'seo_description',
    ];

    public static function booted()
    {
        static::creating(function ($brand) {
            $brand->slug = Str::slug($brand->name);
        });

        static::updating(function ($brand) {
            $brand->slug = Str::slug($brand->name);
        });
    }

    protected $cats = [
        'is_active' => 'boolean',
    ];
}
