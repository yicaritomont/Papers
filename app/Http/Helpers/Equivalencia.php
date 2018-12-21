<?php

namespace App\Http\Helpers;
use App\SignaFormat;
use App\SelloFormat;

class Equivalencia{

    private static $activo = 1;
    private static $inactivo = 0;

    //files
    private static $size = 20000;
    private static $types = ["jpg","png","gif","pdf","html","htm","txt","csv","docx","doc","odt","xlsx","csv","xls","xlsb","ods","pptx","sldx","mp3","wav","ogg","wma","mp4","avi","mkv","mpeg","mov","zip","rar","7z"];


    //Getters
    public static function activo(){
        return self::$activo;
    }

    public static function inactivo(){
        return self::$inactivo;
    }

    public static function size(){
        return self::$size;
    }

    public static function types(){
        return self::$types;
    }

    public static function numeroFirmasPorFormato($id)
    {
        $firmasFormato = SignaFormat::where('id_formato',$id)->get();
        return count($firmasFormato);
    }

    public static function numeroSellosPorFormato($id)
    {
        $sellosFormato = SelloFormat::where('id_formato',$id)->get();
        return count($sellosFormato);
    }

}
