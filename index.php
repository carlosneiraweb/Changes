<?php 
require_once('entidades/Usuarios.php');
require_once('entidades/DataObj.php');
require_once 'validar/ValidoForm.php';

session_start(); 
$_SESSION["url"] = basename($_SERVER['PHP_SELF']);
//Pasamos a JavaScript el tamaño de paginado de las paginas.
    //Solo es necesario para mostrarlos ya que es una constante y accedemos 
    //a ella directamente desde el script JSON
    //Por donde empezar a mostrar y la URL de la pagina concreta que llama al script JSON
 echo '<script type="text/javascript">';
               echo "var PAGESIZE = "; echo PAGE_SIZE;
 echo '</script>';
            
           
?>

<!DOCTYPE html>

<html>
    <head>
       <meta charset="UTF-8">
       <title>Tú portal de intercambio</title>
	<meta name="description" content="Portal para intercambiar las cosas que ya no usas o utilizas por otras que necesitas o te gustan."/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link href="img/fabicon.ico" rel="icon" type="image/x-icon"/>
	<link rel="stylesheet" href="css/estilos.css"/>
        <script src="jquery-2.2.2.js" type="text/javascript"></script>
        <script src="mostrar/menu.js"></script>	
        <script src="mostrar/elementos.js"></script>
        <script src="validar/formulario_login.js"></script>
        <script src="mostrar/redireccionar.js"></script>
        <script src="mostrar/script.js"></script>
        <script src="mostrar/main.js"></script>
    <!--Para navegadores viejos-->
        <!--[if lt IE 9]>
            <script
        src="//html5shiv.googlecode.com/svn/trunk/html5.js">
        </script>
        <![endif]-->
        
   </head>
   <body id="cuerpo">
        <?php
    
  
    global $valido;
    $valido = new ValidoForm();
    
    if(isset($_POST["logeo"]) and $_POST["logeo"] == "aceptar"){
        processForm();
    } else{
        //Si no se ha pulsado el boton de enviar se muestra por primera vez el formulario 'vacio'
        //Recive tres parametros
        //Un array para los campos perdidos
        //Una instancia de usuarios
        //Un bolean para saber si la validacion ha sido correcto
        displayFormLogeo(array(), new Usuarios(array()), true); 
        }
    //en caso de error se muestra la capa de fondo
    function mostrarOculto(){
       echo'<div id="ocultarPHP" class="mostrar_transparencia"></div>';
    }
    echo'<header>';
	echo'<figure id="logo" class="fade">';
		echo'<img src="img/logo.png" alt="Logo del portal"/>';
		echo'<figcaption id="titulo">Cambia todo lo que ya no uses.</figcaption>';
	echo'</figure>';
	echo'<section id="cabecera">';
            echo'<section id="btns_logueo">';
			
                        //Mostramos la foto del usuario una vez se ha logueado
                        if(isset($_SESSION["user"]) and $_SESSION != ""){
                            echo '<section id="foto_usuario">';
                                echo '<figure id="img_usuario">';
                                    echo '<img src='."datos_usuario/".$_SESSION['user']->getValue('nick')."/".$_SESSION['user']->getValue('nick').".jpg".' alt="imagen del usuario" />';
                            echo '</section>';
                        }
                echo'<input type="button" id="ingresar" name="ingresar" value="Ingresar"/>';
                echo'<input type="button" id="registrar" name="registrar" value="Registrarse"/>';
            echo'</section>';
            
			echo'<h1>Te lo cambio</h1>';
			echo'<h3>Miles de personas compartiendo te están esperando.</h3>';
		
            echo '<section id="btns_sesion">';
          
                    if(isset($_SESSION["user"]) and $_SESSION != ""){
                        echo'<input type="button" id="salirSesion" name="salirSesion" value="Salir Sesion"/>';
                        echo'<input type="button" id="menu" name="menu" value="menu"/>';
                     
                    }
               
                echo '</section>';   
                echo '</section>';
                
	echo'</section>';
     
    echo'</header>';
    
 echo'<div id="ocultar" class="oculto"> </div>'; 
 
      //class="oculto login_form_tamanyo"
function displayFormLogeo($missingFields, $user, $test){
      global $valido;
 echo"<section id='login_form' ";
    if($missingFields){ 
        echo 'class="mostrar_formulario"'; 
    } elseif (!$test) {
        echo 'class="mostrar_formulario"';    
    }else{
       echo 'class="oculto"'; 
    }
    //cerramos section
    echo '>';
    	 echo'<h4>Introduzca sus datos</h4>';
    echo'<form name="logeo" action="index.php" method="post" id="form_login">';
        echo'<fieldset>';
  
            echo'<legend>Formulario de ingreso</legend>';
echo'<label '.$valido->validateField("nick", $missingFields). ' for="nick" >Introduce nombre de usuario:</label><span class="obligatorio"><img src="img/obligado.png" ></span>';
echo'<input  type="text" name="nick" id="nick" autofocus placeholder="Escribe tú nick" value="'.$user->getValueEncoded("nick").'" ><br></br>';            
echo'<label '.$valido->validateField("password", $missingFields).' for="password">Introduce tú password</label><span class="obligatorio"><img src="img/obligado.png" ></span>';
echo'<input type="password" name="password" id="password" placeholder="Escribe tú password" value="'.$user->getValueEncoded("password").'" ><br><br>';

//Mostramos un error en el login
if(!$test){
    echo ERROR_VALIDACION_LOGIN;
}
echo'<input type="submit" id="btn_login" name="logeo" value="aceptar" />';          
    echo"</div>";
        echo'</fieldset>';
                echo'</form>';
        echo'</section>';
    
      
    //fin formLogeo   
    }
function processForm(){
    global $valido;
    //Secrea un array con los campos requeridos
            $requiredFields = array("nick", "password");
            //Array para almacenar los campos no rellenados y obligatorios
            $missingFields = array();
  
    $user = new Usuarios(
            array(
                "nick" => isset($_POST["nick"]) ? preg_replace("/[^\-\_a-zAZ0-9. ,'``'´áéíóúäëïöü]/", "", $_POST["nick"]) : "",
                "password" => isset($_POST["password"]) ? preg_replace("/[^\-\_a-zAZ0-9]/", "", $_POST["password"]) : "",          
  
            )
            );
           
    foreach($requiredFields as $requiredField){
        if(!$user->getValue($requiredField)){
            
            $missingFields[] = $requiredField;
        }
    }
    
    if($missingFields){
       displayFormLogeo($missingFields, $user, true);
    } elseif(!$loggedInMember = $user->authenticate(1)) {
       $test = false;
       mostrarOculto();
       displayFormLogeo($missingFields, $user, $test);
       
       
    } else {
       //var_dump($loggedInMember);
       $_SESSION["user"] = $loggedInMember;
       
       session_write_close();
      
    }
//fin processForm
}

    echo'<nav class="slider-container">';
	echo'<figure id="derecha">';
		echo'<img src="img/derecha.png" class="activar" alt="Botones de desplazamiento"/>';
	echo'</figure>';
	
        echo'<figure id="arriba" class="noOcupar">';
		echo'<img src="img/arriba.png" class="activar" alt="Botones de desplazamiento"/>';
        echo'</figure>';	
	
            echo'<ul id="slider" class="slider-wrapper">';
                echo'<li class="slide-current"><a class="separarLetras" href="index.php">Inicio</a><a class="separarLetras" href="index.php">Servicios</a><a class="separarLetras" href="index.php">Automoción</a><a class="separarLetras" href="index.php">Ocio</a></li>';
		echo'<li><a class="separarLetras" href="index.php">Inicio</a><a class="separarLetras" href="index.php">Bricolage</a><a class="separarLetras" href="index.php">Electrónica</a><a class="separarLetras" href="index.php">Moda</a></li>';
		echo'<li><a class="separarLetras" href="index.php">Inicio</a><a class="separarLetras" href="index.php">Hogar</a><a class="separarLetras" href="index.php">Hospedaje</a><a class="separarLetras" href="index.php">Cultura</a></li>';
            echo'</ul>';
	
	echo'<figure id="abajo" class="noOcupar">';
		echo'<img src="img/abajo.png" class="activar" alt="Botones de desplazamiento"/>';
	echo'</figure>';
	echo'<figure id="izquierda" class="slider-controls, ocultar">';
		echo'<img src="img/izquierda.png" class="activar"  alt="Botones de desplazamiento"/>';
	echo'</figure>';
    echo'</nav>';
   
 
    echo'<section id="contenedor">';
    //para la publicidad
    echo'<aside id="publi">';
		echo'<p>Aqui va la publicidad</p>';
                echo'<div>';
        if(isset($_SESSION["user"]) and $_SESSION != ""){    
            echo'<input type="button" id="publicar" name="publicar" value="Publicar"/>';
        }
            echo'</div>';
	echo'</aside>';
     
       //Mostramos el conjunto total de resultados
        echo '<section id="resultados">';
     
        
  
        echo'</section>';
        
        
    //para los posts
    echo'<section id="posts">';
    
    echo'</section>';
   
    
    //Botones de navegacion
    echo '<section id="btn_navegacion">';
    
    echo '</section>';
    
    
      echo '<section id="mostrarSlider" class="oculto">';
      echo '<section class="slider-container-IMG">';
        echo '<h3>Información del Post</h3>';
            echo '<ul id="sliderImg" class="slider-wrapper-IMG">';
                echo '<li class="slide-current">';
                    echo '<img class="cargar">';
                    echo '<div class="caption"></div>';
                echo '</li>';
                
                echo '<li>';
                    echo'<img class="cargar">';
                    echo '<div class="caption"></div>';
                echo '</li>';
                echo '<li>';
                    echo'<img class="cargar">';
                    echo '<div class="caption"></div>';
                echo '</li>';
                echo '<li>';
                    echo'<img class="cargar">';
                    echo '<div class="caption"></div>';
                echo '</li>';
                echo '<li>';
                    echo'<img class="cargar">';
                    echo '<div class="caption"></div>';
                echo '</li>';
            echo'</ul>';
            
            echo'<ul id="slider-controls" class="slider-controls">';
               
                echo'<li class="active"></li>';
                    echo'<li></li>';
                    echo'<li></li>';
                    echo'<li></li>';
                    echo'<li></li>';
              
            echo'</ul>';
           
            
        echo '</section>';
           
        echo '<section id="buscadas">';
            echo'<h3>Cosas que podrían interesar</h3>';
                echo'<section id="lista"></section>';
            echo'</section>';
        echo'<section id="salir">';
            echo'<input type="button" id="salirSlider" value="Salir">';   
        echo'</section>';
       
        echo'</section>';      
     
    echo "</section>";
    
        
        
     echo' <footer>';
    
    echo' <div class="medidas"><p>Ventana: <span id="span1"></span></div>';
    echo'<div class="medidas">Ancho Supercontenedor: <span id="span2"></span> px</p>';
    /*
        <script src="http://platform.twitter.com/widgets.js"></script>
            <a href="http://twitter.com/share" class="twitter-share-button"
                data-text="#te lo cambio.es | Portal de intercambio de objetos entre particulares"
                data-url="https://telocambio.es" >Twittear</a>
                    <br/>
        */
    
    echo'</footer>';
    echo '</body>';
    echo '</html>';