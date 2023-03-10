<?php



  header('Content-Type: application/json');
 // header("Content-type: application/javascript"); 
  header('Cache-Control: no-cache, must-revalidate');
  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
  header('Content-type: application/json; charset=utf-8');

require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/DataObj.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Constantes/ConstantesBbdd.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/Post.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/Usuarios.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Directorios.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Email/mandarEmails.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Controlador/Validar/MisExcepcionesUsuario.php');


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
    
    


function darBajaDefinitiva(){
    
              
            try{
           
               
                
                $test = array();
                $idUsu = $_SESSION["userTMP"]->devuelveId();
                $nickEliTotal = $_SESSION["userTMP"]->getValue('nick');
                $email= $_SESSION["userTMP"]->getValue('email');
                $test = $_SESSION["userTMP"]->eliminarPorId($idUsu);
                
                /**
                dar baja administradores
                */
                
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
                }else{
                    echo json_encode('NO_OK');
                }
            
            
            
            
              
            } catch (Exception $ex) {
                   
            //$excepciones->redirigirPorErrorSistema("darBajaDefinitivamente",false,$);
                    
            }
    

 //fin darBajaDefinitiva   
}





    
    switch ($opc) {
    
    
        case 'Definitivamente':
            
            
            darBajaDefinitiva();

                 
                            
            break;
        
        case "parcialmente":
            
        try{
            
            $excepciones = new MisExcepcionesUsuario(CONST_ERROR_BBDD_DAR_BAJA_USUARIO_PARCIAL[1],CONST_ERROR_BBDD_DAR_BAJA_USUARIO_PARCIAL[0]); 
            global $conMenu;
            $idUsu = $_SESSION["userTMP"]->devuelveId();
                
            //ELIMINAMOS LAS PALABRAS DE AVISO
            $sqlPalabrasEmail =  "update  usuario set activo = 0"
                    . " where idUsuario = :idUsuario;";
                           
            
            $stmEliPalabrasEmail = $conMenu->prepare($sqlPalabrasEmail);
            $stmEliPalabrasEmail->bindParam(":idUsuario", $idUsu, PDO::PARAM_INT );
            $test = $stmEliPalabrasEmail->execute();
            $resultado = array("resultado" => $test);
            echo json_encode($resultado);
            Conne::disconnect($conMenu);
            
        } catch (Exception $ex) {

            Conne::disconnect($conMenu);
            $excepciones->redirigirPorErrorSistema("Error al dar de baja parcial al usuario");
        }    
            
            
            break;
        
        
        default:
            break;
           
    }

