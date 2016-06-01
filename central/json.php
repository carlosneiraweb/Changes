<?php

    require_once("../Sistema/Conne.php");
    require_once("../Sistema/Constantes.php");
    
  

  // -------  cabeceras indicando que se envian datos JSON.
  header('Content-Type: application/json');
  header('Cache-Control: no-cache, must-revalidate');
  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

  // -------   Crear la conexión al servidor y ejecutar la consulta.
  
 
  
  // -------- párametro opción para determinar la select a realizar -------
if (isset($_POST['opcion'])) 
      $opc=$_POST['opcion'];
else
     if (isset($_GET['opcion'])) 
        $opc=$_GET['opcion'];

if (isset($_POST['idPost'])) 
        $idPost=$_POST['idPost'];
  else
     if (isset($_GET['idPost'])) 
        $idPost=$_GET['idPost'];
     
if(isset($_POST['ruta'])){
        $ruta = $_POST['ruta'];
    } else {
        if(isset($_GET['ruta'])){
            $ruta = $_GET['ruta'];
        }
    }    
    
    switch ($opc) {
        case "PP":
            $sql="select nombre from ".TBL_PROVINCIAS.";";     
                break;
        case "PG":
            $sql = "select genero from ".TBL_GENERO.";";
            break;
        case "PS":
            $sql = "Select nombre_seccion from ".TBL_SECCIONES.";";
            break;
        case "PT":
            $sql = "Select * from ".TBL_TIEMPO_CAMBIO.";";
            break;
        case "UI":
            $sql = "Select ruta  as ruta  from ".TBL_IMAGENES." WHERE post_idPost = '".$idPost."'";
            break;
        case "PMI":
            $sql = "SELECT ruta as ruta, texto as texto from ".TBL_IMAGENES." WHERE post_idPost = '".$idPost."' and ruta = '".$ruta."'";
            break;
    }
          
    try{
        
        $con= Conne::connect();
        $st = $con->query($sql);
        $resultados= $st->fetchAll();
        Conne::disconnect($con);
    
        
                $datos = $resultados; // Almacenar en un array cada filas del recordset.
           
          echo json_encode($datos);// función de PHP que convierte a formato JSON el array.
  
    }catch(PDOException $ex){
        Conne::disconnect($con);
        die($ex->getMessage());
    }
    
    