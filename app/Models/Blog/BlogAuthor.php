<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Model;

class BlogAuthor extends Model
{
    protected $fillable = [
        'name',
        'email',
        'photo',
        'bio',
        'github_handle',
        'twitter_handle'
    ];
}
