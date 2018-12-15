<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = ['id', 'name','numcode'];

    public static function getCountryCitiesById($id)
    {
        $clients = Citie::whereHas('countries', function($q) use($id){
            $q->where('id', $id);
        })->get();

        return $clients;
    }
}
