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
    
    $conBusquedas= Conne::connect();
  
    
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

$buscarPorPrecio = null;
$buscarPorProvincia = null;
$buscarPorTiempoCambio = null;
$radioBusqueda = null;
$tabla = null;

if(isset($_POST['tabla'])){
        $radioBusqueda = $_POST['tabla'];
    } else if (isset($_GET['tabla'])){
         $radioBusqueda = $_GET['tabla'];   
    } 

    //Selecionamos en que tabla se hace la busqueda
    //Si el usuario busca cosas ofrecidas por los usuarios
    //O quiere ver cosas que los usuarios van buscando
    
    if($radioBusqueda === "busco"){
        $tabla = TBL_PBS_OFRECIDAS;
    }else if($radioBusqueda === "buscan"){
        $tabla = TBL_PBS_QUERIDAS;
    }
   
if(isset($_POST['buscarPorProvincia'])){
        $buscarPorProvincia = $_POST['buscarPorProvincia'];
    } else if (isset($_GET['buscarPorProvincia'])){
         $buscarPorProvincia = $_GET['buscarPorProvincia'];
         
    }      

if(isset($_POST['buscarPorPrecio'])){
        $buscarPorPrecio = $_POST['buscarPorPrecio'];
    } else if (isset($_GET['buscarPorPrecio'])){
         $buscarPorPrecio = $_GET['buscarPorPrecio'];   
    }   
    

if(isset($_POST['buscarPorTiempoCambio'])){
        $buscarPorTiempoCambio = $_POST['buscarPorTiempoCambio'];
    } else if (isset($_GET['buscarPorTiempoCambio'])){
         $buscarPorTiempoCambio = $_GET['buscarPorTiempoCambio'];   
    }      
   
 //echo"por precio: ".$buscarPorPrecio. ' por provincia '.$buscarPorProvincia.' por tiempo '.$buscarPorTiempoCambio;






    
    
    
    
    if($opc == "ENCONTRADO"){
        //Ponemos distinct por que al escribir el usuario las palabras deseadas
        // Puede repetirlas quiero bici, bicicleta, bicis, etc etc

        $sql = "SELECT distinct SQL_CALC_FOUND_ROWS idPost FROM ".$tabla." where palabra like '$encontrar%'  ORDER BY idPost DESC LIMIT :startRow, :numRows";        
               
                $stmBus = $conBusquedas->prepare($sql);
                $stmBus->bindValue(":startRow", $inicio, PDO::PARAM_INT);
                $stmBus->bindValue(":numRows", PAGE_SIZE, PDO::PARAM_INT);
                $stmBus->execute();
                $v = $stmBus->fetchAll();
                
                $stmBus->closeCursor();
                
                //Calculamos el total final como si  la clausula limit no estuviera
                $stm2Bus = $conBusquedas->query("SELECT found_rows()  AS totalRows");
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
                 
                $stm3Bus = $conBusquedas->prepare($sqlPost);
                $stm3Bus->bindValue(":idPost", $id[0], PDO::PARAM_INT);
                $stm3Bus->execute();
                $tmp = $stm3Bus->fetch();
                $stm3Bus->closeCursor();
                
                 array_push($rs,$tmp);
                  
                }  
                
                
                echo json_encode($rs);
                
                Conne::disconnect($conBusquedas);
        
} else {

        switch ($opc) {
    
            case "BUSCADOR":
                if($buscarPorPrecio == 0 && $buscarPorProvincia == 0 && $buscarPorTiempoCambio == 0 ){
                    $sqlBuscador="Select palabra from ".$tabla." where palabra like  :buscar order by idPost DESC limit 5;";    
                }else{
                    
                    if($buscarPorPrecio == 0){
                        unset($buscarPorPrecio);
                    }else if($buscarPorPrecio == 3001){
                        $pvp = "  and p.precio > 3000";
                    }else {
                        $pvp = " and p.precio < ".$buscarPorPrecio;
                    }
                    if($buscarPorProvincia === '0'){
                        unset($buscarPorProvincia);
                    }
                    
                    if($buscarPorTiempoCambio === '0'){
                       
                        unset($buscarPorTiempoCambio);
                    }
               
    $sqlBuscador="select  pbs.palabra
from post p
inner join usuario u on u.idUsuario= p.idusuario
inner join direccion dire on dire.idDireccion = u.idUsuario
inner join provincias prov on prov.idProvincias = dire.provincias_idprovincias ".(isset($buscarPorProvincia) ? " and prov.nombre = '$buscarPorProvincia'" : "").
" inner join tiempo_cambio tmc on tmc.idTiempoCambio = p.tiempo_cambio_idTiempoCambio ".(isset($buscarPorTiempoCambio) ? " and tmc.tiempo = '$buscarPorTiempoCambio'" : "").
" inner join ".$tabla. " pbs on pbs.idPost = p.idPost and pbs.palabra like :buscar ".(isset($buscarPorPrecio) ? $pvp : ""). " order by pbs.idPost DESC;";
        
    } 
        
                //echo $sqlBuscador; 
                $stm4Bus = $conBusquedas->prepare($sqlBuscador);
                $stm4Bus->bindValue(":buscar", "{$buscar}%", PDO::PARAM_STR);
            
                $stm4Bus->execute();
                $tmp3 = $stm4Bus->fetchAll(); 

                $stm4Bus->closeCursor();
                  
                        echo json_encode($tmp3);
                Conne::disconnect($conBusquedas);
           
           break;
        }
        

       
    } 
    }catch(PDOException $ex){
        Conne::disconnect($conBusquedas);
        die($ex->getMessage());
    }














