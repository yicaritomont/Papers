<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SignaFormat extends Model
{
    //
    protected $fillable = ['id_formato' , 'id_usuario' , 'id_firma' , 'base64'];
    protected $table = 'signa_formats';
    

    public function formato()
    {
        return $this->belongsTo('App\Formato', 'id_formato', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'id_usuario', 'id');
    }

}
