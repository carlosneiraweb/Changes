<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Controlador/Validar/MisExcepciones.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Directorios.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/Usuarios.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/DataObj.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Controlador/Validar/ValidoForm.php');  

global $special;
$especial = new MisExcepciones(null, null);

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
     * 
     * @global type $mensaje
     * @global Post $articulo
     * @param type $st
     * @return boolean
     */
   
 public static function validarCamposSubirPost($st){

 global $especial;
 
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
                   
                    $_SESSION['nuevoSubdirectorio'] = Directorios::crearSubdirectorio("../photos/".$_SESSION['userTMP']->getValue('nick'));
                    Directorios::copiarFoto("../photos/demo.jpg",$_SESSION['nuevoSubdirectorio']."/demo.jpg");    
                  
                }

            } catch (MisExcepciones $exc) {
                 $exc->redirigirPorErrorTrabajosEnArchivosSubirPost();
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
                               
                                throw new MisExcepciones(null, null);
                            }
               
            } catch (MisExcepciones $exc) {
                $exc->redirigirPorErrorTrabajosEnArchivosSubirPost();
            }
                
                
                
            try {
                $testDir = Directorios::moverImagen($foto, $destino);
                
                if(!$testDir){
                   
                    throw new MisExcepciones(null, null);
                }
            } catch (MisExcepciones $exc) {
                $exc->redirigirPorErrorTrabajosEnArchivosSubirPost();
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
           
            } catch (MisExcepciones $exc) {
                $exc->redirigirPorErrorTrabajosEnArchivosSubirPost();
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
            $especial->redirigirPorErrorTrabajosEnArchivosSubirPost();
        }

    //switch        
    } 
    
        
//fin de validarCamposSubirPost   
 }
    

 
  /**
     * Metodo que valida los datos introducidos por el usuario al registrarse.
     * Valida los campos con los metodos static de ValidaForm
     * Valida los datos de la bbdd con un objeto de la clase Usuarios
     * @global type $mensaje
     * @global Usuarios $user
     * @param type $st
     * @return boolean
     */
    public static function validarCamposRegistro($st, $user){
    
        global $mensaje;
        $test = true;
        switch ($st){
            case "step1":
            
                //En caso de que exista el nombre de usuario o email
                //Los passwords se repitan o el email sea incorrecto
                    if($user->getByUserName($_SESSION['usuario']['nick'])){
                        $mensaje = ERROR_NOMBRE_USUARIO_EXISTE;
                        $test = false;
                        break;
                    }elseif(!ValidoForm::validarPassword($_SESSION['usuario']['password'])){
                        $mensaje =  ERROR_PASSWORD_INCORRECTO;
                        $test = false;
                        break;
                    }elseif(!ValidoForm::validarIgualdadPasswords($_SESSION['usuario']['password'], $_POST['passReg2'])){
                        $mensaje =  ERROR_IGUALDAD_PASSWORD;
                        $test = false;
                        break;
                    }elseif(!ValidoForm::validarEmail($_SESSION['usuario']['email'])){
                        $mensaje = ERROR_EMAIL_INCORRECTO;
                        $test = false;
                        break;
                    }elseif($user->getByEmailAddress($_SESSION['usuario']['email'])){
                        $mensaje = ERROR_EMAIL_EXISTE;
                        $test = false;
                        break;
                    } 
                 
                return $test;     

                case 'step2':
                
                    if(!ValidoForm::validaTelefono($_SESSION['usuario']['telefono'])){
                        $mensaje =  ERROR_TELEFONO_INCORRECTO;
                        $test = false;
                         break;
                    }
               
             
                return $test;
                   
            case 'step3':
                    
                    if(!ValidoForm::validarCodPostal($_SESSION['usuario']['codPostal'])){
                        
                        $mensaje = ERROR_CODIGO_POSTAL;
                        $test = false;
                         break;
                    }
                        
                return $test;
                
            case 'step4':
            $test = 1;  
               
            //Si el usuario sube una foto para su perfil la validamos
                if(isset($_FILES['photo']) and $_FILES['photo']!= null){
                   
                    $testValidoFoto =  Directorios::validarFoto('photo');  
                    
                        if( $testValidoFoto === 0){  
                         
                            try{
                                //Importante
                                //Recuperamos el nombre del archivo y ruta a la que mover la imagen
                                $destino = '../datos_usuario/'.$_SESSION['usuario']['nick'].'/'.basename($_FILES['photo']['name']);
                                $foto = $_FILES['photo']['tmp_name'];
                              
                                        if($destino == "" || $foto == ""){
                                            throw new MisExcepciones(null, null);
                                        }
                            } catch(MisExcepciones $ex) {
                                $ex->redirigirPorErrorTrabajosEnArchivosAlRegistrarse(); 
                                
                            }
                         
                            try{
                                
                                //El primero donde almacenamos la foto de su perfil, en el futuro guardaremos mas cosas
                                //Si ha ido bien creamos el directorio donde al usuario se le almacenaran las imagenes
                                //de los posts
                                $testPhotos = Directorios::crearDirectorio("../photos/".$_SESSION['usuario']['nick']);
                                //Creamos el directorio con la foto de perfil
                                $testUsuario = Directorios::crearDirectorio("../datos_usuario/".$_SESSION['usuario']['nick']);
                                $testVideos = Directorios::crearDirectorio("../Videos/".$_SESSION['usuario']['nick']);
                                
                                    if((!$testPhotos) || (!$testUsuario) || (!$testVideos)){
                                        throw new MisExcepciones(null, null);
                                    }
                                    
                            } catch (MisExcepciones $ex) {
                                    $ex->redirigirPorErrorTrabajosEnArchivosAlRegistrarse();
                            }catch(Exception $e){
                                echo $e->getCode();
                            }
                            
                            try{
                                 //Se mueve la foto de perfil subida
                                $testMover = Directorios::moverImagen($foto, $destino);
                                    if(!$testMover){
                                        throw new MisExcepciones(null, null);
                                    }
                                    
                            } catch (MisExcepciones $ex) {
                                     $ex->redirigirPorErrorTrabajosEnArchivosAlRegistrarse();
                            }
                            
                            try{
                                 //La renombramos con su nombre
                                $testRenombrar = Directorios::renombrarFoto($destino, $_SESSION['usuario']['nick'], false);
                                    if(!$testRenombrar){
                                        throw new MisExcepciones(null, null);
                                    }
                                
                            } catch (MisExcepciones $ex) {
                                $ex->redirigirPorErrorTrabajosEnArchivosAlRegistrarse();
                            }
         
                  //Si no sube una foto le ponemos la default
        
                } else if ($testValidoFoto == 4) {
                    
                    //Eliminamos la variable error
                    //Por que para registrarse no es obligatorio subir
                    //una imagen del usuario
                    //Esta variable se instancia en el metodo 
                    //validar fotos de la clase Directorios
                    if(isset($_SESSION['error'])){
                        unset($_SESSION['error']);
                    }
                    
                    
                    try{
                          //Si no sube ninguna foto se le asigna la de default
                        $destino = "../datos_usuario/".$_SESSION['usuario']['nick'].'/'.$_SESSION['usuario']['nick'].'.jpg';
                        $testPhotos = Directorios::crearDirectorio("../photos/".$_SESSION['usuario']['nick']);    
                            if(($destino == "") || (!$testPhotos)){
                                //echo ' 4 1 <br>';
                                throw new MisExcepciones(null, null);
                            }
                        
                    } catch (MisExcepciones $ex) {
                            $ex->redirigirPorErrorTrabajosEnArchivosAlRegistrarse();
                    }
                    
                    try{
                        
                        $testDir = Directorios::crearDirectorio("../datos_usuario/".$_SESSION['usuario']['nick']);
                        $testCopy = Directorios::copiarFoto("../datos_usuario/desconocido.jpg", $destino);
                    
                            if((!$testDir) || (!$testCopy)){
                                //echo '4 2 <br>';
                                throw new MisExcepciones(null, null);
                            }
                    } catch (MisExcepciones $ex) {
                            $ex->redirigirPorErrorTrabajosEnArchivosAlRegistrarse();
                    }

                    try{
                        
                        $testCrearDir = Directorios::crearDirectorio("../Videos/".$_SESSION['usuario']['nick']);
                            if(!$testCrearDir){
                                //echo ' 4 3 <br>';
                               throw new MisExcepciones(null, null);
                            }   
                    } catch (MisExcepciones $ex) {
                            $ex->redirigirPorErrorTrabajosEnArchivosAlRegistrarse();
                        
                    }
  
                } else if ($testValidoFoto === 10) {  
                   
                    //Si hay algun error al validar la imagen 
                    //redirigimos a la pagina mostrarError
                        //y le indicamos el motivo del error
                    // Esto ultimo se hace en el switch del
                    //metodo que valida la subida en el directorio Directorios
                    $_SESSION['paginaError'] = 'registrarse.php';
                        mostrarError();      
                        exit();   
                }else{
                    $test = 0;
                }
                
                }

                return $test;
                    break;
                    
                case 'step5':
                    //No hacemos ninguna validacion
                   
                
                
           
   
        }
  //fin validarCamposRegistro      
      }
   

  
//fin ControlErroresSistemaEnArchivos
}
