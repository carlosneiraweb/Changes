<?php

/**
 * Description of Sistema
 *
 * @author Carlos Neira Sanchez
 */
class Sistema {
   
    
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
            echo 'moverImagen nombre Imagen foto: '.$nombreFoto.'<br>';
            echo 'moverImagen nuevoDirectorio al que mover: '.$nuevoDirectorio.'<br>';
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
            
        static function crearSubdirectorio($usuario){
          
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
                           //Este directorio ya existe y altamos
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
            echo 'aaaaa<br>';
            $nuevo = $count + 1;
            mkdir($usuario.'/'.$nuevo); 
            $nuevoDirectorio = $usuario.'/'.$nuevo;              
            }
            echo 'nuevo subdirectorio: '.$nuevoDirectorio.'<br>';
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
            
        static function copiarFoto($imagen, $nuevaImagen){
            $test;
            try{
               $test =  copy($imagen, $nuevaImagen);
               return $test; 
            } catch (Exception $ex) {
                $_SESSION['error'] = ERROR_INSERTAR_FOTO;
                echo $ex->getMessage();
                return $test; 
            }
            
          
        //fin copiarFoto    
        }
            
        /**
         * Metodo que cambia el nombre de la foto subida 
         * por el usuario para su perfil
         * Recive 2 parametros.
         * OJO devuelve el nuevo nombre con la extension .jpg cuando se pasa null como 2º parametro
         * 1º Nombre de la foto para cambiar el nombre
         * 2º Si lo recive el nombre por el que cambiarlo
         * 
         */
        final static function renombrarFoto($nombreViejo, $nombreNuevo){
            
    echo 'en renombrar: nombreViejo: '.$nombreViejo.'<br>';//datos_usuario/aaaaa/bici2.jpg
    echo 'en renombrar nombreNuevo: '.$nombreNuevo.'<br>';//admin => $_SESSION['usuario']['nick']
            
            if($nombreNuevo === 0){
                try{
                    
                //Extraemos del array de imagenes borradas el ultimo elemento   
                $ultimaImagenBorrada = array_pop($_SESSION['imgTMP']['imagenesBorradas']);
                var_dump($ultimaImagenBorrada);
                    
                //Nos quedamos con el numero de la imagen, tipo /admin/2/4 => 4
                //$imgTMP = array_pop($ultimaImagenBorrada);
                $tmp = explode('/', $ultimaImagenBorrada );
                //OJO este parametro esta en la tercera posicion
                var_dump($tmp);
                $newNombre = $_SESSION['nuevoSubdirectorio'].'/'.$tmp[3].'.jpg';
                rename($nombreViejo, $newNombre);
                //Controlamos que el array de Imagenes borradas aun contenga imagenes.
                    //Si hemos ingresado el primer elemento, destruimos la variable de
                    //Sesion para que el programa vuelva a funcionar 
                    //como si el usuario no hubiera borrado ninguna imagen
                if(count($_SESSION['imgTMP']['imagenesBorradas']) == 0){
                    unset($_SESSION['imgTMP']);
                }
                return $newNombre;
                
                }catch(Exception $ex){
                    $_SESSION['error'] = ERROR_INSERTAR_ARTICULO;
                    $ex->getMessage();
                }

            }else if($nombreNuevo === 1){
                echo 'el usuario no ha borrado ninguna imagen<br>';
                //Renombramos las imagenes por el numero de imagenes en su subdirectorio
                $nombreRenombrado= Sistema::contarArchivos($_SESSION['nuevoSubdirectorio']);
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
    static function contarArchivos($ruta){
        
        $count = 0;
        //echo 'La ruta al contar archivos es: '.$ruta.'<br>';
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
static function eliminarImagen($ruta){
    $test = true;
    
    try{
        echo 'Eliminar imagen recive: '.$ruta.'<br>';
        $test = unlink($ruta);
        return $test;
    } catch (Exception $ex) {
        $_SESSION['error'] = ERROR_ELIMINAR_FOTO;
        echo $ex->getMessage();
        return $test;
    }
    
//fin eliminar imagen    
}
//fin sistema    
}
