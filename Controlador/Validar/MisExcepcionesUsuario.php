<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Directorios.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Conne.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Controlador/Validar/MetodosInfoExcepciones.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/Usuarios.php');
/**
 * Description of MisExcepciones
 * Clase que sobreescribe Exception
 * Utiliza metodos  propios para excepciones 
 * 
 * @author carlos
 */

                                    
class MisExcepciones extends MetodosInfoExcepciones{

   
/**
 * Metodo que elimina los directorios creados
 * al registrarse un usuario o actualizar los
 * datos un usuario
 */

public function eliminarDirectoriosUsuario($opc) {
        
    if(isset($_SESSION["userTMP"])){        
        $usuViejo = $_SESSION["userTMP"]->getValue('nick');
    }
    
    switch ($opc) {
        
        
          
        case "registrar":
            
                //Se esta registrando y hay un error
            $fotos = "../photos/".$_SESSION["datos"]["id"];
            $datos = "../datos_usuario/".$_SESSION["datos"]["id"];
            $videos = "../Videos/".$_SESSION["datos"]["id"];
            if(isset($_SESSION["userTMP"])){
                $opc = "EliminarNuevosDirectorios";
            }else{
                $opc = "registrar";
            }
           
            break;
        
        
           
            
          
            
        default:
            
            echo "Hemos tenido un error";
            
            break;
    }
    
        if(isset($_SESSION["datos"]["id"])&& $_SESSION["datos"]["id"] != ""){
            
            try {
                
                Directorios::eliminarDirectoriosSistema($fotos,$opc);
            } catch (Exception $exc) {
                echo $exc->getCode();
                echo $exc->getMessage();
                
            }

            try{
                Directorios::eliminarDirectoriosSistema($datos,$opc);
            } catch (Exception $ex) {
                echo $ex->getCode();
                echo $ex->getMessage();
                
            }

            try{
                Directorios::eliminarDirectoriosSistema($videos,$opc); 
            } catch (Exception $ex){
                echo $ex->getCode();
                echo $ex->getMessage();
                
            }
        }
//fin eliminarDirectoriosUsuario   
}



/**
 * Metodo que elimina el subdirectorio
 * creado al intentar registrar un post
 * y algo sale mal
 */
private function eliminarNuevoSubdirectorio(){
    
    Directorios::eliminarDirectoriosSistema($_SESSION['nuevoSubdirectorio'], "nuevoSubdirectorioSubirPost");
    
    
  //fin eliminarNuevoSubdirectorio  
}





/**
    * Metodo que elimina variables de sesion
    * cuando un usuario ha acabado de subir 
    * un post
    */

public function eliminarVariablesSesionPostAcabado(){
 
    
   
    if(isset($_SESSION['imgTMP'])){
            unset($_SESSION['imgTMP']);
        }
        
    if(isset($_SESSION['atras'])){
        
            unset($_SESSION['atras']);
        }
    
    if(isset($_SESSION['contador'])){
            unset($_SESSION['contador']);
        }
    
    if(isset($_SESSION['png'])){
            unset($_SESSION['png']);
        }
        
    if(isset($_SESSION['error'])){
            unset($_SESSION['error']);
        }
        
    if(isset($_SESSION['post'])){unset($_SESSION['post']);}
     
    /*
     * Se eliminara en el metodo
     * convertir datosToString de la clase 
     * MetodosInfoExcepciones o
     * en el metodo ingresarPOst del archivo subir_post 
     * si todo ha ido bien
    if(isset($_SESSION['post'])){
            unset($_SESSION['post']);
    }
     * 
     */
    //fin eliminarVariablesSesionPostAcabado()         
    }


    /**
     * Este metodo manda a EliminarPost de la clase Post,
     * cuando un usuario quiere subir un post 
     * y a mitad de proceso se sale y no
     * acaba publicandolo
     * @param name $opc<br/>
     * type boolean <br/>
     * Se usa para cortar la secuencia
     * 
     */

 public function eliminarPostAlPublicar($opc){
    
        
            $tmp=  $_SESSION['nuevoSubdirectorio'];//de fotos
            $eliminarPost = "../photos/$tmp[0]/$tmp[1]";
            
            $idPost = $_SESSION['lastId'][0]; 
            Directorios::eliminarDirectoriosSistema($eliminarPost,"nuevoSubdirectorioSubirPost");
            Post::eliminarPostId($idPost,$opc);
        
   
        
        //fin  eliminarPostAlPublicar
}

/**
 * Metodo que trata los <br/>
 * errores al subir un post <br/>
 * Se encarga de eliminar los directorios <br/>
 * y los datos que se han podido ingresar en la bbdd <br/>
 * asi como variables de sesion <br/>
 * @param name $error </br>
 * type String <br/>
 * Mensaje de error <br/>
 * @param $grado <br/>
 * type boolean
 * Grado de error para actuar <br/>
 * de distinta manera en <br/>
 * redirigirPorErrorSistyema <br/>
 * 
 */

public function eliminarDatosErrorAlSubirPost($error,$grado,$excep){
      
    
    $_SESSION['errorArchivos'] = "existo";
    $_SESSION["paginaError"] = "index.php";
    $this->eliminarPostAlPublicar("errorPost");
    $this->eliminarVariablesSesionPostAcabado();
    $this->redirigirPorErrorSistema($error,$grado,$excep);
   // 
    
    
    
    die();
    
    //fin eliminarDatosErrorAlSubirPost
}





/**
 * Metodo que es llamado cuando se produce un error
 * al trabajar con archivos o al trabajar con la bbdd.
 * Elimina los directorios del usuario 
 * en el registro o en la actualizacion
 * Tambien maneja los errores
 * al subir o actualizar un Post
 * @param 
 * $opc <br/>
 * Type String <br/>
 * Opcion para trabajar correctamente con el error
 * @param $grado <br/>
 * @uses Se usa para dependiendo <br/>
 * del grado se actuara en tratar errores <br/>
 * de una forma u otra.</br>
 *Cuando este a true redirige a mostrar error<br/>
 *cuando este a false solo hara insercion en la bbdd
 *
 */


public function redirigirPorErrorSistema($opc,$grado){

   $_SESSION['errorArchivos'] = "existo";
 
    
   
   // echo PHP_EOL."opcion vale ".$opc." y gr4ado vale ".$grado." el id es ".$_SESSION["datos"]["id"].PHP_EOL;
    switch ($opc) {
      
      
           
        case $opc == "registrar":
           
            $_SESSION['error'] = ERROR_INGRESAR_USUARIO;
            $_SESSION["paginaError"] = "registrarse.php";
            $this->eliminarDirectoriosUsuario($opc);
            $_SESSION["usuRegistro"]->eliminarPorId($_SESSION["datos"]["id"]); //POr si ha quedado algun registro
            $this->tratarDatosErrores($opc,$grado);
            
            
                die();
                break;

         case $opc == "RegistrarUsuarioBBDD":
            
            $_SESSION['error'] = ERROR_REGISTRAR_USUARIO;
            $_SESSION["paginaError"] = "registrarse.php";
            $_SESSION['errorArchivos'] = "existo";
            $this->tratarDatosErrores("Error en el gestor bbdd al registrar usuario",$grado);
            
            
                die();
                break;
            
        
        case $opc == "ActualizarUsuarioBBDD":
            
            $_SESSION['error'] = ERROR_ACTUALIZAR_USUARIO; //No hace falta por el rollBlack de mysql
            $_SESSION["paginaError"] = "registrarse.php";
            $this->tratarDatosErrores("Error en el gestor bbdd al actualizar usuario",$grado);
            
                die();
                break;
            
         case $opc == "actualizar":
            
            $_SESSION['error'] = ERROR_ACTUALIZAR_USUARIO; //Sirve de bandera en caso de error
            $_SESSION["paginaError"] = "registrarse.php";
            $this->tratarDatosErrores("No se pudo renombrar o eliminar la vieja la foto del usuario cuando estaba actualizando su nick",$grado);    
            $_SESSION['actualizo']->actualizoDatosUsuario();
                
                die();
                break;
            
        
        case $opc == "ProblemaEmail":
            
            $grado = false;
            $this->tratarDatosErrores($opc,$grado);
            
                die();
                break;
        
        case $opc == "mandarEmailActivacion":   
            
            $grado = true;
            $this->tratarDatosErrores($opc, $grado);
            
                die();
                break;
            
        case $opc == "desbloquearUsuario":
            $_SESSION['error'] = ERROR_DESBLOQUEAR_USUARIO;
            $grado = true;
            $this->tratarDatosErrores($opc, $grado);
            
                die();
                break;
            
        default:
            
             $this->tratarDatosErrores($opc,false);
            die();
            break;
    } 
    
   
   
          
    
   

   
    if(!isset($_SESSION["userTMP"])){
        $_SESSION['error'] = ERROR_INGRESAR_USUARIO;
             
    }
            
//fin redirigirPorErrorTrabajosEnArchivos   
}



//fin mis excepciones    
}
