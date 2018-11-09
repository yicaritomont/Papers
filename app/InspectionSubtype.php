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
    protected $fillable = ['inspection_type_id', 'name'];

    public function inspection_types()
    {
        return $this->belongsTo(InspectionType::class, 'inspection_type_id', 'id');
    }

}
