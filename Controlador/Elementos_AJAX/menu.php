<?php


  header('Content-Type: application/json');
 // header("Content-type: application/javascript"); 
  header('Cache-Control: no-cache, must-revalidate');
  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
  header('Content-type: application/json; charset=utf-8');

require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/DataObj.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Conne.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Constantes/ConstantesBbdd.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Controlador/Validar/ValidoForm.php');
    
if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
    
    //Solo en caso el usuario se logee
    if(isset($_SESSION['userTMP'])){
        //var_dump($_SESSION['userTMP']);
        $usuBloqueo = new Usuarios(array());
        $usuLogeado = $_SESSION['userTMP']->devuelveId();
    }
 

try {
    
    $conMenu= Conne::connect();
  
    
     // -------- párametro opción para determinar la select a realizar -------
    if (isset($_POST['opcion'])){ 
            $opc=$_POST['opcion'];
        }else{
            if (isset($_GET['opcion'])){
                $opc=$_GET['opcion'];
         }        
    }
    
    
    
     if(isset($_POST['inicio'])){
            $inicio = (int)$_POST['inicio'];
        } else if (isset($_GET['inicio'])){
             $inicio = (int)$_GET['inicio'];   
        }   
    
        
        if($opc == 'Inicio'){
            
        $sql = "SELECT SQL_CALC_FOUND_ROWS idPost
                FROM post p ORDER BY idPost DESC LIMIT :startRow, :numRows;";    
        }else{
        
        $sql = "SELECT SQL_CALC_FOUND_ROWS idPost
                FROM post p
                where  p.secciones_idsecciones = (select idSecciones from secciones where nombre_seccion = '{$opc}') 
                    ORDER BY idPost DESC LIMIT :startRow, :numRows;";
        //echo $sql;
        }
      
                        //$sql = "SELECT idPost FROM post ORDER BY fechaPost  DESC";
                        $stm = $conMenu->prepare($sql);
                        $stm->bindValue(":startRow", $inicio, PDO::PARAM_INT);
                        $stm->bindValue(":numRows", PAGE_SIZE, PDO::PARAM_INT);
                        $stm->execute();
                        $v = $stm->fetchAll();

                
                                //Calculamos el total final como si  la clausula limit no estuviera
                                $stm2Menu = $conMenu->query("SELECT found_rows()  AS totalRows");
                                $row = array ('totalRows' => $stm2Menu->fetch());
                                $stm2Menu->closeCursor();

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
    //echo $sqlPost;      
        
                $stm3Menu = $conMenu->prepare($sqlPost);
                $stm3Menu->bindValue(":idPost", $id[0], PDO::PARAM_INT);
                $stm3Menu->execute();
                $tmp = $stm3Menu->fetch();
                $stm3Menu->closeCursor();
 
                $sqlTotal = "Select IFNULL(COUNT(idComentariosPosts),0) as comentarios "
                . " FROM comentario_post where post_idPost = :idPost";
        
                $stm3Menu = $conMenu->prepare($sqlTotal);
                $stm3Menu->bindValue(":idPost", $id[0], PDO::PARAM_INT);
                $stm3Menu->execute();
                $tmp3Menu = $stm3Menu->fetch();
                $stm3Menu->closeCursor();
                $x = $tmp3Menu[0];
                array_push($tmp, $x);
       
                
         //Solo en caso el usuario se logee
if(isset($_SESSION['userTMP'])){
   
    $usuBloqueados = $usuBloqueo->devuelveUsuariosBloqueados($tmp[2]); 
    $totalUsuarioBloqueado =  count($usuBloqueados);
    
        

            //  Si el usuario que ha colgado el Post ha bloqueado 
            // algun usuario se verifica que no sea el que esta logueado
            //Se le impide ver este Post
        
                if($totalUsuarioBloqueado > 0){
                    for($i=0; $i < $totalUsuarioBloqueado; $i++){
                        if(($usuLogeado[0] == $usuBloqueados[$i][0]) and ($usuBloqueados[$i]['bloqueadoTotal'] == 1) ){
                            $tmp['coment'] = 2;
                        }else if (($usuLogeado[0] == $usuBloqueados[$i][0]) and ($usuBloqueados[$i]['bloqueadoParcial'] == 1)){
                            //Agregamos un testigo para cuando se 
                            //muestre en JAVASCRIPT el POST
                            //Se inavilite el boton de comentar
                            $tmp['coment'] = 1;
                        }
                        
                    }
                    
                    array_push($rs, $tmp);

                    
                    
                    }else{
                        array_push($rs, $tmp);
                    }
      
        }else{
           
                array_push($rs, $tmp);
       
        }
                  
    }  
                
               
                echo json_encode($rs);
                
            
                           
        
  
} catch (Exception $exc) {
    echo $exc->getMessage().PHP_EOL;
    echo $exc->getCode().PHP_EOL;
    echo $exc->getTraceAsString();
}



