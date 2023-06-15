<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/Changes/Sistema/System.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Changes/Controlador/Validar/ControlErroresSistemaEnArchivosUsuarios.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Changes/Controlador/Validar/ControlErroresSistemaEnArchivosPost.php');
require_once($_SERVER['DOCUMENT_ROOT'] . "/Changes/Sistema/Constantes/ConstantesErrores.php");

if (!isset($_SESSION)) {
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
    final static function validarFoto() {



        $test = $_FILES['photoArticulo']['error'];
        $size = $_FILES['photoArticulo']['size'];
        $tipo = $_FILES['photoArticulo']['type'];
        //echo  $_FILES['photoArticulo']['error'];
        //echo  $_FILES['photoArticulo']['size'];
        //echo  $_FILES['photoArticulo']['type'];
        //echo 'test dice '.$test;


        if ($test !== 4) {

            if ($size > '3145728') {
                $test = 1;
            }
            if (($tipo != "jpeg") and ($tipo != "jpg") and ($tipo != "image/jpeg")) {
                $test = 10;
            }
        }

        switch ($test) {

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
     * de la carpeta temporal del servidor al directorio </br>
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
    final static function moverImagen($nombreFoto, $nuevoDirectorio, $opc) {

        if ($opc == "subirImagenPost") {
            $mensaje = "No se pudo mover la imagen al subir un post";
        } else if ($opc == "registrar") {
            $mensaje = "No se pudo mover la imagen al registrarse.";
        } else if ($opc == "actualizar") {
            $mensaje = "No se pudo mover la imagen al actualizar.";
        }

        try {

            if (!move_uploaded_file($nombreFoto, $nuevoDirectorio)) {
                throw new Exception($mensaje, 0);
            }
        } catch (Exception $ex) {

            //En caso error se llama al metodo redirigirPorErrorTrabajosEnArchivosRegistro
            //De la clase mis excepciones con la opcion adecuada
            if ($opc == "actualizar" || $opc == "registrar") {
                $excepciones = new MisExcepcionesUsuario(CONST_ERROR_MOVER_IMAGEN_ACTUALIZAR_REGISTRAR[1], CONST_ERROR_MOVER_IMAGEN_ACTUALIZAR_REGISTRAR[0], $ex);
                $excepciones->redirigirPorErrorSistema($opc, true);
            }

            if ($opc == "subirImagenPost") {
                $_SESSION['error'] = ERROR_INSERTAR_ARTICULO;
                $excepciones = new MisExcepcionesPost(CONST_ERROR_MOVER_IMAGEN_SUBIR_POST[1], CONST_ERROR_MOVER_IMAGEN_SUBIR_POST[0], $ex);
                $excepciones->redirigirPorErrorTrabajosEnArchivosSubirPost("errorPost", true);
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
     */
    final static function crearDirectorio($ruta, $opc) {

        try {
            //Comprobamos que los directorios ya no existan
            if (file_exists($ruta) || (!mkdir($ruta))) {
               
                throw new Exception("Error al crear los directorio registro", 0);
            }
        } catch (Exception $ex) {
           
            $excepciones = new MisExcepcionesUsuario(CONST_ERROR_CREAR_DIRECTORIO[1], CONST_ERROR_CREAR_DIRECTORIO[0], $ex);

            if ($opc == 'registrar') {
                
                $excepciones->redirigirPorErrorSistema($opc, true);
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
     * Id del usuario </br>
     * 
     */
    final static function crearSubdirectorio($usuario) {



        try {

            $dir = $usuario;
            $count = 0;
            $test = true; //Bandera para saber cuando se crea el subdirectorio
            //Para saber que se ha borrado un directorio
            //y se asigna a otro post
            $testSalir = true;

            if (!is_dir($dir)) {

                throw new Exception("El directorio pasado para crear el subdirectorio no existe", 0);
            }

            $handle = opendir($dir);

            if (!$handle) {

                throw new Exception("Al crear el subdirectorio el manejador no pudo abrir el directorio", 0);
            }



            while ($file = readdir($handle)) {
                //Limpia la cache al poder
                //ser usado el directorio por el mismo script
                clearstatcache();

                if ($file != "." && $file != "..") {
                    $count++;
                    if (is_dir($usuario . '/' . $file) and file_exists($usuario . '/' . $count)) {
                        //Este directorio ya existe y saltamos
                        continue;
                        //En el caso que un subdirectorio halla sido borrado al eliminar un POST
                    } else if (is_dir($usuario . '/' . $file) and !file_exists($usuario . '/' . $count)) {
                        $test = mkdir($usuario . '/' . $count);
                        $nuevoDirectorio = $count;
                        $testSalir = false; //Cambiamos la bandera para que no se cree uno al final
                        break;
                    }
                }
            }
            //Sino ha sido borrado ninguno se suma uno al total de subdirectorios y se crea    
            if ($testSalir) {

                $nuevo = $count + 1;

                $test = mkdir($usuario . '/' . $nuevo) ? true : false;
                $nuevoDirectorio = $nuevo;
            }


            if (!$test) {

                throw new Exception("No se pudo crear el nuevo subdirectorio con mkdir", 0);
            }


            return $nuevoDirectorio;
        } catch (Exception $ex) {

            $_SESSION['error'] = ERROR_ARCHIVOS;
            $excepciones = new MisExcepcionesPost(CONST_ERROR_CREAR_SUBDIRECTORIO_POST[1], CONST_ERROR_CREAR_SUBDIRECTORIO_POST[0], $ex);
            $excepciones->redirigirPorErrorTrabajosEnArchivosSubirPost("errorPost", true);
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
    final static function copiarFoto($imagen, $destino, $opc) {


        if ($opc == "registrar") {
            $mensaje = "No se pudo copiar imagen al registrarse un usuario";
        } else {
            $mensaje = "No se pudo copiar imagen Demo al registrar un Post";
        }
        try {

            if (!copy($imagen, $destino)) {
                throw new Exception($mensaje, 0);
            }
        } catch (Exception $ex) {

            if ($opc == "registrar") {
                $excepciones = new MisExcepcionesUsuario(CONST_ERROR_COPIAR_DEMO[1], CONST_ERROR_COPIAR_DEMO[0], $ex);
                $excepciones->redirigirPorErrorSistema($opc, true);
            } else {
                $_SESSION['error'] = ERROR_ARCHIVOS;
                $excepciones = new MisExcepcionesPost(CONST_COPIAR_ARCHIVO[1], CONST_COPIAR_ARCHIVO[0], $ex);
                $excepciones->redirigirPorErrorTrabajosEnArchivosSubirPost("errorPost", true);
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
    public function eliminarImagenDemoSubirPost() {

        if (is_file($_SESSION['nuevoSubdirectorio'] . '/demo.jpg')) {

            unlink($_SESSION['nuevoSubdirectorio'] . '/demo.jpg');
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
     *   " $_SESSION['imgTMP']['imagenesBorradas'] " instanciada en la clase Post linea 660. </br>
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
    public static function renombrarFotoSubirPost($nombreViejo, $opc) {



        if ($opc === 0) {



            try {

                //Extraemos del array de imagenes borradas el ultimo elemento 

                $ultimaImagenBorrada = $_SESSION['imgTMP']['imagenesBorradas'][0];

                //Nos quedamos con el numero de la imagen, tipo admin/2/4 => 4 
                //este numero es el que interesa, y que es el nombre de la imagen + .jpg
                //explode nos devuelve un array de strings
                //como limitados el caracter pasado
                $tmp = explode('/', $ultimaImagenBorrada);

                $newNombre = '../photos/' . $tmp[0] . '/' . $tmp[1] . '/' . $tmp[2] . '.jpg';

                $test = rename($nombreViejo, $newNombre) ? true : false;

                if (!$test) {
                    throw new Exception("Error al renombrar una imgen cuando el usuario elimino alguna", 0);
                }



                //Devolvemos el nuevo nombre para ser ingresado si el usuario sube una nueva imagen
                return $newNombre;
            } catch (Exception $ex) {

                $_SESSION['error'] = ERROR_INSERTAR_ARTICULO;
                $excepciones = new MisExcepcionesPost(CONST_ERROR_RENOMBRAR_IMG_AL_SUBIR_UN_POST[1], CONST_ERROR_RENOMBRAR_IMG_AL_SUBIR_UN_POST[0], $ex);
                $excepciones->redirigirPorErrorTrabajosEnArchivosSubirPost("errorPost", true);
            }
        } else if ($opc === 1) {


            //echo 'el usuario no ha borrado ninguna imagen<br>';
            //Renombramos las imagenes por el numero de imagenes en su subdirectorio

            $nombreRenombrado = Directorios::contarArchivos('../photos/' . $_SESSION['nuevoSubdirectorio'][0] . '/' . $_SESSION['nuevoSubdirectorio'][1]);

            try {

                if ($opc) {
                    // echo 'nombre viejo '.$nombreViejo.'<br>';
                    $original = basename($nombreViejo); //quedando en => indice.jpg
                    //strstr con parametro true devuelve 
                    //un string al encontrar la primera aparicion del string
                    $tmp = strstr($nombreViejo, $original, true); //OJO
                    //En este paso nos quedamos con la parte del directorio
                    // echo 'Nombre temporal '.$tmp.'<br>';
                    $newNombre = $tmp . $nombreRenombrado . '.jpg';
                    // echo 'nuevo nombre '.$newNombre;
                    //Le asignamos el nuevo nombre a la parte del directorio substraida antes

                    $test = rename($nombreViejo, $newNombre) ? true : false;

                    if (!$test) {
                        throw new Exception("No se pudo renombrar la imagen subiendo Post. No se había eliminado ninguna imagen.", 0);
                    }
                }


                return $newNombre;
            } catch (Exception $ex) {

                $_SESSION['error'] = ERROR_INSERTAR_ARTICULO;
                $excepciones = new MisExcepcionesPost(CONST_ERROR_RENOMBRAR_IMG_AL_SUBIR_UN_POST[1], CONST_ERROR_RENOMBRAR_IMG_AL_SUBIR_UN_POST[0], $ex);
                $excepciones->redirigirPorErrorTrabajosEnArchivosSubirPost("errorPost", true);
            }
        }


        //fin renombrarImagenes
    }

    /**
     * Metodo que cuenta el numero de archivos de un </br>
     * directorio. </br>
     * @param $ruta </br>
     * type String </br>
     * Ruta al directorio donde contar los archivos </br>
     * 
     */
    final static function contarArchivos($ruta) {


        $count = 0;

        try {

            if (!$handle = opendir($ruta)) {
                throw new Exception('Hubo un error al contar los directorios.', 0);
            }

            while ($file = readdir($handle)) {
                if ($file != "." && $file != "..") {
                    $count++;
                }
            }

            return $count;
        } catch (Exception $ex) {

            $_SESSION["error"] = ERROR_INSERTAR_ARTICULO;
            $excepciones = new MisExcepcionesPost(CONST_ERROR_CONTAR_ARCHIVOS[1], CONST_ERROR_CONTAR_ARCHIVOS[0], $ex);
            $excepciones->redirigirPorErrorTrabajosEnArchivosSubirPost("errorPost", true);
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
    final static function eliminarImagen($ruta, $opc) {
        //echo $ruta;

        try {

            if (!unlink($ruta)) {
                throw new Exception("No se pudo eliminar la imagen", 0);
            }
        } catch (Exception $ex) {

            if ($opc == 'actualizar') {

                $excepciones = new MisExcepcionesUsuario(CONST_ERROR_ELIMINAR_FOTO_VIEJA_AL_ACTUALIZAR[1], CONST_ERROR_ELIMINAR_FOTO_VIEJA_AL_ACTUALIZAR[0], $ex);
                $excepciones->redirigirPorErrorSistema("actualizar", true);
            } else if ($opc == "eliminarImgDemoSubirPost") {

                $_SESSION["error"] = ERROR_INSERTAR_ARTICULO;
                $excepciones = new MisExcepcionesPost(CONST_ERROR_ELIMINAR_IMG_DEMO_POST_DEL_DIRECTORIO[1], CONST_ERROR_ELIMINAR_IMG_DEMO_POST_DEL_DIRECTORIO[0], $ex);
                $excepciones->redirigirPorErrorTrabajosEnArchivosSubirPost("errorPost", true);
            } else if ($opc == "eliminarImagenSubiendoPost") {

                $_SESSION["error"] = ERROR_INSERTAR_ARTICULO;
                $excepciones = new MisExcepcionesPost(CONST_ERROR_ELIMINAR_IMG_SUBIR_POST[1], CONST_ERROR_ELIMINAR_IMG_SUBIR_POST[0], $ex);
                $excepciones->redirigirPorErrorTrabajosEnArchivosSubirPost("errorPost", true);
            }
        }
//fin eliminar imagen    
    }

    /**
     * Metodo que elimina los directorios creados <br>
     * cuando hay un error al registrarse o publicar un Post. <br>
     * Una vez ingresado el usuario en la bbdd el sitema <br />
     * intenta crear los directorios al usuario. <br>
     * Si esto no es posible intenta eliminar
     * las carpetas creadas en datos_usuario, photos y Videos <br />
     * Recive una ruta con el directorio a eliminar. <br />
     *  glob() busca todos los nombres de ruta que coinciden con pattern <br />
     * @param $src <br />
     * type String <br />
     * Ruta donde estan los directorios que hay que eliminar
     */
    final static function eliminarDirectoriosSistema($src, $opc) {


        try {

            //Nos aseguramos recive rutas de directorios

            if (is_dir($src)) {

                foreach (glob($src . "/*") as $archivos_carpeta) {

                    if (is_dir($archivos_carpeta)) {
                        Directorios::eliminarDirectoriosSistema($archivos_carpeta, $opc);
                    } else {
                        unlink($archivos_carpeta);
                    }
                }

                if (is_dir($src)) {

                    if (!rmdir($src)) {

                        throw new Exception("NO se pudo eliminar el directorio", 0);
                    }
                }
            } else {
                throw new Exception("NO se pudo eliminar el directorio", 0);
            }
        } catch (Exception $ex) {

            if ($opc == "SubirPost") {
                $excepciones = new MisExcepcionesPost(CONST_ERROR_ELIMINAR_DIR_PUBLICAR_POST[1], CONST_ERROR_ELIMINAR_DIR_PUBLICAR_POST[0], $ex);
                $excepciones->redirigirPorErrorTrabajosEnArchivosSubirPost("", true);
            } elseif ($opc == 'eliminarDirectoriosBajaUsuario') {
                $excepciones = new MisExcepcionesUsuario(CONST_ERROR_ELIMINAR_DIRECTORIOS_BAJA[1], CONST_ERROR_ELIMINAR_DIRECTORIOS_BAJA[0], $ex);
                $excepciones->redirigirPorErrorSistema("eliminarDirectoriosBajaUsuario", true);
            } elseif($opc == "registrar"){
                $excepciones = new MisExcepcionesUsuario(CONST_ERROR_ELIMINAR_DIRECTORIOS_ALTA[1], CONST_ERROR_ELIMINAR_DIRECTORIOS_ALTA[0], $ex);
                $excepciones->redirigirPorErrorSistema("eliminarDirectoriosAltaUsuario", false);
            }else {
                $excepciones = new MisExcepcionesUsuario(CONST_ERROR_ELIMINAR_DIRECTORIO[1], CONST_ERROR_ELIMINAR_DIRECTORIO[0], $ex);
                $excepciones->redirigirPorErrorSistema(' ', true);
            }
        }



//eliminarDirectorioRegistro    
    }

//fin Directorios   
}
