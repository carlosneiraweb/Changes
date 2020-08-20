<?php

//  header('Content-type: application/json; charset=utf-8');
//  header('Cache-Control: no-cache, must-revalidate');
//  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');



    require_once($_SERVER['DOCUMENT_ROOT']."/Changes/Sistema/Conne.php"); 
    require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/DataObj.php');
    require_once($_SERVER['DOCUMENT_ROOT']."/Changes/Modelo/Usuarios.php");
    
    if (isset($_POST['idComentariosBuscar'])){ 
        $idPostBuscar = $_POST['idComentariosBuscar'];
    }else{
        if (isset($_GET['idComentariosBuscar'])){ 
           $idPostBuscar = $_GET['idComentariosBuscar'];
        }
    }
 
    if (isset($_POST['totalPost'])){ 
        $totalPost = $_POST['totalPost'];
    }else{
        if (isset($_GET['totalPost'])){ 
           $totalPost = $_GET['totalPost'];
        }
    }
   
  
     $conBuscarPost = Conne::connect();
    
    
    
     
    
    $sqlBuscarComentarios = "select nombreComenta as nombreComenta, ciudadComentario as ciudadComentario,
        fechaComentario as fechaComentario, tituloComentario as tituloComentario,
        comentarioPost as comentarioPost, post_idPost as idPost
from comentariosposts where post_idPost = :idPost;";
        
  
    $tmpBus = array();
    $stmBusPosts = $conBuscarPost->prepare($sqlBuscarComentarios);
    $stmBusPosts->bindValue(":idPost", $idPostBuscar, PDO::PARAM_INT);
    $stmBusPosts->execute();
    $tmpBus = $stmBusPosts->fetchAll();
    
    $idPost = $tmpBus[0][5];
    
  
     $sqlUsuarioComenta = "Select nick as nombrePublica, DATE_FORMAT(fecha,'%d-%m-%Y') as fechaPublica, ciudad as ciudadPublica
from usuario usu
inner join direccion di on di.idDireccion = usu.idUsuario 
inner join post p on p.idUsuarioPost = usu.idUsuario and p.idPost = $idPost;";
        
        $stmUsuComenta = $conBuscarPost->prepare($sqlUsuarioComenta);
        $stmUsuComenta->bindValue(":idUsuComenta", $idPost, PDO::PARAM_INT);
        $stmUsuComenta->execute();
        $tmpUsu = $stmUsuComenta->fetch();
     
        //var_dump($tmpUsu);
        array_push($tmpUsu, $totalPost);
       
        array_unshift($tmpBus, $tmpUsu);
    //var_dump($tmpBus);
                   echo   json_encode($tmpBus);
         