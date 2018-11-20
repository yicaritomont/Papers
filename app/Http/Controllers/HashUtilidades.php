<?php
/**
* HashUtilidades.php
*/
namespace App\Http\Controllers;

class HashUtilidades
{
    public static function generarHash(string $datos):string
    {
        return hash('sha256', $datos);
    }
}