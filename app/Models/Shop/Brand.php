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

        static::deleting(function ($brand) {

            if ($brand->isForceDeleting()) {
                $brand->products()->withTrashed()->each(function ($product) {
                    Storage::delete($product->image);
                });
                $brand->products()->forceDelete();
            } else {
                $brand->products()->withTrashed()->delete();
            }
        });

        static::restoring(function ($brand) {
            $brand->products()->restore();
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
