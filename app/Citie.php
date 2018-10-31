<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Headquarters;

class Citie extends Model
{

    //protected $table = 'cities';

    public function headquarters()
    {
        return $this->hasMany(Headquarters::class);
    }
}