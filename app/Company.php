<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{

    protected $fillable = ['name', 'address', 'phone', 'email', 'status', 'activity'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_company');
    }

    public function inspectors()
    {
        return $this->belongsToMany(Inspector::class, 'company_inspector');
    }

}
