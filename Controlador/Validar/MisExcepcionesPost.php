<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Directorios.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Conne.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Controlador/Validar/MetodosInfoExcepciones.php');
/**
 * Description of MisExcepcionesPost
 *
 * @author carlos
 */
class MisExcepcionesPost extends MetodosInfoExcepciones{





/**
    * Metodo que elimina variables de sesion
    * cuando un usuario ha acabado de subir 
    * un post
    */

public function eliminarVariablesSesionPostAcabado(){

    if(isset($_SESSION['imgTMP'])){unset($_SESSION['imgTMP']);}
    if(isset($_SESSION['atras'])){unset($_SESSION['atras']);}
    if(isset($_SESSION['contador'])){unset($_SESSION['contador']);}
    if(isset($_SESSION['png'])){unset($_SESSION['png']);}
    if(isset($_SESSION['imgTMP']['imagenesBorradas'])){unset($_SESSION['imgTMP']['imagenesBorradas']);}
    if(isset($_SESSION['error'])){unset($_SESSION['error']);}
    if(isset($_SESSION['post'])){unset($_SESSION['post']);}

    //fin eliminarVariablesSesionPostAcabado()         
    }


    /**
     * Este metodo manda a EliminarPost de la clase Post,<br/>
     * cuando un usuario quiere subir un post <br/>
     * y a mitad de proceso se sale y no<br/>
     * acaba publicandolo<br/>
     * Tambien se usa en caso de error.<br/>
     * @param name $opc<br/>
     * type boolean <br/>
     * Se usa para cortar la secuencia
     * 
     */

 public function eliminarPostAlPublicar(){
    
        
            $tmp=  $_SESSION['nuevoSubdirectorio'];//de fotos
           
            $eliminarPost = "../photos/$tmp[0]/$tmp[1]";
            
            $idPost = $_SESSION['lastId'][0];
            //En caso de que no se pueda crear el subdirectorio
            if($eliminarPost !== "../photos/$tmp[0]/"){
                Directorios::eliminarDirectoriosSistema($eliminarPost,"SubirPost");
            }
                Post::eliminarPostId($idPost);
        
   
        
        //fin  eliminarPostAlPublicar
}


    
 /**
     * Metodo que en caso de error al
     * trabajar con archivos cuando se sube un Post nos redirige 
     * a la pagina correspondiente y
     * elimina todas las variables de sesion
     */
   
public  function redirigirPorErrorTrabajosEnArchivosSubirPost($opc,$grado){
      
   

        //$_SESSION['errorArchivos'] = "existo";
        
        //Para mostrar el error al usuario en mostrar_error.php
        $_SESSION['mostrarError'] = $_SESSION['error'];
        $_SESSION["paginaError"] = "subir_posts.php";
        
        switch ($opc) {
            
            case 'errorPost':
                
               
                $this->tratarDatosErrores($opc, $grado);
                $this->eliminarPostAlPublicar();
                $this->eliminarVariablesSesionPostAcabado();

                    
                    break;
                
            case "errorPostEliminarBBDD":

                $this->tratarDatosErrores($opc, $grado);
                $this->eliminarVariablesSesionPostAcabado();
                
                    
                    break;
                
            default:
                
                 $this->tratarDatosErrores($opc, $grado);
                
                die();
                break;
        }
     
//fin redirigirPorErrorTrabajosEnArchivosSubirPost()
}    
    
//fin   MisExcepcionesPost  
}
