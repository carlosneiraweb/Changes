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
        
        final static function validarFoto(){
            $test = true;
            if(isset($_FILES['photo']) and $_FILES['photo']['error'] == UPLOAD_ERR_OK){
           
                if($_FILES['photo']['type'] != 'image/jpeg'){
                    $test = false;
                    echo FORMATO_FOTO;
                }

            }else{
                switch ($_FILES['photo']['error']){
                    case UPLOAD_ERR_INI_SIZE:
                        $test = false;
                        echo TAMAÑO_FOTO;
                            break;
                    case UPLOAD_ERR_FORM_SIZE:
                        $test = false;
                        echo TAMAÑO_FOTO;
                            break;
                    case UPLOAD_ERR_NO_FILE:
                        $test = false;
                        echo FOTO_NO_ELIGIDA;
                            break;
                    default:
                        $test = false;
                        echo FOTO_NO_ELIGIDA;
                }
               
            }
            //echo 'Valido foro dice: '.$test.'<br>';
            return $test;
     
        //fin validar foto    
        }
    
        /**
         * Metodo que mueve las fotos que el usuario sube
         * de la carpeta temporal del servidor a directorio 
         * definitivo.
         */
        final static  function moverImagen($nombreFoto, $nuevoDirectorio){
            $test;
            echo 'nombre: '.$nombreFoto.'<br>';
            echo 'nuevodirectorio: '.$nuevoDirectorio.'<br>';
         try{
             $test = move_uploaded_file($nombreFoto, $nuevoDirectorio);
         } catch (Exception $ex) {
             echo $ex->getCode();
         }
            echo "mover dice: ".$test."<br>";
            return $test;  
        //fin mover imagen    
        }

        /**
         * Metodo que recibe una ruta y crea un directorio
         * @param type $ruta
         */
        final static function crearDirectorio($ruta){
            echo 'Soy llamado con ruta'.$ruta.'<br>';
         $test;
         try{
            $test =    mkdir($ruta.'/'.$_SESSION['usuario']['nick']);
         }catch(Exception $ex){
            echo $ex->getMessage();
         }
         echo "creardirectorio dice: ".$test.'<br>';
         return $test;
            }
        
         /**
          * Metodo que cuenta el numero de directorios
          * que tiene un usuario. Se utiliza a la hora de crear
          * uno nuevo asignarle el nombre.
          * Los directorios tienen nombre consecutivo.
          */
            
        static function contarDirectorios($usuario){
         
            $dir = 'photos/'.$usuario;
        $count = 0;
            if(!($handle = opendir($dir))) die("Cannot open $dir.");
             
                while($file = readdir($handle)){
                    if($file != "." && $file != ".."){
                        if(is_dir('photos/'.$usuario.'/'.$file)){
                            $count++;
                        }
                    }
                }
            
            if($count === 0){
                mkdir('photos/'.$usuario.'/1');
                $nuevoDir = 'photos/'.$usuario.'/1';
                
            }else{
                $nuevo= $count+1;
                mkdir('photos/'.$usuario.'/'.$nuevo); 
                $nuevoDir = 'photos/'.$usuario.'/'.$nuevo; 
                
            }
          
            return $nuevoDir;
            
            
        //fin contarDirectorios    
        }    
            
        /**
         * Metodo que copia una imagen 
         * de un directorio a otro
         * 
         */   
            
        static function copiarFoto($imagen, $nuevaImagen){
            $test;
            try{
               echo 'image: '.$imagen.'<br>';
               echo 'nueva: '.$nuevaImagen.'<br>';
               $test =  copy($imagen, $nuevaImagen);
                
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }
            
        return $test;   
        //fin copiarFoto    
        }
            
        /**
         * Metodo que cambia el nombre de la foto subida 
         * por el usuario para su perfil
         * @param type $nombre
         */
        final static function renombrarFotoPerfilUsuario($nombreViejo, $usuario){
            $test = false;
            
            try{
               if($nombreViejo){
                   $original = basename($nombreViejo);
                   echo 'nombre original: '.$original.'<br>';
                   $tmp = strstr($nombreViejo, $original, true);//OJO
                   echo 'nombre tmp: '.$tmp.'<br>';
                   $nuevoNombre = $tmp.$usuario.'.jpg';
                   echo "nuevo nombre: ".$nuevoNombre.'<br>';
                   echo "nombreViejo: ".$nombreViejo.'<br>';
                   $test = rename($nombreViejo, $nuevoNombre);
                   
               }
                
             echo 'renombrar dice: '.$test.'<br>';
             return $test;
            } catch (Exception $ex) {
                $ex->getMessage();
            }
           
            
        //fin renombrarFotoPerfilUsuario
        }
    
    
//fin sistema    
}
