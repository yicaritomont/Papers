<?php

namespace App\Http\Helpers;

class Equivalencia{

    private static $activo = 1;
    private static $inactivo = 0;


    //Getters
    public static function activo(){
        return self::$activo;
    }

    public static function inactivo(){
        return self::$inactivo;
    }

}
