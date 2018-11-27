<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Format extends Model
{
    protected $filable = [
      'company_id','client_id','preformat_id', ' format', 'status',
    ];

    public function preformato()
    {
      return $this->belongsTo('App\Preformato', 'preformat_id', 'id');
    }

    public function client()
    {
      return $this->belongsTo('App\Client', 'client_id', 'id');
    }

    public function company()
    {
      return $this->belongsTo('App\Company', 'company_id', 'id');
    }
}
