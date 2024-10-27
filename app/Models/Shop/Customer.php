<?php

namespace App\Models\Shop;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    const GENDER = [
        'male' => 'Male',
        'female' => 'Female',
    ];

    protected $fillable = [
        'country_id',
        'state_id',
        'city_id',
        'name',
        'email',
        'photo',
        'gender',
        'phone',
        'birthday'
    ];

    public function country(){
        return $this->belongsTo(Country::class);
    }

    public function state(){
        return $this->belongsTo(State::class);
    }

    public function city(){
        return $this->belongsTo(City::class);
    }
}
