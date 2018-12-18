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
            $result = array();
    
            if (!is_array($arregloObjeto)) {
                $arregloObjeto = func_get_args();
            }
    
            foreach ($arregloObjeto as $key => $value) {
                if (is_array($value)) {
                    $result = array_merge($result, array_flatten($value));
                } else {
                    $result = array_merge($result, array($key => $value));
                }
            }
    
            $cadenaConcatenada = implode('_', str_replace(' ', '_',$result));            
        }       

        return $cadenaConcatenada;
    }
}


?>