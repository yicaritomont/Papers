<?php
/**
* ObtenerConcatenadoObjeto.php
* Ruta:              
* Fecha Creación:    Ago 2018
*
* 
*
* @author          
* @copyright        2018 
* @license          GPL 2 or later
* @version          2018
*
*/

namespace App\Http\Controllers;

class ObtenerConcatenadoObjeto
{
    /*
	* La functiòn recibe como parametro el objeto eloquent 
	* para retornar el objeto concatenado.	
    */    
    
    public static function concatenar($objeto)
    {
       

        $cadenaConcatenada = "";
        // Se verifica la collection
        if($objeto)
        {
            $arregloObjeto = $objeto->toArray();
            ksort($arregloObjeto);
            $cadenaConcatenada = implode('_', str_replace(' ', '_',$arregloObjeto));
        }
        
        return $cadenaConcatenada;
    }
}


?>