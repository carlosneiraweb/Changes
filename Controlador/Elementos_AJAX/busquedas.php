<?php

/**
 * @author Carlos Neira Sanchez
 * @mail arj.123@hotmail.es
 * @telefono ""
 * @nameAndExt busquedas.php
 * @fecha 04-oct-2016
 */


require_once("../../Sistema/Conne.php");
    require_once("../../Sistema/Constantes.php");
    
  

  // -------  cabeceras indicando que se envian datos JSON.
  header('Content-Type: application/json');
  header('Cache-Control: no-cache, must-revalidate');
  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

  // -------   Crear la conexión al servidor y ejecutar la consulta.
    try{
    
    $con= Conne::connect();
  
    
     // -------- párametro opción para determinar la select a realizar -------
if (isset($_POST['opcion'])){ 
      $opc=$_POST['opcion'];
}else{
     if (isset($_GET['opcion'])) 
        $opc=$_GET['opcion'];
}

if (isset($_POST['BUSCAR'])) {
        $buscar=$_POST['BUSCAR'];
} else {
     if (isset($_GET['BUSCAR'])) 
        $buscar=$_GET['BUSCAR'];
}

if (isset($_POST['ENCONTRADO'])) {
        $encontrado=$_POST['ENCONTRADO'];
}  else {
     if (isset($_GET['ENCONTRADO'])) 
        $encontrado=$_GET['ENCONTRADO'];
}     
 
if (isset($_POST['ENCONTRAR'])) {
        $encontrar=$_POST['ENCONTRAR'];
}  else {
     if (isset($_GET['ENCONTRAR'])) 
        $encontrar=$_GET['ENCONTRAR'];
}     
     
if(isset($_POST['inicio'])){
        $inicio = (int)$_POST['inicio'];
    } else if (isset($_GET['inicio'])){
         $inicio = (int)$_GET['inicio'];   
    }      

    
/*********  Variable que recibimos de los filtros de busqueda   ***************/


if(isset($_POST['provincia'])){
        $provincia = $_POST['provincia'];
    } else if (isset($_GET['provincia'])){
         $provincia = $_GET['provincia'];   
    }      

if(isset($_POST['precio'])){
        $precio = $_POST['precio'];
    } else if (isset($_GET['precio'])){
         $precio = $_GET['precio'];   
    }      

if(isset($_POST['tiempoCambio'])){
        $tiempoCambio = $_POST['tiempoCambio'];
    } else if (isset($_GET['tiempoCambio'])){
         $tiempoCambio = $_GET['tiempoCambio'];   
    }      
   
  //echo "provincias vale: ".$provincia; 






    
    
    
    
    if($opc == "ENCONTRADO"){
        //Ponemos distinct por que al escribir el usuario las palabras deseadas
        // Puede repetirlas quiero bici, bicicleta, bicis, etc etc

        $sql = "SELECT distinct SQL_CALC_FOUND_ROWS idPost FROM pbs_queridas where palabra like '$encontrar%'  ORDER BY idPost DESC LIMIT :startRow, :numRows";        
               
                $stmBus = $con->prepare($sql);
                $stmBus->bindValue(":startRow", $inicio, PDO::PARAM_INT);
                $stmBus->bindValue(":numRows", PAGE_SIZE, PDO::PARAM_INT);
                $stmBus->execute();
                $v = $stmBus->fetchAll();
                
                $stmBus->closeCursor();
                
                //Calculamos el total final como si  la clausula limit no estuviera
                $stm2Bus = $con->query("SELECT found_rows()  AS totalRows");
                $row = array ('totalRows' => $stm2Bus->fetch());
                $stm2Bus->closeCursor();
                $rs = array();
                array_push($rs, $row);
                
                foreach($v as $id){
                    
   
        $sqlPost = "select u.nick, prov.nombre AS provincia, DATE_FORMAT(p.fechaPost,'%d-%m-%Y')as fecha, p.titulo, img.ruta, p.titulo, p.comentario, tc.tiempo as tiempoCambio
from post p
inner join usuario u on u.idUsuario= p.idusuario
inner join direccion dire on dire.idDireccion = u.idUsuario
inner join provincias prov on prov.idProvincias = dire.provincias_idprovincias
inner join imagenes img on img.post_idPost = :idPost 
inner join tiempo_cambio tc on tc.idTiempoCambio = p.tiempo_cambio_idTiempoCambio
where p.idPost = :idPost limit 1";
                 
                $stm3Bus = $con->prepare($sqlPost);
                $stm3Bus->bindValue(":idPost", $id[0], PDO::PARAM_INT);
                $stm3Bus->execute();
                $tmp = $stm3Bus->fetch();
                $stm3Bus->closeCursor();
                
                 array_push($rs,$tmp);
                  
                }  
                
                
                echo json_encode($rs);
                Conne::disconnect($con);
        
} else {

        switch ($opc) {
    
            case "BUSCADOR":
                $sqlBuscador="Select palabra from pbs_queridas where palabra like :buscar limit 5";    
                   
      
                $stm4Bus = $con->prepare($sqlBuscador);
                $stm4Bus->bindValue(":buscar", "{$buscar}%", PDO::PARAM_STR);
                $stm4Bus->execute();
                $tmp3 = $stm4Bus->fetchAll(); 
                
                $stm4Bus->closeCursor();
                echo json_encode($tmp3);
                Conne::disconnect($con);
      
            break;
        }
        

        /*
        $st = $con->query($sqlBuscador);
        $resultados= $st->fetchAll();
        $datos = $resultados; 
        echo json_encode($datos);
       
         Conne::disconnect($con);
        */
    } 
    }catch(PDOException $ex){
        Conne::disconnect($con);
        die($ex->getMessage());
    }














