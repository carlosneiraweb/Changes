<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Directorios.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Conne.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Controlador/Validar/MetodosInfoExcepciones.php');

/**
 * Description of MisExcepciones
 * Clase que sobreescribe Exception
 * Utiliza metodos  propios para excepciones 
 * 
 * @author carlos
 */

                                    
class MisExcepciones extends MetodosInfoExcepciones{

   private $misExcepciones = Array();
    
    public function __construct($mensaje,$codigo) {
        $this->misExcepciones[0] = $mensaje;
        $this->misExcepciones[1] = $codigo;
        
    }
    
    function __destruct() {
        
    }

   /**
     * Metodo que recoje las excepciones </br>
     * originales de PHP </br>
     * Es pasado por referencia </br>
     * @param type Objeto tipo Exception</br>
     * 
     * @return type Array()
     */
    public function recojerExcepciones(&$ex){
        
        $excepciones = array();
        $excepciones[0] = $ex->getMessage();
        $excepciones[1] = $ex->getCode();
        $excepciones[2] = $ex->getFile();
        $excepciones[3] = $ex->getLine();
        $excepciones[4] = $ex->getTraceAsString();
        array_push($excepciones, $this->misExcepciones);
        //var_dump($excepciones);
        //echo PHP_EOL;
        unset($ex);
        return $excepciones;
        
        
        //fin recojerExcepciones
        
    } 
    

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
        
        case "actualizar":

             //Esta actualizando datos
            $fotos = "../photos/".$usuViejo;
            $datos = "../datos_usuario/".$usuViejo;
            $videos = "../Videos/".$usuViejo;
            //renombrarActualizar
                if(isset($_SESSION["userTMP"])){
                $opc = "eliminamosViejosDirectoriosActualizar";
            }else{
                $opc = "registrar";
            }
            break;
          
        case "registrar":
            
                //Se esta registrando y hay un error
            $fotos = "../photos/".$_SESSION['usuario']['nick'];
            $datos = "../datos_usuario/".$_SESSION['usuario']['nick'];
            $videos = "../Videos/".$_SESSION['usuario']['nick'];
            if(isset($_SESSION["userTMP"])){
                $opc = "EliminarNuevosDirectorios";
            }else{
                $opc = "registrar";
            }
           
            break;
        
        case "actualizarTMP":
            
                $fotos = "../Sistema/TMP_ACTUALIZAR/photos/".$usuViejo;
                $datos = "../Sistema/TMP_ACTUALIZAR/datos_usuario/".$usuViejo;
                $videos = "../Sistema/TMP_ACTUALIZAR/Videos/".$usuViejo;
            
                     break;

        
        case "renombrarActualizar":
            $fotos = "../photos/".$usuViejo;
            $datos = "../datos_usuario/".$usuViejo;
            $videos = "../Videos/".$usuViejo;
            $opc ="eliminamosViejosDirectoriosActualizar";
           
            
            break;
            
        default:
            
            echo "Hemos tenido un error";
            
            break;
    }
    

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

//fin eliminarDirectoriosUsuario   
}

   
/**
 * Este metodo primero intenta eliminar los directorios <br />
 * que se han cambiado de nombre o actualizado cuando un <br />
 * usuario se esta actualiando los datos y hay un error. <br />
 * Despues intenta restaurar la copia de los directorios <br />
 * que se ha hecho antes de intentar hacer una modificacion. <br />
 * @param type String 
 * name $opc 
 * Description : Opcion para tratar el error 
 */ 
public static function restaurarAntiguosDirectoriosAlActualizar($opc) {
    
    
    // Restauramos la copia de los archivos
    //que se han hecho antes de hacer cualquier 
    //modificacion
    
    if(isset($_SESSION["userTMP"])){
        $usuarioLogeado = $_SESSION["userTMP"]->getValue('nick');
    }
   
   
        Directorios::copiarDirectorios("../Sistema/TMP_ACTUALIZAR/".$usuarioLogeado."/photos/", "../photos/".$usuarioLogeado,$opc);
        Directorios::copiarDirectorios("../Sistema/TMP_ACTUALIZAR/".$usuarioLogeado."/datos_usuario/", "../datos_usuario/".$usuarioLogeado,$opc);
        Directorios::copiarDirectorios("../Sistema/TMP_ACTUALIZAR/".$usuarioLogeado."/Videos/", "../Videos/".$usuarioLogeado,$opc);

        

//fin restaurarAntiguosDirectoriosAlActualizar    
}

/**
 * Metodo que elimin a el subdirectorio
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


public function redirigirPorErrorSistema($opc,$grado,$excep){

   $_SESSION['errorArchivos'] = "existo";
 
    
   
    //echo PHP_EOL."opcion vale ".$opc.PHP_EOL;
    switch ($opc) {
      
        case $opc == "crearDirectorios_TMP":
           
                //Recuperamos los mensajes de error
            $_SESSION['error'] = ERROR_ACTUALIZAR_USUARIO;
            $_SESSION["paginaError"] = "registrarse.php";
            
            $this->tratarDatosErrores("Error al crear directorios TMP",$grado,$excep);  
                die();    
                break;
           
        case $opc == "copiarDirectorios_a_TMP_actualizar":
            
            $_SESSION['error'] = ERROR_ACTUALIZAR_USUARIO;
            $_SESSION["paginaError"] = "registrarse.php";
            
            $this->tratarDatosErrores("Error al copiar los directorios personales del usuario a la carpeta TMP cuando se estaba actualizando",$grado,$excep);  
                die();    
                break;
              
            /*
            
        case $opc == "actualizarCambiandoFoto":
            
            $_SESSION['error'] = ERROR_ACTUALIZAR_USUARIO;
            $_SESSION["paginaError"] = "registrarse.php";
            $this->tratarDatosErrores("No se pudo eliminar la vieja imagen del usuario",$grado);
            //Eliminamos los viejos directorios
            $this->eliminarDirectoriosUsuario("actualizar");
            //Eliminamos los posibles nuevos directorios
            //que creasen en el intento de actualizar
            $this->eliminarDirectoriosUsuario("registrar");
            //Restauramos los viejos directorios
            $this->restaurarAntiguosDirectoriosAlActualizar("restaurarViejosDirectoriosActualizar");
            
                die();       
                break;
       */
        case $opc == "registrar":
            
            $_SESSION['error'] = ERROR_INGRESAR_USUARIO;
            $_SESSION["paginaError"] = "registrarse.php";
            $this->eliminarDirectoriosUsuario($opc);
            $this->tratarDatosErrores($opc,$grado,$excep); 
                die();
                break;
         
        case $opc == "renombrarDirectortiosActualizar":
            
            $_SESSION['error'] = ERROR_ACTUALIZAR_USUARIO;
            $_SESSION["paginaError"] = "registrarse.php";
            $this->tratarDatosErrores("No se pudo renombrar los directorios cuando el usuario estaba actualizando sus datos cambiando el nick y subiendo foto",$grado,$excep);
            
            //Eliminamos los posibles nuevos directorios
            //que creasen en el intento de actualizar
            $this->eliminarDirectoriosUsuario("registrar");
            //Restauramos los viejos directorios
            $this->restaurarAntiguosDirectoriosAlActualizar("restaurarViejosDirectoriosActualizar");
            
            die();    
                break;
           
        case $opc == "errorFotoActualizar":
            
            $_SESSION['error'] = ERROR_ACTUALIZAR_USUARIO;
            $_SESSION["paginaError"] = "registrarse.php";
            $this->tratarDatosErrores("No se pudo renombrar o eliminar la vieja la foto del usuario cuando estaba actualizando su nick",$grado,$excep);
            //Eliminamos los viejos directorios
            $this->eliminarDirectoriosUsuario("actualizar");
            //Eliminamos los posibles nuevos directorios
            //que creasen en el intento de actualizar
            $this->eliminarDirectoriosUsuario("registrar");
            //No eliminamos los viejos pòr que se han renombrado anteriormente
            //Restauramos los viejos directorios
            $this->restaurarAntiguosDirectoriosAlActualizar("renombrarFotoActualizar");
            
            die();
                break;
           
        case $opc == "ActualizarUsuarioBBDD":
       
            $_SESSION['error'] = ERROR_ACTUALIZAR_USUARIO;
            $_SESSION["paginaError"] = "registrarse.php";
            $this->tratarDatosErrores("Error en el gestor bbdd al actualizar usuario",$grado,$excep);
            //Eliminamos los posibles nuevos directorios
            //que creasen en el intento de actualizar
            $this->eliminarDirectoriosUsuario("registrar");
            //Restauramos los viejos directorios
            $this->restaurarAntiguosDirectoriosAlActualizar("renombrarFotoActualizar");
            
                die();
                break;
           
        case $opc == "RegistrarUsuarioBBDD":
            
            $_SESSION['error'] = ERROR_REGISTRAR_USUARIO;
            $_SESSION["paginaError"] = "registrarse.php";
            $_SESSION['errorArchivos'] = "existo";
            $this->tratarDatosErrores("Error en el gestor bbdd al registrar usuario",$grado,$excep);
            //Eliminamos los posibles nuevos directorios
            //que creasen en el intento de actualizar
            $this->eliminarDirectoriosUsuario("registrar");
            
                die();
                break;
        
        case $opc == "ProblemaEmail":
            
            $grado = false;
            $this->tratarDatosErrores($opc,$grado,$excep);
            
                die();
                break;
        
        default:
            
             $this->tratarDatosErrores($opc,$grado,$excep);
            break;
    } 
    
   
   
          
    
   

   
    if(!isset($_SESSION["userTMP"])){
        $_SESSION['error'] = ERROR_INGRESAR_USUARIO;
             
    }
            
//fin redirigirPorErrorTrabajosEnArchivos   
}



//fin mis excepciones    
}
