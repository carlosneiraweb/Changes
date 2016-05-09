<?php 
session_start(); 
?>
<!DOCTYPE html>
<!--
 author Carlos Neira Sanchez
 mail arj.123@hotmail.es
 telefono ""
 nameAndExt abandonar_sesion.php
 fecha 24-abr-2016
-->

<html>
    <head>
        <meta charset="UTF-8">
        <title>Abandono de sesion</title>
        <meta name="description" content="Portal para intercambiar las cosas que ya no usas o utilizas por otras que necesitas o te gustan."/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link href="img/fabicon.ico" rel="icon" type="image/x-icon">
	<link rel="stylesheet" href="css/estilos.css"/>
        <script language="javascript">
            function volverInicio(){
                setTimeout("location.href=' http://localhost/Proyecto-Final/index.php'", 3000);  
            }
        
        </script>   
    </head>
    <body class="mi_body">
        <?php
        /*
     echo 'usuario: '.$_SESSION["user"]. ' : url: '.$_SESSION["url"].'<br>'; 
 */
        echo '<section id="salir_sesion">';
            echo'<figure id="logo_salir_sesion">';
		echo'<img src="img/logo.png" alt="Logo del portal"/>';
		echo'<figcaption id="titulo">Acabas de abandonar tu sesión<br>'.
                 'Gracias por participar.</figcaption>';
	echo'</figure>';
        echo '</section>';
       
        //eliminamos la sesion, datos de sesion y cookie de sesion+
    if(isset($_COOKIE[session_name()])){
        setcookie(session_name(),"",time() -3600, "/");
    }
    
    
    try{
    $_SESSION = array();
    session_destroy();
        
    
        echo '<script language="javascript">';
        echo 'volverInicio();';
        echo '</script>';
    }catch(Exception $e){
        header('Location: mostrar_error.php');
    }    
        
        ?>
    </body>
</html>
