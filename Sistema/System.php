
<?php

 require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Constantes/ConstantesSistema.php');

/**
 * @author Carlos Neira Sanchez
 * @mail arj.123@hotmail.es
 * @telefono ""
 * @nameAndExt System.php
 * @fecha 04-oct-2016
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
public final static function comparaHash( $pass, $hash){
     
    try {
        
        $x = password_verify($pass, $hash);
        //$x = hash_equals($pass, $hash);
       return $x;
        
    } catch (Exception $exc) {
         echo $exc->getCode();
    }
//comparaHash     
 }   

 
 
 /**
  * Metodo que desencripta informacion
  */
 
 public final static function desencriptar($valor){
     
     return openssl_decrypt($valor, METHOD, CLAVE, false, IV);
     
 } 
 
 
 
 /**
  * Metodo que encripta informacion
  */
 
 public final static function encriptar($valor){
     
     return openssl_encrypt ($valor, METHOD, CLAVE, false, IV);
     
 } 
 
/**
 * No implementado
 */   
protected function calculoMemoria(){
  $a = exec("vmstat -n 1 2");
     
    $nofin = true;
    $len = strlen($a);
    $c = 0;
    $cad = "";
     
    while($nofin)
    {
     
        if ($a[$len-1] == " " && $a[$len] != " ")       
            $c++;           
        
        if ($c == 4)
        {
            
            $cad = $a[$len].$a[$len+1].$a[$len+2];
            $nofin = false;
        
        }
     
        $len--;
        
    }
     
     
    echo $cad; //BINGO
//fin calculoMemoria       
}     
     
    
        
/*
* Metodo para optener la direción real 
 * del visistante
 */
final static function ipVisitante (){
    
    if (isset($_SERVER["HTTP_CLIENT_IP"]))
    {
        return $_SERVER["HTTP_CLIENT_IP"];
    }
    elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
    {
        return $_SERVER["HTTP_X_FORWARDED_FOR"];
    }
    elseif (isset($_SERVER["HTTP_X_FORWARDED"]))
    {
        return $_SERVER["HTTP_X_FORWARDED"];
    }
    elseif (isset($_SERVER["HTTP_FORWARDED_FOR"]))
    {
        return $_SERVER["HTTP_FORWARDED_FOR"];
    }
    elseif (isset($_SERVER["HTTP_FORWARDED"]))
    {
        return $_SERVER["HTTP_FORWARDED"];
    }
    else
    {
        return $_SERVER["REMOTE_ADDR"];
    }
    
} //Fin optener ip real     
     
    
    
    
    
//fin System    
}
