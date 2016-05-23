<?php 
require_once ('entidades/Post.php');
require_once 'entidades/Usuarios.php';
require_once ('entidades/DataObj.php');
session_start();
global $usuario;       
$usuario = $_SESSION['user']->getValue('nick');
//$usuario = 'admin';
//$url = $_SESSION["url"];
$url = 'index.html';
echo "usuario es: $usuario y session es: ".$url."<br>";

?>
<!DOCTYPE html>
<!--
 author Carlos Neira Sanchez
 mail arj.123@hotmail.es
 telefono ""
 nameAndExt subir_archivos_servidor.php
 fecha 17-abr-2016
-->

<html>
    <head>
        <meta charset="UTF-8">
        <title>Sube tu artículo para poder intercambiarlo</title>
        <link rel='stylesheet' type='text/css' href="css/estilos.css"/>
        <meta name="description" content="Sube lo que quieras cambiar con otras personas."/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link href="img/fabicon.ico" rel="icon" type="image/x-icon">
	<link rel="stylesheet" href="css/estilos.css"/>
        <script src="jquery-2.2.2.js" type="text/javascript"></script>
        <script src="mostrar/elementos.js"></script>
        <script src="validar/formulario_reg.js"></script>
    </head>
    <body>
        
        
        <?php
        
        require_once('validar/ValidoForm.php');
        require_once('Sistema/Directorios.php');
        $user = new Usuarios(array());
        
        global $post;
        $post = new Post(array());
 
        echo'<header>';
	echo'<figure id="logo" class="fade">';
		echo'<img src="img/logo.png" alt="Logo del portal"/>';
		echo'<figcaption id="titulo">Cambia todo lo que ya no uses.</figcaption>';
	echo'</figure>';
	echo'<section id="cabecera">';
			echo'<h1>Te lo cambio</h1>';
			echo'<h3>Miles de personas compartiendo te están esperando.</h3>';
		        echo'<h3>Registrarte solo te llevara un minuto</h3>';
                
	echo'</section>';
     
    echo'</header>';
    
   
    echo'<div id="ocultar" class = "mostrar_transparencia"></div>';
        
    if(!isset($_POST['step'])){
        displayStep1(array());
    }
    
    /*Mandamos a comprobar los campos del primer formulario*/
    if(isset($_POST['primero']) and $_POST['primero'] == "Aceptar"){
        $requiredFields = array('seccion', 'comentario', 'Pa_buscadas', 'Pa_ofrecidas');
        processForm($requiredFields, "step1");
    } elseif(isset($_POST['segundo']) and $_POST['segundo'] == "Aceptar >"){
        $requiredFields = array();
        processForm($requiredFields, "step2");
    } elseif(isset($_POST['segundo']) and $_POST['segundo'] == "< Back"){
        displayStep1(array());
    }
        
        
        
        
    function displayStep1($missingFields){
    global $mensaje; 
    global $user;
    echo'<section id="form_post">';
                echo'<h4>Introduzca los datos del artículo</h4>';
    echo'<form name="post" action="subir_posts.php" method="POST" id="post" >';
        echo'<fieldset>';
        	echo'<legend>Rellena los campos</legend>';
        echo"<input type='hidden' name='step' value='1'>"; 
        
    echo'<label '.ValidoForm::validateField("titulo", $missingFields).' for="titulo">Introduce un titulo para el artículo:</label> <span class="obligatorio"><img src="img/obligado.png" alt="campo obligatorio" title="obligatorio"></span>';
    echo'<input type="text" name="titulo" id="titulo" autofocus placeholder="Pon un titulo al artículo"  value=';if(isset($_SESSION['post']['titulo'])){echo $_SESSION['post']['titulo'];} echo ">";  
    
    echo'<label for="seccion">Seccion:</label> <span class="obligatorio"><img src="img/obligado.png" alt="campo obligatorio" title="obligatorio"></span>';
 
	echo'<select name="seccion" id="seccion">';
           
               echo'</select>'; 

                echo'<br>';
                echo'<br>';
    
    echo'<label '.ValidoForm::validateField("comentario", $missingFields). ' for="comentario">Introduce una descripción general del artículo. </label><span class="obligatorio"><img src="img/obligado.png" alt="campo obligatorio" title="obligatorio"></span>';
    echo'<textarea maxlength="255" name="comentario" id="comentario" placeholder= "Máximo 255 caracteres." value=';if(isset($_SESSION['post']['comentario'])){echo $_SESSION['post']['comentario'];} echo '></textarea>';
    
    echo'<label  for="precio">Introduce un precio aproximado  artículo. </label><span class="obligatorio"><img src="img/obligado.png" alt="campo obligatorio" title="obligatorio"></span>';
    echo'<input type="text" name="precio" id="precio" placeholder="Precio aproximado" value=';if(isset($_SESSION['post']['precio'])){echo $_SESSION['post']['precio'];} echo ">";
    
    echo'<label for="seccion">Elige por cuanto tiempo deseas hacer el cambio.</label> <span class="obligatorio"><img src="img/obligado.png" alt="campo obligatorio" title="obligatorio"></span>';
 
	echo'<select name="tiempoCambio" id="tiempoCambio">';
           
               echo'</select>'; 

                echo'<br>';
                echo'<br>';
                
                
    echo'<label  for="Pa_buscadas">Introduce 4 palabras por lo que tú estarías interesado en cambiarlo. </label>';
        echo '<section id="buscadas" class="introducir_palabras">';
    echo'<input type="text" name="buscada_1" id="buscada_1" placeholder="Máximo 25" maxlength="25"   value=';if(isset($_SESSION['post']['Pa_buscadas'][0])){echo $_SESSION['post']['Pa_buscadas'][0];} echo ">";
    echo'<input type="text" name="buscada_2" id="buscada_2" maxlength="25" value=';if(isset($_SESSION['post']['Pa_buscadas'][1])){echo $_SESSION['post']['Pa_buscadas'][1];} echo ">";
    echo'<input type="text" name="buscada_3" id="buscada_3" maxlength="25" value=';if(isset($_SESSION['post']['Pa_buscadas'][2])){echo $_SESSION['post']['Pa_buscadas'][2];} echo ">";
    echo'<input type="text" name="buscada_4" id="buscada_4" maxlength="25" value=';if(isset($_SESSION['post']['Pa_buscadas'][3])){echo $_SESSION['post']['Pa_buscadas'][3];} echo ">";
    
        echo '</section>';
        
    
    echo'<label  for="Pa_ofrecidas">Introduce 4 palabras para que la gente encuentre tu artículo. </label>';
        echo '<section id="ofrecidas" class="introducir_palabras">';
    echo'<input type="text" name="ofrecida_1" id="ofrecida_1" placeholder="Máximo 25" maxlength="25" value=';if(isset($_SESSION['post']['Pa_ofrecidas'][0])){echo $_SESSION['post']['Pa_ofrecidas'][0];} echo ">";
    echo'<input type="text" name="ofrecida_2" id="ofrecida_2" maxlength="25" value=';if(isset($_SESSION['post']['Pa_ofrecidas'][1])){echo $_SESSION['post']['Pa_ofrecidas'][1];} echo ">";
    echo'<input type="text" name="ofrecida_3" id="ofrecida_3" maxlength="25" value=';if(isset($_SESSION['post']['Pa_ofrecidas'][2])){echo $_SESSION['post']['Pa_ofrecidas'][2];} echo ">";
    echo'<input type="text" name="ofrecida_4" id="ofrecida_4" maxlength="25" value=';if(isset($_SESSION['post']['Pa_ofrecidas'][3])){echo $_SESSION['post']['Pa_ofrecidas'][3];} echo ">";
    
        echo '</section>';
            //Next &gt;
            echo"<input type='submit' name='primero' id='primero'  value='Aceptar' >";
                    
            echo "</form>";
        if($mensaje){
            echo $mensaje;
        }    
        echo'</fieldset>';  
        
    echo'</section>';
 //fin displayStep1
}    
        

function displayStep2($missingFields){
    
    echo 'Hola';
    
    
//fin displayStep2    
}

function ingresarPost(){
    global $articulo;
    global $usuario;
    echo 'El nick de la  variable es: '.$usuario. ' y con session: '.$_SESSION['user']->getValue('nick').'<br>';
        $articulo = new Post(array(
            "IdUsuario" => $usuario,
            "secciones_idsecciones" => $_SESSION['post']['seccion'],
            "tiempo_cambio_idTiempoCambio" => $_SESSION['post']['tiempo_cambio'],
            "titulo" => $_SESSION['post']['titulo'],
            "comentario" => $_SESSION['post']['comentario'],
            "precio" => $_SESSION['post']['precio'],
            "Pa_buscadas" => array(
                $_SESSION['post']['Pa_buscadas'][0],
                $_SESSION['post']['Pa_buscadas'][1],
                $_SESSION['post']['Pa_buscadas'][2],
                $_SESSION['post']['Pa_buscadas'][3]
            ),
            "Pa_ofrecidas" => array(
                $_SESSION['post']['Pa_ofrecidas'][0],
                $_SESSION['post']['Pa_ofrecidas'][1],
                $_SESSION['post']['Pa_ofrecidas'][2],
                $_SESSION['post']['Pa_ofrecidas'][3]
            ),
            "fechaPost" => ""        
        ));
        echo 'Datos de articulos<br>';
        //var_dump($articulo);
        $test = $articulo->insertArticulo();
        if($test){
            echo 'correcto';
        }else{
            echo 'NO CORRECTO';
        }
    
//fin ingresarPost    
}

function processForm($requiredFields, $st){
    //Array para almacenar los campos no rellenados y obligatorios
        global $missingFields;
        $missingFields = array();   

        switch ($st){
            case ('step1'):
                $_SESSION['post']['seccion'] = isset($_POST['seccion']) ? $_POST['seccion'] : "";
                $_SESSION['post']['tiempo_cambio'] = isset($_POST['tiempo_cambio']) ? $_POST['tiempo_cambio'] : "";
                $_SESSION['post']['titulo'] = isset($_POST["titulo"]) ? preg_replace("/[^\-\_a-zAZ0-9.,`'´]/", "", $_POST["titulo"]) : "";       
                $_SESSION['post']['comentario'] = isset($_POST['comentario']) ? preg_replace("/[^\-\_a-zAZ0-9.,`'´]/", "", $_POST["comentario"]) : "";
                $_SESSION['post']['precio'] = isset($_POST['precio']) ? preg_replace("/[^\-\_a-zAZ0-9.,]/", "", $_POST["precio"]) : "";
                $_SESSION['post']['Pa_buscadas'][0] = isset($_POST["buscada_1"]) ? preg_replace("/[^\-\_a-zAZ0-9.,`'´]/", "", $_POST["buscada_1"]) : "";
                $_SESSION['post']['Pa_buscadas'][1] = isset($_POST["buscada_2"]) ? preg_replace("/[^\-\_a-zAZ0-9.,`'´]/", "", $_POST["buscada_2"]) : "";
                $_SESSION['post']['Pa_buscadas'][2] = isset($_POST["buscada_3"]) ? preg_replace("/[^\-\_a-zAZ0-9.,`'´]/", "", $_POST["buscada_3"]) : "";
                $_SESSION['post']['Pa_buscadas'][3] = isset($_POST["buscada_4"]) ? preg_replace("/[^\-\_a-zAZ0-9.,`'´]/", "", $_POST["buscada_4"]) : "";
                $_SESSION['post']['Pa_ofrecidas'][0] = isset($_POST["ofrecida_1"]) ? preg_replace("/[^\-\_a-zAZ0-9.,`'´]/", "", $_POST["ofrecida_1"]) : "";
                $_SESSION['post']['Pa_ofrecidas'][1] = isset($_POST["ofrecida_2"]) ? preg_replace("/[^\-\_a-zAZ0-9.,`'´]/", "", $_POST["ofrecida_2"]) : "";
                $_SESSION['post']['Pa_ofrecidas'][2] = isset($_POST["ofrecida_3"]) ? preg_replace("/[^\-\_a-zAZ0-9.,`'´]/", "", $_POST["ofrecida_3"]) : "";
                $_SESSION['post']['Pa_ofrecidas'][3] = isset($_POST["ofrecida_4"]) ? preg_replace("/[^\-\_a-zAZ0-9.,`'´]/", "", $_POST["ofrecida_4"]) : "";
                
                //var_dump($_SESSION['post']);
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
        global $articulo;
        $test = true;
        
            switch ($st){
            
                case("step1"):
                   //Nada de momento
                    
                    break;
            
            } 
            
        return $test;
     //fin de validarCampos   
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
    
    }
    
    
    
    
    
//fin processForm
}        
        
        
        
        
        
        
        ?>
    </body>
</html>
