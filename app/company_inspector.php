<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class company_inspector extends Model
{
    //
    protected $table = 'company_inspector';
    protected $fillable = ['company_id', 'inspector_id'];
}
