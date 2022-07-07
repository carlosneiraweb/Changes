<?php

  header('Content-Type: application/json');
  header('Cache-Control: no-cache, must-revalidate');
  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
  header('Content-type: application/json; charset=utf-8');

require_once($_SERVER['DOCUMENT_ROOT']."/Changes/Sistema/Conne.php");
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Constantes/ConstantesBbdd.php'); 
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/DataObj.php');
require_once($_SERVER['DOCUMENT_ROOT']."/Changes/Modelo/Usuarios.php");

  if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 

 //Solo en caso el usuario se logee
    if(isset($_SESSION['userTMP'])){
        //var_dump($_SESSION['userTMP']);
        $usuBloqueo = new Usuarios(array());
        $usuLogeado = $_SESSION['userTMP']->devuelveId();
       
        $email = $_SESSION['userTMP']->devuelveEmailPorId($usuLogeado);
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
    
    
 /**para ingresar las palabras buscadas**/
        
        
    if(isset($_POST['palabrasBuscadas'])){
            $palabrasBuscadas = $_POST['palabrasBuscadas'];
        } else if (isset($_GET['palabrasBuscadas'])){
             $palabrasBuscadas = $_GET['palabrasBuscadas'];   
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
        $tabla = TBL_PBS_QUERIDAS;// //Selecciono en la tabla de palabras que la gente ofrece
        $columnaId = "idPost_queridas";
        $columnaPalabra = "palabrasBuscadas";
        
        //En caso no haya resultados y se quiera recibir un email
        //cuando alguien publique un post
        //$tablaPbsPrivada = TBL_BUSQUEDAS_PALABRAS_QUERIDAS_PRIVADAS;  
    }else if($radioBusqueda === "ofrezco"){
        $tabla =  TBL_PBS_OFRECIDAS;//Selecciono la tabla en la que se guardan las palabras que la gente busca
        $columnaId = "idPost_ofrecidas";//columna
        $columnaPalabra = "palabrasOfrecidas"; //columna
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
                     $sqlBuscador="Select distinct $columnaId from $tabla where $columnaPalabra like :buscar order by $columnaId DESC limit 5;";
                //echo $sqlBuscador;
                     
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
            echo $sqlBuscador.PHP_EOL.PHP_EOL;               
                $stm4Bus = $conBusquedas->prepare($sqlBuscador);
                $stm4Bus->bindValue(":buscar",  "{$buscar}%", PDO::PARAM_STR);
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


        
         $sqlPost = "select p.idPost, u.nick, u.idUsuario as idUsu,
                    prov.nombre AS provincia, DATE_FORMAT(p.fechaPost,'%d-%m-%Y')as fecha, 
                    p.titulo, img.nickUsuario, img.ruta, p.comentario, tc.tiempo as tiempoCambio                   
from post p
inner join ".TBL_USUARIO." AS u on u.idUsuario= p.idUsuarioPost
inner join ".TBL_DIRECCION." AS dire on dire.idDireccion = u.idUsuario
inner join ".TBL_PROVINCIAS." AS  prov on prov.nombre = dire.provincia
inner join ".TBL_IMAGENES." AS img on img.post_idPost = :idPost 
inner join ".TBL_TIEMPO_CAMBIO." AS tc on tc.idTiempoCambio = p.tiempo_cambio_idTiempoCambio
where p.idPost = :idPost limit 1";
     
       
        
                $stm3Bus = $conBusquedas->prepare($sqlPost);
                $stm3Bus->bindValue(":idPost", $id[0], PDO::PARAM_INT);
                $stm3Bus->execute();
                $tmp = $stm3Bus->fetch();
                $stm3Bus->closeCursor();
                
        $sqlTotal = "Select IFNULL(COUNT(idComentariosPosts),0) as comentarios "
                . " FROM ".TBL_COMENTARIO. "  where post_idPost = :idPost";                
                
                $stm3To = $conBusquedas->prepare($sqlTotal);
                $stm3To->bindValue(":idPost", $id[0], PDO::PARAM_INT);
                $stm3To->execute();
                $tmpTo = $stm3To->fetch();
                $stm3To->closeCursor();
                $x = $tmpTo[0];
                array_push($tmp, $x);

    
            //inicio=0&opcion=PPS
            //entrar con usuario bloqueado
            //OJO AL PAGESIZE
            //Solo en caso el usuario se logee
            if (isset($_SESSION['userTMP'])) {
                $usuBloqueados = $usuBloqueo->devuelveUsuariosBloqueados($tmp[2]);
                //echo 'entro';
               
                $totalUsuarioBloqueado = count($usuBloqueados);



                //  Si el usuario que ha colgado el Post ha bloqueado 
                // algun usuario se verifica que no sea el que esta logueado
                //Se le impide ver este Post

                if ($totalUsuarioBloqueado > 0) {
                    for ($i = 0; $i < $totalUsuarioBloqueado; $i++) {
                        if (($usuLogeado == $usuBloqueados[$i][0]) and ($usuBloqueados[$i]['bloqueadoTotal'] == 1)) {
                            $tmp['coment'] = 2;
                        } else if (($usuLogeado == $usuBloqueados[$i][0]) and ($usuBloqueados[$i]['bloqueadoParcial'] == 1)) {
                            //Agregamos un testigo para cuando se 
                            //muestre en JAVASCRIPT el POST
                            //Se inavilite el boton de comentar
                            $tmp['coment'] = 1;
                        }
                    }

                    array_push($rs, $tmp);
                } else {

                    array_push($rs, $tmp);
                }
            } else {
                array_push($rs, $tmp);
            }
        }


        echo json_encode($rs);             

        
                break;
                
    case "PIPB":
                
                
                    
                    $sqlInsertarPalabras = "insert into ".TBL_PALABRAS_EMAIL." (usuario_idUsuario,email,palabras_detectar) "
                    . "values (:id_usuario, :email, :palabras_detectar);";         
               

                $stm4Insert = $conBusquedas->prepare($sqlInsertarPalabras);
                $stm4Insert->bindValue(":id_usuario", $usuLogeado, PDO::PARAM_INT);
                $stm4Insert->bindValue(":email", $email[0], PDO::PARAM_STR);
                $stm4Insert->bindValue(":palabras_detectar", $palabrasBuscadas, PDO::PARAM_STR);
                $test = $stm4Insert->execute();
                $stm4Insert->closeCursor();
                $resultPIPB = array("result" => $test);
                echo json_encode($resultPIPB);
                
                break;
           
           
    //SWITCH
        }
        
        
        
    }catch(PDOException $ex){
            Conne::disconnect($conBusquedas);
            echo $ex->getLine().'<br>';
            echo $ex->getFile().'<br>';
            die($ex->getMessage());
    }














