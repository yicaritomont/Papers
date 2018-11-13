<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class usuario_rol extends Model
{
    //
    protected $table = 'usuario_rol';
    protected $fillable = ['user','user_id','rol_id'];
	
}
