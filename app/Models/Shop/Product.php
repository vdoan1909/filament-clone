<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Tags\HasTags;

class Product extends Model implements HasMedia
{
    use SoftDeletes, HasTags, InteractsWithMedia;
    protected $fillable = [
        'shop_category_id',
        'brand_id',
        'name',
        'slug',
        'sku',
        'description',
        'old_price',
        'price',
        'quantity',
        'security_stock',
        'published_at',
        'seo_title',
        'seo_description',
        'is_active'
    ];

    public static function booted()
    {
        static::creating(function ($product) {
            $product->slug = Str::slug($product->name);
        });

        static::updating(function ($product) {
            $product->slug = Str::slug($product->name);
        });
    }

    protected $casts = [
        'is_active' => 'boolean',
        'is_stock' => 'boolean',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function shop_category()
    {
        return $this->belongsTo(ShopCategory::class);
    }
}
