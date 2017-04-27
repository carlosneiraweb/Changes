<?php

/**
 * Description of Connection
 *
 * @author Carlos Neira Sanchez
 * Clase que crea y cierra una conexion
 */

require_once 'Constantes.php';
class Conne{
   
      /**
     * Metodo protected
     * connect
     * @return \PDO
     */
    static function connect(){
        
        try{
            
            $utf = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
            //array(1002 => 'SET NAMES utf8')
            $con = new PDO(DB_DNS, DB_USERNAME, DB_PASSWORD,$utf);
            $con->setAttribute(PDO::ATTR_PERSISTENT, true);
            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $ex) {
            die("Connection failed: ".$ex->getMessage());
        }
       
        return $con;
    //fin connect    
    }
    
    /**
     * Metodo protected
     * disconnect
     * @param string $con
     */
    static function disconnect($con){
        //Ojo no eliminamos la conexion
        $con="";
    }
    
    
    
    
//fin connection    
}
