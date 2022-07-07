<?php

  header('Content-type: application/json; charset=utf-8');
  header('Cache-Control: no-cache, must-revalidate');
  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
  header('Content-type: application/json; charset=utf-8');

require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/DataObj.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Constantes/ConstantesBbdd.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/Usuarios.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Controlador/Validar/MisExcepciones.php');


 if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 

global $conBloqueo;
$conBloqueo = Conne::connect();
global $usuBloquea;
$usuBloquea =$_SESSION["userTMP"]->devuelveId();


/**
 * Metodo que comprueba el tipo </br>
 * de bloqueo verdadero </br>
 * por si el usuario trata de </br>
 * desbloquear a otro usario y no esta bloqueado</br>
 * como solicita en el formulario
 * @param type $id
 * id usuario a comprobar el tipo mde bloqueo </br>
 * @return array $result</br>
 * los tipos de bloqueo que tiene el usuario
 */

function comprobarTipoBloqueo($id,$opc,$opc2){
    global $conBloqueo;
    global $usuBloquea;
    
    
        try{
            $sqlComprobarUsu = "select $opc from ".TBL_USUARIOS_BLOQUEADOS.
                                    " where  usuario_idUsuario= :usuBloquea and idUsuarioBloqueado = :idUsuarioBloqueado and $opc2;";
            
            $stmComprobar = $conBloqueo->prepare($sqlComprobarUsu);
                    $stmComprobar->bindValue(":idUsuarioBloqueado",$id,PDO::PARAM_STR);
                    $stmComprobar->bindValue(":usuBloquea",$usuBloquea , PDO::PARAM_STR);
                    $stmComprobar->execute();

                    $result = $stmComprobar->fetchAll();
                    Conne::disconnect($conBloqueo); 
                    
                   //echo json_encode($result);
        
        
        return $result;
        
        }catch(Exception $ex){
            $ex->getMessage();
        }
}




/**
 * 
 * @global type Conection <br>
 * Variable conexion
 * @global type usuario<br>
 * Usuario que bloquea
 * @param  type String <br>
 * Id usuario para comprobar que el usuario <br>
 * no esta ya bloqueado
 * 
 */
function comprobarUsuario($id,$opcion){
    
    try{
    
    global $conBloqueo;
    global $usuBloquea;
    $excepciones = new MisExcepciones(CONST_ERROR_BBDD_CONSULTAR_USUARIOS_BLOQUEADOS[1],CONST_ERROR_BBDD_CONSULTAR_USUARIOS_BLOQUEADOS[0]); 
   

                $sqlComprobarUsu = "select idUsuarioBloqueado from ".TBL_USUARIOS_BLOQUEADOS.
                                " where idUsuarioBloqueado = :idUsuarioBloqueado and usuario_idUsuario= :usuBloquea and ";
                
                if ($opcion =="bloqueoTotal"){
                    $sqlComprobarUsu .= "bloqueadoTotal = 1;";
                }else{
                    $sqlComprobarUsu .="bloqueadoParcial = 1;";
                }                
                //echo $sqlComprobarUsu;
                $stmComprobar = $conBloqueo->prepare($sqlComprobarUsu);
                $stmComprobar->bindValue(":idUsuarioBloqueado",$id,PDO::PARAM_STR);
                $stmComprobar->bindValue(":usuBloquea",$usuBloquea , PDO::PARAM_STR);
                $stmComprobar->execute();
               
                $idsUsuBloquear = $stmComprobar->fetch();
                Conne::disconnect($conBloqueo); 
                return $idsUsuBloquear[0];
                //echo json_encode($idsUsuBloquear);
        
       
    } catch (Exception $ex) {
        $excep =  $excepciones->recojerExcepciones($ex);
        $excepciones->redirigirPorErrorSistema("consultarUsuariosBloqueados",false,$excep);
                Conne::disconnect($conBloqueo); 

    }
    
    
}


/**
 * Metodo que nos devuelve el id 
 * del usuario a bloquear
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
               
    
        return $idUsuBloquear[0];
        
    } catch (Exception $ex){
        $ex->getMessage();
    }
    
    
}



    try{
        
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
    
    
    
    
    
   
    switch ($opc) {
   
        
        
        case 'bloqueoTotal':
            
            try {
                
                
                $idUsuBloquear = devuelveIdUsu($nickBloquear);
                
                if($idUsuBloquear != null){
                    
                    $test = comprobarUsuario($idUsuBloquear,$opc);
                    
                    if($test != null){
                       echo json_encode("YA_BLOQUEADO_TOTAL"); 
                    }else{
                        
                        $sqlBloquearTotal = "insert into ".TBL_USUARIOS_BLOQUEADOS." (usuario_idUsuario,idUsuarioBloqueado,bloqueadoTotal,bloqueadoParcial)"
                     . " values (:usuBloquea,:usuBloqueado,:total,:parcial);";
                    $stmBloquearTotal = $conBloqueo->prepare($sqlBloquearTotal);
                    $stmBloquearTotal->bindValue(":usuBloquea", $usuBloquea, PDO::PARAM_INT);
                    $stmBloquearTotal->bindValue(":usuBloqueado", $idUsuBloquear, PDO::PARAM_INT );
                    $stmBloquearTotal->bindValue(":total", "1", PDO::PARAM_INT );
                    $stmBloquearTotal->bindValue(":parcial", "0", PDO::PARAM_INT );
                    $test = $stmBloquearTotal->execute();
                   
                        if($test){
                            echo json_encode('OK'); 
                        }else{
                            echo json_encode('NO_OK');
                        }
                    }
                    
                }else{
                    echo json_encode("NO_EXISTE_USUARIO");
                }
                 
                
              
                 Conne::disconnect($conBloqueo);
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
                echo $exc->getMessage();
                
            }


            break;
            
            
        case 'bloqueoParcial':
            
            try{
            
                $idUsuBloquear = devuelveIdUsu($nickBloquear);
       
                if($idUsuBloquear != null){
                     //Comprobamos que el usuario no este bloqueado totalmente ya
                    $testTotal = comprobarUsuario($idUsuBloquear,'bloqueoTotal');
                    
                    if($testTotal == null){
                        //Comprobamos que el usuario no este bloqueado parcialmente ya
                        $test = comprobarUsuario($idUsuBloquear,$opc);
                       // echo $test;
                        if($test != null){
                           echo json_encode("USUARIO_YA_BLOQUEADO_PARCIALMENTE"); 
                        }else{

                            $sqlBloquearTotal = "insert into ".TBL_USUARIOS_BLOQUEADOS." (usuario_idUsuario,idUsuarioBloqueado,bloqueadoTotal,bloqueadoParcial)"
                         . " values (:usuBloquea,:usuBloqueado,:total,:parcial);";
                        $stmBloquearTotal = $conBloqueo->prepare($sqlBloquearTotal);
                        $stmBloquearTotal->bindValue(":usuBloquea", $usuBloquea, PDO::PARAM_INT);
                        $stmBloquearTotal->bindValue(":usuBloqueado", $idUsuBloquear, PDO::PARAM_INT );
                        $stmBloquearTotal->bindValue(":total", "0", PDO::PARAM_INT );
                        $stmBloquearTotal->bindValue(":parcial", "1", PDO::PARAM_INT );
                        $test = $stmBloquearTotal->execute();

                            if($test){
                                echo json_encode('OK'); 
                            }else{
                                echo json_encode('NO_OK');
                            }
                        }

                    }else{
                        echo json_encode("YA_BLOQUEADO_TOTAL");
                        
                    }
                }else{
                    echo json_encode("NO_EXISTE_USUARIO");
                }        
              
                 Conne::disconnect($conBloqueo);
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
                echo $exc->getMessage();
                
            }
            
            break;
            
            
        case 'desbloquear':
            
            $resp = null;
            $idUsuBloquear = devuelveIdUsu($nickDesbloquear);
            
                if($idUsuBloquear != null){
                   
                    
                    if($total == "true"){
                        $opc = 'bloqueoTotal';
                        $stmTotal = "1";
                        $stmParcial = "0";
                    }else if ($parcial == "true"){
                        $opc = 'bloqueoParcial';
                        $stmTotal = "0";
                        $stmParcial = "1";
                    }
                   
                    $bloqueadoTotal = comprobarTipoBloqueo($idUsuBloquear,'bloqueadoTotal','bloqueadoTotal = 1');
                    $bloqueadoParcial = comprobarTipoBloqueo($idUsuBloquear,'bloqueadoParcial','bloqueadoParcial = 1');
                    
                    //var_dump($bloqueadoTotal);
                    //var_dump($bloqueadoParcial);
                    
                    
                    
                    if($bloqueadoTotal[0][0] == '1' || $bloqueadoParcial[0][0] == '1'){
                        
                        if($bloqueadoTotal[0][0] == "0" and $total == "true"){
                            $resp = "NO_BLOQUEADO_TOTAL";  
                        }elseif($bloqueadoTotal == null and $total == "true") {
                            $resp = "NO_BLOQUEADO_TOTAL";  
                        }else if($bloqueadoParcial[0][0] == "0" and $parcial == "true"){
                            $resp = "NO_BLOQUEADO_PARCIAL";
                        }else if($bloqueadoParcial == null and $parcial == "true"){
                            $resp = "NO_BLOQUEADO_PARCIAL";
                        }else if($total == "false" and $parcial == "false"){
                            $resp = "NO_SELECCION_BLOQUEO";
                        }
                        
                    }else{
                        $resp = "USUARIO_NO_BLOQUEADO";
                    }
                        
                     
                        
                        
                        if($resp == null){
                            
                            try{

                                $sqlDesbloquear = "Delete from usuarios_bloqueados where "
                                . " usuario_idUsuario=:usuBloquea and idUsuarioBloqueado=:usuBloqueado " 
                                . " and bloqueadoTotal=:total and bloqueadoParcial=:parcial;";
                               // echo $sqlDesbloquear;
                                    $stmDesbloquear = $conBloqueo->prepare($sqlDesbloquear);
                                    $stmDesbloquear->bindValue(":usuBloquea", $usuBloquea, PDO::PARAM_INT);
                                    $stmDesbloquear->bindValue(":usuBloqueado", $idUsuBloquear, PDO::PARAM_INT );
                                    $stmDesbloquear->bindValue(":total", $stmTotal, PDO::PARAM_INT );
                                    $stmDesbloquear->bindValue(":parcial", $stmParcial, PDO::PARAM_INT );
                                   
                                    $test = $stmDesbloquear->execute();

                                        if($test){
                                            echo json_encode('OK'); 
                                        }else{
                                            echo json_encode('NO_OK');
                                        }
                            }catch(Exception $ex){
                                $ex->getMessage();
                            }
                        }else{
                             echo json_encode($resp);
                        }
                        
                           
                }else{
                   $resp = "NO_EXISTE_USUARIO";
                   echo json_encode($resp);
                }
                 
           
            break;
            
            
        case 'mostrarBloqueos':
            
            $totalArr = array();
            $parcialArr = array();
            $final = array();
            
            try{
            
               $sqlIdBloqueados= "Select distinct  idUsuarioBloqueado from ".TBL_USUARIOS_BLOQUEADOS.
                       " WHERE usuario_idUsuario =  :usuBloquea;";
               //echo $sqlIdBloqueados;
               $stmIdBloqueados = $conBloqueo->prepare($sqlIdBloqueados);
               $stmIdBloqueados->bindValue(":usuBloquea",$usuBloquea,PDO::PARAM_STR);
               $stmIdBloqueados->execute();
               $idBloqueados = $stmIdBloqueados->fetchAll();
               $x = count($idBloqueados);
               
                if($x > 0){
                    //var_dump($idBloqueados);
                    foreach($idBloqueados as $f){

                         $arr2[]=$f[0];
                     }
                    $ids = implode(",",$arr2);


                
        $sqlMostrarBloqueos = "select u.nick, b.bloqueadoTotal, b.bloqueadoParcial  from ".TBL_USUARIOS_BLOQUEADOS." b ".
                " inner join ".TBL_USUARIO. "  as u on u.idUsuario = b.idUsuarioBloqueado ". 
                " where  usuario_idUsuario= :usuBloquea and idUsuarioBloqueado in ($ids)".
                " order by bloqueadoTotal DESC;";
                
                $stmMostrarBloqueos = $conBloqueo->prepare($sqlMostrarBloqueos);
                //$stmMostrarBloqueos->bindValue(":idUsuarioBloqueado",$ids,PDO::);
                $stmMostrarBloqueos->bindValue(":usuBloquea",$usuBloquea , PDO::PARAM_STR);
                $stmMostrarBloqueos->execute();

                   
                   
                    $tmp = $stmMostrarBloqueos->fetchAll(PDO::FETCH_ASSOC);
                    
                    
                    
                    
                        foreach ($tmp as $clave => $dato) {
                            if($dato["bloqueadoTotal"] =='1'){
                                array_push($totalArr, $dato['nick']);
                            }elseif($dato["bloqueadoParcial"] == '1'){
                                array_push($parcialArr,$dato['nick']);
                            }
                        }
                    
                   
                        array_push($final,$totalArr,$parcialArr);
                }else{
                    $totalArr = 'NO_BLOQUEADOS';
                    $parcialArr = 'NO_BLOQUEADOS';
                    array_push($final,$totalArr,$parcialArr);
                }        
                   echo json_encode($final);
                    
            Conne::disconnect($conBloqueo); 
            } catch (Exception $ex) {
                $ex->getMessage();
            }
            
            
            break;

        default:
            break;
    }
    
    
    
        
    } catch (Exception $ex) {
        $ex->getMessage();
    }