<?php

    require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Constantes/ConstantesBbdd.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/DataObj.php');
    require_once($_SERVER['DOCUMENT_ROOT']."/Changes/Modelo/Usuarios.php");
    require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Controlador/Validar/ValidoForm.php');

   if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
   
  //Solo en caso el usuario se logee
    if(isset($_SESSION['userTMP'])){
        //var_dump($_SESSION['userTMP']);
        $nickComenta = $_SESSION['userTMP']->getValue('nick');
        $idUsuComenta = $_SESSION['userTMP']->devuelveId();
        $imgUsuComenta = $nickComenta;//"<img src='"."../datos_usuario/$nickComenta/$nickComenta.jpg"."'/>";
        $ciudadComenta = $_SESSION['userTMP']->retornoDireccionUsuario();
        $fechaComentario  = date('Y-m-d');
       
       
    //echo $nickComenta.$idUsuComenta[0].$imgUsuComenta."  ".$ciudadComenta[0][4].$fechaComentario;
    }
    
   
    
    $idPostComentado =(int)$_POST['idPostComentado'];
    $tituloComentario = $_POST['tituloComentario'];
    $comentario = $_POST['comentario'];  
    
    $idPostComentado = ValidoForm::htmlCaracteres($idPostComentado);
    $tituloComentario = ValidoForm::htmlCaracteres($tituloComentario);
    $comentario = ValidoForm::htmlCaracteres($comentario);
    
 
    try{
        $con = Conne::connect();

            $sql = " INSERT INTO ".TBL_COMENTARIO. "(
                   
                   post_idPost,
                   idUsuarioComentario,
                   nombreComenta,
                   imgUsuarioComentario,
                   tituloComentario,
                   comentarioPost,
                   ciudadComentario,
                   fechaComentario
                   
                   ) VALUES (
                   
                   :post_idPost,
                   :idUsuarioComentario,
                   :nombreComenta,
                   :imgUsuarioComentario,
                   :tituloComentario,
                   :comentarioPost,
                   :ciudadComentario,
                   :fechaComentario
                   
                   );";
            
            //echo $sql;
            
            $stComentario = $con->prepare($sql);
            $stComentario->bindValue(":post_idPost", $idPostComentado, PDO::PARAM_INT);
            $stComentario->bindValue(":idUsuarioComentario",$idUsuComenta[0] , PDO::PARAM_INT);
            $stComentario->bindValue(":nombreComenta", $nickComenta, PDO::PARAM_STR);
            $stComentario->bindValue(":imgUsuarioComentario", $imgUsuComenta, PDO::PARAM_STR);
            $stComentario->bindValue(":tituloComentario", $tituloComentario, PDO::PARAM_STR);
            $stComentario->bindValue(":comentarioPost", $comentario, PDO::PARAM_STR);
            $stComentario->bindValue(":ciudadComentario", $ciudadComenta[0][4], PDO::PARAM_STR);
            $stComentario->bindValue(":fechaComentario", $fechaComentario, PDO::PARAM_STR);
            
            
            $test = $stComentario->execute();
            $resultadoSubirComentario = array("res" => $test);
                echo json_encode($resultadoSubirComentario);
          
             Conne::disconnect($con);
        }catch(Exception $ex){
            Conne::disconnect($con);
            echo 'El error se produce en la línea: '.$ex->getLine().'archivo '.$ex->getFile().'<br>';
            die("Query failed: ".$ex->getMessage());
        } 
         

   
            
        
            
            

       
      
            
            
            

        
 