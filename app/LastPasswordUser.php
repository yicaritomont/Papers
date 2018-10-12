<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LastPasswordUser extends Model
{
    //
    protected $fillable = ['user','password'];
	protected $table = 'lasts_password_users';

}
