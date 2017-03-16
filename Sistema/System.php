<?php

/**
 * Description of System
 * Esta clase se encarga de todos los metodos
 * relacionados con el Systema
 * @author carlos
 */
class System {
    
    
/**
 * Este metodo devuelve un hash calculando
 * el coste computacional. Sin un coste
 * demasiado elevado en este.
 * El código que sigue tiene como objetivo un tramo de
 * ≤ 50 milisegundos, 
 * que es una buena referencia para sistemas con registros interactivos.
 * Fuente: php.net/manual/es/function.password-hash.php
 * @param type $pass
 * @return type
 */    
final static function generoHash ($pass){
     
$timeTarget = 0.05; // 50 milisegundos 
$coste = 8;
$hash;
do {
    $coste++;
    $inicio = microtime(true);
    $hash = password_hash($pass, PASSWORD_BCRYPT, ["cost" => $coste]);
    $fin = microtime(true);
} while (($fin - $inicio) < $timeTarget);
   
    return $hash; 
     
     
//generoHash     
}   

/**
 * Metodo que recive un hash sacado
 * de la bbdd y lo compara con el recivido
 * como de un formulario.
 * Si es correcto devuelve true sino false
 */
 final static function comparaHash($hash, $pass){
     
    try {
        if (password_verify($pass, $hash)) {
            return 1;
        } else {
            return 0;
        }
        
    } catch (Exception $exc) {
         echo $exc->getCode();
    }
//comparaHash     
 }   
    
    
    
    
    
    
    
//fin System    
}
