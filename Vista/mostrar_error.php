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
      
     
        if(isset($_SESSION['png'])){
                    unset($_SESSION['png']);
                }
    
      
        
       //Mostramos el error que se ha producido 
       //y reseteamos la variable
        if(isset($_SESSION['error']) and $_SESSION['error'] != null){
            echo "<h3>".$_SESSION['error']."</h3>";
            
                $_SESSION['error'] = 'error';
                
                
        }
        $ex = $_SESSION['paginaError'];
        echo '<script type="text/javascript">';
           //indicamos la url a javascript
           //para redirecionarnos a la pagina correcta
                echo 'urlVolverError = '; echo "'$ex'".';';  
                
        echo '</script>';
        
           
    
        ?>
      
      
      <input type="button" id="volver_intentar" value="Aceptar"/>
              </section>  
      </section>      
        
        
     
    </body>
</html>
