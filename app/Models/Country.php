<?php

namespace App\Models;

use App\Models\Shop\Customer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function states()
    {
        return $this->hasMany(State::class);
    }

    public function customers(){
        return $this->hasMany(Customer::class);
    }
}
