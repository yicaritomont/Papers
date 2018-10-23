<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inspector extends Model
{
      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'identification', 'phone', 'addres', 'email', 'profession_id', 'inspector_type_id'
    ];

    public function profession()
    {
        return $this->belongsTo('professions','profession_id','id');
    }

    public function inspectorType()
    {
        return $this->belongsTo('inspector_types','inspector_type_id','id');
    }
}
