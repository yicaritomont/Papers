<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{

    protected $fillable = ['name', 'address', 'phone', 'email', 'status', 'activity','user_id'];

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public static function getCompanyClientsById($id)
    {
        $clients = Client::with('user')->whereHas('user.companies', function($q) use($id){
            $q->where('companies.id', $id);
        })->get();

        return $clients;
    }

}
