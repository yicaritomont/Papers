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

    public static function generarBase64Documento($sourcePath)
    {
        $sourcePath=asset('files/test.pdf');

        $source = file_get_contents($sourcePath);
        if (empty($source) ) 
        {
            die('source file is empty');
        }

        $data = base64_encode($source);
        
        return $data;
    }

    public static function obtenerDocumentoBase64($base64)
    {
        //return $base64;    
        $data = base64_decode($base64);
        header('Content-Type: application/pdf');
        echo $data;    
    }

    public static function generarHashDocumento($file)
    {
        $file=asset('files/test.pdf');

        return hash_file('sha256', $file);

    }

}