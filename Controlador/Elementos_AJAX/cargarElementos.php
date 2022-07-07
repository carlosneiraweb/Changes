
<?php

 
  header('Content-Type: application/json');
  header('Cache-Control: no-cache, must-revalidate');
  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
  header('Content-type: application/json; charset=utf-8');


require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Conne.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Constantes/ConstantesBbdd.php');

 
  
/**
 * @author Carlos Neira Sanchez
 * @mail arj.123@hotmail.es
 * @telefono ""
 * @nameAndExt cargarElementos.php
 * @fecha 26-oct-2020
 */
 
    try{
    

  
  
  // -------- párametro opción para determinar la select a realizar -------
if (isset($_POST['opcion'])){ 
      $opc=$_POST['opcion'];
}else { if (isset($_GET['opcion'])) 
        $opc=$_GET['opcion'];
}
     


 switch ($opc) {
        case "PP":
            $sqlCargarElementos = "select nombreProvincia from ".TBL_PROVINCIAS.";"; 
                break;
        case "PG":
            $sqlCargarElementos = "select genero from ".TBL_GENERO.";";
                break;
        case "PS":
            $sqlCargarElementos = "Select nombreSeccion from ".TBL_SECCIONES.";";
                break;
        case "PT":
            $sqlCargarElementos = "Select * from ".TBL_TIEMPO_CAMBIO." ;";
                break;
        default:
            echo 'error';
            
      
 }
 

         $con = Conne::connect(); 
       $gsent = $con->prepare($sqlCargarElementos);
        $gsent->execute();
       $result = $gsent->fetchAll();
      
        echo json_encode($result);
        Conne::disconnect($conCargarElementos);
    
     
    }catch(Exception $ex){
        Conne::disconnect($con);
        $ex->getMessage();
    }
    
    