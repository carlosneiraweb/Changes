<?php 


require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/Post.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/Usuarios.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/Imagenes.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/DataObj.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Controlador/Validar/ValidoForm.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Directorios.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Controlador/Validar/ControlErroresSistemaEnArchivosPost.php');

 if(!isset($_SESSION)) 
    { 
        session_start(); 
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
    header("Location: mostrar_error.php");
}  



//Variable que utiliza la pagina
//Mostrar error para devolvernos a 
//la pagina donde se a producido
$_SESSION["paginaError"] = basename($_SERVER['PHP_SELF']);
   

global $articulo;
$articulo = new Post(array());
global $pa_queridas;

?>
<!DOCTYPE html>
<!--
 author Carlos Neira Sanchez
 telefono ""
 nameAndExt subir_posts.php
 fecha 17-abr-2020
-->

<html>
    <head>
       
        <meta charset="UTF-8">
        <title>Sube tu artículo para poder intercambiarlo</title>
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
    echo '<section id="mostrarImgSeleccionada" class="">';
    echo '</section>';
        
        
       
    
    
    
 //Iniciamos la variable de session contador a 0
        //Iremos incrementando el numero de fotos subidas
            if(!isset($_SESSION['contador'])){
                $_SESSION['contador'] = 0; 
            }   
            
            
    //Si no se ha recivido el step
    //se muestra el formulario por primera vez
    if(!isset($_POST['step'])){  
      
        displayStep1(array());
    }
   
    if(isset($_SESSION['nuevoSubdirectorio'])){
        $eliminarPost = "../photos/".$_SESSION['nuevoSubdirectorio'][0]."/".$_SESSION['nuevoSubdirectorio'][1];
            
    }
   
    /*Mandamos a comprobar los campos del primer formulario*/
    if(isset($_POST['primeroSubirPost']) and $_POST['primeroSubirPost'] == "Siguiente"){        
        $requiredFields = array('tituloSubirPost', 'comentarioSubirPost','precioSubirPost');    
        //Pos si el usuario vuelve a este paso y decide
        //no publicar el post
          processForm($requiredFields, "step1");
          
    } elseif(isset($_POST['primeroSubirPostSalir']) and $_POST['primeroSubirPostSalir'] == "Salir"){
        //Llamamos a este metodo cuando el usuario ha pasado al segundo paso
        //luego vuelve al paso anterior y decide no subir el post
        //Llamamos a este metodo para eliminar los datos con los que hemos trabajado
        if(isset($_SESSION['atras']) and $_SESSION['atras'] === "atras"){
            Directorios::eliminarDirectoriosSistema($eliminarPost,"SubirPost");
            Post::eliminarPostId($_SESSION["lastId"][0],"SubirPost");
            MisExcepcionesPost::eliminarVariablesSesionPostAcabado();
            //Estas variables de sesion no se destruyen junto a las 
            //otras por que es necesaria para hacer un update
            //del post mientras se esta publicando.
            //Solo se puede destruir cuando se finaliza el proceso de publicar.
            //o deciden no publicarlo
            if(isset($_SESSION['lastId'])){unset($_SESSION['lastId']);}
            //Solo cuando se ha acabado de publicar el post
            if(isset($_SESSION['nuevoSubdirectorio'])){unset($_SESSION['nuevoSubdirectorio']);}  
            
                
        }else{
            //Si no se ha llegado al segundo paso
            //redirigimos a index.php y eliminamos las variables
            //con las que hemos trabajado
            MisExcepcionesPost::eliminarVariablesSesionPostAcabado();
            
        } 
        
        
        header(MOSTRAR_PAGINA_INDEX);
        
    } elseif(isset($_POST['segundoSubirPost']) and $_POST['segundoSubirPost'] == "Enviar" ){    
        //El usario  quiere subir una foto al post
        $requiredFields = array();
        processForm($requiredFields, "step2");
        
    } elseif(isset($_POST['segundoSubirPost']) and $_POST['segundoSubirPost'] == "Atras"){
        //Esto significa que el usuario ha dado un paso atras en el formulario
        //Lo que hacemos es actualizar los datos, no volver a registrar el post
        //Para ello instanciamos una variable de session para que lo tenga en cuenta
        //Al ingresar en la bbdd
        $_SESSION['atras'] = 'atras';
             
        displayStep1(array());
        
                
                
    } elseif(isset($_POST['segundoSubirPost']) and $_POST['segundoSubirPost'] == "Fin"){
        //El usuario ha terminado de ingresar los datos del post
        //Le redirigimos a cualqier url que estuviera
        //Destruimos la sesion atras, la sesion contador y si existiera la 
            //la variable de imagenes borradas
            MisExcepcionesPost::eliminarVariablesSesionPostAcabado(); 
            //Estas variables de sesion no se destruyen junto a las 
            //otras por que es necesaria para hacer un update
            //del post mientras se esta publicando.
            //Solo se puede destruir cuando se finaliza el proceso de publicar.
            if(isset($_SESSION['lastId'])){unset($_SESSION['lastId']);}
            //Solo cuando se ha acabado de publicar el post
            if(isset($_SESSION['nuevoSubdirectorio'])){unset($_SESSION['nuevoSubdirectorio']);}  
                
                volverAnterior();
           
        
        //Parte del formulario agregado con JQUERY subirPost.js
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
        
         
                //Aqui escojemos el valor del campo textarea comentarioPost
                
                    if(isset($_SESSION['post']['comentarioSubirPost'])){
                        $coment = $_SESSION['post']['comentarioSubirPost'];

                        //Usamos las comillas `` para hacer una plantilla
                        //como noredoc en php
                            echo '<script type="text/javascript">'; 
                            echo "var coment = `".$coment."`;"; //"`$coment".';'
                            echo '</script>';   
                
                    }else{
                            echo '<script type="text/javascript">'; 
                            echo 'var coment = " ";';    //"; echo "".';'
                            echo '</script>'; 
                    }
        
        
    echo'<section id="form_post_1" class="fuenteFormulario">';
                echo'<h4>Introduzca los datos del artículo</h4>';
    echo'<form name="post" action="subir_posts.php" method="post" id="post_1" >';
        echo'<fieldset>';
        	echo'<legend>Rellena los campos</legend>';
        echo"<input type='hidden' name='step' value='1'>"; 
        
    echo '<section class="contenedor">'; 
    //
    
    echo'<label '.ValidoForm::validateField("tituloSubirPost", $missingFields).'  for="tituloSubirPost">Introduce un título para el anuncio. </label><span class="obligatorio"><img src="../img/obligado.png" alt="campo obligatorio" title="obligatorio"></span>';
    echo'<input type="text" maxlength="50" name="tituloSubirPost" id="tituloSubirPost" autofocus placeholder="Máximo 60 caracteres." value="';if(isset($_SESSION['post']['tituloSubirPost'])){echo $_SESSION['post']['tituloSubirPost'];} echo '">'; 
    echo'<label><span class="cnt">0</span></label>';
    echo'</section>';

    echo'<label for="seccion">Seccion:</label>';
 
	echo'<select name="seccionSubirPost" id="seccionSubirPost">';
           
               echo'</select>'; 

                echo'<br>';
                echo'<br>';
   
                                                                                                                                                                         
    echo '<section class="contenedor">';
    echo'<label '.ValidoForm::validateField("comentarioSubirPost", $missingFields). ' for="comentarioSubirPost">Introduce una descripción general del artículo. </label><span class="obligatorio"><img src="../img/obligado.png" alt="campo obligatorio" title="obligatorio"></span>';
    echo'<textarea spellcheck="true" maxlength="255" name="comentarioSubirPost" id="comentarioSubirPost" placeholder= "Máximo 255 caracteres." value="';if(isset($_SESSION["post"]["comentarioSubirPost"])){echo $_SESSION["post"]["comentarioSubirPost"]; } echo'">'; 
    echo'</textarea>';
    echo'<label><span class="cnt">0</span></label>';
    echo'</section>';
    
    
  
    echo '<section class="contenedor">';
    echo'<label '.ValidoForm::validateField("precioSubirPost", $missingFields).' for="precioSubirPost">Introduce un precio aproximado  artículo. </label><span class="obligatorio"><img src="../img/obligado.png" alt="campo obligatorio" title="obligatorio"></span>';
    echo'<input type="text" maxlength="5" name="precioSubirPost" id="precioSubirPost" placeholder="Precio aproximado, máximo 10 caracteres, solo se aceptan dígitos." maxlength="10" value="';if(isset($_SESSION['post']['precioSubirPost'])){echo $_SESSION['post']['precioSubirPost'];} echo '">';
     
 
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
    echo'<input type="text" name="querida_1" id="querida_1" placeholder="Máximo 40" maxlength="40"   value="';if(isset($_SESSION['post']['Pa_queridas'][0])){echo $_SESSION['post']['Pa_queridas'][0];} echo '">';
        echo'<label><span class="cnt">0</span></label>';
    echo'<input type="text" name="querida_2" id="querida_2" maxlength="40" value="';if(isset($_SESSION['post']['Pa_queridas'][1])){echo $_SESSION['post']['Pa_queridas'][1];} echo '">';
        echo'<label><span class="cnt">0</span></label>';
    echo'<input type="text" name="querida_3" id="querida_3" maxlength="40" value="';if(isset($_SESSION['post']['Pa_queridas'][2])){echo $_SESSION['post']['Pa_queridas'][2];} echo '">';
        echo'<label><span class="cnt">0</span></label>';
    echo'<input type="text" name="querida_4" id="querida_4" maxlength="40" value="';if(isset($_SESSION['post']['Pa_queridas'][3])){echo $_SESSION['post']['Pa_queridas'][3];} echo '">';
        echo'<label><span class="cnt">0</span></label>';
        echo'</section>';
     
    echo '</section>';
    
    
    echo '<section id="contenedorOfrecidas" class="contenedor">'; 
    echo'<label  for="Pa_ofrecidas" class="centrar">Introduce 4 pequeñas frases para que la gente encuentre tu artículo. </label>';
        echo '<section id="ofrecidas" class="introducir_palabras">';
    echo'<input type="text" name="ofrecida_1" id="ofrecida_1" placeholder="Máximo 40" maxlength="40" value="';if(isset($_SESSION['post']['Pa_ofrecidas'][0])){echo $_SESSION['post']['Pa_ofrecidas'][0];} echo '">';
        echo'<label><span class="cnt">0</span></label>';
    echo'<input type="text" name="ofrecida_2" id="ofrecida_2" maxlength="40" value="';if(isset($_SESSION['post']['Pa_ofrecidas'][1])){echo $_SESSION['post']['Pa_ofrecidas'][1];} echo '">';
        echo'<label><span class="cnt">0</span></label>';
    echo'<input type="text" name="ofrecida_3" id="ofrecida_3" maxlength="40" value="';if(isset($_SESSION['post']['Pa_ofrecidas'][2])){echo $_SESSION['post']['Pa_ofrecidas'][2];} echo '">';
        echo'<label><span class="cnt">0</span></label>';
    echo'<input type="text" name="ofrecida_4" id="ofrecida_4" maxlength="40" value="';if(isset($_SESSION['post']['Pa_ofrecidas'][3])){echo $_SESSION['post']['Pa_ofrecidas'][3];} echo '">';
        echo'<label><span class="cnt">0</span></label>';
        echo '</section>';
        
    echo '</section>';
           
    echo '<section id="btns_registrar">';    
        echo"<input type='submit' name='primeroSubirPost' id='primeroSubirPost'  value='Siguiente' >";
        echo"<input type='submit' name='primeroSubirPostSalir' id='primeroSubirPostSalir'  value='Salir' >";
    echo '<section>';
    
        //Mostramos cualquier errror al validar el formulario            
            echo "</form>";
        if($mensaje){
            echo $mensaje;
        }    
        echo'</fieldset>';  
        
    echo'</section>';
  
    //Agregamos el contenido del textarea 
    //comentario Post
    if($coment !== ""){
        echo '<script type="text/javascript">';

            echo 'if( coment != ""){';
                //echo "document.getElementById('comentarioSubirPost').innerHTML = coment; ";
                echo "$('#comentarioSubirPost').html(coment);";       
            echo '}else{';
                //echo "document.getElementById('comentarioSubirPost').innerHTML = coment; ";
                echo "$('#comentarioSubirPost').html('');"; 
            echo '}';

        echo '</script>'; 
    }
 //fin displayStep1
}    
        

function displayStep2($missingFields){
   

    global $mensaje; 
  
    
     //Aqui recuperamos el id del post en el que estamos
        //Se lo pasamos a javascript para que nos muestre 
        //todas las fotos que vamos subiendo via JSON
        //Se instacia en el metodo insertarPost de la clase Post.php
        //Hay que tener en cuenta que primero se inserta en la bbdd
        //todos los datos introducidos en el primer formulario.
        //En el primer paso se hace el insert del Post y 
        //ya tenemmos su id, si
        //luego el usuario quiere puede subir fotos.
        //Ahi es cuando se utiliza.
            if(isset($_SESSION['lastId']) ){
              
                $idPost = $_SESSION['lastId'][0];
            echo '<script type="text/javascript">';
            
                echo "var idPost = "; echo "'$idPost'".";";
            echo '</script>';
         
            }
 
    echo'<section id="form_post_2" class="fuenteFormulario">';
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
      //
    echo'<form name="post" action="subir_posts.php"   method="POST" id="post" enctype="multipart/form-data">';
        echo'<fieldset>';
        	echo'<legend>Introduce alguna imagen.</legend>';
        echo"<input type='hidden' name='step' value='2'>"; 
        //Limitamos el valor máximo del archivo
        // echo'<input type="hidden" name="MAX_FILE_SIZE" value="3145728" />';
        echo '<section class="contenedor">'; 
        echo'<label for="photo">Solo fotos .jpg</label>';
        echo '<br>';    
            echo'<input type="file" name="photoArticulo" id="photo" value="" />';        
        echo'</section>';
        
        
        
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
 * Este metodo hace la insercion en la bbdd </br>
 * de los datos introducidos en el formulario. </br>
 * No hace inserciones en la tabla imagenes ni mueve </br>
 * las imagenes al directorio correspondiente </br>
 * Por que el usuario no esta obligado a subir imagenes </br>
 * @global Post $articulo
 */
function ingresarPost(){
    //Declaramos variable articulo
    global $articulo;
    
    
    
    
        $articulo = new Post(array(
            "idUsuarioPost" => $_SESSION['userTMP']->getValue('nick'),
            "seccionesIdsecciones" => $_SESSION['post']['seccionSubirPost'],
            "tiempoCambioIdTiempoCambio" => $_SESSION['post']['tiempoCambioSubirPost'],
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
        // o ha ido un paso atras y ha modificado algun dato.
        //Si el usuario esta en la segunda parte del formulario y
        // retroce entonces se modifica los datos introducidos
        //La ultima comprobacion es por si el usuario a intentado subir un Post
        //y el Sistema no ha cometido ningun error. En ese caso si el usuario
        //vuelve intentarlo se ingresa un post de nuevo.
        
        //|| isset($_SESSION['error'])
        if(isset($_SESSION['atras'])  ){ 
           
            
            $articulo->actualizarPost();
            
            
                
        }else{
            
           $articulo->insertPost(); 
           

        }

        /******************************************************************/
        unset($articulo);
        session_write_close();   
//fin ingresarPost    
}


/**
 * Este metodo ingresa en la bbdd en la tabla de imagenes </br>
 * las rutas de las imagenes que va subiendo el usuario</br>
 * cada vez que sube una imagen.</br>
 * Es llamado desde procesForm una vez a validado las imagenes.</br>
 * $_SESSION['post']['figcaption']</br>
 * Se instancia en el formulario subir_post </br>
 * de este mismo archivo, en el step 2.</br>
 * $_SESSION['dirImagen'] = 60/2/1</br>
 * Se instancia en ControlErroresSistemaEnArchivosPOst al validar la foto y cambiarle </br>
 * el nombre en la clase Directorios.</br>
 * En este metodo solo se comprueba que el sistema a</br>
 * podido ingresar la imagen en la bbdd</br>
 * De moverla al directorio adecuado se encarga</br>
 * la funcion validar campos.
*/

function ingresarImagenes(){
   
    global $articulo;
           
    $articulo = new Imagenes(array(
       "postIdPost" => $_SESSION['lastId'][0],
       "figcaption" => $_SESSION['post']['figcaption'],
       "directorio" => $_SESSION['dirImagen'] //step 2 controlsErroresArchivos ejemplo 185/5/1
    ));
   
   $articulo->insertarFotos();

    
//fin ingresarImagenes    
}





/**
 * Metodo que actualiza el texto de una imagen.<br/>
 * Las variables $_POST['txtModificar'] y $_POST['ruta']<br/>
 *  vienen de dos campos ocultos del formulario cargarImgEliminar<br/>
 *  creado en subirPosts.js
 */
function actualizarImagen(){
    
    $articulo = new Imagenes(array(
       "figcaption" => $_POST['txtModificar'],
       "idImagen" => $_POST['idImagen'],
       "postIdPost" => $_SESSION['lastId'][0]
    ));
    
    
   $articulo->actualizarTexto();
    

    
//fin actualizarImagen    
}

/**
 * Metodo que elimina una imagen <br/>
 * mientras el usuario esta publicando un Post.<br/>
 * Se generan dos campos hidden<br/>
 * idImagen y ruta, al mostrar el formulario <br/>
 * para eliminar o actualizar la imagen. <br/>
 * Archivo subirPost.js
 *
 */
function eliminarImagen(){
    
    $img = new Imagenes(array(
        "idImagen" => $_POST['idImagen'],
        "directorio" => $_POST['ruta']
    ));
            
   $img->eliminarImg();
   
    
//    
//fin eliminar imagen   
}


function processForm($requiredFields, $st){
    //Array para almacenar los campos no rellenados y obligatorios
        global $missingFields;
        $missingFields = array();
        global $pa_queridas;
       
        
        

        switch ($st){
            case 'step1':
                
                $_SESSION['post']['seccionSubirPost'] = $_POST['seccionSubirPost'];//) ? $_POST['seccionSubirPost'] : "";
                $_SESSION['post']['tiempoCambioSubirPost'] = $_POST['tiempoCambioSubirPost'];//) ? $_POST['tiempoCambioSubirPost'] : "";
                $_SESSION['post']['tituloSubirPost'] = $_POST["tituloSubirPost"];       
                $_SESSION['post']['comentarioSubirPost'] = $_POST['comentarioSubirPost'];
                $_SESSION['post']['precioSubirPost'] = $_POST['precioSubirPost'];
                $_SESSION['post']['Pa_queridas'][0] = $_POST["querida_1"];
                $_SESSION['post']['Pa_queridas'][1] = $_POST["querida_2"];
                $_SESSION['post']['Pa_queridas'][2] = $_POST["querida_3"];
                $_SESSION['post']['Pa_queridas'][3] = $_POST["querida_4"];
                $_SESSION['post']['Pa_ofrecidas'][0] = $_POST["ofrecida_1"];
                $_SESSION['post']['Pa_ofrecidas'][1] = $_POST["ofrecida_2"];
                $_SESSION['post']['Pa_ofrecidas'][2] = $_POST["ofrecida_3"];
                $_SESSION['post']['Pa_ofrecidas'][3] = $_POST["ofrecida_4"];
               
              
                
                
                break;
            
                
                
                
            case 'step2':
                $_SESSION['post']['figcaption'] = $_POST['figcaption']; 
                break;
        }

    foreach($requiredFields as $requiredField){
        if(!$_SESSION['post'][$requiredField]){
            $missingFields[] = $requiredField;
        }
    }

    
       
    //Mandamos a validar al metodo anterior los campos segun 
    //cada paso del formulario
    switch ($st){
   
        case 'step1':
       
            //Si ha habido algun error volvemos a mostrar el paso del formulario
            //  correcto y un mensaje con los campos correspondientes
            
            validarCamposSubirPost($st);
             
             
            if($missingFields){
                displayStep1($missingFields);
            } else{

                ingresarPost();
                displayStep2(array());
                
            }
                break;
        
        case 'step2':
         
           
            validarCamposSubirPost($st);
            if($missingFields){
                displayStep2($missingFields);
            }else {
                //Por si el usuario ha tratado de subir
                //formato png
                //if(!isset($_SESSION['png'])){
                    ingresarImagenes();
                    
                    /*
                    if($_SESSION['contador'] == '1'){
                                //Metodo que busca entre las palabras 
                        //que un usuario ha guardado en sus
                        //busquedas personales
                        //si coincide se le manda email
                        $pa_queridas = array(
                            
                        $_SESSION['post']['Pa_ofrecidas'][0],
                        $_SESSION['post']['Pa_ofrecidas'][1],
                        $_SESSION['post']['Pa_ofrecidas'][2],
                        $_SESSION['post']['Pa_ofrecidas'][3]
                    );

                        $datosPost = array();
                        array_push($datosPost, $pa_queridas, $_SESSION['post']['seccionSubirPost'],$_SESSION['userTMP']->getValue('nick'),$_SESSION['lastId'][0]);
                       // var_dump($datosPost);
                        //Para una mejor busqueda hemos creado
                        //el campo de la tabla como full text
                        //$articulo->buscarUsuariosInteresados($datosPost);
                    }
                     * */
                     
                //}
                displayStep2(array());
               
                
            }
        
    //fin switch    
    }
    
    
    
    
    
//fin processForm
}

    
            
        ?>
    </body>
</html>
