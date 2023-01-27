<?php

/**
 * @author carlos
 * @mail Expression mail is undefined on line 4, column 12 in Templates/Scripting/EmptyPHP.php.
 * @telefono Expression telefono is undefined on line 5, column 16 in Templates/Scripting/EmptyPHP.php.
 * @nameAndExt imagenesAlSubirPost.php
 * @fecha 28-oct-2020
 */


require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Conne.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Constantes/ConstantesBbdd.php');
require_once($_SERVER['DOCUMENT_ROOT']."/Changes/Sistema/Constantes/ConstantesErrores.php");
    
  

  // -------  cabeceras indicando que se envian datos JSON.
  header('Content-type: application/json; charset=utf-8');
  header('Cache-Control: no-cache, must-revalidate');
  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
  
  // -------   Crear la conexión al servidor y ejecutar la consulta.
    try{
    
    $conImgSubirPost = Conne::connect();
  
  // -------- párametro opción para determinar la select a realizar -------
if (isset($_POST['opcion'])){ 
      $opcImgSubirPost = $_POST['opcion'];
}else{
     if (isset($_GET['opcion'])){ 
      $opcImgSubirPost = $_GET['opcion'];
     }
}

if (isset($_POST['idPost'])){ 
        $idPost = $_POST['idPost'];
}else{
     if (isset($_GET['idPost'])){ 
        $idPost = $_GET['idPost'];
     }
}


if(isset($_POST['ruta'])){
        $ruta = $_POST['ruta'];
    } else {
        if(isset($_GET['ruta'])){
            $ruta = $_GET['ruta'];
        }
    }
 
 // echo $idPost." ".$ruta." ".$opcImgSubirPost;
            
 
  
 switch ($opcImgSubirPost) {
        case "ImagenNueva":
            $sqlImgSubirPost = "Select directorio  as ruta  from ".TBL_IMAGENES." WHERE postIdPost = '".$idPost."'";
            break;
        case "ImagenEliminarNueva":
            $sqlImgSubirPost = "SELECT directorio as ruta, texto as texto, idImagen as idImagen from ".TBL_IMAGENES." WHERE postIdPost = '".$idPost."' and directorio = '".$ruta."'";
        
            break;
    }   
        $stImgSubirPost = $conImgSubirPost->query($sqlImgSubirPost);
        $resultImgSubirPost = $stImgSubirPost->fetchAll();
       
        if($resultImgSubirPost == null){throw new Exception();}
        
        echo json_encode($resultImgSubirPost);
        
        
            $stImgSubirPost->closeCursor();
            Conne::disconnect($conImgSubirPost);
    
      
    }catch(Exception $ex){
        
        Conne::disconnect($conImgSubirPost);
        $_SESSION['error'] = ERROR_INSERTAR_ARTICULO;
        
        if($opcImgSubirPost == "ImagenEliminarNueva"){
            $excepciones = new MisExcepcionesPost(CONST_ERROR_BBDD_ELIMINAR_IMG_POST[1],CONST_ERROR_ELIMINAR_IMG_SUBIR_POST[0],$ex);
            $excepciones->redirigirPorErrorTrabajosEnArchivosSubirPost("errorPost",true);
        }else{
            $excepciones = new MisExcepcionesPost(CONST_ERROR_MOSTRAR_IMG_SELECCIONADA[1],CONST_ERROR_MOSTRAR_IMG_SELECCIONADA[0],$ex);
            $excepciones->redirigirPorErrorTrabajosEnArchivosSubirPost("errorPost",true);
        }
    }
    
    

