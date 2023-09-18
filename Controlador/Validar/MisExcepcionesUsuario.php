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
             
            echo " Hemos tenido un error";
          
            
           
            
           
    }
    
        if(isset($_SESSION["datos"]["id"])&& $_SESSION["datos"]["id"] != "" && $opc != null){
           
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
 * cuando un usuario esta registrandose o actualizando<br/>
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


public function redirigirPorErrorSistemaUsuario($opc,$grado){

   //$_SESSION['errorArchivos'] = "existo";

   // echo PHP_EOL."opcion vale ".$opc." y gr4ado vale ".$grado." el id es ".$_SESSION["datos"]["id"].PHP_EOL;
    switch ($opc) {
      
       
           
        case  "registrar":
            
            
            
            
            $_SESSION['error'] = ERROR_INGRESAR_USUARIO;
            $_SESSION["paginaError"] = "registrarse.php";
            $_SESSION["usuRegistro"]->elimanarDesbloqueo($_SESSION["datos"]["id"],null);
            $this->eliminarDirectoriosUsuario(null);
            $_SESSION["usuRegistro"]->eliminarPorId($_SESSION["datos"]["id"],null); //POr si ha quedado algun registro
            $this->tratarDatosErrores("Error al registrar usuario.",$grado);
           

                die();
                break;
              
        case  "RegistrarUsuarioBBDD":
            
            $_SESSION['error'] = ERROR_REGISTRAR_USUARIO;
            $_SESSION["paginaError"] = "registrarse.php";
            $this->tratarDatosErrores("Error en el gestor bbdd al registrar usuario. \n\r",$grado);
            
            
                die();
                break;
            
        
        case "ActualizarUsuarioBBDD":
            
            $_SESSION['error'] = ERROR_ACTUALIZAR_USUARIO; //No hace falta por el rollBlack de mysql
            $_SESSION["paginaError"] = "registrarse.php";
            $this->tratarDatosErrores("Error en el gestor bbdd al actualizar usuario. \n\r",$grado);
            
                die();
                break;
            
        case  "actualizar":
            
            $_SESSION['error'] = ERROR_ACTUALIZAR_USUARIO; 
            $_SESSION["paginaError"] = "registrarse.php";
            $this->tratarDatosErrores("No se pudo hacer la actualización \r\n del usuario.",$grado);    
            $_SESSION['actualizo']->actualizoDatosUsuario();
                
                die();
                break;
                       
        case  "eliminarDirectoriosBajaUsuario";
            
            $_SESSION['error'] = ERROR_ELIMINAR_DIRECTORIO_BAJA_USUARIO;
            $_SESSION['paginaError']= "index.php";
            $this->tratarDatosErrores("No pudimos eliminar los directorios del usuario. \n\r", $grado);
            
                 
                die();
                break;

        case  "ProblemaEmail":
            
           
            $this->tratarDatosErrores($opc,false);
            
                die();
                break;
        
        case  "mandarEmailActivacion":   
            
            $_SESSION['error'] = ERROR_MANDAR_EMAIL_ACTIVACION;
            $_SESSION["paginaError"] = "registrarse.php";
            $_SESSION["usuRegistro"]->elimanarDesbloqueo($_SESSION["datos"]["id"],null);
            $_SESSION["usuRegistro"]->eliminarPorId($_SESSION["datos"]["id"],null); //POr si ha quedado algun registro
            $this->eliminarDirectoriosUsuario("registrar",null);
            $this->tratarDatosErrores("No se pudo enviar el email de activar registro \n\r",$grado);
            
                die();
                break;
            
        case  "activarCuenta": 
           
            $_SESSION['error'] = ERROR_ACTIVAR_CUENTA_EMAIL;
            $_SESSION["paginaError"] = "index.php";
            $this->tratarDatosErrores("No se pudo eliminar el usuario de la tabla Desbloquear",$grado);
            
                die();
                break;
            
        case "recuperarIdUsu":
            
            $_SESSION['error'] = ERROR_BLOQUEAR_USUARIO;
            $_SESSION["paginaError"] = "index.php";
            $this->tratarDatosErrores("No se pudo  recuperar \n\r el id del usuario al bloquearlo",$grado);
            
                die();
                break;
             
        case "elimanarUsuBBDD":
            
            $_SESSION['error'] = ERROR_INTERNO;
            $_SESSION["paginaError"] = "mostrar_error.php";
            $this->tratarDatosErrores("No se pudo  eliminar al usuario \n\r de la bbdd por id",$grado);
            
                die();
                break;   
        
        case "emailBienvenida":
            
            $_SESSION["paginaError"] = "index.php";
            $this->tratarDatosErrores("No se pudo mandar email \r\n de bienvenida",$grado);
            
            
                die();
                break;  
            
        default:
           
            
             $this->tratarDatosErrores($opc,$grado);
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
