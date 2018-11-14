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
        'id','name', 'identification', 'phone', 'addres', 'email', 'profession_id', 'inspector_type_id', 'city_id','user_id',
    ];

    public function profession()
    {
        return $this->belongsTo('App\Profession','profession_id','id');
    }

    public function inspectorType()
    {
        return $this->belongsTo('App\InspectorType','inspector_type_id','id');
    }

    public function cities()
    {
        return $this->belongsTo('cities','city_id','id');
    }

    public function companies(){
        return $this->belongsToMany(Company::class, 'company_inspector');
    }

    public function inspector_agendas(){
        return $this->hasMany('App\InspectorAgenda');
    }


}
