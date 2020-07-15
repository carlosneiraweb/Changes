<?php

 
  header('Cache-Control: no-cache, must-revalidate');
  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
  header('Content-type: application/json; charset=utf-8');
 // -------  cabeceras indicando que se envian datos JSON.
  
  
  
/**
 * @author Carlos Neira Sanchez
 * @mail arj.123@hotmail.es
 * @telefono ""
 * @nameAndExt busquedas.php
 * @fecha 04-oct-2016
 */
//require($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Constantes.php');
require_once($_SERVER['DOCUMENT_ROOT']."/Changes/Sistema/Conne.php");
require_once($_SERVER['DOCUMENT_ROOT']."/Changes/Sistema/Constantes.php"); 
require_once($_SERVER['DOCUMENT_ROOT']."/Changes/Modelo/Usuarios.php");

    //echo Usuarios::devuelveId($usuario);   
//    $user = new Usuarios();
//    echo $user->devuelveId('carlos');
        
try{

    $conBusquedas= Conne::connect();
  
    
     // -------- párametro opción para determinar la select a realizar -------
    if (isset($_POST['opcion'])){ 
            $opc=$_POST['opcion'];
        }else{
            if (isset($_GET['opcion'])){
                $opc=$_GET['opcion'];
         }        
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
    
    
    if(isset($_POST['usuario'])){
            $usuario = $_POST['usuario'];
        } else if (isset($_GET['INSERTAR_PALABRAS'])){
            $usuario = $_GET['usuario'];   
        }
    
    
    
        /*********  Variable que recibimos de los filtros de busqueda   ***************/

        $buscarPorPrecio = null;
        $buscarPorProvincia = null;
        $buscarPorTiempoCambio = null;
        $radioBusqueda = null;
        $tabla = null;

//Seleccionamos en que tabla buscar cuando un usuario 
        //busca algo en el buscador
    if(isset($_POST['tabla'])){
            $radioBusqueda = $_POST['tabla'];
        } else if (isset($_GET['tabla'])){
             $radioBusqueda = $_GET['tabla'];   
        } 

    if($radioBusqueda === "busco"){
        $tabla = TBL_PBS_OFRECIDAS; //Selecciono en la tabla de palabras que la gente ofrece
        //En caso no haya resultados y se quiera recibir un email
        //cuando alguien publique un post
        $tablaPbsPrivada = TBL_BUSQUEDAS_PALABRAS_QUERIDAS_PRIVADAS;  
    }else if($radioBusqueda === "ofrezco"){
        $tabla = TBL_PBS_QUERIDAS; //Selecciono la tabla en la que se guardan las palabras que la gente busca
        //En caso no haya resultados y se quiera recibir un email
        //cuando alguien publique un post
        $tablaPbsPrivada = TBL_BUSQUEDAS_PALABRAS_OFRECIDAS_PRIVADAS;  
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
        
   
            if($opc == "ENCONTRADO"){
     
    
                //Ponemos distinct por que al escribir el usuario las palabras deseadas
                // Puede repetirlas quiero bici, bicicleta, bicis, etc etc
                $sql = "SELECT distinct SQL_CALC_FOUND_ROWS idPost FROM ".$tabla." where palabra like '$encontrar%'  ORDER BY idPost DESC LIMIT :startRow, :numRows";        

                        $stmBus = $conBusquedas->prepare($sql);
                        $stmBus->bindValue(":startRow", $inicio, PDO::PARAM_INT);
                        $stmBus->bindValue(":numRows", PAGE_SIZE, PDO::PARAM_INT);
                        $stmBus->execute();
                        $v = $stmBus->fetchAll();

                        

                        //Calculamos el total final como si  la clausula limit no estuviera
                        $stm2Bus = $conBusquedas->query("SELECT found_rows()  AS totalRows");
                        $row = array ('totalRows' => $stm2Bus->fetch());
                        $stm2Bus->closeCursor();
                        $rs = array();
                        array_push($rs, $row);

                        foreach($v as $id){

   
        $sqlPost = "select u.nick, prov.nombre AS provincia, DATE_FORMAT(p.fechaPost,'%d-%m-%Y')as fecha, p.titulo, img.ruta, p.titulo, p.comentario, tc.tiempo as tiempoCambio
from post p
inner join usuario u on u.idUsuario= p.idUsuario
inner join direccion dire on dire.idDireccion = u.idUsuario
inner join provincias prov on prov.idProvincias = dire.provincias_idprovincias
inner join imagenes img on img.post_idPost = :idPost 
inner join tiempo_cambio tc on tc.idTiempoCambio = p.tiempo_cambio_idTiempoCambio
where p.idPost = :idPost limit 1";
                 
                $stm3Bus = $conBusquedas->prepare($sqlPost);
                $stm3Bus->bindValue(":idPost", $id[0], PDO::PARAM_INT);
                $stm3Bus->execute();
                $tmp = $stm3Bus->fetchAll();
                
                
                 array_push($rs,$tmp);
                  
                }  
                
                
                echo json_encode($rs);
                
                Conne::disconnect($conBusquedas);
                
           
            } else {

                    switch ($opc) {

                        case "BUSCADOR":
                        
                            if($buscarPorPrecio == 0 && $buscarPorProvincia == 0 && $buscarPorTiempoCambio == 0 ){

                                $sqlBuscador="Select distinct idPost, palabra from ".$tabla." where palabra like  :buscar order by idPost DESC limit 5;";    
                                    //echo $sqlBuscador;

                            }else{

                                if($buscarPorPrecio == 0){
                                    unset($buscarPorPrecio);
                                }else if($buscarPorPrecio == 3001){
                                    $pvp = "  and p.precio > 3000";
                                }else {
                                    $pvp = " and p.precio < ".$buscarPorPrecio;
                                }
                                if($buscarPorProvincia == 0){
                                    unset($buscarPorProvincia);
                                }

                                if($buscarPorTiempoCambio == 0){

                                    unset($buscarPorTiempoCambio);
                                }

            $sqlBuscador="select  pbs.palabra
            from post p
            inner join usuario u on u.idUsuario= p.idUsuario
            inner join direccion dire on dire.idDireccion = u.idUsuario
            inner join provincias prov on prov.idProvincias = dire.provincias_idprovincias ".(isset($buscarPorProvincia) ? " and prov.nombre = '$buscarPorProvincia'" : "").
            " inner join tiempo_cambio tmc on tmc.idTiempoCambio = p.tiempo_cambio_idTiempoCambio ".(isset($buscarPorTiempoCambio) ? " and tmc.tiempo = '$buscarPorTiempoCambio'" : "").
            " inner join ".$tabla. " pbs on pbs.idPost = p.idPost and pbs.palabra like :buscar ".(isset($buscarPorPrecio) ? $pvp : ""). "  order by pbs.idPost DESC limit 5;";

                } 
                //echo $sqlBuscador;

                            $stm4Bus = $conBusquedas->prepare($sqlBuscador);
                            $stm4Bus->bindValue(":buscar", "{$buscar}%", PDO::PARAM_STR);

                            $stm4Bus->execute();
                            $tmp3 = $stm4Bus->fetchAll(); 

                        

                                    echo json_encode($tmp3);
                            Conne::disconnect($conBusquedas);

                       break;

           
           
            case "PEMP":
                
                //Creamos dinamicamente los campos 
                //de las tablas. Segun el parametro $tabla
                    
                    $sqlInsertarPalabras = "insert into $tablaPbsPrivada (usuario_idUsuario,".(($radioBusqueda === "busco" ) ? " palabrasBuscadasPrivadas" : "palabrasOfecidasPrivadas").") values (:idUsuario, :palabras);";         
               
                
//                
//                $stm5Bus = $conBusquedas->prepare($sqlInsertarPalabras);
//                $stm5Bus->bindValue(":idUsuario", Usuarios::devuelveId($usuario), PDO::PARAM_INT);
//                $stm5Bus->bindValue(":palabras", $insertarPalabraBuscada, PDO::PARAM_STR);
//                $testInsertPalabras = $stm5Bus->execute();
                
//                   echo json_encode($testInsertPalabras);
                        Conne::disconnect($conBusquedas);
                
                break;
             
                    }      
            }   
        
   
}catch(PDOException $ex){
        Conne::disconnect($conBusquedas);
        echo $ex->getLine().'<br>';
        echo $ex->getFile().'<br>';
        die($ex->getMessage());
}














