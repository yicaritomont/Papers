<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InspectorAgenda extends Model
{

    protected $fillable = ['inspector_id', 'start_date', 'end_date', 'city_id', 'slug'];

    public function getRouteKeyName()
    {
        return 'slug';
    }
    
    public function inspector()
    {
        return $this->belongsTo(Inspector::class);
    }

    public function city()
    {
        return $this->belongsTo(Citie::class);
    }

    public function getFullDateAttribute()
    {
        return "{$this->city->name} {$this->city->countries->name}";
    }

}
