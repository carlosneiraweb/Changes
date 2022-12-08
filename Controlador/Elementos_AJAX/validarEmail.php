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
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Controlador/Validar/MisExcepciones.php');


if(!isset($_SESSION)){
    
 session_start();

} 

if (isset($_POST['email'])) {
        $email =  $_POST['email'];
    } else { if (isset($_GET['email'])) 
        $email=$_GET['email'];
}
 

$test;
try{
 
   global $test;
    
    $con = Conne::connect(); 
    
    $emailDeco = System::desencriptar($email);
    $sqlValiEmail = "Update ".TBL_USUARIO. " SET bloqueado = :bloqueado WHERE email= :email";
    $stmValiEmail = $con->prepare($sqlValiEmail);
    $stmValiEmail->bindValue(":bloqueado", '0', PDO::PARAM_STMT);
    $stmValiEmail->bindValue("email", $emailDeco, PDO::PARAM_STMT);
    
    $test = $stmValiEmail->execute();
    
    if($test){
        header(MOSTRAR_PAGINA_INDEX);
    }
    
} catch (Exception $ex) {
    $_SESSION['emailNoActivado'] = $emailDeco;//se elimina en metodosInfoExcepciones

    //echo $ex->getMessage();
    $excepciones = new MisExcepciones(CONST_ERROR_DESBLOQUEO_USUARIO[1],CONST_ERROR_DESBLOQUEO_USUARIO[0],$ex);
    $excepciones->redirigirPorErrorSistema("mandarEmailActivacion",true);
}    
    
    
    
    //echo json_encode($comprobar);