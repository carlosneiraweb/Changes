<?php



  header('Content-Type: application/json');
  //header("Content-type: application/javascript"); 
  header('Cache-Control: no-cache, must-revalidate');
  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
  header('Content-type: application/json; charset=utf-8');

require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/DataObj.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Constantes/ConstantesBbdd.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/Post.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/Usuarios.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Directorios.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Email/mandarEmails.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Controlador/Validar/MisExcepcionesUsuario.php');

global $conMenu;
$conMenu = Conne::connect();  

 if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
    

     // -------- párametro opción para determinar la select a realizar -------
    if (isset($_POST['opcion'])){ 
            $opc=$_POST['opcion'];
        }else{
            if (isset($_GET['opcion'])){
                $opc=$_GET['opcion'];
         }        
    }
    
   
   
    
/**
 * Metodo que da de baja a un susario definitivamente.</br>
 * Elimina sus datos de la bbdd asi como </br>
 * los directorios que se han creado.</br>
 * @return Objeto Json 
 * 
 * 
 */

function darBajaDefinitiva(){
    
              
            try{
           
                $idUsu = $_SESSION["userTMP"]->devuelveId();
                $nickEliTotal = $_SESSION["userTMP"]->getValue('nick');
                $email= $_SESSION["userTMP"]->getValue('email');
                $test = $_SESSION["userTMP"]->eliminarPorId($idUsu);
                
              
                //dar baja administradores
                
                
                //ELIMINAMOS LOS DIRECTORIOS CREADOS AL REGISTRARSE
                if($test && $idUsu != ""){
                   
                    Directorios::eliminarDirectoriosSistema("../../photos/".$idUsu,"eliminarDirectoriosBajaUsuario");
                    Directorios::eliminarDirectoriosSistema("../../datos_usuario/".$idUsu,"eliminarDirectoriosBajaUsuario");
                    Directorios::eliminarDirectoriosSistema("../../Videos/".$idUsu,"eliminarDirectoriosBajaUsuario");
     
                }
                
                if($test){
                    echo json_encode('OK');
                        $objMandarEmails = new mandarEmails();
                        $objMandarEmails->mandarEmailBajaUsuario($nickEliTotal,$email);
                }

            } catch (Exception $ex) {
                   
                echo $ex->getMessage();
                    
            }
    

 //fin darBajaDefinitiva   
}


function darBajaParcial(){
    
    try{
            
          
            global $conMenu;
            $idUsu = $_SESSION["userTMP"]->devuelveId();
            
            $sqlBajaParcial = "UPDATE  usuario SET bloqueado = ".BLOQUEO_PARCIAL.
                     " where idUsuario = :idUsuario;";
            echo $sqlBajaParcial;
            $stmBajaParcial = $conMenu->prepare($sqlBajaParcial);
            $stmBajaParcial->bindValue(":idUsuario", $idUsu, PDO::PARAM_INT );
            $test = $stmBajaParcial->execute();
            
                if($test){
                    echo json_encode("OK");
                }else{
                    echo json_encode(false);
                }
                
                
            Conne::disconnect($conMenu);
            
        } catch (Exception $ex) {
            
            Conne::disconnect($conMenu);
           // $excepciones->redirigirPorErrorSistema("Error al dar de baja parcial al usuario");
        }    
            
            
    
    
    //fin darBajaParcial
}


    
    switch ($opc) {
    
    
        case 'Definitivamente':
            
            darBajaDefinitiva();
            
                break;
        
        case "parcialmente":
           
             darBajaParcial();

                break;
        
        
        default:
            break;
           
    }

