<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'shop_category_id',
        'brand_id',
        'name',
        'slug',
        'sku',
        'image',
        'description',
        'old_price',
        'price',
        'quantity',
        'published_at',
        'seo_title',
        'seo_description',
        'is_active',
        'is_stock'
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
