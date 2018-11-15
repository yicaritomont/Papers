<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Preformato extends Model
{
    protected $fillable = [
        'name', 'inspection_subtype_id', 'preformato', 'state',

    ];

    public function inspection_subtype()
    {
        return $this->belongsTo('App\InspectionSubtype','inspection_subtype_id','id');
    }
}
