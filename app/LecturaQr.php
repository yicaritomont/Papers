<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LecturaQr extends Model
{
    //
    protected $fillable = ['id_usuario','id_inspector','long','lat'];
	protected $table = 'lecturaqr';
}
