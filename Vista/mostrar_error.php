<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Constantes/ConstantesSistema.php');
    
    if (!isset($_SESSION)) {
        session_start();
    }

function redirigePorError(){
        
    header(MOSTRAR_PAGINA_BASENAME.$_SESSION['paginaError']);
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
    
    <?php
    
    /*
     * Boton misma página 
     * para redireccionar a la página correcta
     */
    if(isset($_POST['redirige_por_error']) and $_POST['redirige_por_error'] == "Aceptar") {
       
        redirigePorError();
    }
    ?>
    
      <section id="error">
      <figure id="imagen_error"><img src="../img/error.png" alt="error"></figure>
      <section id="mensaje_error">
          <h2>Upss <span class="separarLetras">!!!</span> </h2>
      <h3>Hemos tenido un problema.</h3>
      
    <?php 
      
        
        if(isset($_SESSION['png'])){
                    unset($_SESSION['png']);
                }
    
      
        
       //Mostramos el error que se ha producido en el registro
       //o actualizacion y reseteamos la variable
        if(isset($_SESSION['error']) and $_SESSION['error'] != null){
            echo $_SESSION['error'];
            
            unset($_SESSION['error']);
                
          //Para mostrar error al usuario al trabajar con Post      
        }else if(isset($_SESSION['mostrarError'])){
            echo $_SESSION['mostrarError'];
            unset($_SESSION['mostrarError']);
        }
       

        echo "<section id='form_registro_error' class='inputsRegistro'>";
                echo'<form name="redirige" action="mostrar_error.php" method="POST" id="redirigeError">';
                    echo '<section id="btns_error">';
                        echo' <input type="submit" name="redirige_por_error" id="redirige_por_error" value="Aceptar">';
                    echo '</section>';
            echo "</form>";
       
    ?>        
                </section>  
        </section>     
      
      
        
     
    </body>
</html>
