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
        //$sourcePath=asset('files/test.pdf');
        //$sourcePath = asset('../storage/app/Formato1.pdf');

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
        //echo $base64;
        $data = base64_decode($base64);
        header('Content-Type: application/pdf');
        echo $data;    
    }

    public static function generarHashDocumento($file)
    {
        $file=asset('files/test.pdf');

        return hash_file('md5', $file);

    }

    
    public static function almacenarDocumentoPDFdeBase64($base64,$idFirma)
    {
        
        // we give the file a random name
        $name    = "fomato_".$idFirma.".pdf";

        // a route is created, (it must already be created in its repository(pdf)).
        $rute    = '/storage/app/'.$name;

        // decode base64
        $pdf_b64 = base64_decode($base64);

        // you record the file in existing folder
        file_put_contents($rute, $pdf_b64);

        return $rute;
    }

}