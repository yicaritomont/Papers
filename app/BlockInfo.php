<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlockInfo extends Model
{
    //
    protected $fillable = ['id_formato','id_usuario','hash','base64','tx_hash'];
	protected $table = 'block_info';
}
