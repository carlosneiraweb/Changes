<?php

/**
 * Description of Sistema
 *  Esta clase se encarga de crear, eliminar o mover archivos
 *  en toda la aplicacion
 * @author Carlos Neira Sanchez
 */
class Directorios {
   
    
        /**
         * Metodo que valida la foto subida por el usuario, 
         * como el tamaño, el formato o si ha habido
         * un error en el servidor
         */
        
        final static function validarFoto($foto){
           
            $test = true;
            if(isset($_FILES[$foto]) and $_FILES[$foto]['error'] == UPLOAD_ERR_OK){
               
                if($_FILES[$foto]['type'] != 'image/jpeg'){
                    $_SESSION['error'] = ERROR_FORMATO_FOTO;
                    $test = false;
                    
                }
               
            }else{
                
                switch ($_FILES[$foto]['error']){
                    case 1:
                        $_SESSION['error'] = ERROR_TAMAÑO_FOTO;
                        $test = false;
                        
                            break;
                    case UPLOAD_ERR_FORM_SIZE:
                        $_SESSION['error'] = ERROR_TAMAÑO_FOTO;
                        $test = false;
                        
                            break;
                    case UPLOAD_ERR_NO_FILE:
                        $_SESSION['error'] = ERROR_FOTO_NO_ELIGIDA;
                        $test = false;
                        
                            break;
                    default:
                        $_SESSION['error'] = ERROR_FOTO_GENERAL;
                        $test = false;
                        
                }
               
            }    
            
            return $test;
           
        //fin validar foto    
        }
    
         /**
         * Metodo que mueve las fotos que el usuario sube
         * de la carpeta temporal del servidor a directorio 
         * definitivo.
         */
        final static  function moverImagen($nombreFoto, $nuevoDirectorio){
            $test = null;
            //echo 'moverImagen nombre Imagen foto: '.$nombreFoto.'<br>';
            //echo 'moverImagen nuevoDirectorio al que mover: '.$nuevoDirectorio.'<br>';
         try{
             $test = move_uploaded_file($nombreFoto, $nuevoDirectorio);
              return $test;  
         } catch (Exception $ex) {
             $_SESSION['error'] = ERROR_FOTO_GENERAL;
             echo $ex->getCode();
              return $test;  
         }
           
        //fin mover imagen    
        }
        
        
        
        
        /**
         * Metodo que recibe una ruta y crea un directorio
         * @param type $ruta
         */
        final static function crearDirectorio($ruta){
       
         $test = true;
         try{
             //Comprobamos que los directorios ya no existan
            if (file_exists($ruta)) {
               $_SESSION['error'] = ERROR_INSERTAR_ARTICULO;
               $test = false;
            }  else {
               $test = mkdir($ruta);
            }
            //echo "crear directorio dice: ".$test.'<br />';
            return $test;
         }catch(Exception $ex){
            $test = false;
            echo $ex->getMessage();
            return $test;
         }

        //fin de crear directorio 
        }
        
         /**
          * Metodo que cuenta el numero de subdirectorios
          * que tiene un usuario. Se utiliza a la hora de crear
          * un nuevo POST.
          * Los directorios tienen nombre consecutivo.
          * Se calcula el total de subdirectorios y se le suma uno para el siguiente.
          * OJO se vigila que al borrar un POST el directorio
          * que contenia sus imagenes vuelva a ser asignado. Ya que sino
          * habría un error al asignar uno nuevo.
          */
            
        final static function crearSubdirectorio($usuario){
          
        try{
            $dir = $usuario;
            $count = 0;
            $nuevoDirectorio;
            $test = true; //Bandera para saber cuando se crea el subdirectorio
            
            if(!($handle = opendir($dir))) die("Cannot open $dir.");
             
                while($file = readdir($handle)){
                    clearstatcache();
                                    
                    if($file != "." && $file != ".." ){
                         $count++;
                       if(is_dir($usuario.'/'.$file) and file_exists($usuario.'/'.$count)){
                           //Este directorio ya existe y saltamos
                           continue;
                           //En el caso que un subdirectorio halla sido borrado al eliminar un POST
                        } else if(is_dir($usuario.'/'.$file) and !file_exists($usuario.'/'.$count)){
                            mkdir($usuario.'/'.$count); 
                            $nuevoDirectorio = $usuario.'/'.$count;
                            $test = false; //Cambiamos la bandera para que no se cree uno al final
                            break;
                        }
                 
                    }
                }
            //Sino ha sido borrado ninguno se suma uno al total de subdirectorios y se crea    
            if ($test) {
            
            $nuevo = $count + 1;
            mkdir($usuario.'/'.$nuevo); 
            $nuevoDirectorio = $usuario.'/'.$nuevo;              
            }
           
            //echo 'Nuevo Subdirectorio creado en el metodo crearsubdirectorio: '.$nuevoDirectorio.'<br>';
            //el nuevo subidrectorio creado siempre es: usuario/total subdirectorio => admin/1
            return $nuevoDirectorio; 
        }catch(Exception $ex){
            $_SESSION['error'] = ERROR_INSERTAR_ARTICULO;
            echo $ex->getMessage();
        }
        //fin crearSubdirectorio

        }    
            
        /**
         * Metodo que copia una imagen 
         * de un directorio a otro
         * 
         */   
            
        final static function copiarFoto($imagen, $destino){
            //echo 'imagen a copiar: '.$imagen.'<br>';
            //echo 'en copiar foto: '.$destino.'<br>';
           
            try{
               $test =  copy($imagen, $destino);
               return $test; 
            } catch (Exception $ex) {
                $_SESSION['error'] = ERROR_INSERTAR_FOTO;
                echo $ex->getMessage();
                return $test; 
            }
            
          
        //fin copiarFoto    
        }
            
       /**
        * OJO ESTE METODO ES IMPORTANTE COMPRENDERLO
        * 
        *       Este metodo se utiliza para:
        * 1º Si este metodo recibe como segundo parametro un 0
        *   Si ocurre esto es que el usuario al subir imagenes para un Post a
        *   eliminado una imagen o varias. Entonces lo que sucede es que accedemos
        *   a un array con el nombre de la imagen borrada dentro de la variable 
        *   " $_SESSION['imgTMP']['imagenesBorradas'] " instanciada en la clase Post.
        *   Lo que hacemos es ir recuperando su nombre y vamos asignando ese nombre 
        *   a las fotos que el usuario va subiendo.
        * 
        * 2º Si como segundo parametro recibe  un 1
        *    Si ocurre esto es que subiendo imagenes para el Post no ha borrado ninguna.
        *    Entonces lo unico que hace es contar el total de imagenes que hay en el
        *    directorio donde se guardan en cada Post.
        * 
        *  3º El usuario se registra y le cambiamos el nombre de la foto que
        *       ha subido por el nick que tiene, de esta forma sabemos que nombre
        *       va a tener esa foto. Podemos mostrarla en la web sin consultar la bbdd
        *       En este caso se comprueba que el segundo parametro no es ni true ni false.
        * @param type $nombreViejo
        * @param type $nombreNuevo
        * @return string
        */
        final static function renombrarFoto($nombreViejo, $nombreNuevo){
            
            if($nombreNuevo === 0){
                try{
                    
                //Extraemos del array de imagenes borradas el ultimo elemento   
                $ultimaImagenBorrada = array_pop($_SESSION['imgTMP']['imagenesBorradas']);
                    
                //Nos quedamos con el numero de la imagen, tipo admin/2/4 => 4 
                //este numero es el que interesa, y que es el nombre de la imagen + .jpg
                //explode nos devuelve un array
                $tmp = explode('/', $ultimaImagenBorrada );
                
                //OJO este parametro esta en la segunda posicion
                $newNombre = $_SESSION['nuevoSubdirectorio'].'/'.$tmp[2].'.jpg';
               
                rename($nombreViejo, $newNombre);
                //Controlamos que el array de Imagenes borradas aun contenga imagenes.
                    //Si hemos ingresado el primer elemento, destruimos la variable de
                    //Sesion para que el programa vuelva a funcionar 
                    //como si el usuario no hubiera borrado ninguna imagen
                if(count($_SESSION['imgTMP']['imagenesBorradas']) == 0){    
                    unset($_SESSION['imgTMP']);
                }
                
                //Devolvemos el nuevo nombre para ser ingresado si el usuario sube una nueva imagen
                return $newNombre;
                
                }catch(Exception $ex){
                    $_SESSION['error'] = ERROR_INSERTAR_ARTICULO;
                    $ex->getMessage();
                }

            }else if($nombreNuevo === 1){
                //echo 'el usuario no ha borrado ninguna imagen<br>';
                //Renombramos las imagenes por el numero de imagenes en su subdirectorio
                $nombreRenombrado= Directorios::contarArchivos($_SESSION['nuevoSubdirectorio']);
                try{
                    
                if($nombreViejo){
                   //datos_usuario/admin/indice.jpg
                   $original = basename($nombreViejo); //quedando en => indice.jpg
                        // echo 'Nombre viejo despues de pasar por basename '.$original.'<br>';
                   $tmp = strstr($nombreViejo, $original, true);//OJO
                   //En este paso nos quedamos con la parte del directorio
                    //datos_usuario/admin/
                        //echo 'Nombre temporal '.$tmp.'<br>';
                   $newNombre = $tmp.$nombreRenombrado.'.jpg';
                    //Le asignamos el nuevo nombre a la parte del directorio substraida antes
                        //datos_usuario/aaaaa/aaaaa.jpg
                        //echo 'Nombre nuevo es: '.$nuevoNombre.'<br>';
                   rename($nombreViejo, $newNombre);
                   
               }
             
             return $newNombre;
            } catch (Exception $ex) {
                $_SESSION['error'] = ERROR_INSERTAR_ARTICULO;
                $ex->getMessage();
            }

            }else{
                //Si el metodo recive un nombre nuevo se le asigna ese nombre. Esto ocurre
                    //cuando se registra un usuario y sube una foto de perfil
                $nombreRenombrado = $nombreNuevo;
                //datos_usuario/admin/indice.jpg
                   $original = basename($nombreViejo); //quedando en => indice.jpg
                        // echo 'Nombre viejo despues de pasar por basename '.$original.'<br>';
                   $tmp = strstr($nombreViejo, $original, true);//OJO
                   //En este paso nos quedamos con la parte del directorio
                    //datos_usuario/admin/
                        //echo 'Nombre temporal '.$tmp.'<br>';
                   $newNombre = $tmp.$nombreRenombrado.'.jpg';
                    //Le asignamos el nuevo nombre a la parte del directorio substraida antes
                        //datos_usuario/aaaaa/aaaaa.jpg
                        //echo 'Nombre nuevo es: '.$nuevoNombre.'<br>';
                   rename($nombreViejo, $newNombre);
                   
                   return $newNombre;
            }
           
            
        //fin renombrarImagenes
        }
    
    /**
     * Metodo que cuenta el numero de archivos de un
     * directorio
     */
    final static function contarArchivos($ruta){
        
        $count = 0;
        $dir =  $ruta;
    
    if(!($handle = opendir($dir))) die("Cannot open $dir.");
             
                while($file = readdir($handle)){
                    if($file != "." && $file != ".."){
                            $count++;
                    }
                }
                
        return $count;
        
        
    //fin contarArchivos    
    }
    
    
    /**
     * Metodo que elimina una imagen
     * recive como parametro la ruta
     */ 
    final static function eliminarImagen($ruta){
    $test = true;
    
        try{
            //echo 'Eliminar imagen recive: '.$ruta.'<br>';
            $test = unlink($ruta);
            return $test;
        } catch (Exception $ex) {
            $_SESSION['error'] = ERROR_ELIMINAR_FOTO;
            echo $ex->getMessage();
            return $test;
        }
    
//fin eliminar imagen    
}

/**
 * Metodo que elimina los directorios creados 
 * cuando hay un error al registrarse.
 * Si la bbdd no hace el isert correcto ente metodo 
 * elimina las carpetas creadas en datos_usuario y photos
 * Recive una ruta con el directorio a eliminar.
 * Devuelve true o false
 */

final static function eliminarDirectorioRegistro($src){
    $test; 
   
    try{
            foreach(glob($src . "/*") as $archivos_carpeta)
            {   
                if (is_dir($archivos_carpeta))
                {
                    Directorios::eliminarDirectorioRegistro($archivos_carpeta);   
                }
                else
                {
                    try{
                        unlink($archivos_carpeta);
                       
                    }catch (Exception $ex){
                        echo "Error al borrar el archivo: ".$ex->getCode();
                        echo "Error al borrar el archivo: ".$ex->getLine();
                        echo "Error al borrar el archivo: ".$ex->getMessage();
                            
                    }  
                }
            }

            
                $test = rmdir($src);
                return $test;
    } catch (Exception $ex) {
        echo "Error al borrar el directorio: ".$ex->getCode();
        echo "Error al borrar el directorio: ".$ex->getLine();
        echo "Error al borrar el directorio: ".$ex->getMessage();
        
    }
     
//eliminarDirectorioRegistro    
}


/**
 * Metodo que recive los datos introducidos 
 * por el usuario en caso de error para poder
 * Ademas de los los datos de los fallos, tanto
 * de la bbdd y de los archivos creados
 */
final static function escribirErrorValidacion(DataObj $obj, $mensaje,$repEliminarDatosUsuario, $repEliminarPhotos){
    $test;
    
 
    try{
         $cuerpoMensaje = '
            ******************************************************
            Nueva entrada Con fecha:'. FECHA_DIA. ' Ha habido un problema de registro de un usuario.'.PHP_EOL.'
            El error es: '.$mensaje. ''.PHP_EOL.'
            La IP del visitante es: '.Directorios::ipVisitante().'
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
                    }else{
                        $cuerpoMensaje .= "No ha sido eliminada la carpeta Photos de este usuario.".PHP_EOL;      
                    }
            } else{
                
              $cuerpoMensaje .= "Los directorios de Datos_usuario y Photos se han elimanado.".PHP_EOL; 
            }
            
        if(!($archivo = fopen(TXT_ERROR_VALIDACION, 'a'))) die("No se puede abrir el archivo");
           $test =  fwrite($archivo, $cuerpoMensaje. PHP_EOL);
           $test =  fclose($archivo);
      
           return $test;
    }catch(Exception $ex){
        echo "Error al abrir y escribir en el archivo.".$ex->getCode();
        echo "Error al abrir y escribir en el archivo.".$ex->getMessage();
        echo "Error al abrir y escribir en el archivo.".$ex->getLine();
    }
    
    
    
//escribirErrorValidacion   
}

/*
* Metodo para optener la direción real 
 * del visistante
 */
final static function ipVisitante (){
    
    if (isset($_SERVER["HTTP_CLIENT_IP"]))
    {
        return $_SERVER["HTTP_CLIENT_IP"];
    }
    elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
    {
        return $_SERVER["HTTP_X_FORWARDED_FOR"];
    }
    elseif (isset($_SERVER["HTTP_X_FORWARDED"]))
    {
        return $_SERVER["HTTP_X_FORWARDED"];
    }
    elseif (isset($_SERVER["HTTP_FORWARDED_FOR"]))
    {
        return $_SERVER["HTTP_FORWARDED_FOR"];
    }
    elseif (isset($_SERVER["HTTP_FORWARDED"]))
    {
        return $_SERVER["HTTP_FORWARDED"];
    }
    else
    {
        return $_SERVER["REMOTE_ADDR"];
    }
    
    //Fin optener ip real
}
//fin sistema    
}
