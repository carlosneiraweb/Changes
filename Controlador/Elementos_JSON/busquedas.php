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
   
    

         
    if($opc == "ENCONTRADO"){
        //Ponemos distinct por que al escribir el usuario las palabras deseadas
        // Puede repetirlas quiero bici, bicicleta, bicis, etc etc

        $sql = "SELECT distinct SQL_CALC_FOUND_ROWS idPost FROM pbs_queridas where palabra like '%$encontrar%'  ORDER BY idPost DESC LIMIT :startRow, :numRows";        
        
                
                $stm = $con->prepare($sql);
                $stm->bindValue(":startRow", $inicio, PDO::PARAM_INT);
                $stm->bindValue(":numRows", PAGE_SIZE, PDO::PARAM_INT);
                $stm->execute();
                $v = $stm->fetchAll();
   
                //Calculamos el total final como si  la clausula limit no estuviera
                $stm = $con->query("SELECT found_rows()  AS totalRows");
                $row = array ('totalRows' => $stm->fetch());
                
                $rs = array();
                array_push($rs, $row);
                
                foreach($v as $id){
               
                //$sqlPost = "Select p.idPost from post as p where p.idPost = $id[0]";
                $sqlPost = "select  u.nick as nick, prov.nombre as provincia, DATE_FORMAT(p.fechaPost,'%d-%m-%Y')as fecha, p.titulo as titulo, img.ruta as ruta, p.titulo as titulo, p.comentario as comentario, tc.tiempo as tiempoCambio
from usuario AS u, post AS p, imagenes AS img, provincias AS prov, direccion as dir, tiempo_cambio as tc
where p.idUsuario = u.idUsuario and p.idPost = $id[0] and img.post_idPost = $id[0]  limit 1";
                $stm2 = $con->query($sqlPost);
                $tmp = $stm2->fetch();
                
                 array_push($rs,$tmp);
                }  
                
                
                 echo json_encode($rs);
                 Conne::disconnect($con);
        
} else {

        switch ($opc) {
    
            case "BUSCADOR":
                $sql="Select palabra from pbs_queridas where palabra like  '$buscar%' limit 5";    
                    break;
            
        }
        


        $st = $con->query($sql);
        $resultados= $st->fetchAll();
        $datos = $resultados; 
        echo json_encode($datos);
       
         Conne::disconnect($con);
    
    } 
    }catch(PDOException $ex){
        Conne::disconnect($con);
        die($ex->getMessage());
    }














