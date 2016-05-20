<?php 
session_start(); 
$_SESSION["url"] = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>

<html>
   <head>
       <meta charset="UTF-8">
       <title>Tú portal de intercambio</title>
	<meta name="description" content="Portal para intercambiar las cosas que ya no usas o utilizas por otras que necesitas o te gustan."/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link href="img/fabicon.ico" rel="icon" type="image/x-icon">
	<link rel="stylesheet" href="css/estilos.css"/>
        <script src="jquery-2.2.2.js" type="text/javascript"></script>
        <script src="mostrar/menu.js"></script>					
        <script src="validar/formulario_login.js"></script>
        <script src="mostrar/redireccionar.js"></script>	
    <!--Para navegadores viejos-->
        <!--[if lt IE 9]>
            <script
        src="//html5shiv.googlecode.com/svn/trunk/html5.js">
        </script>
        <![endif]-->
        
   </head>
   <body id="cuerpo">
       
        <?php
 
        require_once 'entidades/Usuarios.php';
        require_once 'entidades/DataObj.php';
        require_once 'validar/ValidoForm.php';
        
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
			echo'<h1>Te lo cambio</h1>';
			echo'<h3>Miles de personas compartiendo te están esperando.</h3>';
		echo'<section id="btns_logueo">';
			echo'<input type="button" id="ingresar" name="ingresar" value="Ingresar"/>';
			echo'<input type="button" id="registrar" name="registrar" value="Registrarse"/>';
                        
		echo'</section>';
                echo '<section id="btn_desloguear">';
          
                    if(isset($_SESSION["user"]) and $_SESSION != ""){
                        echo'<a href="abandonar_sesion.php">Salir Sesión</a>';
                    }
               
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
    
        
    
    echo '>';
    	 echo'<h4>Introduzca sus datos</h4>';
    echo'<form name="logeo" action="index.php" method="post" id="form_login">';
        echo'<fieldset>';
  
            echo'<legend>Formulario de ingreso</legend>';
echo'<label '.$valido->validateField("nick", $missingFields). ' for="nick" >Introduce nombre de usuario:</label><span class="obligatorio"><img src="img/obligado.png" ></span>';
echo'<input  type="text" name="nick" id="nick" autofocus placeholder="Escribe tú nick" value="'.$user->getValueEncoded("nick").'" ><br></br>';            
echo'<label '.$valido->validateField("password", $missingFields).' for="password">Introduce tú password</label><span class="obligatorio"><img src="img/obligado.png" ></span>';
echo'<input type="password" name="password" id="password" placeholder="Escribe tú password" value="'.$user->getValueEncoded("password").'" ><br><br>';
  
if(!$test){
    echo '<h5>El usuario o la contraseña <br> <strong>no son validos</strong>.</h5>';
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
                "nick" => isset($_POST["nick"]) ? preg_replace("/[^\-\_a-zAZ0-9]/", "", $_POST["nick"]) : "",
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
                echo'<li class="slide-current"><a href="index.php">Inicio</a><a href="index.php">Servicios</a><a href="index.php">Automoción</a><a href="index.php">Ocio</a></li>';
		echo'<li><a href="index.php">Inicio</a><a href="index.php">Bricolage</a><a href="index.php">Electrónica</a><a href="index.php">Moda</a></li>';
		echo'<li><a href="index.php">Inicio</a><a href="index.php">Hogar</a><a href="index.php">Hospedaje</a><a href="index.php">Cultura</a></li>';
            echo'</ul>';
	
	echo'<figure id="abajo" class="noOcupar">';
		echo'<img src="img/abajo.png" class="activar" alt="Botones de desplazamiento"/>';
	echo'</figure>';
	echo'<figure id="izquierda" class="slider-controls, ocultar">';
		echo'<img src="img/izquierda.png" class="activar"  alt="Botones de desplazamiento"/>';
	echo'</figure>';
    echo'</nav>';
   
 
    echo'<section id="contenedor">';
    echo'<section id="posts">';
            echo'<div>';
        if(isset($_SESSION["user"]) and $_SESSION != ""){    
            echo'<input type="button" id="publicar" name="publicar" value="Publicar"/>';
        }
            echo'</div>';
        echo'</section>';
        echo'<aside id="publi">';
		echo'<p>Aqui va la publicidad</p>';
	echo'</aside>';
	
    
    echo'</section>';
               
         
     echo' <footer>';
    /*
        <script src="http://platform.twitter.com/widgets.js"></script>
            <a href="http://twitter.com/share" class="twitter-share-button"
                data-text="#te lo cambio.es | Portal de intercambio de objetos entre particulares"
                data-url="https://telocambio.es" >Twittear</a>
                    <br/>
        */
    echo'</footer>';