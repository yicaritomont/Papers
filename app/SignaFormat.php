<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SignaFormat extends Model
{
    //
    protected $fillable = ['id_formato' , 'id_usuario' , 'id_firma' , 'base64'];
	protected $table = 'signa_formats';
}
