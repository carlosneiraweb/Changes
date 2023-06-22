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
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Constantes/ConstantesEmail.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Constantes/ConstantesBbdd.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Controlador/Validar/MisExcepcionesUsuario.php');


class Post extends DataObj{
    
    
    protected $data = array(
        
        "idUsuarioPost" => "",
        "seccionesIdsecciones" => "",
        "tiempoCambioIdTiempoCambio" => "",
        "titulo" => "",
        "comentario" => "",
        "precio" => "",
        "Pa_queridas" => array(),
        "Pa_ofrecidas" => array(),
        "idImagen" => "",
        "figcaption" => "",
        "fechaPost" => ""
       
    );
    
    
    ///////FALTA MODIFICAR EXCEPCIONES
    
   /**
    * Recibe array con las palabras
    * Si hay coincidencia mandaremos email al usuario
    * que este interesado en las palabras que se acaban de publicar
    * @param type array palabras buscadas
    */ 
    public function buscarUsuariosInteresados($datosPost){
        
    
        try {
           
            $con = Conne::connect();
        //var_dump($datosEmail);
        //Si el usuario solo ha escrito una palabra
        //utilizamos la sql like 
        //esta es mas efectiva
        $palabras = explode(" ", $datosPost[0][0]);
        $totalPalabras = count($palabras);
        
        if($totalPalabras != '0'){
            
            if($totalPalabras == "1"){
                $sql = "select DISTINCT pe.usuario_idUsuario as idUsuBusca, pe.email as emailUsuBusca, usu.nick as nickRecibeEmail 
                from ".TBL_PALABRAS_EMAIL." pe
                inner join usuario usu on usu.idUsuario = pe.usuario_idUsuario
                where palabras_detectar like :palabra;"; 
                //Si es mayor a una palabra usamos
                //MATCH ... AGAINST
            }else{
                //Si ha utilizado varias palabras usamo MATCH AGAINST
                $sql ="SELECT DISTINCT pe.usuario_idUsuario as idUsuBusca, pe.email as emailUsuBusca , usu.nick  as nickRecibeEmail FROM ".TBL_PALABRAS_EMAIL." pe
                        inner JOIN ".TBL_USUARIO." usu
                        ON usu.idUsuario = pe.usuario_idUsuario
                        WHERE MATCH(palabras_detectar) AGAINST (:palabra);";

            }
     
            $usuInteresados = array();         
            $stm = $con->prepare($sql);
            $stm->bindValue(":palabra", "%{$datosPost[0][0]}%", PDO::PARAM_STR);
            $stm->execute();
            $usuInteresados = $stm->fetchAll();
            $total = count($usuInteresados);
            $total = intval($total);
           
            
            
            
            if($total > 0){
           
                        
             $sqlNombre ="Select provincia as provinciaPublica 
                    from direccion d where d.idDireccion 
                    = (select idUsuario from usuario where nick = :nick) ";
             
             $sqlRutaImg = "Select nickUsuario as nick,ruta as ruta from imagenes where "
                . "post_idPost = :post_idPost limit 1";

            $stmNombre = $con->prepare($sqlNombre);
            $stmNombre->bindValue(":nick", $datosPost[2], PDO::PARAM_STR);
            $stmNombre->execute();
            $provinciaUsuPublica =  $stmNombre->fetch();
            
            $stmRutaImg = $con->prepare($sqlRutaImg);
            $stmRutaImg->bindValue(":post_idPost", $datosPost[3], PDO::PARAM_STR);
            $stmRutaImg->execute();
            $ruta = $stmRutaImg->fetch();
           

            $objMandarEmails = new mandarEmails();
            //Mandamos email a todos los usuarios interesado
           
            for($i=0; $i < $total; $i++){
               
                $email = $usuInteresados[$i][1];
                //Evitamos que un mismo usuario se mande email
                if($_SESSION["userTMP"]->getValue("email") != $email){
                    
                   $objMandarEmails->mandarEmailPalabrasBuscadas($datosPost,$usuInteresados,$email,$provinciaUsuPublica,$ruta);
                
                }
                
            }
            
            }                      
           
        }
                 
        Conne::disconnect($con);  
          
        } catch (Exception $exc) {
            Conne::disconnect($con);
            //Seguimos al usuario permitirle seguir en la web
            //no es un fallo critico
           $excepciones = new MisExcepcionesPost(CONST_ERROR_BBDD_BUSCAR_USUARIOS_EMAIL[1], CONST_ERROR_BBDD_BUSCAR_USUARIOS_EMAIL[0],$ex);
           $excepciones->redirigirPorErrorSistema("Hubo un error al buscar en la tabla palabras email", false);
         
            
        }
        
        
   //fin palabras     
    }
    
    
    

   /**
     * 
     * Metodo que devuelve un array con <br/>
     * el id de las palabras buscada o ofrecidas. <br/>
     * Se utiliza para actualizar las <br/>
     * palabras que un usuario quiere cambiar <br/>
     * @return array
     */

public function devuelvoIdPalabras($tabla, $columnaIdImagen, $columnaIdPost, $idPostPalabra){
   // echo 'tabla '.$tabla. ' columnaIdImagen '.$columnaIdImagen. ' palabra '.$palabras.' columnaIdPOST '.$columnaIdPost. ' IDpOSTpALABRA '.$idPostPalabra.'<br>';    
       
    
    try {
           
            $con = Conne::connect();

                            
            $stm = "Select $columnaIdImagen from $tabla where ".$columnaIdPost." = :idImagen;";
                            //echo "idPab ".$stm.'<br>';
            $stm = $con->prepare($stm);
            $stm->bindValue(":idImagen", $idPostPalabra, PDO::PARAM_INT);
            $stm->execute();
            $misPalabras = $stm->fetchAll(PDO::FETCH_ASSOC);//fetchAll(PDO::FETCH_COLUMN, 0);
                          
            Conne::disconnect($con);  
           
            
            return $misPalabras;   
   
            
            
    } catch (Exception $ex) {
        
            Conne::disconnect($con);
            
            if(isset($_SESSION['atras'])){
                $_SESSION['error'] = ERROR_INSERTAR_ARTICULO;
            }else{
                $_SESSION['error'] = ERROR_ACTUALIZAR_POST;
            }
           
                $excepciones = new MisExcepcionesPost(CONST_ERROR_BBDD_DEVOLVER_ID_PALABRAS_AL_ACTUALIZAR[1],CONST_ERROR_BBDD_DEVOLVER_ID_PALABRAS_AL_ACTUALIZAR[0],$ex);
                $excepciones->redirigirPorErrorTrabajosEnArchivosSubirPost("errorPost", true);
    }
        
     
       
       //devuelvoIdPalabras
    }  
 
    
   /**
     * Metodo que actualiza las palabras </br>
     * que un usuario busca en un post.</br>
     * Este metodo llama a devuelvoIdPalabras. </br> 
     * Metodo comun para actualizarPalarasOfrecidas y  </br>
     * actualizarPalabrasBuscadas. Se le pasa la tabla, columna y el id </br>
     * del post.
    */
    
    private function actualizarPalabrasBuscadas($idPostPalabras){
        
        
       
        
        $idPostPa;
        
            if(isset($_SESSION['lastId'][0])){
                $idPostPa = $_SESSION['lastId'][0];
            }else{
                $idPostPa = $idPostPalabras;
            }

        try {
            
            $con = Conne::connect();
         
            //Solicitamos el id de las palabras a modificar       
            $buscadas =  $this->devuelvoIdPalabras("busquedas_pbs_buscadas", "idPbsBuscada", "idPostQueridas", $idPostPa);
            
            //Las palabras nuevas para modificar ls antiguas
            $nuevasPalabras = $this->getValue('Pa_queridas');
            
            
                for($i = 0; $i < 4; $i++){
                    
                    if($nuevasPalabras[$i] == null){$nuevasPalabras[$i] = "";} //Nos aseguramos 4 palabras      
                        
                        $stm = " UPDATE ".TBL_PBS_QUERIDAS. " SET palabrasBuscadas = :palabrasBuscadas WHERE idPbsBuscada = :idPbsBuscada and idPostQueridas = :idPostQueridas;";
                                //echo $stm;
                        $stm = $con->prepare($stm);
                        $stm->bindValue(":idPostQueridas", $idPostPa, PDO::PARAM_INT);
                        $stm->bindValue(":palabrasBuscadas", $nuevasPalabras[$i], PDO::PARAM_STR);
                        $stm->bindValue(":idPbsBuscada", $buscadas[$i]['idPbsBuscada'], PDO::PARAM_INT);
                        $stm->execute();
                         
                           
                            //$actualizo = $stm->rowCount();
                                
                }                 
              
                Conne::disconnect($con);  
              
        }catch (Exception $ex) {
            
            Conne::disconnect($con); 
            
            if(isset($_SESSION['atras'])){
                $_SESSION['error'] =  ERROR_INSERTAR_ARTICULO;
            }else if(!isset($_SESSION['error'])){
                $_SESSION['error'] =  ERROR_ACTUALIZAR_POST;
            }
            
            $excepciones = new MisExcepcionesPost(CONST_ERROR_BBDD_ACTUALIZAR_PBS_QUERIDAS[1],CONST_ERROR_BBDD_ACTUALIZAR_PBS_QUERIDAS[0],$ex);
            $excepciones->redirigirPorErrorTrabajosEnArchivosSubirPost("errorPost", true);
            
            
        }
        //insertarPalabrasBuscadas    
    }
    
     
    
    
    /**
     * Metodo privado para actualiza las palabras </br>
     * que el usuario ingresa un post </br>
     * para definir su articulo.</br>
     * Este metodo llama a devuelvoIdPalabras.</br>
     * Metodo comun para actualizarPalarasOfrecidas y  </br>
     * actualizarPalabrasBuscadas. Se le pasa la tabla, columna y el id</br>
     * del post.
     * 
     */
    private function actualizarPalarasOfrecidas($idPostPalabras){
       
        
        
            if(isset($_SESSION['lastId'][0])){
                $idPostOfre = $_SESSION['lastId'][0];
            }else{
                $idPostOfre = $idPostPalabras;
            }
       
        //El id de las viejas palabras
            
            $ofrecidas = $this->devuelvoIdPalabras("busquedas_pbs_ofrecidas", "idPbsOfrecida", "idPostOfrecidas", $idPostOfre);

                try {
                    
                    $con = Conne::connect();
                  
                    
                    //Las palabras nuevas para modificar ls antiguas
                    $nuevasOfrecidas = $this->getValue('Pa_ofrecidas');  
                    
                        for($i = 0; $i < 4; $i++){
                                
                            if($nuevasOfrecidas[$i] == null){$nuevasOfrecidas[$i] = "";} //Nos aseguramos 4 palabras
                                
                                $stm = " UPDATE ".TBL_PBS_OFRECIDAS. " SET palabrasOfrecidas = :palabrasOfrecidas  WHERE idPbsOfrecida = :idPbsOfrecida and idPostOfrecidas = :idPostOfrecidas;";
                                $stm = $con->prepare($stm);
                                $stm->bindValue(":idPostOfrecidas", $idPostOfre, PDO::PARAM_INT);
                                $stm->bindValue(":palabrasOfrecidas", $nuevasOfrecidas[$i], PDO::PARAM_STR);
                                $stm->bindValue(":idPbsOfrecida", $ofrecidas[$i]['idPbsOfrecida'], PDO::PARAM_INT);
                                $stm->execute();
                                //$stm->rowCount();
                                
                        }

            Conne::disconnect($con); 
           
        } catch (Exception $ex) {
        
            Conne::disconnect($con); 
            
            if(isset($_SESSION['atras'])){
                $_SESSION['error'] =  ERROR_INSERTAR_ARTICULO;
            }else if(!isset($_SESSION['error'])){
                $_SESSION['error'] =  ERROR_ACTUALIZAR_POST;
            }
            
            $excepciones = new MisExcepcionesPost(CONST_ERROR_BBDD_ACTUALIZAR_PBS_OFRECIDAS[1],CONST_ERROR_BBDD_ACTUALIZAR_PBS_OFRECIDAS[0],$ex);
            $excepciones->redirigirPorErrorTrabajosEnArchivosSubirPost("errorPost", true);
             
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
    
    
    //if(isset($_SESSION['errorArchivos'])){unset($_SESSION['errorArchivos']);}
    
    try{
    $con = Conne::connect();
    
        $sql = "UPDATE ".TBL_POST. " SET "
               . "idUsuarioPost = (SELECT idUsuario FROM ".TBL_USUARIO. " WHERE nick = :nick), "
               . "seccionesIdsecciones = (SELECT idSecciones FROM ".TBL_SECCIONES. " WHERE nombreSeccion = :seccionesIdsecciones), "
               . "tiempoCambioIdTiempoCambio = (SELECT idTiempoCambio FROM ".TBL_TIEMPO_CAMBIO. " WHERE tiempo = :tiempoCambioIdTiempoCambio), "
               . "titulo = :titulo, "
               . "comentario = :comentario, "
               . "precio = :precio, "
               . "fechaPost = :fechaPost "
               . " WHERE idPost = :idPost ";
        //echo $sql.'<br>';     
    
        $date = date('Y-m-d');
        
            $stm = $con->prepare($sql);
            $stm->bindValue(":nick", $this->data["idUsuarioPost"], PDO::PARAM_STR);
            $stm->bindValue(":seccionesIdsecciones", $this->data["seccionesIdsecciones"], PDO::PARAM_STR);
            $stm->bindValue(":tiempoCambioIdTiempoCambio", $this->data["tiempoCambioIdTiempoCambio"], PDO::PARAM_STR);
            $stm->bindValue(":titulo", $this->data["titulo"], PDO::PARAM_STR);
            $stm->bindValue(":comentario", $this->data["comentario"], PDO::PARAM_STR);
            $stm->bindValue(":precio", $this->data["precio"], PDO::PARAM_STR);
            $stm->bindValue(":fechaPost", $date, PDO::PARAM_STR);
            $stm->bindValue(":idPost", $_SESSION['lastId'][0] , PDO::PARAM_INT);
            
            $stm->execute();
            
            //Si todo va bien se llama a los metodos que
            //actualian las palabras.
            //En este caso como tenemos en una variable se session
            //el idPost le pasamos un null
            //En eliminar 
            $this->actualizarPalabrasBuscadas(null);
                    
            $this->actualizarPalarasOfrecidas(null); 
               
        Conne::disconnect($con);  
           
    }catch(Exception $ex){
        
        Conne::disconnect($con);
        
        if(isset($_SESSION['atras'])){
            $_SESSION['error'] = ERROR_INSERTAR_ARTICULO;
        }else{
            $_SESSION['error'] = ERROR_ACTUALIZAR_POST;
        }
        $excepciones = new MisExcepcionesPost(CONST_ERROR_BBDD_ACTUALIZAR_POST[1],CONST_ERROR_BBDD_ACTUALIZAR_POST[0],$ex);
        $excepciones->redirigirPorErrorTrabajosEnArchivosSubirPost("errorPost",true);
    }
//fin actualizar articulo    
}

/**
 * Metodo que inserta las palabras <br/>
 * por las que el usuario quiere cambiar <br/>
 * su item.
 * 
 */
private  function insertarPalabrasQueridas(){
    
    
    
            //Creamos un array con las palabras buscadas
            $buscadas = $this->getValue("Pa_queridas");
    
            try {
                $con = Conne::connect();
                 
                    //Pasamos en bucle insertandolas si no son null
                    for($i=0; $i < 4; $i++){  
                        if($buscadas[$i] == null){$buscadas[$i] = "";} //Nos aseguramos 4 palabras
                            $st3 = "Insert into ".TBL_PBS_QUERIDAS." (idPostQueridas, palabrasBuscadas) values (:idPostQueridas, :palabra)";
                            $st3 = $con->prepare($st3);
                            $st3->bindValue(":idPostQueridas", $_SESSION['lastId'][0], PDO::PARAM_INT);
                            $st3->bindValue(":palabra", $buscadas[$i], PDO::PARAM_STR);
                            $st3->execute();
                    }       
                    Conne::disconnect($con);
                   
            
               
            }catch(Exception $ex){
               
               Conne::disconnect($con);
               $_SESSION['error'] = ERROR_INSERTAR_ARTICULO;
               $excepciones =  new MisExcepcionesPost(CONST_ERROR_BBDD_INGRESAR_PALABRAS_QUERIDAS[1], CONST_ERROR_BBDD_INGRESAR_PALABRAS_QUERIDAS[0],$ex);
               $excepciones->redirigirPorErrorTrabajosEnArchivosSubirPost("errorPost", true);
                
            }
//fin insertarPalabrasQueridas    
}


/**
 * Metodo que inserta las palabras <br/>
 * por las que el usuario quiere cambiar
 * 
 */

private function insertarPalabrasOfrecidas(){
    
    
     //Creamos un array con las palabras buscadas
           
            
            try {
                
                $con = Conne::connect();
                
                //Creamos un array con las palabras buscadas
            $ofrecidas = $this->getValue("Pa_ofrecidas");
            
             //Pasamos en bucle insertandolas si no son null
        
                
                for($i = 0; $i < 4; $i++){
                     if($ofrecidas[$i] == null){$ofrecidas[$i] = "";} //Nos aseguramos 4 palabras
                    $st3 = "Insert into ".TBL_PBS_OFRECIDAS." (idPostOfrecidas, palabrasOfrecidas) values (:idPostOfrecidas, :palabra)";
                    $st3 = $con->prepare($st3);
                    $st3->bindValue(":idPostOfrecidas", $_SESSION['lastId'][0], PDO::PARAM_INT);
                    $st3->bindValue(":palabra", $ofrecidas[$i], PDO::PARAM_STR);
                    $st3->execute();
                    
            
                }

                
                Conne::disconnect($con);
                
            } catch (Exception $ex) {
                
                Conne::disconnect($con);
                
                $_SESSION['error'] = ERROR_INSERTAR_ARTICULO;
                $excepciones = new MisExcepcionesPost(CONST_ERROR_BBDD_INGRESAR_PALABRAS_OFRECIDAS[1], CONST_ERROR_BBDD_INGRESAR_PALABRAS_OFRECIDAS[0],$ex); 
                $excepciones->redirigirPorErrorTrabajosEnArchivosSubirPost("errorPost", true);

            }

//fin insertarPalabrasOfrecidas    
}


/**
 * Metodo que inserta en la bbdd un post.<br/>
 * @return type boolean
 */
public function insertPost(){
       
    
            
//if(isset($_SESSION['errorArchivos'])){unset($_SESSION['errorArchivos']);}  
    
    try{
        $con = Conne::connect();
       
         
            $sql = " INSERT INTO ".TBL_POST. "(
                   
                   idUsuarioPost,
                   seccionesIdsecciones,
                   tiempoCambioIdTiempoCambio,
                   titulo,
                   comentario,
                   precio,
                   fechaPost
                   
                   ) VALUES (
                   (SELECT idUsuario FROM ".TBL_USUARIO. " WHERE nick = :nick),
                   (SELECT idSecciones FROM ".TBL_SECCIONES. " WHERE nombreSeccion = :seccionesIdsecciones),
                   (SELECT idTiempoCambio FROM ".TBL_TIEMPO_CAMBIO. " WHERE tiempo = :tiempoCambioIdTiempoCambio),
                   :titulo,
                   :comentario,
                   :precio,
                   :fechaPost
                   
                   );";
            
           // echo $sql;
                    
            $date = date('Y-m-d');
            $st = $con->prepare($sql);
            $st->bindValue(":nick", $this->data["idUsuarioPost"], PDO::PARAM_STR);
            $st->bindValue(":seccionesIdsecciones", $this->data["seccionesIdsecciones"], PDO::PARAM_STR);
            $st->bindValue(":tiempoCambioIdTiempoCambio", $this->data["tiempoCambioIdTiempoCambio"], PDO::PARAM_STR);
            $st->bindValue(":titulo", $this->data["titulo"], PDO::PARAM_STR);
            $st->bindValue(":comentario", $this->data["comentario"], PDO::PARAM_STR);
            $st->bindValue(":precio", $this->data["precio"], PDO::PARAM_STR);
            $st->bindValue(":fechaPost", $date, PDO::PARAM_STR);
            
            $con->beginTransaction();
              
            $st->execute();
            //Esta variable luego se usa tambien 
            //en el segundo paso de subir un Post, cuando se sube una imagen
            //Si el usuario quiere eliminar una imagen en el proceso
            $_SESSION['lastId'][0] =  $con->lastInsertId();
            
            
            
            $con->commit();
        
            $this->insertarPalabrasQueridas();
                       
            $this->insertarPalabrasOfrecidas();
              
            //Metodo static clase Imagenes
            Imagenes::insertarImagenDemo();

            Conne::disconnect($con);
           
           
        }catch(Exception $ex){
          
            $_SESSION['error'] = ERROR_INSERTAR_ARTICULO;
            $con->rollBack();
            $excepciones = new MisExcepcionesPost(CONST_ERROR_BBDD_REGISTRAR_POST[1],CONST_ERROR_BBDD_REGISTRAR_POST[0],$ex);
            $excepciones->redirigirPorErrorTrabajosEnArchivosSubirPost('errorPost', true);
            
           
             
        } finally {
            Conne::disconnect($con);
        }
         
   
//fin inserArticulo    
}    


/**
 * Metodo static <br/>
 * Recive el id del post a eliminar <br/>
 * @param type $id
 */


static function eliminarPostId($id){
    
    try{
        
        
        $con = Conne::connect();
        $sql = "DELETE  from ".TBL_POST.
                " where idPost = :idPost";
  
        $stm = $con->prepare($sql);
        $stm->bindValue(":idPost", $id, PDO::PARAM_INT );
        $stm->execute();
        
        Conne::disconnect($con);
            
    }catch(Exception $ex){
        
        Conne::disconnect($con);
            
            $excepciones = new MisExcepcionesPost(CONST_ERROR_ELIMINAR_POST_AL_REGISTRARLO[1], CONST_ERROR_ELIMINAR_POST_AL_REGISTRARLO[0],$ex);
            $excepciones->redirigirPorErrorTrabajosEnArchivosSubirPost("",true);
        
    }
   
        
//fin eliminarPostId
}



//fin de clase Post    
}
