<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InspectorAgenda extends Model
{

    protected $fillable = ['inspector_id', 'headquarters_id', 'date', 'start_time', 'end_time'];

    public function getRouteKeyName()
    {
        return 'slug';
    }
    
    public function inspector()
    {
        return $this->belongsTo(Inspector::class);
    }

    public function headquarters()
    {
        return $this->belongsTo(Headquarters::class);
    }

}
