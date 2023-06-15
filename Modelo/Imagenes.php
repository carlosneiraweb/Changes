<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Conne.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/DataObj.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Constantes/ConstantesBbdd.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Controlador/Validar/MisExcepcionesPost.php');

/**
 * Description of Imagenes
 *
 * @author carlos
 */
class Imagenes extends DataObj{


    protected $data = array(
    
        "idImagen" => "",
        "postIdPost" => "",
        "directorio" => "",
        "figcaption" => ""
    );
    
    
/**
 * Metodo que inserta la imagen <br/>
 * demo cuando un usuario esta <br/>
 * subiendo un post en la BBDD.
 * 
 */

public static function insertarImagenDemo(){
    
    $url = $_SESSION['nuevoSubdirectorio'][0]."/".$_SESSION['nuevoSubdirectorio'][1].'/demo';
    
    
    
    try {
        
        $con = Conne::connect();
        
             //Insertamos en la tabla imagenes la ruta de la imagen demo
            
            $st4 = "Insert into ".TBL_IMAGENES." (postIdPost,directorio) values (:idPost, :directorio)";
            $st4 = $con->prepare($st4);
            $st4->bindValue(":idPost", $_SESSION['lastId'][0], PDO::PARAM_INT);
            $st4->bindValue(":directorio", $url, PDO::PARAM_STR);
             
            $st4->execute();
           
        Conne::disconnect($con);
            
    } catch (Exception $ex) {
        
        Conne::disconnect($con);
        $_SESSION['error'] = ERROR_INSERTAR_ARTICULO;
        $excepciones = new MisExcepcionesPost(CONST_ERROR_BBDD_INGRESAR_IMG_DEMO_SUBIR_POST[1], CONST_ERROR_BBDD_INGRESAR_IMG_DEMO_SUBIR_POST[0],$ex);
        $excepciones->redirigirPorErrorTrabajosEnArchivosSubirPost("errorPost", true);
   
    }

//insertarImagenDemo    
}

   

/**
 * Metodo que inserta las imagenes<br/>
 * y comentarios de cada imagen subida.<br/>
 * Este metodo comprueba que subiendo alguna <br/>
 * imagen el usuario no ha eliminado una <br/>
 * mientras subia nel Post<br/>
 * Si es asi se le asigna el nombre que contiene <br/>
 * la variable $_SESSION['imgTMP']. <br/>
 * Esta se instancia en metodo eliminarImg de este archivo.<br/>
 * Si llega a estar en  $_SESSION['imgTMP']['imagenesBorradas'][0] <br/>
 * es destruida. <br/>
 * Ademas vigila que el usuario no elimine <br/>
 * todas las imagenes y deje al final la de demo. <br/>
 * Entonces ingresa en la bbdd la ruta /demo
 *
 */
  public function insertarFotos(){
   
     
      
       $_SESSION['contador'] = $_SESSION['contador'] + 1;
       
       if(isset($_SESSION['imgTMP']) and (!empty($_SESSION['imgTMP']['imagenesBorradas'][0]))){

           $tmp = array_shift($_SESSION['imgTMP']['imagenesBorradas']);

       }else{
                   
           $tmp = $_SESSION['nuevoSubdirectorio'][0].'/'.$_SESSION['nuevoSubdirectorio'][1]."/".$_SESSION['contador'];
           
       }
       
                if(empty($_SESSION['imgTMP']['imagenesBorradas'][0])){unset($_SESSION['imgTMP']);}
       
    try{
        
        $con = Conne::connect();

        $sql = "INSERT INTO ".TBL_IMAGENES." (postIdPost, directorio, texto) VALUES ( :postIdPost, :directorio, :texto)";
        
        $st = $con->prepare($sql);
        
        $st->bindValue(":postIdPost", $_SESSION['lastId'][0], PDO::PARAM_INT);
        $st->bindValue(":directorio",$tmp, PDO::PARAM_STR);
        //En caso el usuario no escriba una descripcion de la imagen
        if($this->data['figcaption'] == null){
            $st->bindValue(":texto", " ", PDO::PARAM_STR);  
        }else{
            $st->bindValue(":texto", $this->data['figcaption'], PDO::PARAM_STR);
        }
   
        
        $st->execute();
       
       
        //Si la foto se ha subido con exito el contador de imagenes se incrementa en 1
            
        try{
            
                //                    IMPORTANTE
                //Cuando insertamos una imagen eliminamos de la tabla imagenes
                // la imagen demo que subimos.Unicamente hacemos eso si contador == 1


            if(isset($_SESSION['contador']) and $_SESSION['contador'] == 1){
                $sql = "DELETE FROM ".TBL_IMAGENES." WHERE postIdPost = :postIdPost and directorio = :directorio";

                    $st = $con->prepare($sql);
                    $st->bindValue(":postIdPost", $_SESSION['lastId'][0], PDO::PARAM_INT);       
                    $st->bindValue(":directorio",$_SESSION['nuevoSubdirectorio'][0].'/'.$_SESSION['nuevoSubdirectorio'][1]."/demo", PDO::PARAM_STR);
                    //echo 'eliminar demo bbdd '.$_SESSION['nuevoSubdirectorio'][0].'/'.$_SESSION['nuevoSubdirectorio'][1]."/demo";
                    $st->execute() ? true : false;

                     
            }
        
        } catch (Exception $ex) {
            
            Conne::disconnect($con);
            $_SESSION['error'] = ERROR_INSERTAR_ARTICULO;
            $excepciones = new MisExcepcionesPost(CONST_ERROR_BBDD_ELIMINAR_IMG_DEMO_POST[1],CONST_ERROR_BBDD_ELIMINAR_IMG_DEMO_POST [0],$ex); 
            $excepciones->redirigirPorErrorTrabajosEnArchivosSubirPost("errorPost", true);
        
            
        }    
        
        
        
        Conne::disconnect($con);
        
    
    }catch(Exception $ex){
       
        Conne::disconnect($con);
        $_SESSION['error'] = ERROR_INSERTAR_ARTICULO;
        $excepciones = new MisExcepcionesPost(CONST_ERROR_BBDD_AL_SUBIR_UNA_IMG_SUBIENDO_POST[1],CONST_ERROR_BBDD_AL_SUBIR_UNA_IMG_SUBIENDO_POST[0],$ex); 
        $excepciones->redirigirPorErrorTrabajosEnArchivosSubirPost("errorPost", true);
        
    }

//fin insertarFotos
  }    
    
   
/**
 * Metodo que elimina una imagen. <br/>
 * y cometario de la bbdd y del sistema <br/>
 * que el usuario quiera cuando se esta <br/>
 * publicando un Posts. <br/>
 * Se instancia la variable $_SESSION['imgTMP'] <br/>
 * para que si el usuario quiere subir otra foto <br/>
 * se le asigne ese nombre. <br/>
 * Vigilamos que el usuario elimina <br/>
 * todas las fotos y no sube ninguna.<br/>
 * Volvemos a ingresar en le bbdd la imagen /demo <br/>
 * y movemos al directorio Post la imagen.
 * 
 */

public function eliminarImg(){
    
    
        //Si es la primera imagen que borra el usuario se instancia
        //Para guardar en el array su ruta
        if(!isset($_SESSION['imgTMP']['imagenesBorradas'])){
            $_SESSION['imgTMP']['imagenesBorradas'][0] = null;    
        } 
        
        
        $idImagen = $this->getValue('idImagen');
        
        
    try{
        
        
        $con = Conne::connect();
        $sql = "DELETE FROM ".TBL_IMAGENES." WHERE idImagen = :idImagen;";
        //echo 'Sql eliminarImagen: '.$sql.'<br>';
        $st = $con->prepare($sql);
        
        //Iniciamos una variable de sesion para asignarle a la siguiente 
            //imagen ingresada el nombre de la eliminada.
            //Creamos un array de arrays por si el usuario quiere
            // eliminar varias imagenes a la vez
        
        for($i = 0; $i< 5; $i++ ){
            
            if (empty($_SESSION['imgTMP']['imagenesBorradas'][$i])){
              $_SESSION['imgTMP']['imagenesBorradas'][$i] = $this->getValue('directorio');
                           break;
            }
        }
        
        $st->bindValue(":idImagen", $idImagen, PDO::PARAM_STMT);
        // echo 'lastId: '.$_SESSION['lastId'][0].' : '. 'idImagen: '.$this->getValue('idImagen').'<br>';
        $st->execute();
        $columnas = $st->rowCount();
       
        //En caso de ser todo correcto eliminamos la imagen 
        //del sistema y restamos 1 al contador de imagenes
        if($columnas){
            
            $ruta = $this->getValue('directorio');
            Directorios::eliminarImagen("../photos/$ruta.jpg", "eliminarImagenSubiendoPost");
           
            
                
                $_SESSION['contador'] = $_SESSION['contador'] - 1;
               
            
            try{
                
                //Si el contador vuelve a 0, volvemos a copiar la foto demo
                //Evitamos que en cada momento el que el usuario no tenga una  imagen en el Post
                    if(isset($_SESSION['contador']) and $_SESSION['contador'] == 0){
                        
                        Directorios::copiarFoto("../photos/demo.jpg",'../photos/'.$_SESSION['nuevoSubdirectorio'][0].'/'.$_SESSION['nuevoSubdirectorio'][1]."/demo.jpg","copiarDemoSubirPost");
                //                    IMPORTANTE
                //  Volvemos a ingresar en la bbdd la ruta de la imagen /demo
                // para poder mostrar siempre una imagen
                        $sql = "INSERT INTO ".TBL_IMAGENES." (postIdPost,directorio) VALUES ( :postIdPost, :directorio)";

                        $st = $con->prepare($sql);

                        $st->bindValue(":postIdPost", $_SESSION['lastId'][0], PDO::PARAM_INT);
                        $st->bindValue(":directorio", $_SESSION['nuevoSubdirectorio'][0].'/'.$_SESSION['nuevoSubdirectorio'][1].'/demo', PDO::PARAM_STR);  

                            $st->execute();
                        }
                
            } catch (Exception $ex) {

                Conne::disconnect($con);
                $_SESSION['error'] = ERROR_INSERTAR_ARTICULO;
                $excepciones = new MisExcepcionesPost(CONST_ERROR_BBDD_INGRESAR_IMG_DEMO_AL_ELIMINAR_TODAS_IMG_SUBIR_POST[1], CONST_ERROR_BBDD_INGRESAR_IMG_DEMO_AL_ELIMINAR_TODAS_IMG_SUBIR_POST[0],$ex);
                $excepciones->redirigirPorErrorTrabajosEnArchivosSubirPost("errorPost",true);
            
                
            }
            
            
           
           
        }
        
        Conne::disconnect($con);

    } catch (Exception $ex) {
        
        Conne::disconnect($con);
        $_SESSION['error'] = ERROR_INSERTAR_ARTICULO;
        $excepciones = new MisExcepcionesPost(CONST_ERROR_BBDD_ELIMINAR_IMG_POST[1], CONST_ERROR_BBDD_ELIMINAR_IMG_POST[0],$ex);
        $excepciones->redirigirPorErrorTrabajosEnArchivosSubirPost("errorPost",true);
        
    }finally{
        
    }  
     
//fin eliminarImg    
}    
    
    
/**
 * Metodo que actualiza el texto <br/>
 * introducido en una imagen cuando <br/>
 * se inserta una imagen en un post .
 * 
 */
 public function actualizarTexto(){
  
    
        
    
    try{
      
        $con = Conne::connect();
            $sql = "UPDATE ".TBL_IMAGENES. " SET ".
                    "texto = :descripcion ".
                    " WHERE postIdPost = :idPost and idImagen = :idImagen ";
            //echo "sql actualizarTexto ".$sql.'<br>';

            $stm = $con->prepare($sql);
            $stm->bindValue(":descripcion", $this->getValue('figcaption'), PDO::PARAM_STR );
            $stm->bindValue(":idPost", $_SESSION['lastId'][0] , PDO::PARAM_INT);
            $stm->bindValue(":idImagen", $this->getValue('idImagen'), PDO::PARAM_STR);
            $stm->execute();

            Conne::disconnect($con);
        
        }catch(Exception $ex){
            
            Conne::disconnect($con);
            $_SESSION['error'] = ERROR_INSERTAR_ARTICULO;
            $excepciones = new MisExcepcionesPost(CONST_ERROR_BBDD_ACTUALIZAR_TEXT_IMG_SUBIR_POST[1],CONST_ERROR_BBDD_ACTUALIZAR_TEXT_IMG_SUBIR_POST[0],$ex);
            $excepciones->redirigirPorErrorTrabajosEnArchivosSubirPost("errorPost",true);

        }
    
//fin actualizarTexto    
}    
    
    
    //fin Imagenes
}
