<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Format extends Model
{
    protected $filable = [
      'preformato_id', ' format', 'state',
    ];

    public function preformato()
    {
      return $this->belongsTo('App\Preformato', 'preformato_id', 'id');
    }
}
