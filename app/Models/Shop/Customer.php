<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    const GENDER = [
        'male' => 'Male',
        'female' => 'Female',
    ];

    protected $fillable = [
        'name',
        'email',
        'photo',
        'gender',
        'phone',
        'birthday'
    ];
}
