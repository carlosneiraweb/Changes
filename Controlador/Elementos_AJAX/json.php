<?php

header('Content-type: application/json; charset=utf-8');
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');



require_once($_SERVER['DOCUMENT_ROOT'] . '/Changes/Modelo/DataObj.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Changes/Sistema/Constantes/ConstantesBbdd.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Changes/Controlador/Validar/ValidoForm.php');


if (!isset($_SESSION)) {
    session_start();
}

//Solo en caso el usuario se logee
if (isset($_SESSION['userTMP'])) {
    //var_dump($_SESSION['userTMP']);
    $usuBloqueo = new Usuarios(array());
    $usuLogeado = $_SESSION['userTMP']->devuelveId();
   
    
}


// -------   Crear la conexión al servidor y ejecutar la consulta.
try {

    $conPost = Conne::connect();

    // -------- párametro opción para determinar la select a realizar -------
    if (isset($_POST['opcion'])) {
        $opc = $_POST['opcion'];
    } else {
        if (isset($_GET['opcion'])) {
            $opc = $_GET['opcion'];
        }
    }

    if (isset($_POST['srcImg'])) {
        $idPost = $_POST['srcImg'];
    } else {
        if (isset($_GET['srcImg'])) {
            $idPost = $_GET['srcImg'];
        }
    }


    if (isset($_POST['inicio'])) {
        $inicio = ((int) $_POST['inicio']);
    } else if (isset($_GET['inicio'])) {
        $inicio = (int) $_GET['inicio'];
    }


    if (isset($_POST['usuario'])) {
        $usuario = $_POST['usuario'];
    } else if (isset($_GET['usuario'])) {
        $usuario = $_GET['usuario'];
    }

  

    if ($opc == "PPS") {

        
      
        
        $sql = "SELECT SQL_CALC_FOUND_ROWS idPost FROM " . TBL_POST . "  ORDER BY idPost DESC LIMIT :startRow, :numRows";
        //$sql = "SELECT idPost FROM post ORDER BY fechaPost  DESC";
        $stmBus = $conPost->prepare($sql);
        $stmBus->bindValue(":startRow", $inicio, PDO::PARAM_INT);
        $stmBus->bindValue(":numRows", PAGE_SIZE, PDO::PARAM_INT);
        $stmBus->execute();
        $v = $stmBus->fetchAll();


        //Calculamos el total final como si  la clausula limit no estuviera
        $stm2Bus = $conPost->query("SELECT found_rows()  AS totalRows");
        $row = array('totalRows' => $stm2Bus->fetch());
        $stm2Bus->closeCursor();

        $rs = array();
        array_push($rs, $row);
        
       
        foreach ($v as $id) {
            
            $sqlPost = "select p.idPost, u.nick, u.idUsuario as idUsu,
                    prov.nombreProvincia AS provincia, DATE_FORMAT(p.fechaPost,'%d-%m-%Y')as fecha, 
                    p.titulo,  img.directorio, p.comentario, tc.tiempo as tiempoCambio                   
                        from post p
                        inner join usuario AS u on u.idUsuario= p.idUsuarioPost
                        inner join direccion AS dire on dire.idDireccion = u.idUsuario
                        inner join provincias AS  prov on prov.nombreProvincia = dire.provincia
                        inner join imagenes AS img on img.postIdPost = :idPost 
                        inner join tiempo_cambio AS tc on tc.idTiempoCambio = p.tiempoCambioIdTiempoCambio
                        where p.idPost = :idPost limit 1";
       
            $stm3Bus = $conPost->prepare($sqlPost);
            $stm3Bus->bindValue(":idPost", $id[0], PDO::PARAM_INT);
            $stm3Bus->execute();
            $tmp = $stm3Bus->fetch();
            $stm3Bus->closeCursor();
           

            $sqlTotal = "Select IFNULL(COUNT(idComentariosPosts),0) as comentarios "
                    . " FROM " . TBL_COMENTARIO . " where postIdPost = :idPost";
            //echo $sqlTotal;

            $stm3To = $conPost->prepare($sqlTotal);
            $stm3To->bindValue(":idPost", $id[0], PDO::PARAM_INT);
            $stm3To->execute();
            $tmp3To = $stm3To->fetch();
            $stm3To->closeCursor();
            $x = $tmp3To[0];
            //var_dump($x);
            array_push($tmp, $x);
            
            //inicio=0&opcion=PPS
            //entrar con usuario bloqueado
            //OJO AL PAGESIZE
            //Solo en caso el usuario se logee
            /*
            if (isset($_SESSION['userTMP'])) {
                $usuBloqueados = $usuBloqueo->devuelveUsuariosBloqueadosTotal($tmp[2]);
                //  Si el usuario que ha colgado el Post ha bloqueado 
                // algun usuario se verifica que no sea el que esta logueado
                //Se le impide ver este Post
                if($usuBloqueados != null){
                    $t = count($usuBloqueados);
                        for($i = 0; $i < $t; $i++){
                            if($usuLogeado == $usuBloqueados[$i][0]){
                                $to['total'] = 1;
                                
                                    continue;
                            }
                        }
                    array_push($tmp,$to);     
                }        
                
                $usuBloqueados = $usuBloqueo->devuelveUsuariosBloqueadosParcial($tmp[2]);
                
                if($usuBloqueados != null){
                    $t = count($usuBloqueados);
                        for($i = 0; $i < $t; $i++){
                            if($usuLogeado == $usuBloqueados[$i][0]){
                                $to['parcial'] = 1;
                                
                                    continue;
                            }
                        }
                }      
                
                array_push($tmp,$to);
                
            }
            */
            array_push($rs,$tmp);
        }

        echo json_encode($rs);
        
         
    } else if ($opc == "SLD") {
       
        
     
        //Recuperamos la ruta de la imagen y la descripcion de cada una
        $sqlImg = "select idImagen as idImagen, postIdPost as idPost, directorio as directorio, texto as texto from " . TBL_IMAGENES . " where postIdPost = :idPost order by directorio ASC;";
        $stmImg = $conPost->prepare($sqlImg);
        $stmImg->bindValue(":idPost", $idPost);
        $stmImg->execute();
        $mostrarElegido = $stmImg->fetchAll();
        
        
        //Nos aseguramos que el array contenga 
        //5 elementos. Puede ser que el usuario 
        //no suba 5 imagenes.
        while(count($mostrarElegido) < 5 ){
            array_push($mostrarElegido,array(""));
            
        }

        

        //Recuperamos las palabras queridas o buscadas del usuario
        $sqlPbq = "select palabrasBuscadas as pbsQueridas from " . TBL_PBS_QUERIDAS . " where idPostQueridas = :idPost;";
        $stmPq = $conPost->prepare($sqlPbq);
        $stmPq->bindValue(":idPost", $idPost, PDO::PARAM_INT);
        $stmPq->execute();
        $arrayPq = $stmPq->fetchAll();
        array_push($mostrarElegido, $arrayPq);
 

        echo json_encode($mostrarElegido); //
    }
} catch (PDOException $ex) {
    Conne::disconnect($conPost);
    die($ex->getMessage());
}
    
    