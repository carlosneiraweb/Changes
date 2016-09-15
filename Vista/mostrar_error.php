<?php
session_start();
?>
<!DOCTYPE html>
<!--
 author Carlos Neira Sanchez
 mail arj.123@hotmail.es
 telefono ""
 nameAndExt mostrar_error.php
 fecha 21-abr-2016
-->

<html>
    <head>
        <meta charset="UTF-8">
        <title>Error en web</title>
        <link href="../img/fabicon.ico" rel="icon" type="image/x-icon">
	<link rel="stylesheet" href="../css/estilos.css"/>
        <script src="../Controlador/jquery-2.2.2.js" type="text/javascript"></script>
        <script src="../Controlador/redireccionar.js"></script>
    </head>
    <body class="mi_body">
     
        
      <section id="error">
      <figure id="imagen_error"><img src="../img/error.png" alt="error"></figure>
      <section id="mensaje_error">
          <h2>Upss <span class="separarLetras">!!!</span> </h2>
      <h3>Hemos tenido un problema.</h3>
      <?php 
       //Mostramos el error que se ha producido 
       //y reseteamos la variable
        if(isset($_SESSION['error']) and $_SESSION['error'] != null){
        echo "<h3>".$_SESSION['error']."</h3>";
        $_SESSION['error'] = null;
        }
      ?>
      <h4>Puedes volver a intentarlo</h4>
      
      <input type="button" id="volver_intentar" value="Aceptar"/>
              </section>  
      </section>      
        
        
     
    </body>
</html>
