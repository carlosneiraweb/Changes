<?php

  header('Content-Type: application/json');
  header('Cache-Control: no-cache, must-revalidate');
  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
  header('Content-type: application/json; charset=utf-8');

require_once($_SERVER['DOCUMENT_ROOT']."/Changes/Sistema/Conne.php");
require_once($_SERVER['DOCUMENT_ROOT']."/Changes/Sistema/Constantes.php"); 
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/DataObj.php');
require_once($_SERVER['DOCUMENT_ROOT']."/Changes/Modelo/Usuarios.php");

 session_start();  

 //Solo en caso el usuario se logee
    if(isset($_SESSION['userTMP'])){
        //var_dump($_SESSION['userTMP']);
        $usuBloqueo = new Usuarios(array());
        $usuLogeado = $_SESSION['userTMP']->devuelveId();
    }

 
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



     
     
    if(isset($_POST['inicio'])){
            $inicio = (int)$_POST['inicio'];
        } else if (isset($_GET['inicio'])){
             $inicio = (int)$_GET['inicio'];   
        }   
    
    
    
    
        /*********  Variable que recibimos de los filtros de busqueda   ***************/

        $buscarPorPrecio = null;
        $buscarPorProvincia = null;
        $buscarPorTiempoCambio= null;
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
        $columnaId = "idPost_ofrecidas";//columna
        $columnaPalabra = "palabrasOfrecidas"; //columna
        //En caso no haya resultados y se quiera recibir un email
        //cuando alguien publique un post
        //$tablaPbsPrivada = TBL_BUSQUEDAS_PALABRAS_QUERIDAS_PRIVADAS;  
    }else if($radioBusqueda === "ofrezco"){
        $tabla = TBL_PBS_QUERIDAS; //Selecciono la tabla en la que se guardan las palabras que la gente busca
        $columnaId = "idPost_queridas";
        $columnaPalabra = "palabrasBuscadas";
        //En caso no haya resultados y se quiera recibir un email
        //cuando alguien publique un post
        //$tablaPbsPrivada = TBL_BUSQUEDAS_PALABRAS_OFRECIDAS_PRIVADAS;  
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
            $buscarPorTiempoCambio= $_POST['buscarPorTiempoCambio'];           
        } else if (isset($_GET['buscarPorTiempoCambio'])){
            $buscarPorTiempoCambio = $_GET['buscarPorTiempoCambio'];   
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
    
        switch ($opc) {

            case "BUSCADOR":
                         
                if($buscarPorPrecio === '0' && $buscarPorProvincia === '1' && $buscarPorTiempoCambio === '0' ){
                     $sqlBuscador="Select distinct $columnaId from $tabla where $columnaPalabra like  :buscar order by $columnaId DESC limit 5;";
                }else{
                                   
                    if($buscarPorPrecio == '0'){
                        unset($buscarPorPrecio);
                    }else if($buscarPorPrecio == 3001){
                        $pvp = "  and p.precio > 3000";
                    }else {
                        $pvp = " and p.precio < ".$buscarPorPrecio;
                    }
                                        
                    if($buscarPorProvincia == '1'){
                        unset($buscarPorProvincia);
                    }
                                   
                    if($buscarPorTiempoCambio == '0'){
                        unset($buscarPorTiempoCambio);
                    }            
           
            $sqlBuscador= "select distinct $columnaId
            from post p
            inner join usuario u on u.idUsuario = p.idUsuarioPost
            inner join direccion dire on dire.idDireccion = u.idUsuario".
            " inner join tiempo_cambio tmc on tmc.idTiempoCambio = p.tiempo_cambio_idTiempoCambio ".(isset($buscarPorTiempoCambio) ? " and tmc.tiempo = '$buscarPorTiempoCambio'" : "").
            " inner join ".$tabla. " pbs on $columnaId = p.idPost ".
            (isset($buscarPorProvincia) ? " and dire.provincia = '$buscarPorProvincia'" : "").
            " and $columnaPalabra like :buscar ".(isset($buscarPorPrecio) ? $pvp : ""). "  order by $columnaId DESC limit 5;";       
               
                    }
                            
                $stm4Bus = $conBusquedas->prepare($sqlBuscador);
                $stm4Bus->bindValue(":buscar",  "%{$buscar}%", PDO::PARAM_STR);
                $stm4Bus->execute();
                $tmp3 = $stm4Bus->fetchAll(); 
                           
    $palabras = array();  
        
            foreach ($tmp3 as $id){
                                
    $sqlPalabras = "Select $columnaPalabra AS palabras from $tabla  where $columnaId = :idPost limit 1;";                            
  
    
        $stmPalabras = $conBusquedas->prepare($sqlPalabras);
        $stmPalabras->bindValue(":idPost", $id[0], PDO::PARAM_INT);                        
        $stmPalabras->execute();
        $tmpPalabras = $stmPalabras->fetch();
        $stmPalabras->closeCursor();
                                
                                
        array_push($palabras, $tmpPalabras);
        
                                    }
        
        echo json_encode($palabras);   
       
                       break;

            case 'ENCONTRADO':
                
               $sql = 'select distinct SQL_CALC_FOUND_ROWS '.$columnaId.' FROM '.$tabla.' where MATCH ('.$columnaPalabra.') AGAINST ('."'$encontrar'".') LIMIT :startRow, :numRows';
                //echo $sql;
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

                               
   
        $sqlPost = "select pos.idPost, u.nick, u.idUsuario as idUsu, prov.nombre AS provincia, DATE_FORMAT(p.fechaPost,'%d-%m-%Y')as fecha, p.titulo, img.ruta, p.comentario, tc.tiempo as tiempoCambio
from post p
inner join usuario u on u.idUsuario= p.idUsuarioPost
inner join direccion dire on dire.idDireccion = u.idUsuario
inner join post pos on u.idUsuario = pos.idUsuarioPost
inner join provincias prov on prov.nombre = dire.provincia
inner join imagenes img on img.post_idPost = :idPost 
inner join tiempo_cambio tc on tc.idTiempoCambio = p.tiempo_cambio_idTiempoCambio
where pos.idPost = :idPost limit 1";
   
     
        
        
                $stm3Bus = $conBusquedas->prepare($sqlPost);
                $stm3Bus->bindValue(":idPost", $id[0], PDO::PARAM_INT);
                $stm3Bus->execute();
                $tmp = $stm3Bus->fetch();
                $stm3Bus->closeCursor();
                
        $sqlTotal = "Select IFNULL(COUNT(idComentariosPosts),0) as comentarios "
                . " FROM comentariosposts where post_idPost = :idPost";                
                
                $stm3To = $conBusquedas->prepare($sqlTotal);
                $stm3To->bindValue(":idPost", $id[0], PDO::PARAM_INT);
                $stm3To->execute();
                $tmpTo = $stm3To->fetch();
                $stm3To->closeCursor();
                $x = $tmpTo[0];
                array_push($tmp, $x);

                 

                
         //Solo en caso el usuario se logee
if(isset($_SESSION['userTMP'])){
    $usuBloqueados = $usuBloqueo->devuelveUsuariosBloqueados($tmp[2]); 
    $totalUsuarioBloqueado =  count($usuBloqueados);
    
        $x= 0;
        
            //  Si el usuario que ha colgado el Post ha bloqueado 
            // algun usuario se verifica que no sea el que esta logueado
            //Se le impide ver este Post
        
                if($totalUsuarioBloqueado > 0){
                    for($i=0; $i < $totalUsuarioBloqueado; $i++){
                        if(($usuLogeado[0] == $usuBloqueados[$i][0]) and ($usuBloqueados[$i]["bloqueadoTotal"] == 1) ){
                            $x++;
                        }else if (($usuLogeado[0] == $usuBloqueados[$i][0]) and ($usuBloqueados[$i]["bloqueadoParcial"] == 1)){
                            //Agregamos un testigo para cuando se 
                            //muestre en JAVASCRIPT el POST
                            //Se inavilite el boton de comentar
                            $tmp['coment'] = 1;
                        }
                    }
                    //Si el usuario logueado no esta en el 
                    //array del usuario de bloqueados por la 
                    //persona que ha subido el Post
                    //se agrega al array de Posts
                            if($x == 0){
                                array_push($rs, $tmp);
                            }
                    
                    
                    }else{
                        array_push($rs, $tmp);
                    }
      
        }else{
           
                array_push($rs, $tmp);
       
        }
                  
    }  
                
               
                echo json_encode($rs);
                
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
                      
                break;
           
    //SWITCH
        }
        
        
        
    }catch(PDOException $ex){
            Conne::disconnect($conBusquedas);
            echo $ex->getLine().'<br>';
            echo $ex->getFile().'<br>';
            die($ex->getMessage());
    }














