<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Password_reminder extends Model
{
    //
    protected $fillable = ['email','token'];
	protected $table = 'password_reminders';
}
