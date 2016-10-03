<?php

/**
 * Description of Post
 *
 * @author Carlos Neira Sanchez
 */
require_once('../Sistema/Conne.php');
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
     * Se utiliza si el usuaro se mueve de adelante a atras
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
            
            $test = $stm->execute();
    
                if($test){
                    
                     //Creamos un array con las palabras buscadas
                    $buscadas = $this->getValue("Pa_queridas");
                    //Pasamos en bucle insertandolas si no son null
                    foreach ($buscadas as $val){
                
                        if($val != null){
                    
                            $stm = " UPDATE ".TBL_PBS_QUERIDAS. " SET palabra = :palabra WHERE idPost = :idPost";
                            $stm = $con->prepare($stm);
                            $stm->bindValue(":idPost", $_SESSION['lastId'][0], PDO::PARAM_INT);
                            $stm->bindValue(":palabra", $val, PDO::PARAM_STR);
                            $test = $stm->execute();
            
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
                            $test = $stm->execute();
            
                        }
                    }
                }
             
        Conne::disconnect($con);  
        return $test;   
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
       
        $test;
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

            $test = $st->execute();
            //Como estamos haciendo un commit es seguro de recuperar el ultimo id
            $sql2 = "SELECT last_insert_id() FROM ".TBL_POST;
            $st2 = $con->prepare($sql2);
            $st2->execute();
            //Esta variable luego se usa tambien 
            //Por en el segundo paso de subir un Post, cuando se sube una imagen
            //Si el usuario quiere eliminar una imagen en el proceso
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
            //Insertamos en la tabla imagenes la ruta de la imagen demo
            
            $st4 = "Insert into ".TBL_IMAGENES." (post_idPost, ruta) values (:idPost, :ruta)";
            $st4 = $con->prepare($st4);
            $st4->bindValue(":idPost", $_SESSION['lastId'][0], PDO::PARAM_INT);
            $st4->bindValue(":ruta", "/demo", PDO::PARAM_STR);
            $st4->execute();
           
            Conne::disconnect($con);
            return $test;
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
 * y comentarios de cada imagen subida
 */
 function insertarFotos(){
    
      
    $_SESSION['idImgadenIngresar'] = $this->getValue('idImagen');
   //echo 'en insertar idImagen: '.$_SESSION['idImgadenIngresar'].'<br>';
    try{
        $con = Conne::connect();
        
        $sql = "INSERT INTO ".TBL_IMAGENES." (post_idPost, ruta, texto) VALUES ( :post_idPost, :ruta, :texto)";
        //echo 'Sql insertArticulo: '.$sql.'<br>';
        $st = $con->prepare($sql);
        //Nos quedamos con la parte imprescindible de la ruta de la imagen
        //Para ocupar menos espacio en la tabla de la bbdd
        //El primer caso es si no se ha eliminado ninguna imagen
            //echo 'recibo: ',$_SESSION['idImgadenIngresar'].'<br>';
            $tmp = substr($_SESSION['idImgadenIngresar'], 10);
            //echo $tmp.'<br>';
            
        $tmp = strstr($tmp,'.',true);// $tmp => /admin/1/2
        //echo 'tmp en ingresar vale: '.$tmp.'<br>';
        $st->bindValue(":post_idPost", $_SESSION['lastId'][0], PDO::PARAM_INT);
        $st->bindValue(":ruta",$tmp, PDO::PARAM_STR);
        //En caso el usuario no escriba una descripcion de la imagen
        if($this->data['figcaption'] == null){
            $st->bindValue(":texto", " ", PDO::PARAM_STR);  
        }else{
            $st->bindValue(":texto", $this->data['figcaption'], PDO::PARAM_STR);
        }
   
        
        $test = $st->execute();
        //Si la foto se ha subido con exito el contador de imagenes se incrementa en 1
            if($test){
                $_SESSION['contador'] = $_SESSION['contador'] + 1;
            }
        //                    IMPORTANTE
        //Cuando insertamos una imagen eliminamos de la tabla imagenes
        // la imagen demo que subimos.Unicamente hacemos eso si contador == 1
        if(isset($_SESSION['contador']) and $_SESSION['contador'] == 1){
            $sql = "DELETE FROM ".TBL_IMAGENES." WHERE post_idPost = :post_idPost and ruta = :url";
            //echo 'Sql eliminarImagen: '.$sql.'<br>';
                $st = $con->prepare($sql);
                $st->bindValue(":post_idPost", $_SESSION['lastId'][0], PDO::PARAM_INT);       
                $st->bindValue(":url", "/demo", PDO::PARAM_STR);
       
                    $test = $st->execute();
        }
        
        
        Conne::disconnect($con);
        return $test;
    } catch (Exception $ex) {
        Conne::disconnect($con);
        echo 'codigo: '.$ex->getCode().'<br>';
        $_SESSION['error'] = ERROR_INSERTAR_FOTO;
        echo 'El error se produce en la línea: '.$ex->getLine().'<br>';
        die("Query failed: ".$ex->getMessage());
    }
    
    
   
//fin insertarFotos  
}


/***
 * Metodo que elimina una imagen 
 * y cometario de la bbdd y del sistema
 * que el usuario quiera
 */

function eliminarImg(){
        //Si es la primera imagen que borra el usuario se instancia
        //Para guardar en el array su ruta
        if(!isset($_SESSION['imgTMP']['imagenesBorradas'])){
            $_SESSION['imgTMP']['imagenesBorradas'][0] = null;
            echo 'Post creamos la variable de session imgTMP <BR>';
        } 
        
     try{
        
        $con = Conne::connect();
        $sql = "DELETE FROM ".TBL_IMAGENES." WHERE post_idPost = :post_idPost and ruta = :url";
        //echo 'Sql eliminarImagen: '.$sql.'<br>';
        $st = $con->prepare($sql);
        $st->bindValue(":post_idPost", $_SESSION['lastId'][0], PDO::PARAM_INT); 
        //Iniciamos una variable de sesion para asignarle a la siguiente 
            //imagen ingresada el nombre de la eliminada.
            //Creamos un array de arrays por si el usuario quiere
            // eliminar varias imagenes a la vez
        
        for($i = 0; $i< 5; $i++ ){
            
            if (empty($_SESSION['imgTMP']['imagenesBorradas'][$i])){
              $_SESSION['imgTMP']['imagenesBorradas'][$i] = $this->getValue('idImagen');
                           break;
            }
        }
         echo 'en Post eliminar: '.var_dump($_SESSION['imgTMP']['imagenesBorradas']).'<br>';
        
        $st->bindValue(":url", $this->getValue('idImagen'), PDO::PARAM_STR);
        // echo 'lastId: '.$_SESSION['lastId'][0].' : '. 'idImagen: '.$this->getValue('idImagen').'<br>';
        $test = $st->execute();
        //En caso de ser todo correcto eliminamos la imagen 
        //del sistema y restamos 1 al contador de imagenes
        if($test){
            $test = Sistema::eliminarImagen("../photos/".$this->getValue('idImagen').".jpg");
            if($test){
                
                $_SESSION['contador'] = $_SESSION['contador'] - 1;
                echo 'Hemos eliminado de la bbdd y del sistema, restamos contador <br>';
            }
            
            //Si el contador vuelve a 0, volvemos a copiar la foto demo
            //Evitamos que en cada momento el que el usuario no tenga una  imagen en el Post
            if(isset($_SESSION['contador']) and $_SESSION['contador'] == 0){
            $test = Sistema::copiarFoto("../photos/demo.jpg",$_SESSION['nuevoSubdirectorio']."/demo.jpg");
            //                    IMPORTANTE
            //  Volvemos a ingresar en la bbdd la ruta de la imagen /demo
            // para poder mostrar siempre una imagen
            $sql = "INSERT INTO ".TBL_IMAGENES." (post_idPost, ruta) VALUES ( :post_idPost, :ruta)";
       
                $st = $con->prepare($sql);
        
            $st->bindValue(":post_idPost", $_SESSION['lastId'][0], PDO::PARAM_INT);
            $st->bindValue(":ruta","/demo", PDO::PARAM_STR);  
                
                $test = $st->execute();
            }

           
        }
                
        Conne::disconnect($con);
        return $test;
      
      
    } catch (Exception $ex) {
        Conne::disconnect($con);
        $_SESSION['error'] = ERROR_ELIMINAR_FOTO;
        echo 'El error se produce en la línea: '.$ex->getLine().'<br>';
        die("Query failed: ".$ex->getMessage());
    }  
     
//fin eliminarImg    
}

/**
 * Metodo que actualiza el texto
 * introducido en una imagen cuando 
 * se inserta una imagen en un post
 */
 function actualizarTexto(){
  
    try{
    
    $con = Conne::connect();
        $sql = "UPDATE ".TBL_IMAGENES. " SET ".
                "texto = :descripcion ".
                " WHERE post_idPost = :idPost and ruta = :ruta ";
        //echo "sql actualizarTexto ".$sql.'<br>';
        
        $stm = $con->prepare($sql);
        $stm->bindValue(":descripcion", $this->getValue('figcaption'), PDO::PARAM_STR );
        $stm->bindValue(":idPost", $_SESSION['lastId'][0] , PDO::PARAM_INT);
        $stm->bindValue(":ruta", $this->getValue('idImagen'), PDO::PARAM_STR);
        
        $test = $stm->execute();
        
        Conne::disconnect($con);
        return $test;    
    }catch(Exception $ex){
        Conne::disconnect($con);
        echo 'El error se produce en la línea: '.$ex->getCode().'<br>';
        die("Query failed: ".$ex->getMessage());
    }
    
//fin actualizarTexto    
}



//fin de clase Post    
}
