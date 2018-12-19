<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['identification', 'phone', 'cell_Phone', 'email', 'cell_phone', 'user_id'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public static function getClientContractsById($id)
    {
        $contracts = Contract::with('client')->whereHas('client', function($q) use($id){
            $q->where('id', $id);
        })->get();

        return $contracts;
    }

}
