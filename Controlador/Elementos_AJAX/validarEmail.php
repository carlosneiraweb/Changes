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
    } else { if (isset($_GET['actv'])) 
        $hash = $_GET['actv'];
    //echo $hash;
}

if (isset($_POST['nick'])) {
        $nick =  $_POST['nick'];
    } else { if (isset($_GET['nick'])) 
        $nick = $_GET['nick'];
}


/**
 * Este metodo elimina de la tabla<br/>
 * Desbloquear al usuario que se <br/>
 * desbloqueado desde el email recibido.
 * @param String $id
 */
function eliminarTablaDesbloquear($id){
    
   
    
    try{
        
        $con = Conne::connect();
        
        
        $sqlEliminar = "Delete from ".TBL_DESBLOQUEAR. 
                " WHERE idDesbloquear = :idDesbloquear;";
        
        $stmELiminar = $con->prepare($sqlEliminar);
        $stmELiminar->bindValue(":idDesbloquear", $id, PDO::PARAM_INT);
        $test = $stmELiminar->execute();
        
        if(!$test){throw Exception();}


    }catch(Exception $ex){
        
        $_SESSION['emailNoActivado'] = $id;//se elimina en metodosInfoExcepciones

        //echo $ex->getMessage();
        $excepciones = new MisExcepcionesUsuario(CONST_ERROR_DESBLOQUEO_USUARIO[1],CONST_ERROR_DESBLOQUEO_USUARIO[0],$ex);
        $excepciones->redirigirPorErrorSistema("desbloquearUsuario",true);
    }
    
    
    
    
}

/**
 * Este metodo desbloquea un usuario <br/>
 * cuando este pincha en el enlace recibido<br/>
 */
function desbloquearUsuario($id){ 

    try{
 
        echo $id;

         $con = Conne::connect(); 


         $sqlValiEmail = "Update ".TBL_USUARIO. " SET bloqueado = :bloqueado WHERE  idUsuario = :idUsuario";
         $stmValiEmail = $con->prepare($sqlValiEmail);
         $stmValiEmail->bindValue(":bloqueado", '0', PDO::PARAM_STMT);
         $stmValiEmail->bindValue(":idUsuario", $id, PDO::PARAM_INT);
        
         $test = $stmValiEmail->execute();
        
         if($test){
             
             eliminarTablaDesbloquear($id);
             header(MOSTRAR_PAGINA_INDEX);
         }

    } catch (Exception $ex) {
   
        $_SESSION['emailNoActivado'] = $id;//se elimina en metodosInfoExcepciones

        //echo $ex->getMessage();
        $excepciones = new MisExcepcionesUsuario(CONST_ERROR_DESBLOQUEO_USUARIO[1],CONST_ERROR_DESBLOQUEO_USUARIO[0],$ex);
        $excepciones->redirigirPorErrorSistema("desbloquearUsuario",true);
    }    
    
    
}



/**
 *Comprobamos que en la tabla <br/>
 * Desbloquear hay un hash e su correo.
 * 
 * 
 *  
 */
  
try{
    
 
    $con = Conne::connect(); 
     
    $sqlCorreo = " SELECT email from ".TBL_USUARIO. 
            " WHERE nick = :nick;";
    
    $stmCorreo = $con->prepare($sqlCorreo);
    $stmCorreo->bindValue(":nick", $nick, PDO::PARAM_STR);
    $stmCorreo->execute();
    $rowCorreo = $stmCorreo->fetch();
    
   
    $sqlCompararHash = "SELECT idDesbloquear, correo from ".TBL_DESBLOQUEAR.
              " WHERE  nick = :nick;";
   
    $stmComparar = $con->prepare($sqlCompararHash);
    $stmComparar->bindValue(":nick", $nick, PDO::PARAM_STR);
    $stmComparar->execute();
    $row = $stmComparar->fetch();
    
    echo $row[0];
   
    if(strcmp($hash, $row[1]) === 0){
     
        if(System::comparaHash($rowCorreo[0], $hash)){
            desbloquearUsuario($row[0]);
        }
      
    }
 
    } catch (Exception $ex) {
        
        $_SESSION['emailNoActivado'] = $nick;//se elimina en metodosInfoExcepciones

        //echo $ex->getMessage();
        $excepciones = new MisExcepcionesUsuario(CONST_ERROR_DESBLOQUEO_USUARIO[1],CONST_ERROR_DESBLOQUEO_USUARIO[0],$ex);
        $excepciones->redirigirPorErrorSistema("desbloquearUsuario",true);

} 