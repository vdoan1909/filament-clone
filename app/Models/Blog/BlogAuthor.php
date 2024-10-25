<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogAuthor extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'photo',
        'bio',
        'github_handle',
        'twitter_handle'
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
