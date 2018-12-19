<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SelloFormat extends Model
{
    //
    protected $fillable = ['id_formato','id_usuario','id_sello','sello'];
	protected $table = 'sello_formats';
}
