<?php
  header('Content-type: application/json; charset=utf-8');
  header('Cache-Control: no-cache, must-revalidate');
  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
  header('Content-type: application/json; charset=utf-8');

    require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Constantes/ConstantesBbdd.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/DataObj.php');
    require_once($_SERVER['DOCUMENT_ROOT']."/Changes/Modelo/Usuarios.php");
    require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/System.php');
   
 
   if (isset($_POST['opcion'])){ 
      $opc=$_POST['opcion'];
}else { if (isset($_GET['opcion'])) 
        $opc=$_GET['opcion'];
}
    
    if (isset($_POST['actualizarUsu'])){ 
        $actualizo = $_POST['actualizarUsu'];
    }else{
        if (isset($_GET['actualizarUsu'])){ 
           $actualizo = $_GET['actualizarUsu'];
        }
    }
    
    if (isset($_POST['correo'])){ 
        $correo = $_POST['correo'];
    }else{
        if (isset($_GET['correo'])){ 
           $correo = $_GET['correo'];
        }
    }
    
    if (isset($_POST['pass'])){ 
        $pass = $_POST['pass'];
    }else{
        if (isset($_GET['pass'])){ 
           $pass = $_GET['pass'];
        }
    }
    
    
 if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
   
$conActualizar = Conne::connect();    
      
    switch ($opc) {
            
        case 'comprobar':
            
            $idUsuComprobar = $_SESSION["userTMP"]->devuelveId();
            $sqlComprobar = "Select password, email from usuario "
                    . " where idUsuario = :idUsuario;";
            
            $stmComprobar = $conActualizar->prepare($sqlComprobar);
            $stmComprobar->bindValue(":idUsuario", $idUsuComprobar);
            $stmComprobar->execute();
            $rsComprobar = $stmComprobar->fetch();
            $comprobar = $rsComprobar[0];
            
            //$hash = sodium_crypto_pwhash_str_verify($rsComprobar[0],$pass);
            //Comparamos password
            $hash = System::comparaHash($comprobar, $pass);
            
            //comparamos correos
            if (strcmp($rsComprobar[1], $correo) === 0){
                $correoResult = 1;
            } else {
                $correoResult = 0;   
            }
           
            $resultComprobar = array("result" => $hash.$correoResult);
                echo json_encode($resultComprobar);
                
                break;
            
        case 'recuperar':
                
            try{               

        $sqlActualizo = "SELECT usu.nick as nick, usu.email as correo, 
	dir.calle as calle, dir.numeroPortal as portal, dir.ptr as puerta, dir.codigoPostal as codigoPostal, dir.ciudad as ciudad, dir.provincia as provincia, dir.pais as pais,
    dat.nombre as nombre, dat.apellido_1 as primerApellido, dat.apellido_2 as segundoApellido, dat.telefono as tlf, dat.genero as gn
from usuario as usu 
inner join direccion as dir on dir.idDireccion = usu.idUsuario
inner join datos_usuario as dat on dat.idDatosUsuario = usu.idUsuario
where usu.idUsuario = :idUsuario;";
        
        $stmActualizo = $conActualizar->prepare($sqlActualizo);
        $stmActualizo->bindValue(":idUsuario", $_SESSION["userTMP"]->devuelveId($_SESSION["userTMP"]->getValue('nick')), PDO::PARAM_INT);
        $stmActualizo->execute();
        $tmpAct = $stmActualizo->fetchAll();
        //var_dump($tmpAct);
        
      
            $_SESSION['actualizo']['nick'] = $tmpAct[0][0];
            $_SESSION['actualizo']['correo'] = $tmpAct[0][1];
            $_SESSION['actualizo']['calle'] = $tmpAct[0][2];
            $_SESSION['actualizo']['portal'] = $tmpAct[0][3];
            $_SESSION['actualizo']['puerta'] = $tmpAct[0][4];
            $_SESSION['actualizo']['codigoPostal'] = $tmpAct[0][5]; 
            $_SESSION['actualizo']['ciudad'] = $tmpAct[0][6]; 
            $_SESSION['actualizo']['provincia'] = $tmpAct[0][7]; 
            $_SESSION['actualizo']['pais'] = $tmpAct[0][8];
            $_SESSION['actualizo']['nombre'] = $tmpAct[0][9];
            $_SESSION['actualizo']['primerApellido'] = $tmpAct[0][10];
            $_SESSION['actualizo']['segundoApellido'] = $tmpAct[0][11];
            $_SESSION['actualizo']['tlf'] = $tmpAct[0][12];
            $_SESSION['actualizo']['gn'] = $tmpAct[0][13];
//            
//            echo PHP_EOL;
//            echo 'actualio';
//            var_dump($_SESSION['actualizo']);
       
        if($_SESSION['actualizo']['nick'] != ""){
           $test = array('respuesta'=> 'OK');
          
        }else{
            $test = array('respuesta'=> "DOWN");
        }
        
       
        echo json_encode($test);
         
        
    } catch (Exception $ex) {
        echo 'Archivo '.$ex->getFile();
        echo 'Causa '.$ex->getMessage();
        echo 'Linea '.$ex->getLine();

    }
                
                
                break;

            default:
                break;
                
                
    }
        
         
 