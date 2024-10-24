<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
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

        static::deleting(function ($category) {

            if ($category->isForceDeleting()) {
                $category->products()->withTrashed()->each(function ($product) {
                    Storage::delete($product->image);
                });
                $category->products()->forceDelete();
            } else {
                $category->products()->withTrashed()->delete();
            }
        });

        static::restoring(function ($category) {
            $category->products()->restore();
        });
    }

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
