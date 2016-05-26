<?php

/**
 * Description of Post
 *
 * @author Carlos Neira Sanchez
 */
require_once('Sistema/Conne.php');
require_once('DataObj.php');


class Post extends DataObj{
    
    
    protected $data = array(
        
        "idUsuario" => "",
        "secciones_idsecciones" => "",
        "tiempo_cambio_idTiempoCambio" => "",
        "titulo" => "",
        "comentario" => "",
        "precio" => "",
        "Pa_queridas" => array(),
        "Pa_ofrecidas" => array(),
        "idImagen" => "",
        "figcaption" => "",
        "fechaPost" => ""
       
    );

     
    /**
     * Metodo que actualiza un articulo de un Post
     * Se utiliza si el usuaro de mueve de adelante a atras
     * por el formulario
     */
    
    public function actualizarArticulo(){
    
    try{
    $con = Conne::connect();
    
        $sql = "UPDATE ".TBL_POST. " SET "
               . "idUsuario = (SELECT idUsuario FROM ".TBL_USUARIO. " WHERE nick = :nick), "
               . "secciones_idsecciones = (SELECT idSecciones FROM ".TBL_SECCIONES. " WHERE nombre_seccion = :secciones_idsecciones), "
               . "tiempo_cambio_idTiempoCambio = (SELECT idTiempoCambio FROM ".TBL_TIEMPO_CAMBIO. " WHERE tiempo = :tiempo_cambio_idTiempoCambio), "
               . "titulo = :titulo, "
               . "comentario = :comentario, "
               . "precio = :precio, "
               . "fechaPost = :fechaPost "
               . " WHERE idPost = :idPost ";
        //echo $sql.'<br>';     
    
        $date = date('Y-m-d');
        
            $stm = $con->prepare($sql);
            $stm->bindValue(":nick", $this->data["idUsuario"], PDO::PARAM_INT);
            $stm->bindValue(":secciones_idsecciones", $this->data["secciones_idsecciones"], PDO::PARAM_INT);
            $stm->bindValue(":tiempo_cambio_idTiempoCambio", $this->data["tiempo_cambio_idTiempoCambio"], PDO::PARAM_INT);
            $stm->bindValue(":titulo", $this->data["titulo"], PDO::PARAM_STR);
            $stm->bindValue(":comentario", $this->data["comentario"], PDO::PARAM_STR);
            $stm->bindValue(":precio", $this->data["precio"], PDO::PARAM_STR);
            $stm->bindValue(":fechaPost", $date, PDO::PARAM_STR);
            $stm->bindValue(":idPost", $_SESSION['lastId'][0] , PDO::PARAM_INT);
            
            $result = $stm->execute();
    
                if($result){
                    
                     //Creamos un array con las palabras buscadas
                    $buscadas = $this->getValue("Pa_queridas");
                    //Pasamos en bucle insertandolas si no son null
                    foreach ($buscadas as $val){
                
                        if($val != null){
                    
                            $stm = " UPDATE ".TBL_PBS_QUERIDAS. " SET palabra = :palabra WHERE idPost = :idPost";
                            $stm = $con->prepare($stm);
                            $stm->bindValue(":idPost", $_SESSION['lastId'][0], PDO::PARAM_INT);
                            $stm->bindValue(":palabra", $val, PDO::PARAM_STR);
                            $stm->execute();
            
                        }
                    }
                    
                      //Creamos un array con las palabras buscadas
                    $ofrecidas = $this->getValue("Pa_ofrecidas");
            //Pasamos en bucle insertandolas si no son null
                    foreach ($ofrecidas as $val){
                
                        if($val != null){
                    
                            $stm = " UPDATE ".TBL_PBS_OFRECIDAS. " SET palabra = :palabra WHERE idPost = :idPost";
                            $stm = $con->prepare($stm);
                            $stm->bindValue(":idPost", $_SESSION['lastId'][0], PDO::PARAM_INT);
                            $stm->bindValue(":palabra", $val, PDO::PARAM_STR);
                            $stm->execute();
            
                        }
                    }
                }
                
        Conne::disconnect($con);       
    }catch(Exception $ex){
        Conne::disconnect($con);
        echo 'El error se produce en la línea: '.$ex->getLine().'<br>';
        die("Query failed: ".$ex->getMessage());
    }
    
//fin actualizar articulo    
}


/**
 * Metodo que inserta un articulo en un post
 * @return type
 */
public function insertArticulo(){
  
        
        try{
        $con = Conne::connect();
        $con->beginTransaction();
        
            $sql = " INSERT INTO ".TBL_POST. "(
                   
                   idUsuario,
                   secciones_idsecciones,
                   tiempo_cambio_idTiempoCambio,
                   titulo,
                   comentario,
                   precio,
                   fechaPost
                   
                   ) VALUES (
                   (SELECT idUsuario FROM ".TBL_USUARIO. " WHERE nick = :nick),
                   (SELECT idSecciones FROM ".TBL_SECCIONES. " WHERE nombre_seccion = :secciones_idsecciones),
                   (SELECT idTiempoCambio FROM ".TBL_TIEMPO_CAMBIO. " WHERE tiempo = :tiempo_cambio_idTiempoCambio),
                   :titulo,
                   :comentario,
                   :precio,
                   :fechaPost
                   
                   );";
            
            
            $date = date('Y-m-d');
            $st = $con->prepare($sql);
            $st->bindValue(":nick", $this->data["idUsuario"], PDO::PARAM_INT);
            $st->bindValue(":secciones_idsecciones", $this->data["secciones_idsecciones"], PDO::PARAM_INT);
            $st->bindValue(":tiempo_cambio_idTiempoCambio", $this->data["tiempo_cambio_idTiempoCambio"], PDO::PARAM_INT);
            $st->bindValue(":titulo", $this->data["titulo"], PDO::PARAM_STR);
            $st->bindValue(":comentario", $this->data["comentario"], PDO::PARAM_STR);
            $st->bindValue(":precio", $this->data["precio"], PDO::PARAM_STR);
            $st->bindValue(":fechaPost", $date, PDO::PARAM_STR);

            $result = $st->execute();
            
            if($result){
                //Si ha ido bien creamos el directorio donde el usuario
                    //almacenara las imagenes de cada post 
                    if(!is_dir("photos/".$this->data["idUsuario"])){
                $result = Sistema::crearDirectorio("photos/".$this->data["idUsuario"]);
                    }
                //Creamos un subdirectorio para almacenar las imagenes 
                    //de cada post. Solo se crea la primera vez que se manda una
                    //una foto. 
                if($_SESSION['contador'] === 0 ){
                    echo 'creamos subdirectorio '.$_SESSION['contador'] == 0;'<br>';
                    $_SESSION['nuevoDirectorio'] = Sistema::crearSubdirectorio($this->data['idUsuario']);
                }
                
                $result = Sistema::copiarFoto('photos/demo.jpg', $_SESSION['nuevoDirectorio'].'/demo.jpg');
            }
            
        //Si hay algun tipo de error al subir la foto
                //Redirigimos a la pagina de mostrar error
                //Para que el usuario vuelva a intentarlo    
            if(!$result){
                mostrarError();
                exit();
            }
            
            
            $sql2 = "SELECT last_insert_id() FROM ".TBL_POST;
            $st2 = $con->prepare($sql2);
            $st2->execute();
            $_SESSION['lastId'] =  $st2->fetch();
            
            
            //Ejecutamos el commit
            $con->commit();
            //cerramos el cursor
            $st2->closeCursor();
            
            //Creamos un array con las palabras buscadas
            $buscadas = $this->getValue("Pa_queridas");
           
            //Pasamos en bucle insertandolas si no son null
            foreach ($buscadas as $val){
                
                if($val != null){
                    $st3 = "Insert into ".TBL_PBS_QUERIDAS." (idPost, palabra) values (:idPost, :palabra)";
                    $st3 = $con->prepare($st3);
                    $st3->bindValue(":idPost", $_SESSION['lastId'][0], PDO::PARAM_INT);
                    $st3->bindValue(":palabra", $val, PDO::PARAM_STR);
                    $st3->execute();
            
                }
            }
            
            //Creamos un array con las palabras buscadas
            $ofrecidas = $this->getValue("Pa_ofrecidas");
           
            //Pasamos en bucle insertandolas si no son null
            foreach ($ofrecidas as $val){
                
                if($val != null){
                    $st3 = "Insert into ".TBL_PBS_OFRECIDAS." (idPost, palabra) values (:idPost, :palabra)";
                    $st3 = $con->prepare($st3);
                    $st3->bindValue(":idPost", $_SESSION['lastId'][0], PDO::PARAM_INT);
                    $st3->bindValue(":palabra", $val, PDO::PARAM_STR);
                    $st3->execute();
            
                }
            }
           
            Conne::disconnect($con);
            return $result;
        }catch(Exception $ex){
            $con->rollBack();
            Conne::disconnect($con);
            echo 'El error se produce en la línea: '.$ex->getLine().'<br>';
            die("Query failed: ".$ex->getMessage());
        } 
        

//fin inserArticulo    
}    


/**
 * Metodo que inserta las imagenes
 * y comntarios de cada imagen subida
 */
public function insertarFotos(){
         
          $_SESSION['contador'] = $_SESSION['contador'] + 1;
          
    try{
        $con = Conne::connect();
        
        $sql = "INSERT INTO ".TBL_IMAGENES." VALUES(:post_idPost, :idImagen, :ruta, :texto)";
        //echo 'Sql insertArticulo: '.$sql.'<br>';
        $st = $con->prepare($sql);
       
        $st->bindValue(":post_idPost", $_SESSION['lastId'][0], PDO::PARAM_INT);
        $st->bindValue(":idImagen", $this->data['idImagen'], PDO::PARAM_INT);
        $st->bindValue(":ruta",$_SESSION['nuevoDirectorio'].'/'.$this->data['idImagen'].'.jpg' , PDO::PARAM_STR);
        $st->bindValue(":texto", $this->data['figcaption'], PDO::PARAM_STR);
        
        $total = $st->execute();
        
        Conne::disconnect($con);
    } catch (Exception $ex) {
        Conne::disconnect($con);
        echo 'El error se produce en la línea: '.$ex->getLine().'<br>';
        die("Query failed: ".$ex->getMessage());
    }
//fin insertarFotos  
}


/***
 * Metodo que elimina una imagen 
 * y cometario que el usuario quiera
 */

function eliminarImg(){
    
     try{
        $con = Conne::connect();
        
        $sql = "DELETE FROM ".TBL_IMAGENES." WHERE (post_idPost = :post_idPost  AND  idImagen = :idImagen)";
        //echo 'Sql insertArticulo: '.$sql.'<br>';
        $st = $con->prepare($sql);
       
        $st->bindValue(":post_idPost", $_SESSION['lastId'][0], PDO::PARAM_INT);
        $st->bindValue(":idImagen", $_SESSION['idImg'], PDO::PARAM_INT);
       
        $total = $st->execute();
        
        return $total
                ;
        Conne::disconnect($con);
    } catch (Exception $ex) {
        Conne::disconnect($con);
        echo 'El error se produce en la línea: '.$ex->getLine().'<br>';
        die("Query failed: ".$ex->getMessage());
    }
    
    
    
    
//fin eliminarImg    
}


//fin de clase Post    
}
