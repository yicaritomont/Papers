<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Client;
use App\Citie;

class Headquarters extends Model
{

    protected $fillable = ['client_id', 'latitude', 'longitude', 'name', 'address', 'status'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function cities()
    {
        return $this->belongsTo(Citie::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
