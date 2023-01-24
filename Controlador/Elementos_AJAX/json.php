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
        $idImg = $_POST['srcImg'];
    } else {
        if (isset($_GET['srcImg'])) {
            $idImg = $_GET['srcImg'];
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
        //Nos quedamos con la parte necesaria para sacar de la tabla imagenes el id del post
        //Ejemplo '../photos/joseMartin/50000/5.jpg'
        //usuario joseMartin
        //url 50000/5

        $tmpUrl = explode('/', $idImg); //
        $nickUsuario = $tmpUrl[2];
        $tmpUrl[4] = substr($tmpUrl[4], 0, -4);
        $url = $tmpUrl[3] . '/' . $tmpUrl[4];
        //echo $nickUsuario . '  '.$url;
        // echo PHP_EOL;
        $sql = 'select post_idPost from ' . TBL_IMAGENES . ' where nickUsuario = :nickUsuario and  ruta = :ruta;';
        //echo $sql;
        $stm4 = $conPost->prepare($sql);
        $stm4->bindValue(':nickUsuario', $nickUsuario, PDO::PARAM_STR);
        $stm4->bindValue(':ruta', $url, PDO::PARAM_STR);
        $stm4->execute();
        //Recuperamos el id del post
        $idImgSLD = $stm4->fetch();
        //echo $idImgSLD;
        //echo PHP_EOL;
        //$stm4->closeCursor();
        //Almacenaremos varios arrays para mostrar todos los datos
        //La ruta de las imagenes, el texto que describe la imagen y las palabras buscadas
        $rutaTextoPbsBuscadas = array();
        //Recuperamos la ruta de la imagen y la descripcion de cada una
        $sql = "select nickUsuario as nick, ruta as ruta, texto as texto from " . TBL_IMAGENES . " where post_idPost =" . $idImgSLD[0] . ";";
        $stm5 = $conPost->query($sql);
        $tmpRutaTexto = $stm5->fetchAll();
        array_push($rutaTextoPbsBuscadas, $tmpRutaTexto);


        //Recuperamos las palabras queridas o buscadas del usuario
        $sql = "select palabrasBuscadas as pbsQueridas from " . TBL_PBS_QUERIDAS . " where idPost_queridas = " . $idImgSLD[0] . ";";
        $stm6 = $conPost->query($sql);
        $tmpPbsBuscadas = $stm6->fetchAll();

        array_push($rutaTextoPbsBuscadas, $tmpPbsBuscadas);


        echo json_encode($rutaTextoPbsBuscadas); //
    }
} catch (PDOException $ex) {
    Conne::disconnect($conPost);
    die($ex->getMessage());
}
    
    