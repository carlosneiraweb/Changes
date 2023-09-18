<?php 

require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/DataObj.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/ComentarioPost.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Controlador/Validar/ValidoForm.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Constantes/ConstantesErrores.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Constantes/ConstantesBbdd.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/Post.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/System.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/Usuarios.php');




 if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 


/**
 * @author Carlos Neira Sanchez
 * @mail arj.123@hotmail.es
 * @telefono ""
 * @nameAndExt index.php
 * @fecha 04-oct-2020
 */

 $_SESSION["url"] = basename($_SERVER['PHP_SELF']);   


?>

<!DOCTYPE html>

<html>
    <div id="ocultar" class="oculto"> </div>
    <head>
       <meta charset="utf8mb4_unicode_520_ci">
       <title>Tú portal de intercambio</title>
	<meta name="description" content="Portal para intercambiar las cosas que ya no usas o utilizas por otras que necesitas o te gustan."/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link href="../img/fabicon.ico" rel="icon" type="image/x-icon"/>
        <link rel="stylesheet" href="../css/estilos.css"/>
        
        <script src="../Controlador/jquery-2.2.2.js"></script>
        <script src="../Controlador/Validar/formulario_login.js"></script>
        
        <script src="../Controlador/Validar/iconoObligatorio.js"></script>
        <script src="../Controlador/Elementos_AJAX/CONEXION_AJAX.js"></script>
        <script src="../Controlador/redireccionar.js"></script>
        <script src="../Controlador/menuPrincipal.js"></script>
        <script src="../Controlador/script.js"></script>
        <script src="../Controlador/Elementos_AJAX/buscarComentarios.js"></script>
        <script src="../Controlador/Validar/formulario_comentarios.js"></script>
        <script src="../Controlador/Elementos_AJAX/menu.js"></script>
        <script src="../Controlador/Elementos_AJAX/paginacion.js"></script>
        
        
        
        <script src="../Controlador/Elementos_AJAX/cargarPostsElegido.js"></script>
        <script src="./mostrarPosts.js"></script>
        <script src="./buscador.js"></script>
        <script src="../Controlador/Elementos_AJAX/comentarios.js"></script>
        <script src="./mostrarComentarios.js"></script>
        
        <script src="../Controlador/Elementos_AJAX/bloquearDesbloquearUsuarios.js"></script>
        <script src="../Controlador/Elementos_AJAX/darBajaUsuario.js"></script>
        <script src="../Controlador/Elementos_AJAX/actualizarDatos.js"></script>
        <script src="./mostrarMenuUsuario.js"></script>
        <script src="../Controlador/Elementos_AJAX/principal.js"></script>
        <script src="../Controlador/Elementos_AJAX/subirComentarios.js"></script>
     
    <!-- PARA GOOGLE ANALYTICS-->
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-88763824-1"></script>
    <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', 'UA-88763824-1');
    </script>

      <!--<span class="icocor1">&#129505;</span> --> 
    <!--Para navegadores viejos-->
        <!--[if lt IE 9]>
            <script
        src="//html5shiv.googlecode.com/svn/trunk/html5.js">
        </script>
        <![endif]-->
        
    <script type="text/javascript">
           //Indicamos que elementos vamos a cargar
           //De esta manera controlamos que peticiones hacemos en cada pagina
           var PPS = true;
       </script>  
    </head>
    <body id="cuerpo">
    
        <?php
     //echo'<div id="ocultar" class="oculto"> </div>';         
        
        //Pasamos a JavaScript el tamaño de paginado de las paginas.
        //La utilizamos en el script elementos de javascript para mostrar 
        //paginados los posts y en json.php para la peticion 
        echo '<script type="text/javascript">';
               echo "var PAGESIZE = "; echo PAGE_SIZE.';';          
        echo '</script>';
    
    
       
    //Variable user para instanciar 
    //objetos usuario
    $userLogin;
  
    if(isset($_POST["logeo"]) and $_POST["logeo"] == "aceptar"){
       
        processForm();
        
    } else{
        
        //Si no se ha pulsado el boton de enviar se muestra por primera vez el formulario 'vacio'
        //Recive tres parametros
        //Un array para los campos perdidos
        //Una instancia de usuarios
        //Un bolean para saber si la validacion ha sido correcto
        //El formulario esta oculto por una clase css
        //se hace visible cuando se pulsa boton ingresar
        displayFormLogeo(array(), new Usuarios(array()), true); 
        }
    
        
        
   
       
    //en caso de error en la validacion PHP se muestra la capa de fondo
    function mostrarOculto(){
       
      // echo'<div id="ocultarPHP" class="mostrar_transparencia"></div>';
        echo'<div id="ocultar" class="mostrar_transparencia"></div>';
    }
    
    
  
    echo'<header>';
	echo'<figure id="logo" class="fade">';
		echo'<img src="../img/logo.png" alt="Logo del portal"/>';
		echo'<figcaption id="titulo">Cambia todo lo que ya no uses.</figcaption>';
	echo'</figure>';
	echo'<section id="cabecera">';
            echo'<section id="btns_logueo">';
			
                        //Mostramos la foto del usuario una vez se ha logueado
                            //Sin consultar la BBDD
                            
                        if(isset($_SESSION["userTMP"]) and $_SESSION["userTMP"] != ""){
                            echo '<section id="foto_usuario">';
                                echo '<figure id="img_usuario">';
                                $nickFoto = $_SESSION['userTMP']->getValue('idUsuario');
                                    echo '<img src='."../datos_usuario/".$nickFoto."/".$nickFoto.".jpg".' alt="imagen del usuario" title="Este eres tú"/>';
                                echo'</figure>';
                            echo '</section>';
                        }
                    
                    if((!isset($_SESSION["userTMP"]))){
                        echo'<input type="button" id="ingresar" name="ingresar" value="Ingresar"/>';
                        echo'<input type="button" id="registrar" name="registrar" value="Registrarse"/>';
                    }
            echo'</section>';
            
			echo'<h1>Te lo cambio</h1>';
			echo'<h3>Miles de personas compartiendo te están esperando.</h3>';
		
            echo '<section id="btns_sesion">';
          
                    if(isset($_SESSION["userTMP"]) and $_SESSION["userTMP"] != ""){
                       
                        echo'<input type="button" id="salirSesion" name="salirSesion" value="salirSesion"/>';
                        echo'<input type="button" id="menu" name="menu" value="menu"/>';
                        echo'<input type="button" id="publicar" name="publicar" value="Publicar"/>';
                        $nick = $_SESSION['userTMP']->getValue('nick');
                        
                        
                       // echo $nick;
                        //Pasamos una variable a javascript
                        //para que si el usuario esta logeado
                        //aparezca un boton para poder hacer comentarios
                        //de los posts, o poder guardar busquedas personalizadas.
                        //Declaramos la variable javascript user,
                        //Esta variable la usaremos  en buscador.js
                        //para almacenar busquedas concretas
                            echo '<script type="text/javascript">';
                                echo 'var logeoParaComentar = '; echo '"logeado";';
                                echo 'var user = '; echo "'$nick';";
                                
                            echo '</script>'; 
                    }
               
                echo '</section>';   
                
                
	echo'</section>';
     
    echo'</header>';
    
 

    echo'<nav class="slider-container">';
	echo'<figure id="derecha">';
		echo'<img src="../img/derecha.png" class="activar" alt="Botones de desplazamiento"/>';
	echo'</figure>';
	
            echo'<ul id="slider" class="slider-wrapper">';
                echo'<li class="slide-current"><a class="separarLetras">Inicio</a><a class="separarLetras">Automocion</a><a class="separarLetras">Ocio</a><a class="separarLetras" >Bricolaje</a></li>';
		echo'<li><a class="separarLetras">Inicio</a><a class="separarLetras">Electronica</a><a class="separarLetras">Cultura</a><a class="separarLetras">Moda</a></li>';
            echo'</ul>';

	echo'<figure id="izquierda" class="slider-controls, ocultar">';
		echo'<img src="../img/izquierda.png" class="activar"  alt="Botones de desplazamiento"/>';
	echo'</figure>';
    echo'</nav>';
   
    /**
     * En esta seccion agregamos el buscador por jquery
     * desde principal llama metodo en buscador
     */
    echo '<section id="buscar_datos">';
       
    echo '</section>';
   
    
    
    echo'<section id="contenedor">';
    
    /**
    * Elemento html que se agregaran 
    * el formulario para agregar busquedas personales
     * en archivo buscador.js
    */
        echo'<section id="busquedasPersonales">';
        echo'</section>';
        
        
        
    //para la publicidad
    echo'<aside id="publi">';
		echo'<p>Aqui va la publicidad</p>';
                echo'<div>';
        if(isset($_SESSION["userTMP"]) and $_SESSION['userTMP'] != ""){    
            
        }
            echo'</div>';
	echo'</aside>';
     
       
        /**
         * Elemento html que se agregaran los posts
         */
        echo'<section id="posts">';
        
        echo'</section>';
   
    
        /**
         * Elemento html que se agregara los
         * li para la navegacion
         */
        echo '<section id="btn_navegacion">';
        echo '</section>';
    
      /*
         * Elemento html al que se le 
         * añadira elementos para mostrar 
         * el posts seleccionado al hacer click
         * en la imagen en la pagina principal
         */ 
        echo '<section id="mostrarPostSeleccionado" class="oculto">';
        echo "</section>";
        
 
 //pincipal
 //subircomentario

 echo'<section id="form_comentario" class="oculto">'; 
   
                echo'<h4>Introduzca su Comentario</h4>';
    echo'<form name="comentario_post" action="index.php" method="POST" id="comentario_post">';
        echo'<fieldset>';
        	echo'<legend>Haz tú cometario</legend>';
    echo '<section id="comentar">';
    echo '<section class="contEtiquetas">';
    echo'<label for="tituloComentario">Titulo:</label> ';
    echo'<input type="text" name="tituloComentario" id="tituloComentario" autofocus  placeholder="Escribe el comentario" maxlength= "75" value="">';
     
    echo '<section class="contEtiquetas">';
    echo'<label  for="comentarioPost">Escribe tú comentario aqui. </label>';
    echo'<textarea spellcheck="true" maxlength="255" name="comentarioPost" id="comentarioPost" placeholder= "Máximo 255 caracteres." ';  echo'>'; 
    echo'</textarea>';
    echo '</section>';
    echo'</section>';
    
    echo '<section id="botones_comentar">';
    echo'<input type="button" id="btn_mandar_comentario" name="btn_mandar_comentario" value="Mandar" />'; 
    echo'<input type="button" id="btn_salir_comentario" name="btn_salir_comentario" value="Salir " />'; 
    
    echo'<figure id="vComentario">';
    echo'<img id="imgResultComentVerde" class="oculto" src="../img/verde.png" />';
    echo'<img id="imgResultComentRojo" class="oculto" src="../img/rojo.png" />';
    echo'<figcaption id="captionComentario"></figcaption>';
    echo'</figure>';
    
    
    echo '</section>';  
    
      echo "</form>";
      echo'</section>';    
  
       
      
 
      
  

 
 
 
 
 
 
function displayFormLogeo($missingFields, $user, $test){
      global $valido;
   
 echo"<section id='login_form' ";
    //Aqui se muestra o esconde el formulario login despues de las comprobaciones PHP
        //Dependiendo si el usuario ha rellenado todos los campos
    if($missingFields){
        echo 'class="mostrar_formulario"';
        echo '$("#ocultar").addClass("mostrar_transparencia")';
        //Que PHP no detecte ningun error, usuario no existe, error en el password
    } elseif (!$test) {
        echo 'class="mostrar_formulario"'; 
        echo '$("#ocultar").addClass("mostrar_transparencia")';
        //En caso de que en los casos contrarios no se den, se esconde el formulario
    }else{
       echo 'class="oculto"'; 
    }
    //cerramos section
    echo '>';
      
       
    	 echo'<h4>Introduzca sus datos</h4>';
    echo'<form name="logeo" action="index.php" method="post" id="form_login">';
        echo'<fieldset>';
        
            echo'<legend>Formulario de ingreso</legend>';
echo'<label '. ValidoForm::validateField("nick", $missingFields). ' for="nick" >Introduce nombre de usuario:</label><span class="obligatorio"><img src="../img/obligado.png" ></span>';
echo"<input  type='text' name='nick' id='nick' autofocus='autofocus' placeholder='Escribe tú nick' value="; if(isset($_POST["nick"])){echo $_POST["nick"];} echo "><br/><br/>";            
echo'<label '. ValidoForm::validateField("password", $missingFields).' for="password">Introduce tú password</label><span class="obligatorio"><img src="../img/obligado.png" ></span>';
echo"<input type='password' name='password' id='password' placeholder='Escribe tú password'  ><br/><br/>";


//Mostramos un error en el login
    if(!$test){

        
    
        
        //Si la cuenta del usuario ha sido bloqueada
                if(!isset($_SESSION["userTMP"])){

                    if($user->getValue('bloqueado') == "1"){
                       echo ERROR_VALIDACION_NO_ACTIVO;   
                    }elseif($user->getValue('bloqueado') == "2"){
                       echo ERROR_BAJA_PARCIAL;
                    }else{
                        echo ERROR_VALIDACION_LOGIN;
                    }

                }
    }

 
echo'<input type="submit" id="btn_login" name="logeo" value="aceptar" />'; 
echo'<input type="submit" id="btn_salir" name="salir" value="salir" />'; 
    
        echo'</fieldset>';
                echo'</form>';
        echo'</section>';
    
      
    //fin formLogeo   
    }
    
    
    
    
function processForm(){
    
    global $userLogin;
    //Secrea un array con los campos requeridos
            $requiredFields = array("nick", "password");
            //Array para almacenar los campos no rellenados y obligatorios
            $missingFields = array();
  
    $userLogin = new Usuarios(
            array(
                "nick" => isset($_POST["nick"]) ? preg_replace("/[^\-\_a-zA-Z0-9ñÑ]/", "", $_POST["nick"]) : "",
                "password" => isset($_POST["password"]) ? preg_replace("/[^\-\_a-zA-Z0-9ñÑ]/", "", $_POST["password"]) : "",          
  
            )
            );
   
    
  
            
    foreach($requiredFields as $requiredField){
        if(!$userLogin->getValue($requiredField)){
            
            $missingFields[] = $requiredField;
        }
    }
    
    if($missingFields){
       displayFormLogeo($missingFields, $userLogin, true);
       mostrarOculto();
    }elseif(!$loggedInMember = $userLogin->authenticate(1)) {
           
           //La identificacion no ha sido correcta 
            mostrarOculto();
            displayFormLogeo($missingFields, $userLogin, false);
            
       
    } else { 
       
        if($loggedInMember->getValue('bloqueado') != '0'){
            
            mostrarOculto();
            displayFormLogeo($missingFields, $loggedInMember, false);
            
        }else{
            
            $_SESSION["userTMP"] = $loggedInMember;
            
            }
         
         
    }
       // var_dump($_SESSION["userTMP"]);
        
        unset($loggedInMember);
        unset($userLogin);
        //session_write_close(); 
        
         
    
//fin processForm
}



    
    
       

       
    echo '</section>';    
     echo' <footer>';
  
    echo' <div class="medidas"><p>Ventana: <span id="span1"></span></div>';
    echo'<div class="medidas">Ancho Supercontenedor: <span id="span2"></span> px</p>';
   
     
     //validador web https://jigsaw.w3.org/css-validator/
     
    /* 
    
     <p>
    <a href="http://jigsaw.w3.org/css-validator/check/referer">
        <img style="border:0;width:88px;height:31px"
            src="http://jigsaw.w3.org/css-validator/images/vcss"
            alt="¡CSS Válido!" />
    </a>
    </p>
    
    <p>
    <a href="http://jigsaw.w3.org/css-validator/check/referer">
    <img style="border:0;width:88px;height:31px"
        src="http://jigsaw.w3.org/css-validator/images/vcss-blue"
        alt="¡CSS Válido!" />
    </a>
    </p>
        
    */
    
    // <?php
    echo'</footer>';
   
       
   
    echo '</body>';
    echo '</html>';
