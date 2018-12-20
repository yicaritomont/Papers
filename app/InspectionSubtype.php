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

    public function inspector_types()
    {
        return $this->hasMany(InspectorType::class,'inspection_subtypes_id');
    }

    public function getSubtypeTypeAttribute()
    {
        return $this->name . ' - ' . $this->inspection_types->name;
    }
}
