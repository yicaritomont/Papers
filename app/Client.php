<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['name', 'lastname', 'phone', 'email', 'cell_phone', 'slug'];

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
