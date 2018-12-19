<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = ['formato_id', 'user_id', 'nombre', 'nombre_url', 'mime_type','estado','extension'];
}
