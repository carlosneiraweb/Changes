
<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Constantes/ConstantesSistema.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Conne.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/DataObj.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/Usuarios.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Constantes/ConstantesBbdd.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Controlador/Validar/MisExcepcionesUsuario.php');

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
     * Metodo que recupera el Hash del usuario de la bbdd
     * Devuelve el hash si es posible o false 
     * en caso negativo.
     * Recive el nick del usuario.
     * @param type nick de usuario
     *  @return type columna de la tabla el Hash
     */
    final public static function recuperarHash($nick){
        
        $conHash = Conne::connect();
        $sqlHash = null;
            //Recuperamos el Hash del usuario "Contraseña encriptada"
        
        try{
            $sqlHash = "Select password FROM ".TBL_USUARIO. " WHERE nick = :nick; ";
            $stHash = $conHash->prepare($sqlHash);
            $stHash->bindValue(":nick", $nick, PDO::PARAM_STR);
            $stHash->execute();
            $rowHash = $stHash->fetch();

            $stHash->closeCursor();
            Conne::disconnect($conHash);
            
            if ($rowHash[0]) {
                return $rowHash[0];
            }else{
                return 0;
            }
            
               
        } catch (Exception $ex) {
            echo $ex->getCode();
            echo '<br>';
            echo $ex->getLine().'<br>';
            echo $ex->getFile().'<br>';
           Conne::disconnect($conHash);
          
        }
        
    //fin recuperarHash    
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
