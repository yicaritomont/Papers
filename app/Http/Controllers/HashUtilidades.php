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

        return hash_file('md5', $file);

    }

    public static function base64binary()
    {
        $file=asset('files/test.pdf');
        $realname = "/usr/local/ampps/www/roles-permissions/public/files/test.pdf";
        $fd = fopen($file, 'rb');
        $size = filesize($realname);
        $cont = fread($fd, $size);
        fclose($fd);
        $encimg = base64_encode($cont);
        return $encimg;
    }

}