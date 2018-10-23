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
    protected $fillable = [
        'name','inspection_type_id'
    ];

    public function inspectionType()
    {
        return $this->belongsTo('inspection_subtypes','inspection_subtype_id','id');
    }

}
