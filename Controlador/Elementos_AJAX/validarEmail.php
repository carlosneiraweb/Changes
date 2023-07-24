<?php

  header('Content-Type: application/json');
  header('Cache-Control: no-cache, must-revalidate');
  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
  header('Content-type: application/json; charset=utf-8');


require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Conne.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Constantes/ConstantesBbdd.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/DataObj.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/Usuarios.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Email/mandarEmails.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Controlador/Validar/ControlErroresSistemaEnArchivosUsuarios.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Controlador/Validar/MisExcepcionesUsuario.php');


if(!isset($_SESSION)){
    
 session_start();

} 


if (isset($_POST['actv'])) {
    $hash =  $_POST['actv'];
} else { 
    if(isset($_GET['actv'])){ 
        $hash = $_GET['actv'];
    }
}

if (isset($_POST['nick'])) {
        $nick =  $_POST['nick'];
} else { 
    if (isset($_GET['nick'])){ 
        $nick = $_GET['nick'];
    }
}

/**
 * Este metodo elimina de la tabla<br/>
 * Desbloquear al usuario que se <br/>
 * desbloqueado desde el email recibido.<br/>
 * @param String id<br>
 * @param String nick 
 */
function eliminarTablaDesbloquear($id, $nick){
    
   
    
    try{
        
        $con = Conne::connect();
        
        
        $sqlEliminar = "Delete from ".TBL_DESBLOQUEAR. 
                " WHERE idDesbloquear = $id;";
        
       
        $count = $con->exec($sqlEliminar);
        
        if(!$count){throw new Exception("Hubo un error al eliminar al usuario de la tabla Desbloquear",0);}


    }catch(Exception $ex){
     
        $_SESSION['emailNoActivado'] = $nick. " y su id es $id";//se elimina en metodosInfoExcepcione
        $excepciones = new MisExcepcionesUsuario(CONST_ERROR_BBDD_ELIMINAR_TABLA_DESBLOQUEO[1],CONST_ERROR_BBDD_ELIMINAR_TABLA_DESBLOQUEO[0],$ex);
        $excepciones->redirigirPorErrorSistemaUsuario("desbloquearUsuario",false);
    }
    
    
    
    
}
/**
 * 
 * @param type $id <br/>
 * El id del usuario a desbloquear<br/>
 * @param type $nick <br/>
 * El nick del usuario <br/>
 * Si tiene exito elimina de la tabla Desbloquear al usuario<br/>
 * y nos redirige a index.php
 */
function activarCuenta($id,$nick){ 

    try{
 
      
       
        $con = Conne::connect(); 

        $sqlValiEmail = "Update ".TBL_USUARIO. " SET bloqueado = ".DESBLOQUEO_EMAIL." WHERE  idUsuario = $id;";
        
        $count = $con->exec($sqlValiEmail);
        
                if($count === 0){throw new Exception("Error al activar la cuenta desde el email",0);}
       
            if($count === 1){

               eliminarTablaDesbloquear($id,$nick);
               mandarEmails::mandarEmailWelcome($nick);
               header(MOSTRAR_PAGINA_INDEX);
            }

    } catch (Exception $ex) {
      
        $_SESSION['emailNoActivado'] = $nick. " y su id es: $id";//se elimina en metodosInfoExcepcione
        $excepciones = new MisExcepcionesUsuario(CONST_ERROR_BBDD_ACTIVAR_CUENTA_EMAIL[1],CONST_ERROR_BBDD_ACTIVAR_CUENTA_EMAIL[0],$ex);
        $excepciones->redirigirPorErrorSistemaUsuario("desbloquearUsuario",true);
    }    
    
    
}



/**
 *Comprobamos que en la tabla <br/>
 * Desbloquear hay un hash como el recibido en su correo.<br/>
 * Recuperamos id, hash y nick
  *
 */
  
try{
  
    $con = Conne::connect(); 
    
    $sqlCompararHash = "SELECT idDesbloquear from ".TBL_DESBLOQUEAR.
              " WHERE  nick = :nick;";
   
    $stmComparar = $con->prepare($sqlCompararHash);
    $stmComparar->bindValue(":nick",$nick , PDO::PARAM_STR);
    $stmComparar->execute();
    $row = $stmComparar->fetch();
    //var_dump($row);
    if($row[0] == false){throw new Exception("No se pudo recuperar datos de la tabla Desbloqueo",0);}
    
   
    if(strcmp($hash, $row[1]) === 0){
        
        activarCuenta($row[0],$nick);
    }
      
    
 
    } catch (Exception $ex) {
        
        //Se elimina en metodosInfoExcepciones
        //se usa de bandera para recojer datos
        //para ingresar en la bbdd en los errores
        $_SESSION['emailNoActivado'] = $nick;
        $excepciones = new MisExcepcionesUsuario(CONST_ERROR_BBDD_RECUPERAR_DATOS_TABLA_DESBLOQUEAR[1],CONST_ERROR_BBDD_RECUPERAR_DATOS_TABLA_DESBLOQUEAR[0],$ex);
        $excepciones->redirigirPorErrorSistemaUsuario("desbloquearUsuario",true);

} 