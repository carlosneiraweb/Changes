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
            //echo 'moverImagen nombre foto: '.$nombreFoto.'<br>';
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
               return $test;
            }  else {
               $test = mkdir($ruta);
               return $test;
            }
         }catch(Exception $ex){
            $test = false;
            echo $ex->getMessage();
            return $test;
         }

        //fin de crear directorio 
        }
        
         /**
          * Metodo que cuenta el numero de directorios
          * que tiene un usuario. Se utiliza a la hora de crear
          * uno nuevo asignarle el nombre.
          * Los directorios tienen nombre consecutivo.
          */
            
        static function crearSubdirectorio($usuario){
          
        try{
            //echo 'en crear subdirectorio la ruta recivida es: '.$usuario.'<br>';
            $dir = $usuario;
            $count = 0;
            $nuevoDirectorio;
            if(!($handle = opendir($dir))) die("Cannot open $dir.");
             
                while($file = readdir($handle)){
                    if($file != "." && $file != ".."){
                        if(is_dir($usuario.'/'.$file)){
                            $count++;
                        }
                    }
                }
            
            if($count === 0){
                mkdir($usuario.'/1');
                $nuevoDirectorio = $usuario.'/1';
            }else{
                $nuevo= $count+1;
                mkdir($usuario.'/'.$nuevo); 
                $nuevoDirectorio = $usuario.'/'.$nuevo;
            }
            //echo 'Nuevo Subdirectorio creado en el metodo crearsubdirectorio: '.$nuevoDirectorio.'<br>';
            //el nuevo subidrectorio creado siempre es: usuario/total directorio => admin/1
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
               //echo 'imagen a copiar: '.$imagen.'<br>';
               //echo 'imagen copiada: '.$nuevaImagen.'<br>';
               $test =  copy($imagen, $nuevaImagen);
               //echo 'en copiar foto dice: '.$test.'<br>';
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
         * @param type $nombre
         */
        final static function renombrarFoto($nombreViejo, $nombreNuevo){
            
                    //echo 'en renombrar: nombreViejo: '.$nombreViejo.'<br>';
                    //echo 'en renombrar nombreNuevo: '.$nombreNuevo.'<br>';
            //Si el nombreNuevo es null este metodo es llamado 
            //desde subir_post. Lo que hacemos es cambiarle el nombre
            //de la imagen del usuario de modo que no tengamos problemas
            //ni por duplicados al insertar ni acentos o signos raros en el nombre de las imagenes
            if($nombreNuevo == null){
                //Renombramos las imagenes por el numero de imagenes en su subdirectorio
                $_SESSION['nombreNuevo']  = Sistema::contarArchivos($_SESSION['nuevoSubdirectorio']);
            }else{
                //En caso de que no sea null se le asigna el nombre pasado
                $_SESSION['nombreNuevo'] = $nombreNuevo;
            }
           
            try{
               if($nombreViejo){
                   $original = basename($nombreViejo);
                   $tmp = strstr($nombreViejo, $original, true);//OJO
                   $nuevoNombre = $tmp.$_SESSION['nombreNuevo'].'.jpg';
                   rename($nombreViejo, $nuevoNombre);
                   
               }
             
             return $nuevoNombre;
            } catch (Exception $ex) {
                $_SESSION['error'] = ERROR_INSERTAR_ARTICULO;
                $ex->getMessage();
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
