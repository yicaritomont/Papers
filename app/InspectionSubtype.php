<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InspectionSubtype extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['inspection_type_id', 'name'
    ];

    public function inspectionType()
    {
        return $this->belongsTo('inspection_types','inspection_type_id','id');
    }

}
