<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class InspectorType extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'inspection_subtypes_id'
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function inspection_subtypes()
    {
        return $this->belongsTo(InspectionSubtype::class, 'inspection_subtypes_id', 'id');
    }

}
