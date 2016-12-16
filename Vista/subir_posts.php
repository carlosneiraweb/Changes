<?php 
require_once ('../Modelo/Post.php');
require_once ('../Modelo/Usuarios.php');
require_once ('../Modelo/DataObj.php');
require_once('../Controlador/Validar/ValidoForm.php');
require_once('../Sistema/Directorios.php');
session_start();

//Iniciamos la variable de session contador a 0
//Iremos incrementando el numero de fotos subidas
if(!isset($_SESSION['contador'])){
    $_SESSION['contador'] = 0; 
}
//Recuperamos la url de la anterior pagina
$url = $_SESSION["url"]; 
 /**
 * Metodo que nos devuelve a la pagina anterior
 */
function volverAnterior(){
    header('Location:'. $_SESSION["url"]);
}
/**
 * Metodo que nos redirige a la pagina de mostrar error
 */
function mostrarError(){
    header('Location: mostrar_error.php');
}

global $articulo;
$articulo = new Post(array());
?>
<!DOCTYPE html>
<!--
 author Carlos Neira Sanchez
 mail arj.123@hotmail.es
 telefono ""
 nameAndExt subir_archivos_servidor.php
 fecha 17-abr-2016
-->

<html>
    <head>
        <meta charset="UTF-8">
        <title>Sube tu artículo para poder intercambiarlo</title>
        <link rel='stylesheet' type='text/css' href="css/estilos.css"/>
        <meta name="description" content="Sube lo que quieras cambiar con otras personas."/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link href="../img/fabicon.ico" rel="icon" type="image/x-icon">
	<link rel="stylesheet" href="../css/estilos.css"/>
        <script src="../Controlador/jquery-2.2.2.js" type="text/javascript"></script>
        <script src="../Controlador/Elementos_AJAX/elementos.js"></script>
        <script src="../Controlador/Elementos_AJAX/cargarElementos.js"></script>
        <script src="../Controlador/Elementos_AJAX/imagenesAlSubirPost.js"></script>
        <script src="../Controlador/Validar/formulario_reg.js"></script>
        <script src="../Controlador/Validar/contador.js"></script>
        <script src="../Controlador/Validar/otras_validaciones.js"></script>
        
        <script type="text/javascript">
                    //Indicamos que elementos vamos a cargar
                    //De esta manera controlamos que peticiones hacemos en cada pagina
                        var PS = true;
                        var PT = true;
                        var UI = true;
        </script>
        
    </head>
    <body id="cuerpo">
        
        
    <?php
        
        echo'<div id="ocultar" class="oculto"> </div>';   
        
        
        echo'<section id="fecha"></section>';
        echo'<header>';
	echo'<figure id="logo" class="fade">';
		echo'<img src="../img/logo.png" alt="Logo del portal"/>';
		echo'<figcaption id="titulo">Cambia todo lo que ya no uses.</figcaption>';
	echo'</figure>';
	echo'<section id="cabecera">';
			echo'<h1>Te lo cambio</h1>';
			echo'<h3>Miles de personas compartiendo te están esperando.</h3>';
		        echo'<h3>Sube todos los artículos que desees.</h3>';
                
	echo'</section>';
     
    echo'</header>';
    
    //Si no se ha recivido el step
    //se muestra el formulario por primera vez
    if(!isset($_POST['step'])){      
        displayStep1(array());
    }
    
    /*Mandamos a comprobar los campos del primer formulario*/
    if(isset($_POST['primero']) and $_POST['primero'] == "Siguiente"){        
        $requiredFields = array('seccion', 'comentario');
        processForm($requiredFields, "step1");
    } elseif(isset($_POST['segundo']) and $_POST['segundo'] == "Enviar" ){    
        //El usario  quiere subir una foto al post
        $requiredFields = array();
        processForm($requiredFields, "step2");
        
    } elseif(isset($_POST['segundo']) and $_POST['segundo'] == "Atras"){
        //Esto significa que el usuario ha dado un paso atras en el formulario
        //Lo que hacemos es actualizar los datos, no volver a registrarlo
        //Para ello instanciamos una variable de session para que lo tenga en cuenta
        //Al ingresar en la bbdd
        $_SESSION['atras'] = 'atras'; 
        displayStep1(array());
    } elseif(isset($_POST['segundo']) and $_POST['segundo'] == "Fin"){
        //El usuario ha terminado de ingresar los datos del post
        //Le redirigimos a cualqier url que estubiera
        //Destruimos la sesion atras, la sesion contador y si existiera la 
            //la variable de imagenes borradas
        if(isset($_SESSION['imgTMP'])){
            unset($_SESSION['imgTMP']);
        }
        
            unset($_SESSION['atras']);
            unset($_SESSION['contador']); 
        
        
        volverAnterior();
    } elseif(isset($_POST['modificar']) && $_POST['modificar'] == 'Borrar'){
        eliminarImagen();
        displayStep2(array());
    } elseif(isset($_POST['modificar']) && $_POST['modificar'] == 'OK'){
        actualizarImagen();
        displayStep2(array());
    }

    function displayStep1($missingFields){
        global $mensaje; 
        
    echo'<section id="form_post">';
                echo'<h4>Introduzca los datos del artículo</h4>';
    echo'<form name="post" action="subir_posts.php" method="POST" id="post" >';
        echo'<fieldset>';
        	echo'<legend>Rellena los campos</legend>';
        echo"<input type='hidden' name='step' value='1'>"; 
        
    echo '<section class="contenedor">';    
    echo'<label '.ValidoForm::validateField("titulo", $missingFields).' for="titulo">Introduce un titulo para el anuncio. </label> <span class="obligatorio"><img src="../img/obligado.png" alt="campo obligatorio" title="obligatorio"></span>';
    echo'<input type="text" maxlength="60" name="titulo" id="titulo" autofocus placeholder="Máximo 60 caracteres."  value="';if(isset($_SESSION['post']['titulo'])){echo $_SESSION['post']['titulo'];} echo '">'; 
    echo'<label><span class="cnt">0</span></label>';
    echo'</section>';

    echo'<label for="seccion">Seccion:</label> <span class="obligatorio"><img src="../img/obligado.png" alt="campo obligatorio" title="obligatorio"></span>';
 
	echo'<select name="seccion" id="seccion">';
           
               echo'</select>'; 

                echo'<br>';
                echo'<br>';
   
                
    echo '<section class="contenedor">';
    echo'<label '.ValidoForm::validateField("comentario", $missingFields). ' for="comentario">Introduce una descripción general del artículo. </label><span class="obligatorio"><img src="../img/obligado.png" alt="campo obligatorio" title="obligatorio"></span>';
    echo'<textarea maxlength="255" name="comentario" id="comentario" placeholder= "Máximo 255 caracteres." value="';if(isset($_SESSION['post']['comentario'])){echo $_SESSION['post']['comentario'];} echo '">'; 
    echo'</textarea>';
    echo'<label><span class="cnt">0</span></label>';
    echo'</section>';
    
    echo '<section class="contenedor">';
    echo'<label  for="precio">Introduce un precio aproximado  artículo. </label>';
    echo'<input type="text" maxlength="10" name="precio" id="precio" placeholder="Precio aproximado, máximo 10 caracteres, solo se aceptan dígitos." value="';if(isset($_SESSION['post']['precio'])){echo $_SESSION['post']['precio'];} echo '">';
    echo'<label><span class="cnt">0</span></label>';
    echo'</section>';
    
    
    echo'<label for="tiempoCambio">Elige por cuanto tiempo deseas hacer el cambio.</label> <span class="obligatorio"><img src="../img/obligado.png" alt="campo obligatorio" title="obligatorio"></span>';
 
	echo'<select name="tiempoCambio" id="tiempoCambio">';
           
               echo'</select>'; 

                echo'<br>';
                echo'<br>';
     
                
    
    echo '<section class="contenedor">';
    echo'<label  for="Pa_queridas" class="centrar">Introduce 4 palabras por lo que tú estarías interesado en cambiarlo. </label>';
        echo '<section id="buscadas" class="introducir_palabras">';       
    echo'<input type="text" name="querida_1" id="querida_1" placeholder="Máximo 25" maxlength="25"   value="';if(isset($_SESSION['post']['Pa_queridas'][0])){echo $_SESSION['post']['Pa_queridas'][0];} echo '">';
        echo'<label><span class="cnt">0</span></label>';
    echo'<input type="text" name="querida_2" id="querida_2" maxlength="25" value="';if(isset($_SESSION['post']['Pa_queridas'][1])){echo $_SESSION['post']['Pa_queridas'][1];} echo '">';
        echo'<label><span class="cnt">0</span></label>';
    echo'<input type="text" name="querida_3" id="querida_3" maxlength="25" value="';if(isset($_SESSION['post']['Pa_queridas'][2])){echo $_SESSION['post']['Pa_queridas'][2];} echo '">';
        echo'<label><span class="cnt">0</span></label>';
    echo'<input type="text" name="querida_4" id="querida_4" maxlength="25" value="';if(isset($_SESSION['post']['Pa_queridas'][3])){echo $_SESSION['post']['Pa_queridas'][3];} echo '">';
        echo'<label><span class="cnt">0</span></label>';
        echo'</section>';
     
    echo '</section>';
    
    
    echo '<section class="contenedor">'; 
    echo'<label  for="Pa_ofrecidas" class="centrar">Introduce 4 palabras para que la gente encuentre tu artículo. </label>';
        echo '<section id="ofrecidas" class="introducir_palabras">';
    echo'<input type="text" name="ofrecida_1" id="ofrecida_1" placeholder="Máximo 25" maxlength="25" value="';if(isset($_SESSION['post']['Pa_ofrecidas'][0])){echo $_SESSION['post']['Pa_ofrecidas'][0];} echo '">';
        echo'<label><span class="cnt">0</span></label>';
    echo'<input type="text" name="ofrecida_2" id="ofrecida_2" maxlength="25" value="';if(isset($_SESSION['post']['Pa_ofrecidas'][1])){echo $_SESSION['post']['Pa_ofrecidas'][1];} echo '">';
        echo'<label><span class="cnt">0</span></label>';
    echo'<input type="text" name="ofrecida_3" id="ofrecida_3" maxlength="25" value="';if(isset($_SESSION['post']['Pa_ofrecidas'][2])){echo $_SESSION['post']['Pa_ofrecidas'][2];} echo '">';
        echo'<label><span class="cnt">0</span></label>';
    echo'<input type="text" name="ofrecida_4" id="ofrecida_4" maxlength="25" value="';if(isset($_SESSION['post']['Pa_ofrecidas'][3])){echo $_SESSION['post']['Pa_ofrecidas'][3];} echo '">';
        echo'<label><span class="cnt">0</span></label>';
        echo '</section>';
        
    echo '</section>';
           
    echo '<section id="btns_registrar">';    
        echo"<input type='submit' name='primero' id='primero'  value='Siguiente' >";
    echo '<section>';
    
        //Mostramos cualquier errror al validar el formulario            
            echo "</form>";
        if($mensaje){
            echo $mensaje;
        }    
        echo'</fieldset>';  
        
    echo'</section>';
 //fin displayStep1
}    
        

function displayStep2($missingFields){
    global $mensaje; 

     //Aqui recuperamos el id del post en el que estamos
        //Se lo pasamos a javascript para que nos muestre 
        //todas las fotos que vamos subiendo via JSON
            if(isset($_SESSION['lastId']) ){
                $idPost = $_SESSION['lastId'][0];
            echo '<script type="text/javascript">';
                echo "var idPost = "; echo "'$idPost'".";";
            echo '</script>';
         
            }
    echo '<section id="mostrarImgSeleccionada">';
    
            //Aqui mostramos la imagen ampliada
            //Pos si el usuario quiere modificarla
            //Se muestra desde JSON
    
    echo '</section>';
    
    
    echo'<section id="form_post">';
                echo'<h4>Puedes subir hasta 5 imagenes</h4>';
                
        //Seccion donde mostraemos las imagenes que
        //va subiendo el usuario
        echo '<section id="img_ingresadas">';
            //Vamos mostrando la cantidad de imagenes
            echo '<span id="contador">';
                echo $_SESSION['contador'].'<br>';
            echo '</span>';
                echo'<section id="cnt_img">';
            //Aqui el section creado con JS para las imagenes
                echo '</section>';
        echo '</section>';
        
    echo'<form name="post" action="subir_posts.php" method="POST" id="post" enctype="multipart/form-data">';
        echo'<fieldset>';
        	echo'<legend>Introduce alguna imagen.</legend>';
        echo"<input type='hidden' name='step' value='2'>"; 
        //Limitamos el valor máximo del archivo
        echo'<input type="hidden" name="MAX_FILE_SIZE" value="50000" />';
        echo'<label for="photoArticulo">Solo fotos .jpg</label>';
        echo '<br>';    
            echo'<input type="file" name="photoArticulo" id="photoArticulo" value="" />';        
        
        echo '<br><br>'; 
        
        
    echo '<section class="contenedor">'; 
    echo'<label  for="figcaption">Introduce una pequeña descripción, se verá junto a la imagen. </label>';
    echo'<input type="text" name="figcaption" id="figcaption" placeholder="Una pequeña descripción" maxlength="70" value="" >'; 
        echo'<label><span class="cnt">0</span></label>';
        echo '</section>';
        
   
    echo '<section id="btns_registrar">';
        
        
                        echo"<input type='submit' name='segundo' id='segundo'  value='Atras'>";
                    if($_SESSION['contador'] < 5){
                        echo"<input type='submit' name='segundo' id='segundo'  value='Enviar'>";
                    }    
                        echo"<input type='submit' name='segundo' id='segundo' value='Fin' >";
                    echo"</div>";       
    echo'</section>';
    
            echo "</form>";
        //Mostramos cualquier error en el formulario
            //y cualquier error al validar la foto
        if($mensaje ){
            echo $mensaje.'<br>';
        }   
        echo'</fieldset>';  
        
    echo'</section>';
//fin displayStep2    
}

/**
 * Metodo utilizado para crear un objeto de 
 * la clase Post
 * @global Post $articulo
 * @global string $usuario
 */
function ingresarPost(){
    global $articulo;
    
    
        $articulo = new Post(array(
            "idUsuario" => $_SESSION['user']->getValue('nick'),
            "secciones_idsecciones" => $_SESSION['post']['seccion'],
            "tiempo_cambio_idTiempoCambio" => $_SESSION['post']['tiempoCambio'],
            "titulo" => $_SESSION['post']['titulo'],
            "comentario" => $_SESSION['post']['comentario'],
            "precio" => $_SESSION['post']['precio'],
            "Pa_queridas" => array(
                $_SESSION['post']['Pa_queridas'][0],
                $_SESSION['post']['Pa_queridas'][1],
                $_SESSION['post']['Pa_queridas'][2],
                $_SESSION['post']['Pa_queridas'][3]
            ),
            "Pa_ofrecidas" => array(
                $_SESSION['post']['Pa_ofrecidas'][0],
                $_SESSION['post']['Pa_ofrecidas'][1],
                $_SESSION['post']['Pa_ofrecidas'][2],
                $_SESSION['post']['Pa_ofrecidas'][3]
            ),
            "fechaPost" => ""        
        ));
        
        //Aqui comprobamos que el usuario ya ha ingresado el post
        // y ha ido un paso atras y ha modificado algun dato
        
        if(isset($_SESSION['atras'])){           
            $result = $articulo->actualizarArticulo();  
        }else{
            $result = $articulo->insertArticulo();
        }
        //En caso de error nos redirige a la pagina de error 
        //para que el usuario pueda intentarlo otra vez
            if(!$result){
                mostrarError();
                exit();
            } else{
                //Destruimos el objeto para no ocupar memoria
                unset($articulo);
            }

//fin ingresarPost    
}


/**
 * Este metodo ingresa en la tabla de imagenes
 * las imagenes que tiene cada post
 */

function ingresarImagenes(){
   
    $articulo = new Post(array(
       "figcaption" => $_SESSION['post']['figcaption'],
       "idImagen" => $_SESSION['idImagen']
    ));
    
    $result = $articulo->insertarFotos();
   
     //En caso de error nos redirige a la pagina de error 
     //para que el usuario pueda intentarlo otra vez
        if(!$result){
            mostrarError();
            exit();      
        } 
//fin ingresarImagenes    
}

/**
 * Metodo que actualiza una imagen
 */
function actualizarImagen(){
     
    $articulo = new Post(array(
       "figcaption" => $_POST['txtModificar'],
       "idImagen" => $_POST['ruta']
    ));
    
    
    $result = $articulo->actualizarTexto();
    
    if(!$result){
        mostrarError();
        exit();
    } else{
        //En caso de error nos redirige a la pagina de error 
        //para que el usuario pueda intentarlo otra vez
        unset($articulo);
        
    }
    
//fin actualizarImagen    
}

/**
 * Metodo que elimina una imagen
 * @global Post $articulo
 */
function eliminarImagen(){
    
    $articulo = new Post(array(
        //Este ruta sale del script  elementos.js
        //Ya que el formulario esta generado totalmente con JQUERY
        //Metodo cargarImgEliminar
        "idImagen" => $_POST['ruta'] 
    ));
    
    $result = $articulo->eliminarImg();
    
    //Si ha habido algun error, nos redirige a la pagina que muestra un error
    //Sino, continuamos en el formulario
    if(!$result){
        mostrarError();
        exit();
    } else{
        //Si todo ha ido bien eliminamos el objeto 
        unset($articulo);
    }
//    
//fin eliminar imagen   
}


function processForm($requiredFields, $st){
    //Array para almacenar los campos no rellenados y obligatorios
        global $missingFields;
        $missingFields = array();   

        switch ($st){
            case 'step1':
                $_SESSION['post']['seccion'] = isset($_POST['seccion']) ? $_POST['seccion'] : "";
                $_SESSION['post']['tiempoCambio'] = isset($_POST['tiempoCambio']) ? $_POST['tiempoCambio'] : "";
                $_SESSION['post']['titulo'] = isset($_POST["titulo"]) ? preg_replace("/[^\-\_a-zA-Z0-9.,`'´ ñÑáéíóúäëïöü]/", "", $_POST["titulo"]) : "";       
                $_SESSION['post']['comentario'] = isset($_POST['comentario']) ? preg_replace("/[^\-\_a-zA-Z0-9.,ºª`'´ Ññáéíóúäëïöü \n\r\rn\s]/", "", nl2br($_POST["comentario"])) : "";
                $_SESSION['post']['precio'] = isset($_POST['precio']) ? preg_replace("/[^\-\_a-zAZ0-9., €$]/", "", $_POST["precio"]) : "";
                $_SESSION['post']['Pa_queridas'][0] = isset($_POST["querida_1"]) ? preg_replace("/[^\-\_a-zA-Z0-9ºª., '``'´ñÑáéíóúäëïöü]/", "", $_POST["querida_1"]) : "";
                $_SESSION['post']['Pa_queridas'][1] = isset($_POST["querida_2"]) ? preg_replace("/[^\-\_a-zA-Z0-9ºª., `'´Ññáéíóúäëïöü]/", "", $_POST["querida_2"]) : "";
                $_SESSION['post']['Pa_queridas'][2] = isset($_POST["querida_3"]) ? preg_replace("/[^\-\_a-zA-Z0-9ºª., `'´ñÑáéíóúäëïöü]/", "", $_POST["querida_3"]) : "";
                $_SESSION['post']['Pa_queridas'][3] = isset($_POST["querida_4"]) ? preg_replace("/[^\-\_a-zA-Z0-9ºª., `'´Ññáéíóúäëïöü]/", "", $_POST["querida_4"]) : "";
                $_SESSION['post']['Pa_ofrecidas'][0] = isset($_POST["ofrecida_1"]) ? preg_replace("/[^\-\_a-zA-Z0-9ºª., `'´ñÑáéíóúäëïöü]/", "", $_POST["ofrecida_1"]) : "";
                $_SESSION['post']['Pa_ofrecidas'][1] = isset($_POST["ofrecida_2"]) ? preg_replace("/[^\-\_a-zA-Z0-9ºª., `'´Ññáéíóúäëïöü]/", "", $_POST["ofrecida_2"]) : "";
                $_SESSION['post']['Pa_ofrecidas'][2] = isset($_POST["ofrecida_3"]) ? preg_replace("/[^\-\_a-zA-Z0-9ºª., `'´ñÑáéíóúäëïöü]/", "", $_POST["ofrecida_3"]) : "";
                $_SESSION['post']['Pa_ofrecidas'][3] = isset($_POST["ofrecida_4"]) ? preg_replace("/[^\-\_a-zA-Z0-9ºª., `'´Ññáéíóúäëïöü]/", "", $_POST["ofrecida_4"]) : "";
               
                break;
            
            case 'step2':
                $_SESSION['post']['figcaption'] = isset($_POST['figcaption']) ? preg_replace("/[^\-\_a-zA-Z0-9.,ºª`'´ ñÑáéíóúäëïöü]/", "", $_POST["figcaption"]) : ""; 
                break;
        }

    foreach($requiredFields as $requiredField){
        if(!$_SESSION['post'][$requiredField]){
            $missingFields[] = $requiredField;
        }
    }

     /**
     * Metodo que valida los datos introducidos por el usuario.
     * Valida los campos con los metodos static de ValidaForm
     * Valida los datos de la bbdd con un objeto de la clase Post
     * @global type $mensaje
     * @global Post $articulo
     * @param type $st
     * @return boolean
     */
   
function validarCampos($st){
    global $mensaje;
    $test = true;
        
    switch ($st){
            
        case("step1"):

                //Creamos un subdirectorio para almacenar las imagenes 
                //IMPORTANTE CONOCER EL CONTENIDO DE 'nuevoSubdirectorio' 
                //Es la usada para mover, copiar, eliminar he ingresar en la bbdd
                //Su contenido es del tipo ../photos/nombreUsuario/totalSubdirectorios
                
                //Agregamos una foto demo por si el usuario no quiere subir
                //ninguna imagen
                //Esto solo se hace la primera vez y se evita crearlo otra vez si el usuario 
                // vuelve atras en el formulario comprobando que $_SESSION['atras'] no existe
                if($_SESSION['contador'] == 0 and !isset($_SESSION['atras']) ){
                    
                    $_SESSION['nuevoSubdirectorio'] = Sistema::crearSubdirectorio("../photos/".$_SESSION['user']->getValue('nick'));
                    $test = Sistema::copiarFoto("../photos/demo.jpg",$_SESSION['nuevoSubdirectorio']."/demo.jpg");
                    
                    return $test;
                }

            break;
                 
    case('step2'):
        
        $test = false;
        
        if(isset($_FILES['photoArticulo']['tmp_name']) and $_FILES['photoArticulo']['tmp_name'] != null){
            
            if(Sistema::validarFoto('photoArticulo')){
               
                //Si la foto es correcta entonces eliminamos la imagen default 
                    //que subimos
            
                if(is_file($_SESSION['nuevoSubdirectorio'].'/demo.jpg')){
                    $test = unlink($_SESSION['nuevoSubdirectorio'].'/demo.jpg');  
                }
                  
                    $destino = $_SESSION['nuevoSubdirectorio'].'/'.basename($_FILES['photoArticulo']['name']);                   
                    $foto = $_FILES['photoArticulo']['tmp_name'];
            
                        $test = Sistema::moverImagen($foto, $destino);
                
            //Comprobamos que subiendo imagenes el usuario no ha eliminado ninguna
                //Si lo ha hecho le asignamos en el directorio photos/subdirectorio 
                //Ese nombre
                if(isset($_SESSION['imgTMP']) and $_SESSION['imgTMP'] != null){
                    
                    if($test){ $_SESSION['idImagen'] = Sistema::renombrarFoto($destino, 0);}  
                    
            //Aqui vamos subiendo las fotos al post mientras el usuario no 
                //halla eliminado ninguna mientras subia las fotos
                }elseif (!isset($_SESSION['imgTMP'])){
            
                    if($test){ $_SESSION['idImagen'] = Sistema::renombrarFoto($destino, 1);}
                
                }
               
                //Si hay algun tipo de error al subir la foto
                //Redirigimos a la pagina de mostrar error
                 //Para que el usuario vuelva a intentarlo
                    if(!$test){
                        mostrarError();   
                    }
            }else{
                //Si hay algun problema en la validacion mostramos un error
                    if(!$test){
                        mostrarError();
                       
                    }
                
            }
        }
        
    }
       
       return $test;
//fin de validarCampos   
}
    
    
    //Mandamos a validar al metodo anterior los campos segun 
    //cada paso del formulario
    switch ($st){
   
        case 'step1':
           
            //Si ha habido algun error volvemos a mostrar el paso del formulario
            //  correcto y un mensaje con los campos correspondientes
            if($missingFields || !validarCampos($st)){
                displayStep1($missingFields);
            } else{
                    ingresarPost();
                    displayStep2(array());
               
            }
                break;
        
        case 'step2':
            
            if($missingFields || !validarCampos($st)){
                displayStep2($missingFields);
            } else {
                ingresarImagenes();
                displayStep2(array());
                
            }
            
        
    }
    
    
    
    
    
//fin processForm
}        
        
        ?>
    </body>
</html>
