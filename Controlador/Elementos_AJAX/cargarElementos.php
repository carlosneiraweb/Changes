<?php

/**
 * @author Carlos Neira Sanchez
 * @mail arj.123@hotmail.es
 * @telefono ""
 * @nameAndExt cargarElementos.php
 * @fecha 26-oct-2016
 */


 require_once("../../Sistema/Conne.php");
    require_once("../../Sistema/Constantes.php");
    
  

  // -------  cabeceras indicando que se envian datos JSON.
  header('Content-Type: application/json');
  header('Cache-Control: no-cache, must-revalidate');
  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
  header('Content-type: application/json; charset=utf-8');
  // -------   Crear la conexión al servidor y ejecutar la consulta.
    try{
    
    $conCargarElementos = Conne::connect();
  
  // -------- párametro opción para determinar la select a realizar -------
if (isset($_POST['opcion'])){ 
      $opc=$_POST['opcion'];
}else { if (isset($_GET['opcion'])) 
        $opc=$_GET['opcion'];
}
     


 switch ($opc) {
        case "PP":
            $sqlCargarElementos = "select nombre from ".TBL_PROVINCIAS.";"; 
                break;
        case "PG":
            $sqlCargarElementos = "select genero from ".TBL_GENERO.";";
                break;
        case "PS":
            $sqlCargarElementos = "Select nombre_seccion from ".TBL_SECCIONES.";";
                break;
        case "PT":
            $sqlCargarElementos = "Select * from ".TBL_TIEMPO_CAMBIO." ;";
                break;
      
    }   
        $stCargarElementoss  = $conCargarElementos->query($sqlCargarElementos);
        $resultCargarElementos = $stCargarElementoss->fetchAll();
        $datosCargarElementos = $resultCargarElementos; 
        echo json_encode($datosCargarElementos);
        $stCargarElementoss->closeCursor();
        Conne::disconnect($conCargarElementos);
    
     
    }catch(PDOException $ex){
        Conne::disconnect($conCargarElementos);
        die($ex->getMessage());
    }
    
    