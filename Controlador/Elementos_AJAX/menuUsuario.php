<?php
function redirigirAlEliminarUsuario(){ 
    
    $ruta = 'http://localhost/Changes/Vista/index.php';
    header("Location: $ruta");   
        die();
}



  header('Content-Type: application/json');
 // header("Content-type: application/javascript"); 
  header('Cache-Control: no-cache, must-revalidate');
  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
  header('Content-type: application/json; charset=utf-8');

require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/DataObj.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Conne.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/Post.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/Usuarios.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Directorios.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Email/mandarEmails.php');


session_start(); 
global $conMenu;

 

try {
    global $conMenu;
    $conMenu= Conne::connect();
  
    
     // -------- párametro opción para determinar la select a realizar -------
    if (isset($_POST['opcion'])){ 
            $opc=$_POST['opcion'];
        }else{
            if (isset($_GET['opcion'])){
                $opc=$_GET['opcion'];
         }        
    }
    
    
    switch ($opc) {
    
    
        case 'Definitivamente':
          
            try{
           
                global $conMenu;
                $idUsu = $_SESSION["userTMP"]->devuelveId();
                
                
              
                //ELIMINAMOS LOS DATOS DEL USARIO
                $x = $_SESSION["userTMP"]->deleteFrom('datos_usuario');
                $x .= $_SESSION["userTMP"]->deleteFrom('direccion');
                $x .= $_SESSION["userTMP"]->deleteFrom('usuario');
            //echo 'devuelve '.$x;
              
                if($x == '111'){
                        $sqlEliminar = "Select idPost from ".TBL_POST.
                                " where idUsuarioPost = :idUsuario;";

                        $stm = $conMenu->prepare($sqlEliminar);
                        $stm->bindValue(":idUsuario",$idUsu , PDO::PARAM_INT);
                        $stm->execute();
                        $idPostEliminar = $stm->fetchAll();
                        //var_dump($idPostEliminar);
                        $totalPost = count($idPostEliminar);
                        //echo $totalPost;
                    //ELIMINAMOS SI LOS HAY LOS POSTS   
                        
                    if($totalPost > 0){
                        
                            $sqlElimiarPost = "Delete from ".TBL_POST. 
                                    " where idPost = :idPost;";

                            for($i = 0; $i < $totalPost; $i++){

                                $stmElimPost = $conMenu->prepare($sqlElimiarPost);
                                $stmElimPost->bindParam(":idPost", $idPostEliminar[$i][0], PDO::PARAM_INT );
                                $stmElimPost->execute();
                               // 

                            }    
                    $stmElimPost->closeCursor();
                    
                    }
                }
                
                 
                    //ELIMINAMOS LAS PALABRAS DE AVISO
                    $sqlPalabrasEmail = "Delete from palabras_email"
                            . " where id_usuario = :id_usuario;";
                
                        $stmEliPalabrasEmail = $conMenu->prepare($sqlPalabrasEmail);
                        $stmEliPalabrasEmail->bindParam(":id_usuario", $idUsu, PDO::PARAM_INT );
                        $stmEliPalabrasEmail->execute();
                        $stmEliPalabrasEmail->closeCursor();
                        
                       
                
                Conne::disconnect($conMenu);
            
                //ELIMINAMOS LOS DIRECTORIOS CREADOS AL REGISTRARSE
                if($idUsu !=null ){
                    
                    $x .= Directorios::eliminarDirectorioRegistro("../../photos/".$_SESSION['usuario']['nick']);
                    $x .= Directorios::eliminarDirectorioRegistro("../../datos_usuario/".$_SESSION['usuario']['nick']);
                    $x .= Directorios::eliminarDirectorioRegistro("../../Videos/".$_SESSION['usuario']['nick']);
                    
                        
                }
               
                
                //MANDAMOS EMAIL PARA INFORMAR USUARIO
                //SE HA DADO DE BAJA
                
            if($x == '111111'){
               
                $objMandarEmails = new mandarEmails();
                $test = $objMandarEmails->mandarEmailBajaUsuario($_SESSION["userTMP"]);
                
            }   

                        unset($_SESSION["userTMP"]);     
                        redirigirAlEliminarUsuario();
            
            } catch (Exception $ex) {
                    Conne::disconnect($conMenu);
                    $ex->getMessage();
            }
                 
                            
            break;
               
        default:
            break;
           
    }








    
} catch (Exception $exc) {
    echo $exc->getTraceAsString();
}
