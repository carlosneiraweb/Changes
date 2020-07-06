<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Controlador/Validar/MisExcepciones.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Directorios.php');


global $exception;
$exception = New MisExcepciones(null, null,null);   


/**
 * Controla los errores del sistema
 * al eliminar, crear o copiar
 * Directorios archivos, etc
 * Extiende Directorios
 * @author carlos
 */
class ControlErroresSistemaEnArchivos extends MisExcepciones{
   
  /**
     * Metodo que valida los datos introducidos por el usuario.
     * Valida los campos con los metodos static de ValidaForm
     * Valida los datos de la bbdd con un objeto de la clase Post
     * @global type $mensaje
     * @global Post $articulo
     * @param type $st
     * @return boolean
     */
   
 public static function validarCampos($st){
 
        global $exception;
        
    switch ($st){
         
        case("step1"):
            
            try {
            
                  //Creamos un subdirectorio para almacenar las imagenes 
                //IMPORTANTE CONOCER EL CONTENIDO DE 'nuevoSubdirectorio' 
                //Es la usada para mover, copiar, eliminar he ingresar en la bbdd
                //Su contenido es del tipo ../photos/nombreUsuario/totalSubdirectorios
                
                //Agregamos una foto demo por si el usuario no quiere subir
                //ninguna imagen
                //Esto solo se hace la primera vez y se evita crearlo otra vez si el usuario 
                // vuelve atras en el formulario comprobando que $_SESSION['atras'] no existe
      
                if(($_SESSION['contador'] == 0) and (!isset($_SESSION['atras'])) ){
                   
                    $_SESSION['nuevoSubdirectorio'] = Directorios::crearSubdirectorio("../photos/".$_SESSION['user']->getValue('nick'));
                    Directorios::copiarFoto("../photos/demo.jpg",$_SESSION['nuevoSubdirectorio']."/demo.jpg");    
                  
                }

            } catch (Exception $exc) {
                 $exception->redirigirPorErrorTrabajosEnArchivos();
            }

                break;
               
    case('step2'):
        
        $testSubirArchivo = Directorios::validarFoto('photoArticulo');
 
        //Comprobamos que nos devuelve la constante 0 que significa que se 
        //ha subido correctamente o que no nos devuelve la constante 4
        //que signfica que no se ha elegido un archivo
        if($testSubirArchivo === 0){
         
            
                //Si la foto es correcta entonces eliminamos la imagen default 
                    //que subimos
            try{
                    if(is_file($_SESSION['nuevoSubdirectorio'].'/demo.jpg')){
                    unlink($_SESSION['nuevoSubdirectorio'].'/demo.jpg');      
                    }
                  
                        $destino = $_SESSION['nuevoSubdirectorio'].'/'.basename($_FILES['photoArticulo']['name']);                   
                        $foto = $_FILES['photoArticulo']['tmp_name'];
                        
                            if($destino == "" || $foto == ""){
                                echo "destino vacio <br>";
                                throw new MisExcepciones(null, null);
                            }
               
            } catch (Exception $ex) {
                $exception->redirigirPorErrorTrabajosEnArchivos();
            }
                
                
                
            try {
                $testDir = Directorios::moverImagen($foto, $destino);
                
                if(!$testDir){
                   
                    throw new MisExcepciones(null, null);
                }
            } catch (Exception $exc) {
                $exception->redirigirPorErrorTrabajosEnArchivos();
            }


            
            
            try {
                //Comprobamos subiendo imagenes el usuario no ha eliminado ninguna
                            //Si lo ha hecho le asignamos en el directorio photos/subdirectorio 
                            //Ese nombre
                            if(isset($_SESSION['imgTMP']) and $_SESSION['imgTMP']['imagenesBorradas'][0] != null){
                                
                                $_SESSION['idImagen'] = Directorios::renombrarFoto($destino, 0); 
                                    if(!$_SESSION['idImagen']){
                                        throw new MisExcepciones(null, null);
                                    }
                            
                  //Aqui vamos subiendo las fotos al post mientras el usuario no 
                            //hubiera eliminado ninguna mientras subia las fotos                    
                            }else if (!isset($_SESSION['imgTMP'])){   
                                
                                $_SESSION['idImagen'] = Directorios::renombrarFoto($destino, 1);
                                    if(!$_SESSION['idImagen']){
                                        throw new MisExcepciones(null, null);
                                    }
                            }   
           
            } catch (Exception $exc) {
                $exception->redirigirPorErrorTrabajosEnArchivos();
            }

                
        }else if($testSubirArchivo === 4 || $testSubirArchivo === 10){
            //Si hay algun error al validar la imagen 
            //redirigimos a la pagina mostrarError
            //y le indicamos el motivo del error
            // Esto ultimo se hace en el switch del
            //metodo que valida la subida en el directorio Directorios
                $_SESSION['paginaError'] = 'subir_posts.php';
                mostrarError();      
                exit();   
        
                
        }else{
            $exception->redirigirPorErrorTrabajosEnArchivos();
        }

    //switch        
    } 
    
        
//fin de validarCampos   
 }
    

  
//fin ControlErroresSistemaEnArchivos
}
