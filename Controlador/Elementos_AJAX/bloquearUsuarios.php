<?php

  header('Content-type: application/json; charset=utf-8');
  header('Cache-Control: no-cache, must-revalidate');
  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
  header('Content-type: application/json; charset=utf-8');

require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/DataObj.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Constantes/ConstantesBbdd.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/Usuarios.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Controlador/Validar/ControlErroresRunning.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Constantes/ConstantesErrores.php');


 if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 

    /**
     * 
     * 18 de septiembre
     */
    
    
    
global $conBloqueo;
$conBloqueo = Conne::connect();
global $usuBloquea;
$usuBloquea = $_SESSION["userTMP"]->devuelveId();
global $nickBloquear;


         // -------- párametro opción para determinar la select a realizar -------
    if (isset($_POST['opcion'])){ 
            $opc=$_POST['opcion'];
        }else{
            if (isset($_GET['opcion'])){
                $opc=$_GET['opcion'];
         }        
    }
    
    
    if (isset($_POST['nickBloquear'])){ 
            $nickBloquear=$_POST['nickBloquear'];
        }else{
            if (isset($_GET['nickBloquear'])){
                $nickBloquear=$_GET['nickBloquear'];
         }        
    }
    
    
    if (isset($_POST['nickDesbloquear'])){ 
            $nickDesbloquear=$_POST['nickDesbloquear'];
        }else{
            if (isset($_GET['nickDesbloquear'])){
                $nickDesbloquear=$_GET['nickDesbloquear'];
         }        
    }
    
    
    if (isset($_POST['total'])){ 
            $total=$_POST['total'];
        }else{
            if (isset($_GET['total'])){
                $total=$_GET['total'];
         }        
    }
    
    if (isset($_POST['parcial'])){ 
            $parcial=$_POST['parcial'];
        }else{
            if (isset($_GET['parcial'])){
                $parcial=$_GET['parcial'];
         }        
    }


/**
 * Este metodo comprueba que el usuario <br/>
 * a bloquear no este ya bloqueado <br/>
 * totalmente o parcialmente. Si esta bloqueado totalmente<br/>
 * no dejamos bloquear parcialmente.<br/>
 * @global type Conection <br>
 * Variable conexion
 * @global type usuario<br>
 * Usuario que bloquea
 * @param  type String <br>
 * Id usuario en caso que este bloqueado<br/>
 * totalmente ya.
 * 
 */
function comprobarTipoBloqueo($id,$opcion){
   
    
    global $conBloqueo;
    global $usuBloquea;
        
    try{
    
 
                if ($opcion === "bloqueoTotal"){
                    
                    $sqlComprobarUsu = "select count(*) from ".TBL_BLOQUEADOS_TOTAL.
                            " where usuarioIdUsuario = :usuBloquea and idUsuarioBloqueado = :idUsuarioBloqueado;";
                
                    
                }else if($opcion === "bloqueoParcial"){
                    
                    $sqlComprobarUsu = "select count(*) from ".TBL_BLOQUEADOS_PARCIAL.
                            " where usuarioIdUsuario = :usuBloquea and idUsuarioBloqueado = :idUsuarioBloqueado;";
                    
                }                
                //echo $sqlComprobarUsu;
                $stmComprobar = $conBloqueo->prepare($sqlComprobarUsu);
                $stmComprobar->bindValue(":idUsuarioBloqueado",$id,PDO::PARAM_INT);
                $stmComprobar->bindValue(":usuBloquea",$usuBloquea , PDO::PARAM_INT);
                $stmComprobar->execute();
               
                $idsUsuBloquear = $stmComprobar->fetch();
                Conne::disconnect($conBloqueo); 
               
                //echo json_encode($idsUsuBloquear[0]);
                return $idsUsuBloquear[0];
       
    } catch (Exception $ex) {
       
        echo json_encode('ERROR');
        Conne::disconnect($conBloqueo);
        $_SESSION['error'] = ERROR_BLOQUEAR_USUARIO;
        $_SESSION['paginaError'] = "index.php";
        $excepciones = new ControlErroresRunning(CONST_ERROR_BBDD_COMPROBAR_BLOQUEO_USUARIO[1],CONST_ERROR_BBDD_COMPROBAR_BLOQUEO_USUARIO[0],$ex);
        $excepciones->ErroresRunning("bloquear","El usuario ".$_SESSION["userTMP"]->getValue('nick').
            " consulto que tipo de bloqueo tenía el  usuario a bloquear con id ".$id);
        

    }
    
    
}


/**
 * Metodo que nos devuelve el id <br/>
 * del usuario a bloquear<br/>
 * @param type String </br>
 * id del usuario a bloquear </br>
 * @return idUsuario a bloquear
 */
 
function devuelveIdUsu($nickBloquear){
    
    global $conBloqueo;
    
    
    try{
        
        
         //Conseguimos el id del usuario a bloquear
                $sqlIdBloqueoTotal = "Select idUsuario from ".TBL_USUARIO.
                                " where nick = :nick;";
                
                $stmTotal = $conBloqueo->prepare($sqlIdBloqueoTotal);
                $stmTotal->bindValue(":nick", $nickBloquear, PDO::PARAM_STR);
                $stmTotal->execute();
                $idUsuBloquear = $stmTotal->fetch();

                    Conne::disconnect($conBloqueo);
                
                $result = isset($idUsuBloquear[0]) ?   $idUsuBloquear[0] : null;
     
                return $result;
                    
    } catch (Exception $ex){
       
        echo json_encode('ERROR');
        Conne::disconnect($conBloqueo);
        $_SESSION['error'] = ERROR_BLOQUEAR_USUARIO;
        $_SESSION['paginaError'] = "index.php";
        $excepciones = new ControlErroresRunning(CONST_ERROR_DEVOLVER_ID_USUARIO_BLOQUEAR[1],CONST_ERROR_DEVOLVER_ID_USUARIO_BLOQUEAR[0],$ex);
        $excepciones->ErroresRunning("bloquear",$nickBloquear);  
  
    } 
    
    
}

/**
 * Metodo que bloquea a un usuario<br/>
 * parcialmente.<br/>
 * @global name $usuBloquea<br/>
 * Nick del usuario que bloquea.<br/>
 * @global name $nickBloquear<br/>
 * Nick del usuario a bloquear.<br/>
 * @param type $idUsuBloquear<br/>
 * El id del usuario a bloquear.
 */


function bloquearParcial($idUsuBloquear){
    
    global $usuBloquea;
    global $nickBloquear;
    global $conBloqueo;
    
    
    try {
        
        $sqlBloquearTotal = "insert into ".TBL_BLOQUEADOS_PARCIAL." (usuarioIdUsuario,idUsuarioBloqueado)"
                                . " values (:usuBloquea,:usuBloqueado);";
                        
        $stmBloquearTotal = $conBloqueo->prepare($sqlBloquearTotal);
        $stmBloquearTotal->bindValue(":usuBloquea", $usuBloquea, PDO::PARAM_INT);
        $stmBloquearTotal->bindValue(":usuBloqueado", $idUsuBloquear, PDO::PARAM_INT );
                    
            $test = $stmBloquearTotal->execute();

            if($test){
                echo json_encode('OK'); 
            }else{
                echo json_encode('NO_OK');
            }
            
        Conne::disconnect($conBloqueo);
    
    } catch (Exception $ex) {
        
        echo json_encode('ERROR');
        Conne::disconnect($conBloqueo);
               
            $_SESSION['error'] = ERROR_BLOQUEAR_USUARIO;
            $_SESSION['paginaError'] = "index.php";
            $excepciones = new ControlErroresRunning(CONST_ERROR_BBDD_BLOQUEAR_PARCIAL_USUARIO[1],CONST_ERROR_BBDD_BLOQUEAR_PARCIAL_USUARIO[0],$ex);
            $excepciones->ErroresRunning("bloquear"," Usuario bloquea ".$_SESSION["userTMP"]->getValue('nick').
                            " usuario con id a bloquear ".$nickBloquear);  
        
    }

//fin bloquearParcial    
}

/**
 * Metodo que recibe un id de un usuario<br/>
 * para desbloquearlo PARCIAL.<br/>
 * @global name $usuBloquea<br/>
 * INT id usuario que desbloquea.<br/>
 * @param name $idUsuDesBloquear<br/>
 * INT id usuario a desbloquear·
 */

function eliminarBloqueoParcial($idUsuDesBloquear){
    
    global $usuBloquea;
    global $conBloqueo;
    
    
    
    try {
                                
        $sqlDesbloquearParcial = "DELETE FROM ".TBL_BLOQUEADOS_PARCIAL. 
            " WHERE usuarioIdUsuario = :usuDesbloquea AND idUsuarioBloqueado = :usuBloqueado;";


        $stmDesbloquearParcial = $conBloqueo->prepare($sqlDesbloquearParcial);
        $stmDesbloquearParcial->bindValue(":usuDesbloquea", $usuBloquea, PDO::PARAM_INT);
        $stmDesbloquearParcial->bindValue(":usuBloqueado", $idUsuDesBloquear, PDO::PARAM_INT );

            $test = $stmDesbloquearParcial->execute();
            
                if($test){echo json_encode("OK");}
                
            Conne::disconnect($conBloqueo);    
                                
    } catch (Exception $ex) {
                                
        echo json_encode('ERROR');
        Conne::disconnect($conBloqueo);
               
        $_SESSION['error'] = ERROR_DESBLOQUEAR_USUARIO;
        $_SESSION['paginaError'] = "index.php";
        $excepciones = new ControlErroresRunning(CONST_ERROR_BBDD_DESBLOQUEAR_PARCIAL[1],CONST_ERROR_BBDD_DESBLOQUEAR_PARCIAL[0],$ex);
        $excepciones->ErroresRunning("bloquear"," Usuario desbloquea PARCIAL".$_SESSION["userTMP"]->getValue('nick').
        " IdUsuario a desbloquear ".$idUsuDesBloquear);  
            
                               
    }

    
    //fin eliminarBloqueoParcial
}


/**
 * Metodo que inserta en la tabla<br/>
 * bloqueoTotal a un usuario.<br/>
 * @global name $usuBloquea<br/>
 * Id del usuario que bloquea.<br/>
 * @global name $nickBloquear<br/>
 * El nick del usuario a bloquear.<br/>
 * @param name $idUsuBloquear <br/>
 * El id del usuario a bloquear.
 */
function bloquearTotal($idUsuBloquear){
 
    global $usuBloquea;
    global $nickBloquear;
    global $conBloqueo;
    
    try {
        
        $sqlBloquearTotal = "insert into ".TBL_BLOQUEADOS_TOTAL." (usuarioIdUsuario,idUsuarioBloqueado)"
                     . " values (:usuBloquea,:usuBloqueado);";
                        
                    $stmBloquearTotal = $conBloqueo->prepare($sqlBloquearTotal);
                    $stmBloquearTotal->bindValue(":usuBloquea", $usuBloquea, PDO::PARAM_INT);
                    $stmBloquearTotal->bindValue(":usuBloqueado", $idUsuBloquear, PDO::PARAM_INT );
                    
                        $test = $stmBloquearTotal->execute();
                   
                        if($test){
                            echo json_encode('OK');
  
                        }else{
                            echo json_encode('NO_OK');
                        }
                        
        Conne::disconnect($conBloqueo);
                        
    } catch (Exception $ex) {
        
        echo json_encode('ERROR');
        Conne::disconnect($conBloqueo);
               
        $_SESSION['error'] = ERROR_BLOQUEAR_USUARIO;
        $_SESSION['paginaError'] = "index.php";
        $excepciones = new ControlErroresRunning(CONST_ERROR_BBDD_BLOQUEAR_TOTAL_USUARIO[1],CONST_ERROR_BBDD_BLOQUEAR_TOTAL_USUARIO[0],$ex); 
        $excepciones->ErroresRunning("bloquear"," Usuario bloquea ".$_SESSION["userTMP"]->getValue('nick').
                            " usuario a bloquear ".$nickBloquear);  
    }
}
    //fin bloquearTotal
    
 /**
 * Metodo que recibe un id de un usuario<br/>
 * para desbloquearlo TOTAL.<br/>
 * @global name $usuBloquea<br/>
 * INT id usuario que desbloquea.<br/>
 * @param name $idUsuDesBloquear<br/>
 * INT id usuario a desbloquear·
 */
    
function eliminarBloqueoTotal($idUsuDesBloquear){
        
        global $usuBloquea;
        global $conBloqueo;
    
    
    
            try {

                $sqlDesbloquearTotal = "DELETE FROM ".TBL_BLOQUEADOS_TOTAL. 
                    " WHERE usuarioIdUsuario = :usuDesbloquea AND idUsuarioBloqueado = :usuBloqueado;";


                $stmDesbloquearTotal = $conBloqueo->prepare($sqlDesbloquearTotal);
                $stmDesbloquearTotal->bindValue(":usuDesbloquea", $usuBloquea, PDO::PARAM_INT);
                $stmDesbloquearTotal->bindValue(":usuBloqueado", $idUsuDesBloquear, PDO::PARAM_INT );

                    $test = $stmDesbloquearTotal->execute();

                        if($test){echo json_encode("OK");}

                    Conne::disconnect($conBloqueo);    

            } catch (Exception $ex) {

                echo json_encode('ERROR');
                Conne::disconnect($conBloqueo);

                $_SESSION['error'] = ERROR_DESBLOQUEAR_USUARIO;
                $_SESSION['paginaError'] = "index.php";
                $excepciones = new ControlErroresRunning(CONST_ERROR_BBDD_DESBLOQUEAR_TOTALMENTE_USUARIO[1],CONST_ERROR_BBDD_DESBLOQUEAR_TOTALMENTE_USUARIO[0],$ex);
                $excepciones->ErroresRunning("bloquear"," Usuario desbloquea TOTAL ".$_SESSION["userTMP"]->getValue('nick').
                " IdUsuario a desbloquear ".$idUsuDesBloquear);  


            }
        
        
        
        //fin eliminarBloqueoTotal
    }

    
    
/**
 * Metodo que muestra los usuarios bloqueados <br/>
 * totalmente y parcialmente del usuario <br/>
 * logueado. <br/>
 * @global name $usuBloquea <br/>
 * Id del usuario logueado
 * 
 */
function verBloqueados(){
    
    global $usuBloquea;
    global $conBloqueo;
    
    $verBlo = array();
            
            try {
            
                
            
                $sqlBT = "SELECT nick AS bloqueadoTotal  FROM ".TBL_BLOQUEADOS_TOTAL.
                         " INNER JOIN usuario AS usu ON ".
                         " usu.idUsuario = idUsuarioBloqueado ".
                         " WHERE usuarioIdUsuario = :usuarioIdUsuario;";
                
                $stmBT = $conBloqueo->prepare($sqlBT);
                $stmBT->bindValue(":usuarioIdUsuario", $usuBloquea);
                $stmBT->execute();
                $resultTotal = $stmBT->fetchAll();
                
                array_push($verBlo,$resultTotal);
                
                Conne::disconnect($conBloqueo);
                
                
            } catch (Exception $ex) {
                
                echo json_encode('ERROR');
                Conne::disconnect($conBloqueo);
               
                $_SESSION['error'] = ERROR_MOSTRAR_USUARIOS_BLOQUEADOS;
                $_SESSION['paginaError'] = "index.php";
                $excepciones = new ControlErroresRunning(CONST_ERROR_BBDD_MOSTRAR_USUARIOS_BLOQUEADOS_TOTAL[1],CONST_ERROR_BBDD_MOSTRAR_USUARIOS_BLOQUEADOS_TOTAL[0],$ex);
                $excepciones->ErroresRunning("bloquear"," Usuario para mostrar sus bloqueos ".$_SESSION["userTMP"]->getValue('nick'));
                                 
            }


            try {
            
                
            
                $sqlBP = "SELECT nick AS bloqueadoParcial  FROM ".TBL_BLOQUEADOS_PARCIAL.
                         " INNER JOIN usuario AS usu ON ".
                         " usu.idUsuario = idUsuarioBloqueado ".
                         " WHERE usuarioIdUsuario = :usuarioIdUsuario;";
                
                $stmBP = $conBloqueo->prepare($sqlBP);
                $stmBP->bindValue(":usuarioIdUsuario", $usuBloquea);
                $stmBP->execute();
                $resultParcial = $stmBP->fetchAll();
                
                Conne::disconnect($conBloqueo);
                
                
            } catch (Exception $ex) {
                
                echo json_encode('ERROR');
                Conne::disconnect($conBloqueo);
               
                $_SESSION['error'] = ERROR_MOSTRAR_USUARIOS_BLOQUEADOS;
                $_SESSION['paginaError'] = "index.php";
                $excepciones = new ControlErroresRunning(CONST_ERROR_BBDD_MOSTRAR_USUARIOS_BLOQUEADOS_PARCIAL[1],CONST_ERROR_BBDD_MOSTRAR_USUARIOS_BLOQUEADOS_PARCIAL[0],$ex);
                $excepciones->ErroresRunning("bloquear"," Usuario para mostrar sus bloqueos ".$_SESSION["userTMP"]->getValue('nick'));
                                 
            }


            array_push($verBlo, $resultParcial);
            echo json_encode($verBlo);
 
    //fin verBloqueados
}





    switch ($opc) {
   
        
        
        case 'bloqueoTotal':
   
            $idUsuBloquear = devuelveIdUsu($nickBloquear);
                
            
                if($idUsuBloquear != null){
                    
                    $test = comprobarTipoBloqueo($idUsuBloquear,$opc);

                    if($test == 1){
                        
                       echo json_encode("YA_BLOQUEADO_TOTAL"); 
                       
                    }else{
                      
                        bloquearTotal($idUsuBloquear);
                    }    
                    
                }else{
                    echo json_encode("NO_EXISTE_USUARIO");
                }
     
            break;
            
            
        case 'bloqueoParcial':
            
            
            
                $idUsuBloquear = devuelveIdUsu($nickBloquear);
                
                if($idUsuBloquear != null){
                     //Comprobamos que el usuario no este bloqueado totalmente ya
                    $testTotal = comprobarTipoBloqueo($idUsuBloquear,'bloqueoTotal');
                    
                    if($testTotal != 1){
                        //Comprobamos que el usuario no este bloqueado parcialmente ya
                        $test = comprobarTipoBloqueo($idUsuBloquear,$opc);
                        
                        if($test == 1){
                           echo json_encode("USUARIO_YA_BLOQUEADO_PARCIALMENTE"); 
                        }else{
                            
                            bloquearParcial($idUsuBloquear);
                        }

                    }else{
                        echo json_encode("YA_BLOQUEADO_TOTAL");
                        
                    }
                }else{
                    echo json_encode("NO_EXISTE_USUARIO");
                }        
            
            break;
            
            
        case 'desbloquear':
            
            $resp ="";
           
            $idUsuDesbloquear = devuelveIdUsu($nickDesbloquear);
        
            
                if($idUsuDesbloquear != null){
              
                    if($parcial === 'true'){
                        
                    
                        if($parcial === "true"){

                            $test = comprobarTipoBloqueo($idUsuDesbloquear,"bloqueoParcial");

                                if($test == 0){

                                    $resp = "NO_BLOQUEADO_PARCIAL";

                                }else{

                                    eliminarBloqueoParcial($idUsuDesbloquear);
                                }      

                        }
                        
                    }else if($total === 'true'){
                         
                        $test = comprobarTipoBloqueo($idUsuDesbloquear,"bloqueoTotal");
                        
                            if($test == 0){

                                $resp = "NO_BLOQUEADO_TOTAL";

                            }else{

                                eliminarBloqueoTotal($idUsuDesbloquear);
                            }  
                        
                    }    

                }else{
                    
                    $resp = "NO_EXISTE_USUARIO";
                   
                } 
                    if($resp != ""){echo json_encode($resp);}
                    
        
            break;
            
            
        case 'mostrarBloqueos':
            
            verBloqueados();
            
                break;

        default:
            break;
        //SWITCH        
    }
    
    
    
        
    