<?php 
session_start(); 
function mostrarError(){
    header('Location: mostrar_error.php');
}
function volverAnterior(){
    header('Location:'. $_SESSION["url"]);
}

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
        <script src="mostrar/elementos.js"></script>
        <script src="validar/formulario_reg.js"></script>
    <!--Para navegadores viejos-->
        <!--[if lt IE 9]>
            <script
        src="//html5shiv.googlecode.com/svn/trunk/html5.js">
        </script>
        <![endif]-->
        
   </head>
   <body id="cuerpo">
       
        <?php
             
        require_once('entidades/Usuarios.php');
        require_once('entidades/DataObj.php');
        require_once('validar/ValidoForm.php');
        require_once('Sistema/Directorios.php');
        
        //Variable global para mostrar los errores de validacion
        global $mensaje;
        //Variable global de usuario, Instanciaremos una vez validado todos los campos
        global $user;
        $user = new Usuarios(array());
 
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
    if(isset($_POST['primero']) and $_POST['primero'] == "Next >"){
        $requiredFields = array('nick', 'password', 'email');
        processForm($requiredFields, "step1");
    } elseif(isset($_POST['segundo']) and $_POST['segundo'] == "Next >"){
        $requiredFields = array('nombre');
        processForm($requiredFields, "step2");
    } elseif(isset($_POST['segundo']) and $_POST['segundo'] == "< Back"){
        displayStep1(array());
    } elseif(isset($_POST['tercero']) and $_POST['tercero'] == "Next >"){
        $requiredFields = array('codPostal');
        processForm($requiredFields, "step3");
    } elseif(isset($_POST['tercero']) and $_POST['tercero'] == "< Back"){
        displayStep2(array());
    } elseif(isset($_POST['cuarto']) and $_POST['cuarto'] == "< Back"){
        displayStep3(array());
    } elseif(isset($_POST['cuarto']) and $_POST['cuarto'] == "Aceptar"){
        $requiredFields = array();
        processForm($requiredFields, "step4");
    } 
     
function displayStep1($missingFields){
    global $mensaje; 
    global $user;
    echo'<section id="form_registro">';
                echo'<h4>Introduzca sus datos</h4>';
    echo'<form name="registro" action="register.php" method="POST" id="registro" >';
        echo'<fieldset>';
        	echo'<legend>Formulario de Registro Primer Paso</legend>';
        echo"<input type='hidden' name='step' value='1'>";  
    echo'<label '.ValidoForm::validateField("nick", $missingFields).' for="nick">Introduce nombre de usuario:</label> <span class="obligatorio"><img src="img/obligado.png" ></span>';
    echo'<input type="text" name="nick" id="nick" autofocus placeholder="Tú nombre usuario"  value=';if(isset($_SESSION['usuario']['nick'])){echo $_SESSION['usuario']['nick'];} echo ">";       
    echo'<label '.ValidoForm::validateField("password", $missingFields). ' for="password">Introduce tú password</label><span class="obligatorio"><img src="img/obligado.png" ></span>';
    echo'<input type="password" name="password" id="password"  maxlength="12" >';	
    echo'<label '.ValidoForm::validateField("passReg2", $missingFields). ' for="passReg2">Repite el password</label><span class="obligatorio"><img src="img/obligado.png" ></span>';
    echo'<input type="password" name="passReg2" id="passReg2" maxlength="12"  >';       
    echo'<label '.ValidoForm::validateField("email", $missingFields).' for="email">Email:</label> <span class="obligatorio"><img src="img/obligado.png" ></span>';
    echo'<input type="text" name="email" id="email" placeholder="info@developerji.com" value=';if(isset($_SESSION['usuario']['email'])){echo $_SESSION['usuario']['email'];} echo ">";
    
    
                echo"<input type='submit' name='primero' id='primero'  value='Next &gt;' >";
                    
            echo "</form>";
        if($mensaje){
            echo $mensaje;
        }    
        echo'</fieldset>';  
        
    echo'</section>';
 //fin displayStep1
}

function displayStep2($missingFields){
   
    global $mensaje;
    
    echo'<section id="form_registro">';
                echo'<h4>Introduzca sus datos</h4>';
    echo'<form name="registro" action="register.php" method="POST" id="registro">';
        echo'<fieldset>';
        	echo'<legend>Formulario de Registro Segundo Paso</legend>';
    echo"<input type='hidden' name='step' value='2'>";
    echo'<label '.ValidoForm::validateField("nombre", $missingFields). ' for="nombre">Nombre:</label> <span class="obligatorio"><img src="img/obligado.png" ></span>';
    echo'<input type="text" name="nombre" id="nombre" autofocus  placeholder="Escribe tú nombre" value=';if(isset($_SESSION['usuario']['nombre']))echo $_SESSION['usuario']['nombre']; echo ">";
    echo'<label for="apellido_1">Primer Apellido:</label>';
    echo'<input type="text" name="apellido_1" id="apellido_1" placeholder="Escribe tú apellido"  />';
    echo'<label for="apellido_2">Segundo Apellido:</label>';
    echo'<input type="text" name="apellido_2" id="apellido_2" placeholder="Escribe tú apellido"  />';        
    echo'<label '.ValidoForm::validateField("telefono", $missingFields). ' for="telefono">Teléfono:</label><span class="obligatorio"><img src="img/obligado.png" ></span>';
    echo'<input type="text" name="telefono" id="telefono" placeholder="No será mostrado" value=';if(isset($_SESSION['usuario']['telefono']))echo $_SESSION['usuario']['telefono']; echo ">";
        echo'<label for="genero">Selecciona tu sexo:</label>';
		echo'<select name="genero" id="genero">';			
		echo'</select>';
	
                echo'<br>';        
    
    echo"<div style='clear: both';>";
                        echo"<input type='submit' name='segundo' id='segundo'  value='Next &gt;'>";
                        echo"<input type='submit' name='segundo' id='segundo' value='&lt; Back' >";
                    echo"</div>";
                    
            echo "</form>";
        if($mensaje){
            echo $mensaje;
        }    
        echo'</fieldset>';  
        
    echo'</section>';
 //fin  displayStep2()   
}
 
function displayStep3($missingFields){
    global $mensaje;
    
    echo'<section id="form_registro">';
                echo'<h4>Introduzca sus datos</h4>';
    echo'<form name="registro" action="register.php" method="POST" id="registro">';
        echo'<fieldset>';
        	echo'<legend>Formulario de Registro ya casi estamos</legend>';
    echo"<input type='hidden' name='step' value='3'>";
    echo'<label for="calle">Nombre de la calle o vía:</label>';
    echo'<input type="text" name="calle" id="calle" placeholder="Escribe el nombre de la calle"  />';
    echo'<label for="numeroPortal">Número del portal:</label>';
    echo'<input type="text" name="numeroPortal" id="numeroPortal" placeholder="Escribe el número del portal"  />';
    echo'<label for="ptr">Puerta:</label>';
    echo'<input type="text" name="ptr" id="ptr" placeholder="Escribe el número de la puerta"  />';
    echo'<label for="ciudad">Ciudad:</label>';
    echo'<input type="text" name="ciudad" id="ciudad" placeholder="Nombre de tu Localidad"  />';
    echo'<label for="codPostal">Código Postal:</label><span class="obligatorio"><img src="img/obligado.png" ></span>';
    echo'<input type="text" name="codPostal" id="codPostal" placeholder="Escribe el número del código postal"  maxlength="5" />';
    
    echo'<label for="provincia">Provincia:</label>';
 
	echo'<select name="provincia" id="provincia">';
           
               echo'</select>'; 

                echo'<br>';
                echo'<br>';
                
    echo'<label for="pais">Pais:</label>'; 
	echo'<input type="text" name="pais" id="pais" placeholder="España"  />';		
			
    
    
    echo"<div style='clear: both';>";
                        echo"<input type='submit' name='tercero' id='tercero'  value='Next &gt;'>";
                        echo"<input type='submit' name='tercero' id='tercero' value='&lt; Back' >";
                    echo"</div>";
                    
            echo "</form>";
        if($mensaje){
            echo $mensaje;
        }    
        echo'</fieldset>';  
        
    echo'</section>';
 //fin  displayStep3()   
}

function displayStep4($missingFields){
    
        global $mensaje;
    echo'<section id="form_registro">';
                echo'<h4>Introduzca sus datos</h4>';
    echo'<form name="registro" action="register.php" method="POST" id="registro" enctype="multipart/form-data">';
        echo'<fieldset>';
        	echo'<legend>Personaliza tu perfil, sube una foto tuya.</legend>';
    echo"<input type='hidden' name='step' value='4'>";
    //Modificamos en php.ini y en el formulario el maximo tamaño del archivo
    echo'<input type="hidden" name="MAX_FILE_SIZE" value="50000" />';
    echo'<label for="photo">Solo fotos .jpg</label>';
            
            echo'<input type="file" name="photo" id="photo" value="" />';
            
    echo"<div style='clear: both';>";
                        echo"<input type='submit' name='cuarto' id='cuarto'  value='Aceptar'>";
                        echo"<input type='submit' name='cuarto' id='cuarto' value='&lt; Back' >";
                    echo"</div>";
                    
            echo "</form>";
        if($mensaje){
            echo $mensaje;
        }    
        echo'</fieldset>';  
        
    echo'</section>';
//fin displayStep4    
}


function confirmarRegistro(){
    echo '<h2>Has sido registrado correctamente</h2>';
    echo "<section id='form_registro'>";
    echo'<form name="registro" action="register.php" method="POST" id="registro">';
    echo"<input type='submit' name='volvemos' id='volvemos' value='Aceptar'>";
    echo "</form>";
    echo "</section>";
}

/**
     * Una vez validado todos los campos 
     * Instanciamos un objeto usuario y
     * hacemos la insercion.
     */
    function ingresarUsuario(){
        global $user;
        
        $user = new Usuarios(array(
            "nombre" => $_SESSION['usuario']['nombre'],
            "apellido_1" => $_SESSION['usuario']['apellido_1'],
            "apellido_2" => $_SESSION['usuario']['apellido_2'],
            "calle" => $_SESSION['usuario']['calle'],
            "numeroPortal" => $_SESSION['usuario']['numeroPortal'],
            "ptr" => $_SESSION['usuario']['ptr'],
            "ciudad" => $_SESSION['usuario']['ciudad'],
            "codigoPostal" => $_SESSION['usuario']['codPostal'],
            "provincia" => $_SESSION['usuario']['provincia'],
            "telefono" => $_SESSION['usuario']['telefono'],
            "pais" => $_SESSION['usuario']['pais'],
            "genero" => $_SESSION['usuario']['genero'],
            "email" => $_SESSION['usuario']['email'],
            "nick" => $_SESSION['usuario']['nick'],
            "password" => $_SESSION['usuario']['password'],
            "admin" => 0
                ));
     
            $test = $user->insert();
            if($test){
                confirmarRegistro();
            } else{
                mostrarError();
            }
            
            
    //fin ingresarUsuario    
    }

function processForm($requiredFields, $st){
    //Array para almacenar los campos no rellenados y obligatorios
        global $missingFields;
        $missingFields = array();   
        //Segun el paso vamos rellenando la variable de session  de usuario
    
        switch ($st){
            case "step1":
                $_SESSION['usuario']["nick"] = isset($_POST["nick"]) ? preg_replace("/[^\-\_a-zAZ0-9]/", "", $_POST["nick"]) : "";
                $_SESSION['usuario']["password"] = isset($_POST["password"]) ? preg_replace("/[^\-\_a-zAZ0-9]/", "", $_POST["password"]) : "";  
                $_SESSION['usuario']["email"] = isset($_POST["email"]) ? preg_replace("/[^\@\.\-\_a-zA-Z0-9]/", "", $_POST["email"]) : "";
                    break;
            case "step2":
                $_SESSION['usuario']["nombre"] = isset($_POST["nombre"])  ? preg_replace("/[^\-\_a-zAZ]/", "", $_POST["nombre"]) : "";
                $_SESSION['usuario']["apellido_1"] = isset($_POST["apellido_1"]) ? preg_replace("/[^\-\_a-zAZ]/", "", $_POST["apellido_1"]) : "";
                $_SESSION['usuario']["apellido_2"] = isset($_POST["apellido_2"]) ? preg_replace("/[^\-\_a-zAZ]/", "", $_POST["apellido_2"]) : "";
                $_SESSION['usuario']["telefono"] = isset($_POST["telefono"]) ?  $_POST["telefono"] : "";
                $_SESSION['usuario']["genero"] = isset($_POST["genero"]) ? $_POST['genero'] : "" ;
                    break;
            case "step3":
                $_SESSION['usuario']["calle"] = isset($_POST['calle']) ? preg_replace("/[^\-\_a-zAZ0-9]/", "", $_POST["calle"]) : "";
                $_SESSION['usuario']["numeroPortal"] = isset($_POST['numeroPortal']) ? preg_replace("/[^\-\_0-9]/", "", $_POST["numeroPortal"]) : "";
                $_SESSION['usuario']["ptr"] = isset($_POST['ptr']) ? preg_replace("/[^\-\_a-zAZ0-9]/", "", $_POST["ptr"]) : "";
                $_SESSION['usuario']["ciudad"] = isset($_POST['ciudad']) ? preg_replace("/[^\-\_a-zAZ0-9]/", "", $_POST["ciudad"]) : "";
                $_SESSION['usuario']["codPostal"] = isset($_POST['codPostal']) ? preg_replace("/[^\-\_0-9]/", "", $_POST["codPostal"]) : "";
                $_SESSION['usuario']["provincia"] = isset($_POST['provincia']) ? $_POST['provincia'] : "";
                $_SESSION['usuario']["pais"] = isset($_POST['pais']) ? preg_replace("/[^\-\_a-zAZ0-9]/", "", $_POST["pais"]) : "";
                //cerramos escritura sobre variable de sesion
                session_write_close();
                
                    break; 
            case "step4":
                
                //En este paso no hacemos nada
                    break;
        }
            
       
    foreach($requiredFields as $requiredField){
        if(!$_SESSION['usuario'][$requiredField]){
            $missingFields[] = $requiredField;
        }
    }
    
    
    
    /**
     * Metodo que valida los datos introducidos por el usuario.
     * Valida los campos con los metodos static de ValidaForm
     * Valida los datos de la bbdd con un objeto de la clase Usuarios
     * @global type $mensaje
     * @global Usuarios $user
     * @param type $st
     * @return boolean
     */
    function validarCampos($st){
        global $mensaje;
        global $user;
        $test = true;
        switch ($st){
            case "step1":
            
                //En caso de que exista el nombre de usuario o email
                //Los passwords se repitan o el email sea incorrecto
                    if($user->getByUserName($_SESSION['usuario']['nick'])){
                        $mensaje = PASSWORD_EXISTE;
                        $test = false;
                        break;
                    }elseif(!ValidoForm::validarPassword($_SESSION['usuario']['password'])){
                        $mensaje = PASSWORD_INCORRECTO;
                        $test = false;
                        break;
                    }elseif(!ValidoForm::validarIgualdadPasswords($_SESSION['usuario']['password'], $_POST['passReg2'])){
                        $mensaje = IGUALDAD_PASSWORD;
                        $test = false;
                        break;
                    }elseif(!ValidoForm::validarEmail($_SESSION['usuario']['email'])){
                        $mensaje = EMAIL_INCORRECTO;
                        $test = false;
                        break;
                    }elseif($user->getByEmailAddress($_SESSION['usuario']['email'])){
                        $mensaje = EMAIL_EXISTE;
                        $test = false;
                        break;
                    } 
                 
                return $test;     
                    
            case 'step2':
          
                    if(!ValidoForm::validaTelefono($_SESSION['usuario']['telefono'])){
                        $mensaje = TELEFONO_INCORRECTO;
                        $test = false;
                         break;
                    }
               
             
                return $test;
                   
            case 'step3':
                    if(!ValidoForm::validarCodPostal($_SESSION['usuario']['codPostal'])){
                        $mensaje = CODIGO_POSTAL;
                        $test = false;
                         break;
                    }
                        
                return $test;
                
            case 'step4':
               
            //Si el usuario sube una foto para su perfil la validamos
                if(isset($_FILES['photo']['tmp_name']) and $_FILES['photo']['tmp_name'] != null){
                        
                        if(Sistema::validarFoto()){  
                            
                            //Importante
                            //Recupreamos el nombre del archivo y ruta
                            $destino = 'datos_usuario/'.$_SESSION['usuario']['nick'].'/'.basename($_FILES['photo']['name']);
                            $foto = $_FILES['photo']['tmp_name'];
                            //Creamos dos directorios en el sistema
                            //El primero donde almacenamos la foto de su perfil, en el futuro guardaremos mas cosas
                            //El segundo directorio es donde almacenaremos las imagenes
                                //De todos los posts que el usuario valla subiendo.
                                //Cada post tendra una carpeta independiente
                            //echo 'llamo subo perfil<br>';
                            $test = Sistema::crearDirectorio("photos");
                            $test = Sistema::crearDirectorio("datos_usuario");
                            $test = Sistema::moverImagen($foto, $destino);
                            $test = Sistema::renombrarFotoPerfilUsuario($destino, $_SESSION['usuario']['nick']);  
                           
                        }else{
                            $test = false;
                        } 
                } else {
                    //Si no sube ninguna foto se le asigna la de default
                    //echo 'llamo por que no subo perfil<br>';
                    $test = Sistema::crearDirectorio("photos");
                    $test = Sistema::crearDirectorio("datos_usuario");
                    $test = Sistema::copiarFoto("datos_usuario/desconocido.jpg", 'datos_usuario/'.$_SESSION['usuario']['nick'].'/'.$_SESSION['usuario']['nick'].'.jpg');
                }
            //Si hay algun tipo de error al subir la foto
                //Redirigimos a la pagina de mostrar error
                //Para que el usuario vuelva a intentarlo
//                if(!$test){
//                    mostrarError();
//                    exit();
//                }
            return $test;
   
        }
        
        ///las validaciones
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
                displayStep2(array());
            }
            break;
        case 'step2':
            
            if($missingFields || !validarCampos($st)){
                displayStep2($missingFields);
            } else{
                displayStep3(array());
            }
            break;
     
     
        case 'step3':
    
            if($missingFields || !validarCampos($st)){
                displayStep3($missingFields);
            } else{
                displayStep4(array());
            }
            break;
            
        case 'step4':
            
            if(!validarCampos($st)){
                displayStep4($missingFields);
            } else{
                //finalmente si todo ha ido bien mandamos a
                // ingresar el usuario. En caso de error lo
                //redirigimos a una página para hacerselo saber
                // y darle la oprtunidad de intentarlo otra vez.                       
                ingresarUsuario();           
            }                
   
    }
//fin processForm
}
   
    
    /*section contenedor*/
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
  
   echo'</body>';
echo'</html>';
