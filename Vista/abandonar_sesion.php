<?php 
 if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
?>
<!DOCTYPE html>


<html>
    <head>
        <meta charset="UTF-8">
        <title>Abandono de sesion</title>
        <meta name="description" content="Portal para intercambiar las cosas que ya no usas o utilizas por otras que necesitas o te gustan."/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link href="../img/fabicon.ico" rel="icon" type="image/x-icon">
	<link rel="stylesheet" href="../css/estilos.css"/>
        <script type="text/javascript">
            function volverInicio(){
                setTimeout('location.href="<?php echo $_SESSION['url'] ?>"', 3000);  
            }
        
        </script>   
    </head>
    <body class="mi_body">
        <?php
                           
        echo '<section id="salir_sesion">';
        echo '<h2>Te lo cambio</h2>';
            echo'<figure id="logo_salir_sesion">';
		echo'<img src="../img/logo.png" alt="Logo del portal"/>';
                if(isset($_SESSION['actualizo'])){
                    echo "<h2>Tús datos se han actualizado correctamente</h2>";
                    
                }else{
                    echo'<figcaption id="titulo">Acabas de abandonar tu sesión<br>'.
                       '<strong>Gracias por participar.</strong></figcaption>';
                }
	echo'</figure>';
        echo '</section>';
       
        //eliminamos la sesion, datos de sesion y cookie de sesion+
    if(isset($_COOKIE[session_name()])){
        setcookie(session_name(),"",time() -3600, "/");
    }
    
    
    try{
    
        echo '<script type="text/javascript">';
         echo 'volverInicio();';
        echo '</script>';
                //Destruimos todas variables de sesion
            $_SESSION = array();
            session_unset();
            session_destroy();
    }catch(Exception $e){
       echo 'abandonar sesion => '.$e->getCode();
    }    
        
        ?>
    </body>
</html>
