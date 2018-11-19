<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Citie extends Model
{
    protected $fillable = ['id', 'countries_id', 'name', 'numcode'];

    public function country()
    {
        return $this->belongsTo('App\Country','countries_id');
    }

    public function headquarters()
    {
        return $this->hasMany(Headquarters::class);
    }

    public function countries()
    {
        return $this->belongsTo(Country::class);
    }
}
