<?php
/**
 * @author Carlos Neira Sanchez
 * @mail arj.123@hotmail.es
 * @telefono ""
 * @nameAndExt json.php
 * @fecha 04-oct-2016
 */
    require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/DataObj.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Conne.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Constantes.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/Usuarios.php');
    
 session_start();  
   

  // -------  cabeceras indicando que se envian datos JSON.
  header('Content-type: application/json; charset=utf-8');
  header('Cache-Control: no-cache, must-revalidate');
  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

  // -------   Crear la conexión al servidor y ejecutar la consulta.
    try{
    
    $conPost = Conne::connect();
  
  // -------- párametro opción para determinar la select a realizar -------
if (isset($_POST['opcion'])){ 
      $opc = $_POST['opcion'];
}else{
     if (isset($_GET['opcion'])){ 
        $opc = $_GET['opcion'];
     }
}

if(isset($_POST['srcImg'])){
        $idImg = $_POST['srcImg'];
    } else {
        if(isset($_GET['srcImg'])){
         $idImg = $_GET['srcImg'];
        }
    } 
    
if(isset($_POST['inicio'])){
        $inicio = ((int)$_POST['inicio']);
    } else if (isset($_GET['inicio'])){
         $inicio = (int)$_GET['inicio'];   
    } 
    
    
if(isset($_POST['usuario'])){
        $usuario = $_POST['usuario'];
    } else if (isset($_GET['usuario'])){
         $usuario = $_GET['usuario'];   
    } 

      //Solo en caso el usuario se logee
    if(isset($_SESSION['userTMP'])){
        $usuBloqueo = new Usuarios(array());
        $usuLogeado = $_SESSION['userTMP']->devuelveId();
    }
    
    if($opc == "PPS"){
                
             
                        $sql = "SELECT SQL_CALC_FOUND_ROWS idPost FROM post  ORDER BY idPost DESC LIMIT :startRow, :numRows";
                        //$sql = "SELECT idPost FROM post ORDER BY fechaPost  DESC";
                        $stmBus = $conPost->prepare($sql);
                        $stmBus->bindValue(":startRow", $inicio, PDO::PARAM_INT);
                        $stmBus->bindValue(":numRows", PAGE_SIZE, PDO::PARAM_INT);
                        $stmBus->execute();
                        $v = $stmBus->fetchAll();

                
                                //Calculamos el total final como si  la clausula limit no estuviera
                                $stm2Bus = $conPost->query("SELECT found_rows()  AS totalRows");
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
                
        
                $stm3Bus = $conPost->prepare($sqlPost);
                $stm3Bus->bindValue(":idPost", $id[0], PDO::PARAM_INT);
                $stm3Bus->execute();
                $tmp = $stm3Bus->fetch();
                $stm3Bus->closeCursor();
 
                
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
      
                    //var_dump($rs);
                 echo json_encode($rs);
        
    }else if($opc == "SLD"){
            //Nos quedamos con la parte necesaria para sacar de la tabla imagenes el id del post
             $tmpIdImg = substr($idImg, 10);
             $tmpIdImg = strstr($tmpIdImg,'.',true);
             
             $sql = 'select post_idPost from imagenes where ruta = "'.$tmpIdImg.'";' ;
           
             $stm4 = $conPost->query($sql);
             //Recuperamos el id del post
             $idImgSLD = $stm4->fetch();
             $stm4->closeCursor();
             
             //Almacenaremos varios arrays para mostrar todos los datos
             //La ruta de las imagenes, el texto que describe la imagen y las palabras buscadas
             $rutaTextoPbsBuscadas = array();
             //Recuperamos la ruta de la imagen y la descripcion de cada una
             $sql = "select ruta, texto from imagenes where post_idPost =".$idImgSLD[0].";";
             $stm5 = $conPost->query($sql);
             $tmpRutaTexto = $stm5->fetch();
             $stm5->closeCursor();
             
            //Recuperamos las palabras queridas o buscadas del usuario
             $sql ="select palabrasBuscadas as pbsQueridas from busquedas_pbs_buscadas where idPost_queridas = ".$idImgSLD[0].";";
             $stm6 = $conPost->query($sql);
             $tmpPbsBuscadas = $stm6->fetchAll();
            
             array_push($rutaTextoPbsBuscadas, $tmpRutaTexto, $tmpPbsBuscadas);
             //var_dump($tmpPbsBuscadas);
             echo json_encode($rutaTextoPbsBuscadas);
        
    }
    }catch(PDOException $ex){
        Conne::disconnect($conPost);
        die($ex->getMessage());
    }
    
    