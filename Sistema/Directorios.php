<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/System.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Controlador/Validar/ControlErroresSistemaEnArchivosUsuarios.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Controlador/Validar/ControlErroresSistemaEnArchivosPost.php');
require_once($_SERVER['DOCUMENT_ROOT']."/Changes/Sistema/Constantes/ConstantesErrores.php");

if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
/**
 * Description de Directorios
 *  Esta clase se encarga de crear, eliminar o mover archivos
 *  en toda la aplicacion
 * @author Carlos Neira Sanchez
 */

 

class Directorios {
   
                    
    
    
        /**
         * Metodo que valida la foto subida por el usuario, </br>
         * como el tamaño, el formato o si ha habido </br>
         * un error en el servidor.</br>
         * @param $foto type String </br>
         * Ruta donde esta almacenada la imagen </br>
         * subida por el usuario </br>
         * @return $test type Boolean </br>
         * Constante de la variable $_FILES </br>
         * Maximo 3M = 3145728 bytes </br>
         */
        
        final static function validarFoto(){
           
           
            
                $test  = $_FILES['photoArticulo']['error'];
                $size =  $_FILES['photoArticulo']['size'];
                $tipo =  $_FILES['photoArticulo']['type'];
            //echo  $_FILES['photoArticulo']['error'];
            //echo  $_FILES['photoArticulo']['size'];
            //echo  $_FILES['photoArticulo']['type'];
           //echo 'test dice '.$test;
                
                
            if($test !== 4){
               
                if($size > '3145728'){
                    $test  = 1;
                   
                }
                if(($tipo != "jpeg") and ($tipo != "jpg") and ($tipo != "image/jpeg")){
                    $test = 10;
                }

            }
            
                switch ($test){
 
                    case 0:
                        $_SESSION['error'] = null;
                        //Todo ha ido bien
                            break;
                    case 1:
                        //Se ha sobrepasado el tamaño
                        //indicado en php.ini
                        $_SESSION['error'] = ERROR_TAMAÑO_FOTO;
                            break;
                    case 2:
                        //Se ha sobrepasado el tamaño
                        //indicado en el formulario
                        $_SESSION['error'] = ERROR_TAMAÑO_FOTO;
                            break;
                    case 3:
                        //El archivo ha subido parcialmente
                        $_SESSION['error'] = ERROR_INSERTAR_FOTO;
                            break;
                       
                    case 4:
                       //No se ha subido ningun archivo
                        $_SESSION['error'] = ERROR_FOTO_NO_ELIGIDA;
                            break;
                        
                   
                    case 10:
                         $_SESSION['error'] = ERROR_FORMATO_FOTO;
                            break;    
                    
                    
                    default:
                       //Otros errores 
                        $_SESSION['error'] = ERROR_FOTO_GENERAL;     
                }
            
               
                    return $test;
           // }   
        //fin validar foto    
        }
    
         /**
        * Metodo que mueve las fotos que el usuario sube </br>
        * de la carpeta temporal del servidor a directorio </br>
        * definitivo.</br>
        * Este metodo es usado tanto para </br>
        * cuando el usuario se registre como </br>
        * cuando el usuario sube un Post.</br>
        * Por lo que en caso de error hay que trabajar de forma</br>
        * distinta.<br/>
        * @param $nombreFoto <br/>
          * type String <br/>
          * El nombre de la imagen a mover <br/>
        * @param $nuevoDirectorio <br/>
          * type String <br/>
          * El nuevo directorio donde mover la imagen <br/>
        * @param  $opc <br/>
          * opcion en caso de error <br/>
         */
        final static  function moverImagen($nombreFoto, $nuevoDirectorio, $opc){
            
            if($opc == "subirImagenPost"){
                $mensaje = "No se pudo mover la imagen al subir un post";
            }else if($opc == "registrar" ){
                $mensaje = "No se pudo mover la imagen al registrarse.";
            }else if($opc == "actualizar" ){
                $mensaje = "No se pudo mover la imagen al actualizar.";
            }
            
            try{

                if(!move_uploaded_file($nombreFoto, $nuevoDirectorio)){
                    
                  throw new Exception($mensaje, 0);  
                }
           

            } catch (Exception $ex) {
                   
                $excepciones = new MisExcepciones(CONST_ERROR_MOVER_IMAGEN[1],CONST_ERROR_MOVER_IMAGEN[0],$ex);      
                
                   
                    //En caso error se llama al metodo redirigirPorErrorTrabajosEnArchivosRegistro
                    //De la clase mis excepciones con la opcion adecuada
                    if($opc == "actualizar"){
                        //$excepciones->redirigirPorErrorSistema($opc,true);    
                    }
                    if($opc == "registrar"){
                        $excepciones->redirigirPorErrorSistema($opc,true);  
                    }
                    if($opc == "subirImagenPost"){
                         $_SESSION['error'] = ERROR_INSERTAR_ARTICULO;
                        $excepciones->eliminarDatosErrorAlSubirPost("errorPost",true);
                    }
                 
                 
            }

        //fin mover imagen    
        }
        
        
        
        
        /**
         * Metodo que recibe una ruta y crea un directorio </br>
         * @param $ruta  type String </br>
         * Ruta donde crear el directorio </br>
         * @param $opc type String </br>
         * Opcion para tratar posibles errores </br>
         * @return  $test type Boolean </br>
         * 
         * 
         */
        final static function crearDirectorio($ruta,$opc){
            //echo $ruta;
            
            
            try{
                //Comprobamos que los directorios ya no existan
                if(file_exists($ruta) || (!mkdir($ruta))){
                    
                    throw new Exception("Error al crear los directorio registro",0);
                    
                }
              
            }catch(Exception $ex){
                $excepciones = new MisExcepciones(CONST_ERROR_CREAR_DIRECTORIO[1],CONST_ERROR_CREAR_DIRECTORIO[0],$ex);
                
                $_SESSION['error'] = ERROR_INGRESAR_USUARIO;  
                 
                
                if($opc == 'registrar'){
                    
                   $excepciones->redirigirPorErrorSistema($opc,true);
                }
                 
                
        }
       
        //fin de crear directorio 
        }
        
         /**
          * Metodo que cuenta el numero de subdirectorios </br>
          * que tiene un usuario. Se utiliza a la hora de crear</br>
          * un nuevo POST.</br>
          * Los directorios tienen nombre consecutivo.</br>
          * Se calcula el total de subdirectorios y se le suma uno para el siguiente.</br>
          * OJO se vigila que al borrar un POST el directorio</br>
          * que contenia sus imagenes vuelva a ser asignado. Ya que sino</br>
          * habría un error al asignar uno nuevo.</br>
          * @param $usuario  </br>
          * type String</br>
          * Nombre del usuario </br>
          * 
          * IMPORTANTE
          * Como PHP trata los errores diferente a las Excepciones</br>
          * Tenemos que usar THROWABLE para lanzar una excepcion.</br>
          */
            
final static function crearSubdirectorio($usuario, $opc){
        
        $excepciones =  new MisExcepciones(CONST_ERROR_CREAR_SUBDIRECTORIO_POST[1], CONST_ERROR_CREAR_SUBDIRECTORIO_POST[0]);  
        
        try{
            
            $dir = $usuario;
            $count = 0;
            $nuevoDirectorio;
            $test = true; //Bandera para saber cuando se crea el subdirectorio
            //Para saber que se ha borrado un directorio
            //y se asigna a otro post
            $testSalir = true;
           
            if(!is_dir($dir)){
                throw new Exception("El directorio pasado para crear el subdirectorio no existe",0);
            }
            
            $handle = opendir($dir);
             
                if(!$handle){
                    throw new Exception("Al crear el subdirectorio el manejador no pudo abrir el directorio",0);
                    
                }
             
            
             
                while($file = readdir($handle)){
                    //Limpia la cache al poder
                    //ser usado el directorio por el mismo script
                   // clearstatcache(); 
                                    
                    if($file != "." && $file != ".." ){
                         $count++;
                       if(is_dir($usuario.'/'.$file) and file_exists($usuario.'/'.$count)){
                           //Este directorio ya existe y saltamos
                           continue;
                           //En el caso que un subdirectorio halla sido borrado al eliminar un POST
                        } else if(is_dir($usuario.'/'.$file) and !file_exists($usuario.'/'.$count)){
                            $test = mkdir($usuario.'/'.$count); 
                            $nuevoDirectorio = $count;
                            $testSalir = false; //Cambiamos la bandera para que no se cree uno al final
                            break;
                        }
                 
                    }
                }
            //Sino ha sido borrado ninguno se suma uno al total de subdirectorios y se crea    
            if ($testSalir) {
            
            $nuevo = $count + 1;
            
            $test = mkdir($usuario.'/'.$nuevo) ? true : false; 
            $nuevoDirectorio = $nuevo; 
            
            }
            
           
            if(!$test){           
                 
                 throw new Exception("No se pudo crear el nuevo subdirectorio con mkdir",0);
                 
            }
        
          
            return $nuevoDirectorio;
            
            
        }catch(Exception $ex){
            
            $excep = $excepciones->recojerExcepciones($ex);
            $_SESSION['error'] = ERROR_INSERTAR_ARTICULO;
            $excepciones->eliminarDatosErrorAlSubirPost("errorPost",true,$excep);
            
            
        }
        //fin crearSubdirectorio
       
        }    
            
        /**
         * Metodo que copia una imagen </br>
         * de un directorio a otro </br>
         * @param $imagen </br>
         * type String</br>
         * Nombre de la imagen a copiar</br>
         * @param $destino </br>
         * type String </br>
         * Destino donde copiar la imagen </br>
         * @param $opc </br>
         * type String </br>
         * Opcion para tratar posibles errores </br>
         */   
            
        final static function copiarFoto($imagen, $destino,$opc){
           
        
        if($opc == "registrar"){ 
            $mensaje = "No se pudo copiar imagen al registrarse un usuario";   
        }else{
            $mensaje = "No se pudo copiar imagen Demo al registrar un Post";
        }   
            try{
                
               if(!copy($imagen, $destino)){throw new Exception($mensaje,0);}
               
            } catch (Exception  $ex) {
              
                $_SESSION['error'] = ERROR_INGRESAR_USUARIO;
                $excepciones = new MisExcepciones(CONST_COPIAR_ARCHIVO[1], CONST_COPIAR_ARCHIVO[0],$ex);   
                
                if($opc == "registrar"){
                    $excepciones->redirigirPorErrorSistema($opc,true);
                }else if($opc == "copiarDemoSubirPost"){
                   
                   $excepciones->eliminarDatosErrorAlSubirPost("errorPost",true);
                }
            }
            
          
        //fin copiarFoto    
        }
        
/**
 * Metodo que elimina 
 * la imagen demo que se copia
 * al subdirectorio
 * cuando un usuario sube
 * un post
 */

public function eliminarImagenDemoSubirPost(){
    
    if(is_file($_SESSION['nuevoSubdirectorio'].'/demo.jpg')){
        
        unlink($_SESSION['nuevoSubdirectorio'].'/demo.jpg');      
        
        
    }
                     
  //fin eliminarImagen  
}      
            
       /**
        * OJO ESTE METODO ES IMPORTANTE  LEER    </br>
        *     
        *       Este metodo se utiliza para: </br>
        * 
        * 1º Si este metodo recibe como segundo parametro un 0. </br>
        *   Si ocurre esto es que el usuario al subir imagenes para un Post a </br>
        *   eliminado una imagen o varias. Entonces lo que sucede es que accedemos </br>
        *   a un array con el nombre de la imagen borrada dentro de la variable </br>
        *   " $_SESSION['imgTMP']['imagenesBorradas'] " instanciada en la clase Post. </br>
        *   Lo que hacemos es ir recuperando su nombre y vamos asignando ese nombre  </br>
        *   a las fotos que el usuario va subiendo. </br>
        * 
        * 2º Si como segundo parametro recibe  un 1 </br>
        * 
        *    Si ocurre esto es que subiendo imagenes para el Post no ha borrado ninguna. </br>
        *    Entonces lo unico que hace es contar el total de imagenes que hay en el </br>
        *    directorio donde se guardan en cada Post. </br>
        * 
        *  
        * @param  $nombreViejo  </br>
        * type String </br>
        * Nombre tal cual el usuario sube una imagen al subir un Post </br>
        * @param  $opc </br>
        * type String </br>
        * Nombre renombrado de la imagen </br>
        * @return $newNombre </br> 
        * type String </br>
        * El nuevo nombre </br>
        */
        public static function renombrarFotoSubirPost($nombreViejo, $opc){

             $excepciones = new MisExcepciones(CONST_ERROR_RENOMBRAR_IMG_AL_SUBIR_UN_POST[1],CONST_ERROR_RENOMBRAR_IMG_AL_SUBIR_UN_POST[0]);
            
            if($opc === 0){
            
                
                
                try{
                    
                //Extraemos del array de imagenes borradas el ultimo elemento   
                $ultimaImagenBorrada = array_pop($_SESSION['imgTMP']['imagenesBorradas']);
                    
                //Nos quedamos con el numero de la imagen, tipo admin/2/4 => 4 
                //este numero es el que interesa, y que es el nombre de la imagen + .jpg
                //explode nos devuelve un array de strings
                //como limitados el caracter pasado
                $tmp = explode('/', $ultimaImagenBorrada );
                $newNombre = '../photos/'.$tmp[0].'/'.$tmp[1].'/'.$tmp[2].'.jpg';
                
                $test = rename($nombreViejo, $newNombre) ? true : false; 

                if(!$test){throw new Exception("Error al renombrar una imgen cuando el usuario elimino alguna",0);}

                //Controlamos que el array de Imagenes borradas aun contenga imagenes.
                    //Si hemos ingresado el primer elemento, destruimos la variable de
                    //Sesion para que el programa vuelva a funcionar 
                    //como si el usuario no hubiera borrado ninguna imagen
                //echo 'imgTmp en Directorios '.$_SESSION['imgTMP']['imagenesBorradas'][0].'<br>';
                
                    if(count($_SESSION['imgTMP']['imagenesBorradas']) == 0){    
                        unset($_SESSION['imgTMP']);
                    }

                        //Devolvemos el nuevo nombre para ser ingresado si el usuario sube una nueva imagen
                        return $newNombre;
            
                
                }catch(Exception $ex){
                    $excep = $excepciones->recojerExcepciones($ex);
                    $_SESSION['error'] = ERROR_INSERTAR_ARTICULO;
                    $excepciones->eliminarDatosErrorAlSubirPost("errorPost",true, $excep);
                }

            }else if($opc === 1){
                    
               
                //echo 'el usuario no ha borrado ninguna imagen<br>';
                //Renombramos las imagenes por el numero de imagenes en su subdirectorio
                
                $nombreRenombrado= Directorios::contarArchivos('../photos/'.$_SESSION['nuevoSubdirectorio'][0].'/'.$_SESSION['nuevoSubdirectorio'][1]);
                                
                try{
                    
                if($opc){
                 // echo 'nombre viejo '.$nombreViejo.'<br>';
                   $original = basename($nombreViejo); //quedando en => indice.jpg
                   
                   //strstr con parametro true devuelve 
                      //un string al encontrar la primera aparicion del string
                   $tmp = strstr($nombreViejo, $original, true);//OJO
               
                   //En este paso nos quedamos con la parte del directorio
                    // echo 'Nombre temporal '.$tmp.'<br>';
                   $newNombre = $tmp.$nombreRenombrado.'.jpg';
                  // echo 'nuevo nombre '.$newNombre;
                    //Le asignamos el nuevo nombre a la parte del directorio substraida antes
                        
                   $test = rename($nombreViejo, $newNombre) ? true : false;
                  
                   if(!$test){throw new Exception("No se pudo renombrar la imagen subiendo Post. No se había eliminado ninguna imagen.",0);}
                   
               }
            
            
                return $newNombre;
                
            } catch (Exception $ex) {
                $_SESSION['error'] = ERROR_INSERTAR_ARTICULO;
                $excep = $excepciones->recojerExcepciones($ex);
                $excepciones->eliminarDatosErrorAlSubirPost("errorPost",true,$excep);
                
            }
                
           

            }
           
            
        //fin renombrarImagenes
        }
        
/**
 * 
 * Este metodo elimina de la ruta de la imagen </br>
 * que sube el usuario y la cambia por su usuario </br>
 * Ejemplo: </br>
 * /Sistema/tmp/1254.jpg </br>
 * Cambia el 1254.jpg por el nombre del usuario </br>
 * y la ruta donde queremos que se guarde </br>
 * /photos/jose/jose.jpg </br>
 * 
 * @param $nombreViejo </br>
 * type  String </br>
 * Es la ruta tal como es subida la foto la servidor </br>
 * @param  $nombreNuevo  </br>
 * type String </br>
 * Es el nombre del usuario </br>
 */        
        
public static function renombrarFotoPerfil($nombreViejo, $nombreNuevo){
    
    
    try{
        
        //Si el metodo recive un nombre nuevo se le asigna ese nombre. Esto ocurre
                    //cuando se registra un usuario y sube una foto de perfil
        $nombreRenombrado = $nombreNuevo;
            //datos_usuario/admin/indice.jpg
        //basename nos devuelve la ultima parte de la direccion del archivo
        //localhost/fotos/indice.jpg
        //quedando en => indice.jpg
        $original = basename($nombreViejo); 
        
        //OJO TRUE NOS DEVUELVE LA PARTE DE LA DERECHA DE LA COINCIDENDIA EN EL STRING
        $tmp = strstr($nombreViejo, $original, true);
            //En este paso nos quedamos con la parte del directorio
            //datos_usuario/admin/
            //echo 'Nombre temporal '.$tmp.'<br>';
            $newNombre = $tmp.$nombreRenombrado.'.jpg';
            //Le asignamos el nuevo nombre a la parte del directorio substraida antes
            //datos_usuario/aaaaa/aaaaa.jpg
            //echo 'Nombre nuevo es: '.$nuevoNombre.'<br>';
            if(!rename($nombreViejo, $newNombre)){
                throw new Exception("Error al renombrar la imagen del usuario al registrarse",0);
            }
 
    } catch (Exception $ex) {
        $excepciones = new MisExcepciones(CONST_ERROR_RENOMBRAR_IMG_REGISTRARSE[1], CONST_ERROR_RENOMBRAR_IMG_REGISTRARSE[0],$ex);    
        $_SESSION['error'] = ERROR_INGRESAR_USUARIO;
        $excepciones->redirigirPorErrorSistema("registrar",true);
    }
    
    
    
//fin renombrarFotoPerfil    
}
    
    /**
     * Metodo que cuenta el numero de archivos de un </br>
     * directorio. </br>
     * @param $ruta </br>
     * type String </br>
     * Ruta al directorio donde contar los archivos </br>
     * 
     */
    final static function contarArchivos($ruta){
        
        $excepciones = new MisExcepciones(CONST_ERROR_CONTAR_ARCHIVOS[1],CONST_ERROR_CONTAR_ARCHIVOS[0]);
        $count = 0;
        $dir =  $ruta;
    
    try{
        
        if(!($handle = opendir($dir))){throw new Exception('Hubo un error al contar los directorios.',0);}

                    while($file = readdir($handle)){
                        if($file != "." && $file != ".."){
                                $count++;
                        }
                    }

            return $count;
        
        
    } catch (Exception $ex) {
        $excep = $excepciones->recojerExcepciones($ex);
        $excepciones->eliminarDatosErrorAlSubirPost("Hubo un problema al contar los archivos",true,$excep);
    }
   
    //fin contarArchivos    
    }
    
    
    /**
     * Metodo que elimina una imagen </br>
     * recive como parametro la ruta. </br>
     * Este metodo es usado tanto para </br>
     * cuando el usuario se registre como </br>
     * cuando el usuario sube un Post.</br>
     * Por lo que en caso de error hay que trabajar de forma </br>
     * distinta. </br>
     * 
     * @param  $ruta </br>
     * type String </br>
     * Ruta de la imagen a eliminar </br>
     * @param $opc </br>
     * type String <br/>
     * Opcion para trabajar en caso de error </br>
     */ 
    final static function eliminarImagen($ruta, $opc){
        //echo $ruta;
       
        try{
     
                if(!unlink($ruta)){throw new Exception("No se pudo eliminar la imagen",0);}
            
        } catch (Exception $ex) {
             $excepciones =  new MisExcepciones(CONST_ERROR_ELIMINAR_ARCHIVO[1], CONST_ERROR_ELIMINAR_ARCHIVO[0],$ex);    
            if($opc == 'actualizar'){
                $excepciones->redirigirPorErrorSistema("actualizar",true);
            }else if($opc == "eliminarImgDemoSubirPost"){
                $_SESSION["error"]= ERROR_INSERTAR_ARTICULO;
                $excepciones->eliminarDatosErrorAlSubirPost("errorPost",true);   
            }else if($opc == "eliminarImagenSubiendoPost"){
                $_SESSION["error"]= ERROR_INSERTAR_ARTICULO;
                $excepciones->redirigirPorErrorSistema("eliminarImagenSubiendoPost", true);
                
            }
           
            
        }
    
//fin eliminar imagen    
}

/**
 * Metodo que elimina los directorios creados <br />
 * cuando hay un error al registrarse o cualquier otro motivo.<br />
 * Si la bbdd no hace el insert correcto ente metodo <br />
 * elimina las carpetas creadas en datos_usuario y photos<br />
 * Recive una ruta con el directorio a eliminar.<br />
 *  glob() busca todos los nombres de ruta que coinciden con pattern<br />
 * @param $src <br />
 * type String <br />
 * Ruta donde estan los directorios que hay que eliminar
 */

final static function eliminarDirectoriosSistema($src,$opc){
    
    $excepciones = new  MisExcepciones(CONST_ERROR_ELIMINAR_DIRECTORIO[1],CONST_ERROR_ELIMINAR_DIRECTORIO[0]);
    
        try{
            
            //Nos aseguramos recive rutas de directorios
   
        if(is_dir($src)){
       
            
          
        
                foreach(glob($src . "/*") as $archivos_carpeta)
                {   
                    if (is_dir($archivos_carpeta))
                    {
                        Directorios::eliminarDirectoriosSistema($archivos_carpeta,$opc);   
                    }else{
                        unlink($archivos_carpeta);
                    }
                            
                            
                    }
                
                    if(is_dir($src)){

                        if(!rmdir($src)){
                            
                            throw new Exception("NO se pudo eliminar el directorio",0);
                            
                        }

                                
                                
                        }
                    }
        
    
        } catch (Exception $ex) {
            
            $excep = $excepciones->recojerExcepciones($ex);
          
            if($opc == "actualizar"){
                $excepciones->redirigirPorErrorSistema("actualizar",true,$excep);
            }else if($opc == "registrar"){
                $excepciones->redirigirPorErrorSistema("registrar",true,$excep);
            }
             
           
        }
        
        

//eliminarDirectorioRegistro    
}



/**
 * Metodo que actualia el nombre 
 * de la foto de perfil del usuario
 * al actualizar.
 * <br>
 * 
 * Ejemplo: <br>
 *  "../datos_usuario/$nombreViejoDirectorio/$nombreViejoFoto.jpg"
 * <br>
 * @param $viejoNombre 
 * El nombre del viejo directorio
 * Nombre de usuario tenia antes de actualizar <br>
 * @param $nuevoNombre <br>
 * type String <br>
 * Nombre del usuario actualizado <br>
 * <br>
 * @param  $opc <br>
 * Cadena para mensaje en caso de error
 */
public static function renombrarFotoActualiazar($viejoNombre,$nuevoNombre){
    
   
    try {

        if(!rename($viejoNombre, $nuevoNombre)){
            throw new Exception("No se pudo renombrar la foto al actualizar",0);
        }
           
   
    } catch (Exception $ex) {
         
        $excepciones =   new MisExcepciones(CONST_ERROR_RENOMBRAR_FOTO_ACTUALIZARSE[1], CONST_ERROR_RENOMBRAR_FOTO_ACTUALIZARSE[0],$ex);
        $excepciones->redirigirPorErrorSistema("actualizar",true);
        
    }

//fin renombrarFotoActualiazar()    
}

/**
 * Metodo que copia directorios con su contenido. </br>
 * Si se produce un error al copiar los directorios </br>
 * del usuario al actualizar, elimina los directorios </br>
 * que se hayan creado en el directorio TMP_ACTUALIZAR. </br>
 * @param  $src 
 * type String
 *  directorio que copiar
 * @param  $dst 
 * type String
 *  Directorio destino
 * @param opc
 * type String
 * Opcion en caso de error
 */

public static function copiarDirectorios($src,$dst,$opc) {
    
    $excepciones = new MisExcepciones(CONST_ERROR_CREAR_DIRECTORIO[1], CONST_ERROR_CREAR_DIRECTORIO[0]);
    try{
                //Comprobamois que existe el directorio
                if(!is_dir($src)){throw new Exception("El directorio pasado donde copiar los directorios a TMP no existe",0);}               
                             
                //Comprobamos que ya no exista
                if(!is_dir($dst)){
                    //Comprobamos que se puede abrir
                    if(!$dir = opendir($src)){throw new Exception("No se pudo abrir el directorio a copiar TMP",0);}
                    //Comprobamos que se pueden copiar 
                    if(!mkdir($dst)){
                        throw  new Exception("NO se pudo copiar el directorio en la carpeta TMP",0);
            
                    }else{
                        
                        while(false !== ( $file = readdir($dir)) ) { 
                            if (( $file != '.' ) && ( $file != '..' )) { 
                                if ( is_dir($src . '/' . $file) ) { 
                                    
                                    self::copiarDirectorios($src . '/' . $file,$dst . '/' . $file, $opc); 
   
                                } 
                                else { 
                                   
                                    $test =  copy($src . '/' . $file,$dst . '/' . $file);
                                    
                                        if(!$test){
                                            throw new MisExcepciones(CONST_COPIAR_ARCHIVO[1], CONST_COPIAR_ARCHIVO[0]);
                                        
                                        }
                                } 
                            } 
                        } 
                        closedir($dir); 
                    }

                }else{           
                    throw new Exception("Ya existia el directorio pasado asado en la carpeta TMP",0);                         
                }

    } catch (Exception $ex){ 
         $excep = $excepciones->recojerExcepciones($ex);
        if($opc == "actualizar" ){        
            $excepciones->redirigirPorErrorSistema("actualizar",true,$excep); 
        }
        if($opc == "copiarDirectorios_a_TMP_actualizar"){
            $excepciones->redirigirPorErrorSistema($opc,true,$excep);
        }
        if($opc == "RestaurarArchivosTMP"){    
            $excepciones->redirigirPorErrorSistema("Error al restaurar los directorios del usuario que estaban en la carpeta TMP",true,$excep); 
        }
        if($opc == "renombrarFotoActualizar"){
            $excepciones->redirigirPorErrorSistema("restaurarAntiguosDirectorios",true,$excep);
        }
        if($opc == "restaurarViejosDirectoriosActualizar"){
            $excepciones->redirigirPorErrorSistema("No se pudo restaurar los directorios que tenia el usuario",true,$excep);
        }
        
          
    }
//fin copiarDirectoros    
} 

/**
 * Este metodo crea el directorio padre <br /> 
 * donde se guardaran la copias de los directorios <br /> 
 * con los datos del usuario. <br /> 
 * @param String $dir 
 * @Description Nick del usuario ya registrado
 */
public static function crearDirectorioPadreTMP($dir){
   
    $excepciones =  new MisExcepciones(CONST_ERROR_CREAR_DIRECTORIO_PADRE_TMP[1], CONST_ERROR_CREAR_DIRECTORIO_PADRE_TMP[0]);
    try {
       
            $test = mkdir($dir);
            
            if(!$test){
                throw new Exception("Hubo un problema al crear los directorios TMP al actualizar. Posiblememnte ya existia",0);
            }

    } catch (Exception $ex) {
        $excep = $excepciones->recojerExcepciones($ex);
        $excepciones->redirigirPorErrorSistema("crearDirectorios_TMP",true,$excep);
    }



    //FIN crearDirectorioPadreTMP
}




/**
 * Metodo que recive los datos introducidos 
 * por el usuario en caso de error para poder
 * Ademas de los los datos de los fallos, tanto
 * de la bbdd y de los archivos creados
 */
final public function escribirErrorValidacion(DataObj $obj, $mensaje,$repEliminarDatosUsuario, $repEliminarPhotos, $repEliminarVideos){
    $test = 1;
    
 
    try{
         $cuerpoMensaje = '
            ******************************************************
            Nueva entrada Con fecha:'. FECHA_DIA. ' Ha habido un problema de registro de un usuario.'.PHP_EOL.'
            El error es: '.$mensaje. ''.PHP_EOL.'
            La IP del visitante es: '.System::ipVisitante().'
            Los datos intruducidos por el usuario son: '.PHP_EOL.'
            
            nombre =  '.$obj->getValue("nombre").''.PHP_EOL.'
            1º Apellido: = '.$obj->getValue("apellido_1").''.PHP_EOL.'
            2º Apellido = '.$obj->getValue("apellido_2").''.PHP_EOL.'
            calle = '.$obj->getValue("calle").''.PHP_EOL.'
            numero del Portal = '.$obj->getValue("numeroPortal").''.PHP_EOL.'
            puerta = '.$obj->getValue("ptr").''.PHP_EOL.'
            ciudad = '.$obj->getValue("ciudad").''.PHP_EOL.'
            codigo Postal = '.$obj->getValue("codigoPostal").''.PHP_EOL.'
            provincia = '.$obj->getValue("provincia").''.PHP_EOL.'
            telefono = '.$obj->getValue("telefono").''.PHP_EOL.'
            pais = '.$obj->getValue("pais").''.PHP_EOL.'
            genero = '.$obj->getValue("genero").''.PHP_EOL.'
            email = '.$obj->getValue("email").''.PHP_EOL.'
            nick = '.$obj->getValue("nick").''.PHP_EOL.'
            password = '.$obj->getValue("password").''.PHP_EOL.'
            pais = '.$obj->getValue("pais"). ''.PHP_EOL;
            if (!$repEliminarDatosUsuario || !$repEliminarPhotos){
               $cuerpoMensaje .= "Ha habido un fallo al eliminar los directorios datos_usuario y photos de este usuario. El error dice: ".PHP_EOL;
                    if($repEliminarDatosUsuario){
                        $cuerpoMensaje .= "Ha sido eliminada la carpeta datos_usuario.".PHP_EOL;         
                    }else{
                        $cuerpoMensaje .= "No ha sido eliminada la carpeta datos_usuario.".PHP_EOL;      
                    }
                    if($repEliminarPhotos){
                        $cuerpoMensaje .= "Ha sido eliminada la carpeta Photos de este usuario.".PHP_EOL;         
                    }
                    if($repEliminarVideos){
                        $cuerpoMensaje .= "Ha sido eliminada la carpeta En Videos de este usuario.".PHP_EOL;         
                    
                    }else{
                        $cuerpoMensaje .= "No ha sido eliminada la carpeta Photos de este usuario.".PHP_EOL;      
                    }
            } else{
                
              $cuerpoMensaje .= "Los directorios de Datos_usuario y Photos se han elimanado.".PHP_EOL; 
            }
            
        if(!($archivo = fopen(TXT_ERROR_VALIDACION, 'a'))) die("No se puede abrir el archivo");
           $testEscribir =  fwrite($archivo, $cuerpoMensaje. PHP_EOL);
           $testCerrar =  fclose($archivo);
           if((!$testEscribir) || (!$testCerrar)){
                $test = false;
           }
           
           return $test;
    }catch(Exception $ex){
        
        echo "Error al abrir y escribir en el archivo.".$ex->getCode();
        echo "Error al abrir y escribir en el archivo.".$ex->getMessage();
        echo "Error al abrir y escribir en el archivo.".$ex->getLine();
    }
    
    
    
//escribirErrorValidacion   
}


/**
 * Metodo que escribe el error al intentar 
 * eliminar un post 
 * @param type array()
 * @return type boolean
 */

 public function errorEliminarPost($datos) {
    
        $mensaje = "Nueva entrada Con fecha:". FECHA_DIA.PHP_EOL; 
        $mensaje .= "Parece ser que el usuario ".$datos[0]. " No ha acabado de publicar".PHP_EOL;
        $mensaje.= "Un Post y el sistema ha tenido algun problema al querrer eliminar ".PHP_EOL;
        $mensaje .= "los datos introducidos.".PHP_EOL;
        
        
        if($datos[1] != null){$mensaje.= " El id del Post a eliminar era ".$datos[1].PHP_EOL;}
        if($datos[2] != null){$mensaje.= " El Directorio a eliminar era ".$datos[2].PHP_EOL;}
        if($datos[3] != null){$mensaje.= " Ha habido un problema al eliminar imagenes id de las imagenes ".$datos[3].PHP_EOL;}
        if($datos[4] != null){$mensaje .= "Palabras queridas ".$datos[4].PHP_EOL;}
        if($datos[5] != null){$mensaje .= "Palabras ofrecidas ".$datos[5].PHP_EOL;}
        $mensaje .= PHP_EOL;
        $mensaje .= "***********************************************".PHP_EOL;
        $mensaje .= PHP_EOL;
    
 
    
    
    
    if(!($archivo = fopen(TXT_ERROR_ELIMINAR_POST, 'a'))) die("No se puede abrir el archivo");
           $test =  fwrite($archivo, $mensaje. PHP_EOL);
           $test =  fclose($archivo); 
           return $test;
    
//fin errorEliminarPost    
}




//fin Directorios   
}
