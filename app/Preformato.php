<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Preformato extends Model
{
    protected $fillable = [
        'inspection_subtype_id','name','format', 'state',

    ];

    public function inspection_subtype()
    {
        return $this->belongsTo('App\InspectionSubtype','inspection_subtype_id','id');
    }
}
