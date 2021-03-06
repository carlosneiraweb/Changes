<?php

/**
 * @author Carlos Neira Sanchez
 * @mail arj.123@hotmail.es
 * @telefono ""
 * @nameAndExt Post.php
 * @fecha 04-oct-2016
 */

/**
 * Esta clase extiende de DataObject
 * Crea objetos Post y tiene varios metodos
 * para crear, actualizar y eliminar objetos de la clase Post
 */

require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Conne.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/DataObj.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Email/mandarEmails.php');


class Post extends DataObj{
    
    
    protected $data = array(
        
        "idUsuarioPost" => "",
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
    * Si hay coincidencia mandaremos email
    * al usuario 
    * que este interesado en las palabras que se acaban de publicar
    * @param type array palabras buscadas
    */ 
    public function buscarUsuariosInteresados($datosEmail){
        
        
        try {
           
            $con = Conne::connect();
            
            
            
        $sql = "select pe.id_usuario as idUsuBusca, pe.email as emailUsuBusca, usu.nick as nickRecibeEmail 
                from ".TBL_PALABRAS_EMAIL." pe
                inner join usuario usu on usu.idUsuario = pe.id_usuario
                    where palabras_detectar like :palabra;";
 
                      
            $stm = $con->prepare($sql);
            $stm->bindValue(":palabra", "%{$datosEmail[0][0]}%", PDO::PARAM_STR);
            $stm->execute();
            $usu = $stm->fetch();
            
            $stm->closeCursor();
            if($usu != null){
                array_push($datosEmail, $usu);
                
                
             $sqlNombre ="Select provincia as provinciaPublica 
                    from direccion d where d.idDireccion 
                    = (select idUsuario from usuario where nick = :nick) ";
        
            $stmNombre = $con->prepare($sqlNombre);
            $stmNombre->bindValue(":nick", $datosEmail[3], PDO::PARAM_STR);
            $stmNombre->execute();
            array_push($datosEmail, $stmNombre->fetch());
            $stmNombre->closeCursor();
            
        
                
                
        $sqlRutaImg = "Select ruta as ruta from imagenes where "
                . "post_idPost = :post_idPost";
        
        $stmRutaImg = $con->prepare($sqlRutaImg);
            $stmRutaImg->bindValue(":post_idPost", $datosEmail[1], PDO::PARAM_STR);
            $stmRutaImg->execute();
            $ruta = $stmRutaImg->fetch();
            
            
        array_push($datosEmail, $ruta[0]);     
        
        
            //var_dump($datosEmail);
            $objMandarEmails = new mandarEmails();
            $objMandarEmails->mandarEmailPalabrasBuscadas($datosEmail);
                
                            
                
            }
        
       
                            
        Conne::disconnect($con);  
          
        } catch (Exception $exc) {
            Conne::disconnect($con);
            echo "Error al pedir idPalabras para actualizar ".$exc->getLine().'<br>'.
                 "en el archivo ".$exc->getFile().'<br>'.
                 "code ".$exc->getTraceAsString();
            
        }
        
        
   //fin palabras     
    }
    
    
    

       /**
    * 
     * Metodo que devuelve un array con
     * el id de las palabras buscada o ofrecidas.
     * Se utiliza para actualizar las
     * palabras que un usuario quiere cambiar
     * @return array
     */

public function devuelvoIdPalabras($tabla, $columnaIdImagen,$palabras, $columnaIdPost, $idPostPalabra){
   // echo 'tabla '.$tabla. ' columnaIdImagen '.$columnaIdImagen. ' palabra '.$palabras.' columnaIdPOST '.$columnaIdPost. ' IDpOSTpALABRA '.$idPostPalabra.'<br>';    
         try {
           
            $con = Conne::connect();

                            
                            $stm = "Select $columnaIdImagen, $palabras from $tabla where ".$columnaIdPost." = :idImagen;";
                            //echo "idPab ".$stm.'<br>';
                            $stm = $con->prepare($stm);
                            $stm->bindValue(":idImagen", $idPostPalabra, PDO::PARAM_INT);
                            $stm->execute();
                            $misPalabras = $stm->fetchAll(PDO::FETCH_ASSOC);//fetchAll(PDO::FETCH_COLUMN, 0);
                          
            Conne::disconnect($con);  
            return $misPalabras;   
        } catch (Exception $exc) {
            Conne::disconnect($con);
            echo "Error al pedir idPalabras para actualizar ".$exc->getLine().'<br>'.
                 "en el archivo ".$exc->getFile().'<br>'.
                 "code ".$exc->getTraceAsString();
            
        }
        
     
       
       //devuelvoIdPalabras
    }  
 
    
   /**
     * Metodo que actualiza las palabras
     * que un usuario busca en un post.
     * Son privados por que solo se usan en esta clase,
     *Este metodo llama a devuelvoIdPalabras.
     * Metodo comun para actualizarPalarasOfrecidas y  
     * actualizarPalabrasBuscadas. Se le pasa la tabla, columna y el id
     * del post.
    */
    
    private function actualizarPalabrasBuscadas($idPostPalabras){
        //Se comprueba que MYSQL hace bien el UPDATE
        //Ya que MYSQL si el dato introducido por el usuario
        //es el mismo que el viejo devuelve un "0"
        //no se puede distingir entre si ha habido un error
        $test = false;
        $actualizo = null;
       
        
        $idPostPa;
            if(isset($_SESSION['lastId'][0])){
                $idPostPa = $_SESSION['lastId'][0];
            }else{
                $idPostPa = $idPostPalabras;
            }

        try {
            
            $con = Conne::connect();
            $count = 0;
            //Solicitamos el id de las palabras a modificar       
            $buscadas =  Post::devuelvoIdPalabras("busquedas_pbs_buscadas","palabrasBuscadas", "idPbsBuscada", "idPost_queridas", $idPostPa);
            
            //Las palabras nuevas para modificar ls antiguas
            $nuevasPalabras = $this->getValue('Pa_queridas');
            
            echo '<br>';
                for($i = 0; $i < 4; $i++){
               
                    //Para dejar la nuevas palabras en la misma posicion
                            if($buscadas[$i]["palabrasBuscadas"] == $nuevasPalabras[$i]){
                                $count++;
                                continue;
                            }
                            
                            $palabraNueva = $buscadas[$i]['idPbsBuscada'];
                            $stm = " UPDATE ".TBL_PBS_QUERIDAS. " SET palabrasBuscadas = ". "'$nuevasPalabras[$i]'"." WHERE idPbsBuscada = "."$palabraNueva"." and idPost_queridas = ".$idPostPa.";";
                            $stm = $con->prepare($stm);
                            $stm->bindValue(":idPost_queridas", $idPostPa, PDO::PARAM_INT);
                            $stm->bindValue(":palabrasBuscadas", $nuevasPalabras[$i], PDO::PARAM_STR);
                            $stm->bindValue(":idPbsBuscada", $buscadas[$i], PDO::PARAM_INT);
                            $stm->execute();
                           
                            $actualizo = $stm->rowCount();
                                
                                    if($actualizo != 1){
                                        $actualizo = 0;
                                        continue;
                                    }    
                                
                }
              
           if(($count > 0) || ($actualizo == 1)){$test= true;}
              
                Conne::disconnect($con);  
                return $test;
         
        } catch (Exception $exc) {
            Conne::disconnect($con);
            echo "Error al actualizar palabras buscadas ".$exc->getLine().'<br>'.
                 "en el archivo ".$exc->getFile().'<br>'.
                 "motivo ".$exc->getMessage().'<br>'.
                 "code ".$exc->getTraceAsString();
            
        }
        //insertarPalabrasBuscadas    
    }
    
     
    
    
    /**
     * Metodo privado para actualiza las palabras
     * que el usuario ingresa un post y modifica alguna descripcion
     * para definir su articulo.
     * Este metodo llama a devuelvoIdPalabras.
     * Metodo comun para actualizarPalarasOfrecidas y  
     * actualizarPalabrasBuscadas. Se le pasa la tabla, columna y el id
     * del post.
     * 
     */
    private function actualizarPalarasOfrecidas($idPostPalabras){
        $test = false;
        $actualizo = null;
       
       
            if(isset($_SESSION['lastId'][0])){
                $idPostOfre = $_SESSION['lastId'][0];
            }else{
                $idPostOfre = $idPostPalabras;
            }
       
        //El id de las viejas palabras
            $ofrecidas = Post::devuelvoIdPalabras("busquedas_pbs_ofrecidas","palabrasOfrecidas", "idPbsOfrecida", "idPost_ofrecidas", $idPostOfre);
            //Las palabras nuevas para modificar ls antiguas
            $nuevasOfrecidas = $this->getValue('Pa_ofrecidas');  
            
                try {
                             
                    $con = Conne::connect();
                    $count = 0;
                   
                        for($i = 0; $i < 4; $i++){
                            
                            //Para dejar la nuevas palabras en la misma posicion
                            if($ofrecidas[$i]["palabrasOfrecidas"] == $nuevasOfrecidas[$i]){
                                $count++;
                                continue;
                            }
                            $palabraNueva = $ofrecidas[$i]['idPbsOfrecida'];
                                $stm = " UPDATE ".TBL_PBS_OFRECIDAS. " SET palabrasOfrecidas =". "'$nuevasOfrecidas[$i]'"." WHERE idPbsOfrecida = "."$palabraNueva"." and idPost_ofrecidas = ".$idPostOfre.";";
                                $stm = $con->prepare($stm);
                                $stm->bindValue(":idPost_ofrecidas", $idPostOfre, PDO::PARAM_INT);
                                $stm->bindValue(":palabrasOfrecidas", $nuevasOfrecidas[$i], PDO::PARAM_STR);
                                $stm->bindValue(":idPbsOfrecida", $ofrecidas[$i], PDO::PARAM_INT);
                                $stm->execute();
                                $actualizo = $stm->rowCount();
                                
                                    if($actualizo != 1){
                                        $actualizo = 0;
                                        continue;
                                    }    
                                
                        }
              
           if(($count > 0) || ($actualizo == 1)){$test= true;}
              
           
            Conne::disconnect($con); 
            return $test;
        } catch (Exception $exc) {
            Conne::disconnect($con);
            echo "Error al actualizar palabras ofrecidas ".$exc->getLine().'<br>'.
                 "en el archivo ".$exc->getFile().'<br>'.
                 "code ".$exc->getTraceAsString();
            
        }
     //insertarPalarasOfrecidas      
    }
    
     
    /**
     * Metodo que actualiza un articulo de un Post
     * Se utiliza si el usuaro se mueve de adelante a atras
     * por el formulario
     * @return type boolean
     */
   
    public function actualizarPost(){
    
    try{
    $con = Conne::connect();
    
        $sql = "UPDATE ".TBL_POST. " SET "
               . "idUsuarioPost = (SELECT idUsuario FROM ".TBL_USUARIO. " WHERE nick = :nick), "
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
            $stm->bindValue(":nick", $this->data["idUsuarioPost"], PDO::PARAM_STR);
            $stm->bindValue(":secciones_idsecciones", $this->data["secciones_idsecciones"], PDO::PARAM_STR);
            $stm->bindValue(":tiempo_cambio_idTiempoCambio", $this->data["tiempo_cambio_idTiempoCambio"], PDO::PARAM_STR);
            $stm->bindValue(":titulo", $this->data["titulo"], PDO::PARAM_STR);
            $stm->bindValue(":comentario", $this->data["comentario"], PDO::PARAM_STR);
            $stm->bindValue(":precio", $this->data["precio"], PDO::PARAM_STR);
            $stm->bindValue(":fechaPost", $date, PDO::PARAM_STR);
            $stm->bindValue(":idPost", $_SESSION['lastId'][0] , PDO::PARAM_INT);
            
            $test = $stm->execute();
            
            //Si todo va bien se llama a los metodos que
            //actualian las palabras.
            //En este caso como tenemos en una variable se session
            //el idPost le pasamos un null
            //En eliminar 
            if($test){
                $test = $this->actualizarPalabrasBuscadas(null);
                    if($test){
                       $test = $this->actualizarPalarasOfrecidas(null); 
                    }  
            }  
          
        Conne::disconnect($con);  
        return $test;   
    }catch(Exception $ex){
        Conne::disconnect($con);
        echo 'El error se produce en el metodo actualizarPost en la l??nea: '.$ex->getLine().'<br>';
        echo "En el archivo ".$ex->getFile().'<br>'.
                $ex->getTraceAsString();
        die("Query failed: ".$ex->getMessage());
    }
    
//fin actualizar articulo    
}

/**
 * Metodo que inserta las palabras 
 * por las que el usuario quiere cambiar
 * @return type boolean
 */
private  function insertarPalabrasQueridas(){
    
            //Creamos un array con las palabras buscadas
            $buscadas = $this->getValue("Pa_queridas");
    
            try {
                $con = Conne::connect();
                 
                    //Pasamos en bucle insertandolas si no son null
                    for($i=0; $i <4; $i++){  
                        if($buscadas[$i] == null){$buscadas[$i] = "";} //Nos aseguramos 4 palabras
                            $st3 = "Insert into ".TBL_PBS_QUERIDAS." (idPost_queridas, palabrasBuscadas) values (:idPost_queridas, :palabra)";
                            $st3 = $con->prepare($st3);
                            $st3->bindValue(":idPost_queridas", $_SESSION['lastId'][0], PDO::PARAM_INT);
                            $st3->bindValue(":palabra", $buscadas[$i], PDO::PARAM_STR);
                            $test = $st3->execute();
                    }

            Conne::disconnect($con);
            return $test;   
            } catch (Exception $exc) {
                Conne::disconnect($con);
                echo "Error al ingresar las palabras queridas en la linea ".$exc->getLine().'<br>'.
                        " En el archivo ".$exc->getFile().'<br>'.
                        "con codigo ". $exc->getTraceAsString();
               
            }
//fin insertarPalabrasQueridas    
}


/**
 * Metodo que inserta las palabras
 * por las que el usuario quiere cambiar
 * @return type boolean
 */

private function insertarPalabrasOfrecidas(){
    
     //Creamos un array con las palabras buscadas
            $ofrecidas = $this->getValue("Pa_ofrecidas");
            
            try {
                
                $con = Conne::connect();
                
                //Creamos un array con las palabras buscadas
            $ofrecidas = $this->getValue("Pa_ofrecidas");
            
             //Pasamos en bucle insertandolas si no son null
        
                
                for($i = 0; $i < 4; $i++){
                     if($ofrecidas[$i] == null){$ofrecidas[$i] = "";} //Nos aseguramos 4 palabras
                    $st3 = "Insert into ".TBL_PBS_OFRECIDAS." (idPost_ofrecidas, palabrasOfrecidas) values (:idPost_ofrecidas, :palabra)";
                    $st3 = $con->prepare($st3);
                    $st3->bindValue(":idPost_ofrecidas", $_SESSION['lastId'][0], PDO::PARAM_INT);
                    $st3->bindValue(":palabra", $ofrecidas[$i], PDO::PARAM_STR);
                    $test = $st3->execute();
            
                }
                
                
                
                
                Conne::disconnect($con);
                return $test;
            } catch (Exception $exc) {
                Conne::disconnect($con);
                echo "Error al ingresar las palabras ofrecidas en la linea ".$exc->getLine().'<br>'.
                        " En el archivo ".$exc->getFile().'<br>'.
                        "con codigo ". $exc->getTraceAsString();
               
            }


//fin insertarPalabrasOfrecidas    
}

/**
 * Metodo que inserta la imagen 
 * demo cuando un usuario esta
 * subiendo un post
 * @return type boolean
 */

private function insertarImagenDemo(){
    
    try {
        
        $con = Conne::connect();
        
             //Insertamos en la tabla imagenes la ruta de la imagen demo
            
            $st4 = "Insert into ".TBL_IMAGENES." (post_idPost, ruta) values (:idPost, :ruta)";
            $st4 = $con->prepare($st4);
            $st4->bindValue(":idPost", $_SESSION['lastId'][0], PDO::PARAM_INT);
            $st4->bindValue(":ruta", "demo", PDO::PARAM_STR);
             
            $test = $st4->execute();
               
        Conne::disconnect($con);
        return $test;
    } catch (Exception $exc) {
        Conne::disconnect($con);
        echo "Error al ingresar la imagen demo en la linea ".$exc->getLine().'<br>'.
                        " En el archivo ".$exc->getFile().'<br>'.
                        "con codigo ". $exc->getTraceAsString().'<br>'.
                        "causa".$exc->getMessage();
        
    }

//insertarImagenDemo    
}


/**
 * Metodo que inserta un articulo en un post
 * @return type boolean
 */
public function insertPost(){
       
         $test;
        try{
        $con = Conne::connect();
       
         
            $sql = " INSERT INTO ".TBL_POST. "(
                   
                   idUsuarioPost,
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
            $st->bindValue(":nick", $this->data["idUsuarioPost"], PDO::PARAM_STR);
            $st->bindValue(":secciones_idsecciones", $this->data["secciones_idsecciones"], PDO::PARAM_STR);
            $st->bindValue(":tiempo_cambio_idTiempoCambio", $this->data["tiempo_cambio_idTiempoCambio"], PDO::PARAM_STR);
            $st->bindValue(":titulo", $this->data["titulo"], PDO::PARAM_STR);
            $st->bindValue(":comentario", $this->data["comentario"], PDO::PARAM_STR);
            $st->bindValue(":precio", $this->data["precio"], PDO::PARAM_STR);
            $st->bindValue(":fechaPost", $date, PDO::PARAM_STR);
            $con->beginTransaction();
           
            $test = $st->execute();
            //Esta variable luego se usa tambien 
            //en el segundo paso de subir un Post, cuando se sube una imagen
            //Si el usuario quiere eliminar una imagen en el proceso
            $_SESSION['lastId'][0] =  $con->lastInsertId();
            $con->commit();
           
                            
                if($test){
                   $test = $this->insertarPalabrasQueridas();
                        if($test){
                           $test = $this->insertarPalabrasOfrecidas();
                       }
                            if($test){
                               $test = $this->insertarImagenDemo(); 
                            }             
                }
            
           
            Conne::disconnect($con);
            return $test;
        }catch(Exception $ex){
            Conne::disconnect($con);
            echo 'El error se produce en la l??nea: '.$ex->getLine().'archivo '.$ex->getFile().'<br>';
            die("Query failed: ".$ex->getMessage().$ex->getCode());
        } 
         

//fin inserArticulo    
//fin inserArticulo    
}    




/***
 * Metodo que elimina una imagen 
 * y cometario de la bbdd y del sistema
 * que el usuario quiera cuando se esta
 * publicando un Posts.
 * Se instancia la variable $_SESSION['imgTMP']
 * para que si el usuario quiere subir otra foto 
 * sele asigne ese nombre
 * @return type boolean
 */

public function eliminarImg(){
        //Si es la primera imagen que borra el usuario se instancia
        //Para guardar en el array su ruta
        if(!isset($_SESSION['imgTMP']['imagenesBorradas'])){
            $_SESSION['imgTMP']['imagenesBorradas'][0] = null;    
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
         //echo 'en Post eliminar: '.var_dump($_SESSION['imgTMP']['imagenesBorradas']).'<br>';
        
        $st->bindValue(":url", $this->getValue('idImagen'), PDO::PARAM_STR);
        // echo 'lastId: '.$_SESSION['lastId'][0].' : '. 'idImagen: '.$this->getValue('idImagen').'<br>';
        $st->execute();
        $columnas = $st->rowCount();
        //En caso de ser todo correcto eliminamos la imagen 
        //del sistema y restamos 1 al contador de imagenes
        if($columnas){
            $test = Directorios::eliminarImagen("../photos/".$this->getValue('idImagen').".jpg");
            if($test){
                
                $_SESSION['contador'] = $_SESSION['contador'] - 1;
                //echo 'Hemos eliminado de la bbdd y del sistema, restamos contador <br>';
            }
            
            //Si el contador vuelve a 0, volvemos a copiar la foto demo
            //Evitamos que en cada momento el que el usuario no tenga una  imagen en el Post
            if(isset($_SESSION['contador']) and $_SESSION['contador'] == 0){
            $test = Directorios::copiarFoto("../photos/demo.jpg",$_SESSION['nuevoSubdirectorio']."/demo.jpg");
            //                    IMPORTANTE
            //  Volvemos a ingresar en la bbdd la ruta de la imagen /demo
            // para poder mostrar siempre una imagen
            $sql = "INSERT INTO ".TBL_IMAGENES." (post_idPost, ruta) VALUES ( :post_idPost, :ruta)";
       
                $st = $con->prepare($sql);
        
            $st->bindValue(":post_idPost", $_SESSION['lastId'][0], PDO::PARAM_INT);
            $st->bindValue(":ruta","demo", PDO::PARAM_STR);  
                
                $test = $st->execute();
            }

           
        }
                
        Conne::disconnect($con);
        return $test;
      
      
    } catch (Exception $ex) {
        Conne::disconnect($con);
        $_SESSION['error'] = ERROR_ELIMINAR_FOTO;
        echo 'El error se produce en la l??nea: '.$ex->getLine().'<br>';
        echo "En el directorio ".$ex->getFile();
        die("Query failed: ".$ex->getMessage());
    }  
     
//fin eliminarImg    
}

/**
 * Metodo que actualiza el texto
 * introducido en una imagen cuando 
 * se inserta una imagen en un post
 * @return type boolean
 */
 public function actualizarTexto(){
  
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
        $stm->execute();
        $columnas = $stm->rowCount();
        
        Conne::disconnect($con);
        return $columnas;    
    }catch(Exception $ex){
        Conne::disconnect($con);
        echo 'El error se produce en la l??nea: '.$ex->getCode().'<br>';
        echo "Del archivo ".$ex->getFile().'<br>';
        die("Query failed: ".$ex->getMessage());
    }
    
//fin actualizarTexto    
}

/**
 * Eliminar palabras del post
 * de buscadas y ofrecidas
 * Recive el id del post a eliminar
 * @return type boolean
 */

static function eliminarPalabrasQueridas($id) {
    $idPostPa = $id;
    
    try {
            
          
            $con = Conne::connect();
            //Solicitamos el id de las palabras a eliminar    
            $buscadas = Post::devuelvoIdPalabras("busquedas_pbs_buscadas","palabrasBuscadas", "idPbsBuscada", "idPost_queridas", $idPostPa);
           
                for($i = 0; $i < 4; $i++){

                    $stm = " DELETE from ".TBL_PBS_QUERIDAS. " WHERE idPbsBuscada = :idPbsBuscada and idPost_queridas = :idPost_queridas;";
              
                    $buscada = $buscadas[$i]["idPbsBuscada"];
                    $stm = $con->prepare($stm);
                        $stm->bindValue(":idPost_queridas", $idPostPa, PDO::PARAM_INT);
                        $stm->bindValue(":idPbsBuscada", $buscada , PDO::PARAM_INT);
                        $stm->execute();
                        $columnas = $stm->rowCount();
                        if($columnas != 1){
                            $columnas = 0;
                            break;
                        }
                }
           
            Conne::disconnect($con);  
            return $columnas;       
         
        } catch (Exception $exc) {
            Conne::disconnect($con);
            echo "Error al eliminar palabras buscadas ".$exc->getLine().'<br>'.
                 "en el archivo ".$exc->getFile().'<br>'.
                 "code ".$exc->getTraceAsString();
            
        }
    
//eliminarPalabras   
}


/**
 * Elimina las palabras que el usuario
 * escribe al subir un Post Ofrecidas.
 * Recibe el id del post a elimanar las palabras
 * @param type $id
 * @return type boolean
 */

static function eliminarPalabrasOfrecidas($id) {
    $idPostPa = $id;
    
    try {
            
            $con = Conne::connect();
            //Solicitamos el id de las palabras a eliminar    
            $ofrecidas = Post::devuelvoIdPalabras("busquedas_pbs_ofrecidas","palabrasOfrecidas", "idPbsOfrecida", "idPost_ofrecidas", $idPostPa);
           
                for($i = 0; $i < 4; $i++){

                    $stm = " DELETE from ".TBL_PBS_OFRECIDAS. " WHERE idPbsOfrecida = :idPbsOfrecida and idPost_ofrecidas = :idPost_ofrecidas;";
                    $ofrecida = $ofrecidas[$i]["idPbsOfrecida"];
                    $stm = $con->prepare($stm);
                        $stm->bindValue(":idPost_ofrecidas", $idPostPa, PDO::PARAM_INT);
                        $stm->bindValue(":idPbsOfrecida", $ofrecida, PDO::PARAM_INT);
                        $stm->execute();
                        $columnas = $stm->rowCount();
                        if($columnas != 1){
                            $columnas = 0;
                            break;
                        }
                }
           
            Conne::disconnect($con);  
            return $columnas;       
         
        } catch (Exception $exc) {
            Conne::disconnect($con);
            echo "Error al eliminar palabras ofrecidas ".$exc->getLine().'<br>'.
                 "en el archivo ".$exc->getFile().'<br>'.
                 "code ".$exc->getTraceAsString();
            
        }
    
//eliminarOfrecidas   
}





/**
 * Este metodo recibe el id del 
 * post y se eliminan las imagenes
 * @param type $imgId
 * @return type
 */

static function eliminarImagenesPost($imgId) {
    try{
        
        
        $con = Conne::connect();
        $sql = "DELETE  from ".TBL_IMAGENES.
                " where post_idPost = :post_idPost";
                
        //echo "sql actualizarTexto ".$sql.'<br>';
        
        $stm = $con->prepare($sql);
        $stm->bindValue(":post_idPost", $imgId, PDO::PARAM_INT);
        $stm->execute();
        $columnas = $stm->rowCount();
       
        Conne::disconnect($con);
        return $columnas;    
    }catch(Exception $ex){
        Conne::disconnect($con);
        echo 'El error se produce en la l??nea => '.$ex->getCode().'<br>';
        echo "Del archivo ".$ex->getFile().'<br>';
        die("Query failed: ".$ex->getMessage());
    }
//fin eliminarImagenesPostAlSubir    
}

/**
 * Metodo static
 * Recive el id del post a eliminar
 * @param type $id
 * Retorna el numero de columnas 
 * @return int
 */


static function eliminarPostId($id){
    
   try{
        
        
        $con = Conne::connect();
        $sql = "DELETE  from ".TBL_POST.
                " where idPost = :idPost";
                
        //echo "sql actualizarTexto ".$sql.'<br>';
        
        $stm = $con->prepare($sql);
        $stm->bindValue(":idPost", $id, PDO::PARAM_INT );
        $stm->execute();
        $columnas = $stm->rowCount();
        
        Conne::disconnect($con);
        return $columnas;    
    }catch(Exception $ex){
        Conne::disconnect($con);
        echo 'El error se produce en la l??nea:: '.$ex->getCode().'<br>';
        echo "Del archivo ".$ex->getFile().'<br>';
        die("Query failed: ".$ex->getMessage());
    }
//fin eliminarImagenesPostAlSubir     
        
//fin eliminarPostId
}



/**
 * Metodo que inserta las imagenes
 * y comentarios de cada imagen subida
 * @return type boolean
 */
  public function insertarFotos(){
    
      
      $_SESSION['idImgadenIngresar'] = $this->getValue('idImagen'); 
      
    //echo "validar recive nombre renombrado ".$_SESSION['idImagen'].'<br>';
  
    try{
        $con = Conne::connect();
        
        $sql = "INSERT INTO ".TBL_IMAGENES." (post_idPost, ruta, texto) VALUES ( :post_idPost, :ruta, :texto)";
        
        
        $st = $con->prepare($sql);
        //Nos quedamos con la parte imprescindible de la ruta de la imagen
        //Para ocupar menos espacio en la tabla de la bbdd
        //El primer caso es si no se ha eliminado ninguna imagen
            //echo 'recibo: ',$_SESSION['idImgadenIngresar'].'<br>';
            $tmp = substr($_SESSION['idImgadenIngresar'], 10);
            //echo'despues ee 10 '.$tmp.'<br>';
            
        $tmp = strstr($tmp,'.',true);// $tmp => /admin/1/2
       
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
                $st->bindValue(":url", "demo", PDO::PARAM_STR);
       
                    $test = $st->execute();
        }
        
        
        Conne::disconnect($con);
        return $test;
    } catch (Exception $ex) {
        Conne::disconnect($con);
        echo 'codigo: '.$ex->getCode().'<br>';
        $_SESSION['error'] = ERROR_INSERTAR_FOTO;
        echo 'El error se produce en la l??nea: '.$ex->getLine().'<br>';
        echo 'En el archivo '.$ex->getFile();
        die("Query failed: ".$ex->getMessage());
    }
    
 
   
//fin insertarFotos  
}

/**
 * Metodo que elimina las palabras
 * que un usuario guarda para
 * recibir emails cuando alguien cuelga un post
 * y ofrece alguna de estas
 */
public function eliminarPalabrasEmail($idUsu){
  
    try{
        
        
        $con = Conne::connect();
        $sql = "DELETE  from ".TBL_PALABRAS_EMAIL.
                " where id_usuario = :id_usuario";
                
        //echo "sql actualizarTexto ".$sql.'<br>';
        
        $stm = $con->prepare($sql);
        $stm->bindValue(":id_usuario",$idUsu , PDO::PARAM_INT);
        $test = $stm->execute();
        
       
        Conne::disconnect($con);
        return  $test;
    }catch(Exception $ex){
        Conne::disconnect($con);
        echo 'El error se produce en la l??nea => '.$ex->getCode().'<br>';
        echo "Del archivo ".$ex->getFile().'<br>';
        die("Query failed: ".$ex->getMessage());
    }
    
    
    
//fin eliminarPalabrasEmail(){    
}



//fin de clase Post    
}
