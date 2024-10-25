<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Tags\HasTags;

class Product extends Model
{
    use SoftDeletes, HasTags;
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
        'security_stock',
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

            if ($product->isDirty('image')) {
                if ($product->getOriginal('image')) {
                    Storage::delete($product->getOriginal('image'));
                }
            }
        });

        static::deleting(function ($product) {
            if ($product->isForceDeleting()) {
                if ($product->image) {
                    Storage::delete($product->image);
                }
            }
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
