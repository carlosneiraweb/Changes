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

static function eliminarVariablesSesionPostAcabado(){
    
    if(isset($_SESSION['imgTMP'])){unset($_SESSION['imgTMP']);}
    if(isset($_SESSION['atras'])){unset($_SESSION['atras']);}
    if(isset($_SESSION['contador'])){unset($_SESSION['contador']);}
    if(isset($_SESSION['png'])){unset($_SESSION['png']);}
    if(isset($_SESSION['imgTMP']['imagenesBorradas'])){unset($_SESSION['imgTMP']['imagenesBorradas']);}
    if(isset($_SESSION['error'])){unset($_SESSION['error']);}
    if(isset($_SESSION['post'])){unset($_SESSION['post']);}
    if(isset($_SESSION['idImagen'])){unset($_SESSION['idImagen']);}
    if(isset($_SESSION['imgTMP'])){unset($_SESSION['imgTMP']);}
   // if(isset($_SESSION['nuevoSubdirectorio'])){unset($_SESSION['nuevoSubdirectorio']);}

    //fin eliminarVariablesSesionPostAcabado()         
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
        //Directorio a eliminar al ocurrir un error
        $dir = "../photos/".$_SESSION['nuevoSubdirectorio'][0]."/".$_SESSION['nuevoSubdirectorio'][1];
       
        switch ($opc) {
            
            case 'errorPost':
                
                
                //Tratamos de eliminar los posibles directorios creados
                //y borramos de la bbdd. Si no se ha creado el 
                //$_SESSION['nuevoSubdirectorio'][1] no hace falta realizar este paso
                if($_SESSION['nuevoSubdirectorio'][1] !== null){
                   
                    Directorios::eliminarDirectoriosSistema($dir,"SubirPost");
                    //Eliminamos el post de la bbdd
                    //se pasa un null por si no se ha hecho un insert
                    //no se cree un bucle 
                    if(isset($_SESSION['lastId'][0])){Post::eliminarPostId($_SESSION['lastId'][0], null);}
                }
                
                
                $this->eliminarVariablesSesionPostAcabado();
                $this->tratarDatosErrores($opc, $grado);

                    die();
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
