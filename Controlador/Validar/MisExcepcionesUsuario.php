<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Directorios.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Conne.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Controlador/Validar/MetodosInfoExcepciones.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/Usuarios.php');
/**
 * @Description of MisExcepciones
 * Clase que sobreescribe Exception
 * Utiliza metodos  propios para excepciones 
 * 
 * @author carlos
 */

                                    
class MisExcepcionesUsuario extends MetodosInfoExcepciones{

   
/**
 * Metodo que elimina los directorios creados <br/>
 * al registrarse un usuario o actualizar los <br/>
 * datos un usuario. <br/>
 * @param String $opc <br/>
 * Opción para tratar el error.
 * 
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
 * Metodo que es llamado cuando se produce un error <br/>
 * al trabajar con archivos o al trabajar con la bbdd,<br/>
 * cuando un usuario esta registrandose, actualizando<br/>
 * o hay un fallo de funcionalidad. <br/>
 * Segun opcion y grado elimina los directorios del usuario <br/>
 * en el registro o en la actualizacion <br/>
 * @param 
 * $opc <br/>
 * Type String <br/>
 * Opcion para trabajar correctamente con el error<br/>
 * @param $grado <br/>
 * Type Boolean <br/>
 *  Se usa para dependiendo <br/>
 * del grado se actuara en tratar errores <br/>
 * de una forma u otra.</br>
 * Cuando este a true redirige a mostrar error.<br/>
 * Cuando este a false solo hara insercion en la bbdd
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
            
            
            $this->tratarDatosErrores($opc,$grado);
            
                die();
                break;
        
        case $opc == "mandarEmailActivacion":   
            
            
            $this->tratarDatosErrores($opc, $grado);
            
                die();
                break;
            
        case $opc == "desbloquearUsuario":
            $_SESSION['error'] = ERROR_DESBLOQUEAR_USUARIO;
            $grado = true;
            $this->tratarDatosErrores($opc, $grado);
            
                die();
                break;
        
        case $opc == "bloquear":
            
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
