<?php

  header('Content-type: application/json; charset=utf-8');
  header('Cache-Control: no-cache, must-revalidate');
  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
  header('Content-type: application/json; charset=utf-8');


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
 
    if (isset($_POST['totalComent'])){ 
        $totalComent = $_POST['totalComent'];
    }else{
        if (isset($_GET['totalComent'])){ 
           $totalComent = $_GET['totalComent'];
        }
    }
   
  
     $conBuscarPost = Conne::connect();
    
    
    
     
    
    $sqlBuscarComentarios = "select nombreComenta as nombreComenta, ciudadComentario as ciudadComentario,
         DATE_FORMAT(fechaComentario,'%d-%m-%Y') as fechaComentario, tituloComentario as tituloComentario,
        comentarioPost as comentarioPost, post_idPost as idPost
from comentariosposts where post_idPost = :idPost;";
   // echo $sqlBuscarComentarios;
  
    $tmpBus = array();
    $stmBusPosts = $conBuscarPost->prepare($sqlBuscarComentarios);
    $stmBusPosts->bindValue(":idPost", $idPostBuscar, PDO::PARAM_INT);
    $stmBusPosts->execute();
    $tmpBus = $stmBusPosts->fetchAll();
    
    $idPost = $tmpBus[0][5];
    //var_dump($idPost);
  
     $sqlUsuarioComenta = "select nick as nick, ciudad as ciudadPublica, DATE_FORMAT(fechaPost,'%d-%m-%Y') as fechaPublica
from post p
inner join direccion as di on di.idDireccion = (select idUsuarioPost from post where idPost = :idPost)
inner join usuario as usu on usu.idUsuario = (select idUsuarioPost from post where idPost = :idPost) and p.idPost = :idPost;";
       
        $stmUsuComenta = $conBuscarPost->prepare($sqlUsuarioComenta);
        $stmUsuComenta->bindValue(":idPost", $idPost, PDO::PARAM_INT);
        //$stmUsuComenta->bindValue(":idPost", $idPost, PDO::PARAM_INT);
        //$stmUsuComenta->bindValue(":idPost", $idPost, PDO::PARAM_INT);
        $stmUsuComenta->execute();
        $tmpUsu = $stmUsuComenta->fetch();
     
        //var_dump($tmpUsu);
        array_push($tmpUsu, $totalComent);
       
        array_unshift($tmpBus, $tmpUsu);
    //var_dump($tmpBus);
        
                   echo   json_encode($tmpBus);
         