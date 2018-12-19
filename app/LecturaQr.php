<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LecturaQr extends Model
{
    //
    protected $fillable = ['user','long','lat'];
	protected $table = 'lecturaqr';
}
