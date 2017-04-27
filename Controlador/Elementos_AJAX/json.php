<?php

    require_once("../../Sistema/Conne.php");
    require_once("../../Sistema/Constantes.php");
    
  

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


   
  
    if($opc == "PPS"){
               
                
            
                $sql = "SELECT SQL_CALC_FOUND_ROWS idPost,titulo FROM post  ORDER BY idPost DESC LIMIT :startRow, :numRows";
                //$sql = "SELECT idPost FROM post ORDER BY fechaPost  DESC";
                $stmBus = $conPost->prepare($sql);
                $stmBus->bindValue(":startRow", $inicio, PDO::PARAM_INT);
                $stmBus->bindValue(":numRows", PAGE_SIZE, PDO::PARAM_INT);
                $stmBus->execute();
                $v = $stmBus->fetchAll();
                $stmBus->closeCursor();

                
                
                //Calculamos el total final como si  la clausula limit no estuviera
                $stm2Bus = $conPost->query("SELECT found_rows()  AS totalRows");
                $row = array ('totalRows' => $stm2Bus->fetch());
                $stm2Bus->closeCursor();
                
                $rs = array();
                array_push($rs, $row);
                
                foreach($v as $id){
          
         
                $sqlPost = "select p.idPost, u.nick, prov.nombre AS provincia, DATE_FORMAT(p.fechaPost,'%d-%m-%Y')as fecha, p.titulo, img.ruta, p.comentario, tc.tiempo as tiempoCambio
from post p
inner join usuario u on u.idUsuario= p.idUsuario
inner join direccion dire on dire.idDireccion = u.idUsuario
inner join provincias prov on prov.idProvincias = dire.provincias_idprovincias
inner join imagenes img on img.post_idPost = :idPost 
inner join tiempo_cambio tc on tc.idTiempoCambio = p.tiempo_cambio_idTiempoCambio
where p.idPost = :idPost limit 1";
                
                $stm3Bus = $conPost->prepare($sqlPost);
                $stm3Bus->bindValue(":idPost", $id[0], PDO::PARAM_INT);
                $stm3Bus->execute();
                $tmp = $stm3Bus->fetch();
                $stm3Bus->closeCursor();
                
                 array_push($rs,$tmp);
                }  
                
                
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
             $tmpRutaTexto = $stm5->fetchAll();
             $stm5->closeCursor();
             
            //Recuperamos las palabras queridas o buscadas del usuario
             $sql ="select palabra as pbsQueridas from pbs_queridas where idPost = ".$idImgSLD[0].";";
             $stm6 = $conPost->query($sql);
             $tmpPbsBuscadas = $stm6->fetchAll();
             $stm6->closeCursor();
             array_push($rutaTextoPbsBuscadas, $tmpRutaTexto, $tmpPbsBuscadas);
             
             echo json_encode($rutaTextoPbsBuscadas);
        
    }
    }catch(PDOException $ex){
        Conne::disconnect($conPost);
        die($ex->getMessage());
    }
    
    