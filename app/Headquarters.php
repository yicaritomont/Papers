<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Client;
use App\Cities;

class Headquarters extends Model
{

    protected $fillable = ['client_id', 'cities_id', 'name', 'address', 'status', 'slug'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function cities()
    {
        return $this->belongsTo(Cities::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
