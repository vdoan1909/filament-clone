<?php

namespace App\Models;

use App\Models\Blog\Post;
use App\Models\Shop\Customer;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'customer_id',
        'commentable_type',
        'commentable_id',
        'title',
        'content',
        'is_visible'
    ];

    protected $casts = [
        'is_visible' => 'boolean'
    ];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
