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
    
if(isset($_POST['srcImg'])){
        $idImg = $_POST['srcImg'];
    } else {
        if(isset($_GET['srcImg'])){
         $idImg = $_GET['srcImg'];
        }
    } 
    
//if(isset($_POST['inicio'])){
//        $inicio = $_POST['inicio'];
//    } else if (isset($_GET['inicio'])){
//         $inicio = $_GET['inicio'];   
//    } else{
//        $inicio = 1;
//    }
    
    
    if($opc == "PPS"){
        
                
                $sql = "SELECT SQL_CALC_FOUND_ROWS idPost FROM post ORDER BY fechaPost ASC LIMIT :startRow, :numRows";
                //$sql = "SELECT idPost FROM post ORDER BY fechaPost  DESC";
                $stm = $con->prepare($sql);
                $stm->bindValue(":startRow", 0, PDO::PARAM_INT);
                $stm->bindValue(":numRows", PAGE_SIZE, PDO::PARAM_INT);
                $stm->execute();
                $v = $stm->fetchAll();
                
                //Calculamos el total final como si  la clausula limit no estuviera
                $stm = $con->query("SELECT found_rows()  AS totalRows");
                $row = array ('totalRows' => $stm->fetch());
                
                $rs = array();
                array_push($rs, $row);
                
                foreach($v as $id){
               // p.fechaPost as fecha
                $sqlPost = "select u.nick as nick, prov.nombre as provincia, DATE_FORMAT(p.fechaPost,'%d-%m-%Y')as fecha, p.titulo as titulo, img.ruta as ruta, p.titulo as titulo, p.comentario as comentario, tc.tiempo as tiempoCambio
from usuario AS u, post AS p, imagenes AS img, provincias AS prov, direccion as dir, tiempo_cambio as tc
where p.idUsuario = u.idUsuario and p.idPost = $id[0] and img.post_idPost = $id[0]
and dir.provincias_idprovincias = prov.idprovincias 
and tc.idTiempoCambio = p.tiempo_cambio_idTiempoCambio  limit 1";
                $stm2 = $con->query($sqlPost);
                $tmp = $stm2->fetch();
                
                 array_push($rs,$tmp);
                }  
                
                
                 echo json_encode($rs);
        
                
                
    }else if($opc == "SLD"){
            //Nos quedamos con la parte necesaria para sacar de la tabla imagenes el id del post
             $tmpIdImg = strstr($idImg,'/',false);
             $tmpIdImg = strstr($tmpIdImg,'.',true);
             
             $sql = 'select post_idPost from imagenes where ruta = "'.$tmpIdImg.'";' ;
           
             $smt3 = $con->query($sql);
             //Recuperamos el id del post
             $idImgSLD = $smt3->fetch();
             //Almacenaremos varios arrays para mostrar todos los datos
             //La ruta de las imagenes, el texto que dscribe la imagen y las palabras buscadas
             $rutaTextoPbsBuscadas = array();
             //Recuperamos la ruta de la imagen y la descripcion de cada una
             $sql = "select ruta, texto from imagenes where post_idPost =".$idImgSLD[0].";";
             $smt3 = $con->query($sql);
             $tmpRutaTexto = $smt3->fetchAll();
             //Recuperamos las palabras queridas o buscadas del usuario
             $sql ="select palabra as pbsQueridas from pbs_queridas where idPost = ".$idImgSLD[0].";";
             $smt3 = $con->query($sql);
             $tmpPbsBuscadas = $smt3->fetchAll();
             array_push($rutaTextoPbsBuscadas, $tmpRutaTexto, $tmpPbsBuscadas);
             
             echo json_encode($rutaTextoPbsBuscadas);
        
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
       
         Conne::disconnect($con);
    
    }   
    }catch(PDOException $ex){
        Conne::disconnect($con);
        die($ex->getMessage());
    }
    
    