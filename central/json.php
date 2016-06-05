<?php

    require_once("../Sistema/Conne.php");
    require_once("../Sistema/Constantes.php");
    
  

  // -------  cabeceras indicando que se envian datos JSON.
  header('Content-Type: application/json');
  header('Cache-Control: no-cache, must-revalidate');
  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

  // -------   Crear la conexión al servidor y ejecutar la consulta.
    try{
    
    $con= Conne::connect();
  
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
    
    if($opc == "PPS"){
        
                $sql = "SELECT idPost FROM post ORDER BY fechaPost  DESC";
                $stm = $con->query($sql);
                $v = $stm->fetchAll();
                
                $rs = array();
               
                foreach($v as $id){
               
                $sqlPost = "select u.nick as nick, prov.nombre as provincia, p.fechaPost as fecha, p.titulo as titulo, img.ruta as ruta, p.titulo as titulo, p.comentario as comentario, tc.tiempo as tiempoCambio
from usuario AS u, post AS p, imagenes AS img, provincias AS prov, direccion as dir, tiempo_cambio as tc
where p.idUsuario = u.idUsuario and p.idPost = $id[0] and img.post_idPost = $id[0]
and dir.provincias_idprovincias = prov.idprovincias 
and tc.idTiempoCambio = p.tiempo_cambio_idTiempoCambio  limit 1";
                $stm2 = $con->query($sqlPost);
                $tmp = $stm2->fetch();
        
                 array_push($rs, $tmp);
                }   
               
                echo json_encode($rs);
        
    }else{

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
        $st = $con->query($sql);
        $resultados= $st->fetchAll();
        $datos = $resultados; 
        echo json_encode($datos);
       
    
    }
        Conne::disconnect($con);
    }catch(PDOException $ex){
        Conne::disconnect($con);
        die($ex->getMessage());
    }
    
    