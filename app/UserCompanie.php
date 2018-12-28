<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCompanie extends Model
{
    //
    protected $table = 'user_company';
    protected $fillable = ['company_id', 'user_id','status'];

    public function company()
    {
        return $this->belongsToMany(Company::class, 'user_company','id','company_id');
    }

    public function user()
    {
        return $this->belongsToMany(User::class, 'user_company','id','user_id');
    }
    
}
