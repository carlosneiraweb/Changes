<?php 


require_once ($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/Post.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/Usuarios.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/DataObj.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Controlador/Validar/ValidoForm.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Directorios.php');



session_start();

//Iniciamos la variable de session contador a 0
//Iremos incrementando el numero de fotos subidas
if(!isset($_SESSION['contador'])){
    $_SESSION['contador'] = 0; 
}

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

//Variable que utiliza la pagina
//Mostrar error para devolvernos a 
//la pagina donde se a producido
$_SESSION["paginaError"] = basename($_SERVER['PHP_SELF']);


/**
 * Este metodo manda a EliminarPost de la clase Post,
 * cuando un usuario quiere subir un post 
 * y a mitad de proceso se sale y no
 * acaba publicandolo
 */
 function eliminarPostAlPublicar(){
   $test = true;
   //SubDirectorio que se creo para ir subiendo los post
     //../photos/carlos/10
   
   $tmp=  $_SESSION['nuevoSubdirectorio'];//de fotos
   //$tmpSubdirectorio = preg_split("~[\\\/]~", $tmp);
   //El id del post a eliminar
   $idPost = $_SESSION['lastId'][0];
   //El id de las imagenes
   $idImagenes = $_SESSION['lastId'][0];
           
   if($idPost !== null){
   $test = Post::eliminarImagenesPost($idImagenes);
   }
   if($test){
    $test= Directorios::eliminarDirectorioRegistro($tmp);
   }
        if($test){
                $test = Post::eliminarPostId($idPost);
        }
    echo "eliminar post = ".$test.'<br>';    
   return $test;
}

/**
 * Metodo que elimina variables de sesion
 * cuando un usuario ha acabado de subir 
 * un post
 */
function eliminarVariablesSesionPostAcabado(){
    
    if(isset($_SESSION['imgTMP'])){
            unset($_SESSION['imgTMP']);
        }
            
    if(isset($_SESSION['atras'])){
            unset($_SESSION['atras']);
        }
        
    if(isset($_SESSION['contador'])){
            unset($_SESSION['contador']);
        }       
           
             
    //fin eliminarVariablesSesionPostAcabado()         
    }
    
    





global $articulo;
$articulo = new Post(array());
?>
<!DOCTYPE html>
<!--
 author Carlos Neira Sanchez
 telefono ""
 nameAndExt subir_posts.php
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
        <script src="../Controlador/Elementos_AJAX/CONEXION_AJAX.js"></script>
        <script src="./subirPost.js"></script>
        <script src="../Controlador/Validar/contador.js"></script>
        <script src="../Controlador/Validar/formulario_subir_post.js"></script>
        <script src="../Controlador/Validar/iconoObligatorio.js"></script>
        
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
    
    
            //Aqui mostramos la imagen ampliada
            //Por si el usuario quiere modificar
            //una imagen que esta subiendo o el texto
            //Se muestra desde JSON
    echo '<section id="mostrarImgSeleccionada" class="generalFormularios">';
    echo '</section>';
    
    
    
    //Si no se ha recivido el step
    //se muestra el formulario por primera vez
    if(!isset($_POST['step'])){      
        displayStep1(array());
    }
    
   
    /*Mandamos a comprobar los campos del primer formulario*/
    if(isset($_POST['primeroSubirPost']) and $_POST['primeroSubirPost'] == "Siguiente"){        
        $requiredFields = array('tituloSubirPost', 'comentarioSubirPost','precioSubirPost');
        //Pos si el usuario vuelve a este paso y decide
        //no publicar el post
          processForm($requiredFields, "step1");
    } elseif(isset($_POST['primeroSubirPostAtras']) and $_POST['primeroSubirPostAtras'] == "Salir"){
        //Llamamos a este metodo cuando el usuario ha pasado al segundo paso
        //luego vuelve al paso anterior y decide no subir el post
        //Llamamos a este metodo para eliminar los datos con los que hemos trabajado
        if(isset($_SESSION['atras']) and $_SESSION['atras'] === "atras"){
           $test=  eliminarPostAlPublicar();
            
                if(!$test){
                    $_SESSION["paginaError"] = "index.php";
                    eliminarVariablesSesionPostAcabado();
                    $_SESSION['error'] = ERROR_INSERTAR_ARTICULO;
                    mostrarError();
                }
        } 
        //Si se han eliminado bien lo redirigimos
        //al index.php y eliminamos las variables
        //con las que hemos trabajado
        eliminarVariablesSesionPostAcabado();
        header('Location:'. "index.php");
    } elseif(isset($_POST['segundoSubirPost']) and $_POST['segundoSubirPost'] == "Enviar" ){    
        //El usario  quiere subir una foto al post
        $requiredFields = array();
        processForm($requiredFields, "step2");
        
    } elseif(isset($_POST['segundoSubirPost']) and $_POST['segundoSubirPost'] == "Atras"){
        //Esto significa que el usuario ha dado un paso atras en el formulario
        //Lo que hacemos es actualizar los datos, no volver a registrarlo
        //Para ello instanciamos una variable de session para que lo tenga en cuenta
        //Al ingresar en la bbdd
        $_SESSION['atras'] = 'atras'; 
        displayStep1(array());
    } elseif(isset($_POST['segundoSubirPost']) and $_POST['segundoSubirPost'] == "Fin"){
        //El usuario ha terminado de ingresar los datos del post
        //Le redirigimos a cualqier url que estubiera
        //Destruimos la sesion atras, la sesion contador y si existiera la 
            //la variable de imagenes borradas
            eliminarVariablesSesionPostAcabado(); 
            //Esta nvariable de sesion no se destruye junto a las 
            //otras por que es necesaria para hacer un update
            //del post mientras se esta publicando.
            //Solo se puede destruir cuando se finaliza el proceso de publicar.
            if(isset($_SESSION['lastId'])){
                unset($_SESSION['lastId']);
            }
            volverAnterior();
        
        
        //Parte del formulario agregado con JQUERY 
        //Se utiliza para cuando un usuario quiere
        //borrar o modificar una imagen al subir un Post
        
    } elseif(isset($_POST['modificar']) && $_POST['modificar'] == 'Borrar'){
        eliminarImagen();
        displayStep2(array());
    } elseif(isset($_POST['modificar']) && $_POST['modificar'] == 'OK'){
        actualizarImagen();
        displayStep2(array());
    }

    function displayStep1($missingFields){
        global $mensaje; 
        echo '<script type="text/javascript">';
           //Indicamos que elementos vamos a cargar
           //De esta manera controlamos que peticiones hacemos en cada pagina
           echo 'var PS = true;';
           echo 'var PT = true;';
        echo '</script>';
        
    echo'<section id="form_post_1" class="fuenteFormulario, generalFormularios">';
                echo'<h4>Introduzca los datos del artículo</h4>';
    echo'<form name="post" action="subir_posts.php" method="post" id="post_1" >';
        echo'<fieldset>';
        	echo'<legend>Rellena los campos</legend>';
        echo"<input type='hidden' name='step' value='1'>"; 
        
    echo '<section class="contenedor">';    
    echo'<label '.ValidoForm::validateField("tituloSubirPost", $missingFields).'  for="tituloSubirPost">Introduce un título para el anuncio. </label><span class="obligatorio"><img src="../img/obligado.png" alt="campo obligatorio" title="obligatorio"></span>';
    echo'<input type="text" maxlength="60" name="tituloSubirPost" id="tituloSubirPost" autofocus placeholder="Máximo 60 caracteres."  value="';if(isset($_SESSION['post']['tituloSubirPost'])){echo $_SESSION['post']['tituloSubirPost'];} echo '">'; 
    echo'<label><span class="cnt">0</span></label>';
    echo'</section>';

    echo'<label for="seccion">Seccion:</label>';
 
	echo'<select name="seccionSubirPost" id="seccionSubirPost">';
           
               echo'</select>'; 

                echo'<br>';
                echo'<br>';
   
                                                                                                                                                                         
    echo '<section class="contenedor">';
    echo'<label '.ValidoForm::validateField("comentarioSubirPost", $missingFields). ' for="comentarioSubirPost">Introduce una descripción general del artículo. </label><span class="obligatorio"><img src="../img/obligado.png" alt="campo obligatorio" title="obligatorio"></span>';
    echo'<textarea maxlength="255" name="comentarioSubirPost" id="comentarioSubirPost" placeholder= "Máximo 255 caracteres." maxlength="255" value="';if(isset($_SESSION['post']['comentarioSubirPost'])){echo $_SESSION['post']['comentarioSubirPost'];}  echo'">'; 
    echo'</textarea>';
    echo'<label><span class="cnt">0</span></label>';
    echo'</section>';
    
    echo '<section class="contenedor">';
    echo'<label '.ValidoForm::validateField("precioSubirPost", $missingFields).' for="precioSubirPost">Introduce un precio aproximado  artículo. </label><span class="obligatorio"><img src="../img/obligado.png" alt="campo obligatorio" title="obligatorio"></span>';
    echo'<input type="text" maxlength="10" name="precioSubirPost" id="precioSubirPost" placeholder="Precio aproximado, máximo 10 caracteres, solo se aceptan dígitos." maxlength="10" value="';if(isset($_SESSION['post']['precioSubirPost'])){echo $_SESSION['post']['precioSubirPost'];} echo '">';
    echo'<label><span class="cnt">0</span></label>';
    echo'</section>';
    
    
    echo'<label for="tiempoCambio">Elige por cuanto tiempo deseas hacer el cambio.</label>';
 
	echo'<select name="tiempoCambioSubirPost" id="tiempoCambioSubirPost">';
           
               echo'</select>'; 

                echo'<br>';
                echo'<br>';
     
                
    
    echo '<section id="contenedorQueridas" class="contenedor">';
    echo'<label  for="Pa_queridas" class="centrar">Introduce 4 pequeñas frases por lo que tú estarías interesado en cambiarlo. </label>';
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
    
    
    echo '<section id="contenedorOfrecidas" class="contenedor">'; 
    echo'<label  for="Pa_ofrecidas" class="centrar">Introduce 4 pequeñas frases para que la gente encuentre tu artículo. </label>';
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
        echo"<input type='submit' name='primeroSubirPost' id='primeroSubirPost'  value='Siguiente' >";
        echo"<input type='submit' name='primeroSubirPostAtras' id='primeroSubirPostAtras'  value='Salir' >";
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
        //Se instacia en el metodo insertarPost de la clase Post.php
        //Hay que tener en cuenta que primero se inserta en la bbdd
        //luego si el usuario quiere puede subir fotos.
        //Ahi es cuando se utiliza.
            if(isset($_SESSION['lastId']) ){
                $idPost = $_SESSION['lastId'][0];
            echo '<script type="text/javascript">';
                echo "var idPost = "; echo "'$idPost'".";";
            echo '</script>';
         
            }
 
    echo'<section id="form_post_2" class="fuenteFormulario, generalFormularios">';
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
                //Que el usuario va subiendo en cada nuevo post
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
        
        
                        echo"<input type='submit' name='segundoSubirPost' id='atrasSubirPost'  value='Atras'>";
                    if($_SESSION['contador'] < 5){
                        echo"<input type='submit' name='segundoSubirPost' id='enviarSubirPost'  value='Enviar'>";
                    }    
                        echo"<input type='submit' name='segundoSubirPost' id='segundoSubirPost' value='Fin' >";
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
 * Este metodo hace la insercion en la bbdd
 * de los datos introducidos en el formulario.
 * No hace inserciones en la tabla imagenes ni mueve 
 * las imagenes al directorio correspondiente
 * Por que el usuario no esta obligado a subir imagenes
 * @global Post $articulo
 */
function ingresarPost(){
    global $articulo;
    
    
        $articulo = new Post(array(
            "idUsuarioPost" => $_SESSION['user']->getValue('nick'),
            "secciones_idsecciones" => $_SESSION['post']['seccionSubirPost'],
            "tiempo_cambio_idTiempoCambio" => $_SESSION['post']['tiempoCambioSubirPost'],
            "titulo" => $_SESSION['post']['tituloSubirPost'],
            "comentario" => $_SESSION['post']['comentarioSubirPost'],
            "precio" => $_SESSION['post']['precioSubirPost'],
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
        // o ha ido un paso atras y ha modificado algun dato
        //Si el usuario en la segunda parte del formulario
        // retroce entonces se modifica los datos introducidos
        if(isset($_SESSION['atras']) || $_SESSION['error'] === 'error'){ 
            $result = $articulo->actualizarPost();  
            
        }else{
            $result = $articulo->insertPost();
        }
        //En caso de error nos redirige a la pagina de error 
        //para que el usuario pueda intentarlo otra vez
            if(!$result){
                $_SESSION['error'] = ERROR_INSERTAR_ARTICULO;
                mostrarError();
                exit();
            } else{
                //Destruimos el objeto para no ocupar memoria
                unset($articulo);
            }

//fin ingresarPost    
}


/**
 * Este metodo ingresa en la bbdd en la tabla de imagenes
 * las imagenes que va subiendo el usuario
 * cada vez que sube una imagen.
 * Es llamado desde procesForm una vez a validado las imagenes.
 * $_SESSION['post']['figcaption']
 * Se instancia en el formulario subir_post 
 * de este mismo archivo.
 * $_SESSION['idImagen'] = ../photos/carlos/60/2.jpg
 * Se instancia en processForm al validar la foto y cambiarle 
 * el nombre en la clase Directorios.
*/

function ingresarImagenes(){
   
    $articulo = new Post(array(
       "figcaption" => $_SESSION['post']['figcaption'],
       "idImagen" => $_SESSION['idImagen']
    ));
   
    $result = $articulo->insertarFotos();
           // echo "resultado: ".$result;
     //En caso de error nos redirige a la pagina de error 
     //para que el usuario pueda intentarlo otra vez
        if(!$result){
            $_SESSION['error'] = ERROR_FOTO_GENERAL;
            mostrarError();
            exit();      
        } 
    
    return $result;
//fin ingresarImagenes    
}





/**
 * Metodo que actualiza una imagen
 * Las variables $_POST['txtModificar'] y $_POST['ruta']
 *  vienen de dos campos ocultos del formulario cargarImgEliminar
 *  creado en subirPosts.js
 */
function actualizarImagen(){
    
    $articulo = new Post(array(
       "figcaption" => $_POST['txtModificar'],
       "idImagen" => $_POST['ruta']
    ));
    
    
    $result = $articulo->actualizarTexto();
    
    if(!$result){
        //En caso de error nos redirige a la pagina de error 
        //para que el usuario pueda intentarlo otra vez
        $_SESSION['error'] = ERROR_FOTO_GENERAL;
        mostrarError();
        exit();
    } else{
        unset($articulo);
        
    }
    
//fin actualizarImagen    
}

/**
 * Metodo que elimina una imagen
 *  $_POST['ruta'] Se instancia en
 *  el formulario que esta generado totalmente con JQUERY
 *  Metodo cargarImgEliminar en un campo hidden
 * script  subirPost.js
 * @global Post $articulo
 *
 */
function eliminarImagen(){
    
    $articulo = new Post(array(
        "idImagen" => $_POST['ruta'] 
    ));
    
    $result = $articulo->eliminarImg();
    //Si ha habido algun error, nos redirige a la pagina que muestra un error
    //Sino, continuamos en el formulario
    if(!$result){
        $_SESSION['error'] = ERROR_FOTO_GENERAL;
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
                $_SESSION['post']['seccionSubirPost'] = isset($_POST['seccionSubirPost']) ? $_POST['seccionSubirPost'] : "";
                $_SESSION['post']['tiempoCambioSubirPost'] = isset($_POST['tiempoCambioSubirPost']) ? $_POST['tiempoCambioSubirPost'] : "";
                $_SESSION['post']['tituloSubirPost'] = isset($_POST["tituloSubirPost"]) ? preg_replace("/[^\-\_a-zA-Z0-9.,`'´ ñÑáéíóúäëïöü]/", "", $_POST["tituloSubirPost"]) : "";       
                $_SESSION['post']['comentarioSubirPost'] = isset($_POST['comentarioSubirPost']) ? preg_replace("/[^\-\_a-zA-Z0-9.,ºª`'´ Ññáéíóúäëïöü \n\r\rn\s]/", "", nl2br($_POST["comentarioSubirPost"])) : "";//Nos devuelve el string intruducido con saltos de linea HTML
                $_SESSION['post']['precioSubirPost'] = isset($_POST['precioSubirPost']) ? preg_replace("/[^\-\_a-zAZ0-9., €$]/", "", $_POST["precioSubirPost"]) : "";
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
    $testValidarCampos = true;
        
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
                if(($_SESSION['contador'] == 0) and (!isset($_SESSION['atras'])) ){
                   
                    $_SESSION['nuevoSubdirectorio'] = Directorios::crearSubdirectorio("../photos/".$_SESSION['user']->getValue('nick'));
                    $testValidarCampos = Directorios::copiarFoto("../photos/demo.jpg",$_SESSION['nuevoSubdirectorio']."/demo.jpg");
                 
                }
                return $testValidarCampos;    
                   
            break;
                 
    case('step2'):
        
        $testSubirArchivo = Directorios::validarFoto('photoArticulo');
        
        //Comprobamos que nos devuelve la constante 0 que significa que se 
        //ha subido correctamente o que no nos devuelve la constante 4
        //que signfica que no se ha elegido un archivo
        if($testSubirArchivo === 0){
            
                //Si la foto es correcta entonces eliminamos la imagen default 
                    //que subimos
            
                if(is_file($_SESSION['nuevoSubdirectorio'].'/demo.jpg')){
                    $testValidarCampos = unlink($_SESSION['nuevoSubdirectorio'].'/demo.jpg');  
                }
                  
                    $destino = $_SESSION['nuevoSubdirectorio'].'/'.basename($_FILES['photoArticulo']['name']);                   
                    $foto = $_FILES['photoArticulo']['tmp_name'];
            
                        $testValidarCampos = Directorios::moverImagen($foto, $destino);
                
            //Comprobamos que subiendo imagenes el usuario no ha eliminado ninguna
                //Si lo ha hecho le asignamos en el directorio photos/subdirectorio 
                //Ese nombre
                if(isset($_SESSION['imgTMP']) and $_SESSION['imgTMP'] != null){
                    
                    if($testValidarCampos){ $_SESSION['idImagen'] = Directorios::renombrarFoto($destino, 0);}  
                    
            //Aqui vamos subiendo las fotos al post mientras el usuario no 
                //halla eliminado ninguna mientras subia las fotos
                }elseif (!isset($_SESSION['imgTMP'])){
            
                    if($testValidarCampos){ $_SESSION['idImagen'] = Directorios::renombrarFoto($destino, 1);}
                
                }
               
                //Si hay algun tipo de error copiar las imagenes, crear archivos, etc
                //Redirigimos a la pagina de mostrar error
                 //Para que el usuario vuelva a intentarlo
                
                    if(!$testValidarCampos){
                        unset($testSubirArchivo);
                        eliminarVariablesSesionPostAcabado();
                        $_SESSION['error'] = ERROR_FOTO_GENERAL;
                        mostrarError();  
                        exit();
                    }
                
                    
        } else{
            //Si hay algun error al validar la imagen 
                //redirigimos a la pagina mostrarError
                //y le indicamos el motivo del error
                // Esto ultimo se hace en el switch del
                //metodo que valida la subida en el directorio Directorios
                if($testSubirArchivo === 4){
                        
                        if(!isset($_SESSION['atras'])){
                            $_SESSION['atras'] = 'atras';
                        }
                        mostrarError();
                        exit(0);
                        
                }else {
                        if(!isset($_SESSION['atras'])){
                            $_SESSION['atras'] = 'atras';
                        }
                        mostrarError();
                        exit(0);
                        
                }
        }
                   
    //switch        
    } 
    return $testValidarCampos;
    //fin validar campos
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
            
            
        
            if($missingFields ||  !validarCampos($st)){
                displayStep2($missingFields);
            } else {
                ingresarImagenes();
                displayStep2(array());
                
            }
            
    //fin switch    
    }
    
    
    
    
    
//fin processForm
}        
        
        ?>
    </body>
</html>
