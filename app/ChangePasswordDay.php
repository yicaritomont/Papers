<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChangePasswordDay extends Model
{
    //
    protected $fillable = ['days'];
	protected $table = 'change_password_days';
}
