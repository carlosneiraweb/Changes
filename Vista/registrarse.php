<?php 
function mostrarError(){
    header('Location: mostrar_error.php');
}
function volverAnterior(){
    header('Location:'. $_SESSION["url"]);
    die();
}
function volverPrincipio(){
    header('Location: index.php');
    die();
}
//Variable que utiliza la pagina
//Mostrar error para devolvernos a 
//la pagina donde se a producido
$_SESSION["paginaError"] = basename($_SERVER['PHP_SELF']);
session_start();
?>
<!DOCTYPE html>

<html>
   <head>
       <meta charset="UTF-8">
       <title>Tú portal de intercambio</title>
	<meta name="description" content="Portal para intercambiar las cosas que ya no usas o utilizas por otras que necesitas o te gustan."/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link href="../img/fabicon.ico" rel="icon" type="image/x-icon">
	<link rel="stylesheet" href="../css/estilos.css"/>
        <script src="../Controlador/jquery-2.2.2.js" type="text/javascript"></script>
        <script src="../Controlador/Elementos_AJAX/CONEXION_AJAX.js"></script>
        <script src="../Controlador/Elementos_AJAX/principal.js"></script>
        <script src="./registrarse.js"></script>
        <script src="../Controlador/Validar/formulario_reg.js"></script>
        <script src="../Controlador/Validar/iconoObligatorio.js"></script>
        
        
    <!--Para navegadores viejos-->
        <!--[if lt IE 9]>
            <script
        src="http://html5shiv.googlecode.com/svn/trunk/html5.js">
        </script>
        <![endif]-->
    
   </head>
   <body id="cuerpo">
    
        <?php
        
        require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/Usuarios.php');
        require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/DataObj.php');
        require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Controlador/Validar/ValidoForm.php');
        require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Directorios.php');
        require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Constantes.php');
        require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Email/mandarEmails.php');
        require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Controlador/Validar/ControlErroresSistemaEnArchivos.php');
        
    //Añadimos el div con la clase oculto
    // echo'<div id="ocultar" class="oculto"> </div>';  
       
        //Variable global para mostrar los errores de validacion
       $mensaje;
        //Variable global de usuario
        $user = new Usuarios(array());
        global $errores;
        $errores = new ControlErroresSistemaEnArchivos(null,null);

        
        echo'<header>';
	echo'<figure id="logo" class="fade">';
		echo'<img src="../img/logo.png" alt="Logo del portal"/>';
		echo'<figcaption id="titulo">Cambia todo lo que ya no uses.</figcaption>';
	echo'</figure>';
        
            
	echo'<section id="cabecera">';
			echo'<h1>Te lo cambio</h1>';
			echo'<h3>Miles de personas compartiendo te están esperando.</h3>';
		        echo'<h3>Registrarte solo te llevara un minuto</h3>';
                
	echo'</section>';
     
    echo'</header>';
 
     
        // Si no se ha recivido el step
        // Se muestra por primera vez el formulario
        $paso = null;
            if(!isset($_POST['step'])){
                displayStep1(array());
            }
            
    //Variable donde cargaremos el paso
            //de cada una de las partes del formulario
           
    /*Mandamos a comprobar los campos del primer formulario*/
    if(isset($_POST['primeroReg']) and $_POST['primeroReg'] == "Siguiente"){
        $requiredFields = array('nick', 'password', 'email');
        if(isset($_POST['step']) and $_POST['step'] === "step1"){ $paso = 'step1';}
        processForm($requiredFields, $paso);
    } elseif(isset($_POST['primeroReg']) and $_POST['primeroReg'] == "Salir"){
        volverAnterior();
    } elseif(isset($_POST['segundoReg']) and $_POST['segundoReg'] == "Siguiente"){
        $requiredFields = array('nombre','telefono');
        if(isset($_POST['step']) and $_POST['step'] === "step2"){ $paso = 'step2';}
        processForm($requiredFields, $paso);
    } elseif(isset($_POST['segundoReg']) and $_POST['segundoReg'] == "Atras"){
        displayStep1(array());
    } elseif(isset($_POST['segundoReg']) and $_POST['segundoReg'] == "Salir"){
        volverAnterior();
    }elseif(isset($_POST['terceroReg']) and $_POST['terceroReg'] == "Siguiente"){
        $requiredFields = array('codPostal', 'ciudad');
        if(isset($_POST['step']) and $_POST['step'] === "step3"){ $paso = 'step3';}
        processForm($requiredFields, $paso);
    } elseif(isset($_POST['terceroReg']) and $_POST['terceroReg'] == "Atras"){
        displayStep2(array());
    } elseif(isset($_POST['terceroReg']) and $_POST['terceroReg'] == "Salir"){
        volverAnterior();
    }elseif(isset($_POST['cuartoReg']) and $_POST['cuartoReg'] == "Atras"){
        displayStep3(array());
    } elseif(isset($_POST['cuartoReg']) and $_POST['cuartoReg'] == "Siguiente"){
        $requiredFields = array();
        if(isset($_POST['step']) and $_POST['step'] === "step4"){ $paso = 'step4';}
        processForm($requiredFields, $paso);
    }elseif(isset($_POST['aceptaCondicionesReg']) and $_POST['aceptaCondicionesReg'] == "aceptaCondiciones"){
        $requiredFields = array();
        processForm($requiredFields, 'step5');
    }elseif(isset($_POST['noAceptaCondicionesReg']) and $_POST['noAceptaCondicionesReg'] == "noAceptaCondiciones"){
        //El usuario no acepta las condiciones
        //Eliminado todos los directorios creados
        $errores->eliminarDirectoriosAlRegistrarseUsuario();
         volverPrincipio();
    }elseif(isset($_POST['registroConfirmado']) and $_POST['registroConfirmado'] == "Aceptar") {
        volverPrincipio();
    }

    
     
function displayStep1($missingFields){
    
  
    global $mensaje;
    
    echo'<section id="form_registro_1" class="fuenteFormulario, generalFormularios">';
                echo'<h4>Introduzca sus datos</h4>';
    echo'<form name="registro" action="registrarse.php" method="POST" id="registro_1" >';
        echo'<fieldset>';
        	echo'<legend>Formulario de Registro Primer Paso</legend>';
        echo"<input type='hidden' name='step' value='step1'>";  
    echo'<label '.ValidoForm::validateField("nick", $missingFields).' for="nick">Introduce nombre de usuario:</label><span class="obligatorio"><img src="../img/obligado.png" alt="campo obligatorio" title="obligatorio"></span>';
    echo'<input type="text" name="nick" id="nick" autofocus placeholder="Tú nombre usuario maximo 25 caracteres" maxlength="25" value=';if(isset($_SESSION['usuario']['nick'])){echo $_SESSION['usuario']['nick'];} echo ">";       
    echo'<label '.ValidoForm::validateField("password", $missingFields). ' for="password">Introduce tú password</label><span class="obligatorio"><img src="../img/obligado.png" alt="campo obligatorio" title="obligatorio"></span>';
    echo'<input type="password" name="password" id="password"  maxlength="12" placeholder="Debe  minimo 6 y máximo 12" >';	
    echo'<label '.ValidoForm::validateField("passReg2", $missingFields). ' for="passReg2">Repite el password</label><span class="obligatorio"><img src="../img/obligado.png" alt="campo obligatorio" title="obligatorio"></span>';
    echo'<input type="password" name="passReg2" id="passReg2" maxlength="12"  >';       
    echo'<label '.ValidoForm::validateField("email", $missingFields).' for="email">Email:</label> <span class="obligatorio"><img src="../img/obligado.png" alt="campo obligatorio" title="obligatorio"></span>';
    echo'<input type="text" name="email" id="email" placeholder="info@developerji.com" maxlength="45" value=';if(isset($_SESSION['usuario']['email'])){echo $_SESSION['usuario']['email'];} echo ">";
    
    echo '<section id="btns_registrar">';
                echo"<input type='submit' name='primeroReg' id='primeroSigReg'  value='Siguiente' >";
                echo"<input type='submit' name='primeroReg' id='primeroSalReg'  value='Salir' >";
    echo '</section>';
                    
            echo "</form>";
          
        echo'</fieldset>'; 
    //En caso de error 
        //se muestra en el formulario
    if($mensaje){
            echo $mensaje;
        }        
        
    echo'</section>';
 //fin displayStep1
}

function displayStep2($missingFields){
    global $mensaje;
  
           
       
    echo'<section id="form_registro_2" class="fuenteFormulario, generalFormularios">';
                echo'<h4>Introduzca sus datos</h4>';
    echo'<form name="registro" action="registrarse.php" method="POST" id="registro_2">';
        echo'<fieldset>';
        	echo'<legend>Formulario de Registro Segundo Paso</legend>';
    echo"<input type='hidden' name='step' value='step2'>";
    echo'<label '.ValidoForm::validateField("nombre", $missingFields). ' for="nombre">Nombre:</label> <span class="obligatorio"><img src="../img/obligado.png" alt="campo obligatorio" title="obligatorio"></span>';
    echo'<input type="text" name="nombre" id="nombre" autofocus  placeholder="Escribe tú nombre" maxlength= "25" value=';if(isset($_SESSION['usuario']['nombre'])){echo $_SESSION['usuario']['nombre'];} echo ">";
    echo'<label for="apellido_1">Primer Apellido:</label>';
    echo'<input type="text" name="apellido_1" id="apellido_1" placeholder="Escribe tú apellido"  maxlength= "25" value=';if(isset($_SESSION['usuario']['apellido_1'])){echo $_SESSION['usuario']['apellido_1'];} echo ">";
    echo'<label for="apellido_2">Segundo Apellido:</label>';
    echo'<input type="text" name="apellido_2" id="apellido_2" placeholder="Escribe tú segundo apellido" maxlength= "25" value= ';if(isset($_SESSION['usuario']['apellido_2'])){echo $_SESSION['usuario']['apellido_2'];} echo ">";        
    echo'<label '.ValidoForm::validateField("telefono", $missingFields). ' for="telefono">Teléfono:</label><span class="obligatorio"><img src="../img/obligado.png" alt="campo obligatorio" title="obligatorio"></span>';
    echo'<input type="text" name="telefono" id="telefono" placeholder="Teléfono contacto" maxlength="9" value=';if(isset($_SESSION['usuario']['telefono'])){echo $_SESSION['usuario']['telefono'];} echo ">";
        echo'<label for="genero">Selecciona tu sexo:</label>';
		echo'<select name="genero" id="genero">';			
		echo'</select>';
	
                echo'<br>';        
    
    echo '<section id="btns_registrar">';
                        echo"<input type='submit' name='segundoReg' id='segundoSigReg'  value='Siguiente'>";
                        echo"<input type='submit' name='segundoReg' id='segundoAtrasReg' value='Atras' >";
                        echo"<input type='submit' name='segundoReg' id='segundoSalirReg' value='Salir' >";
    echo"</section>";
                    
            echo "</form>";
         //En caso de error 
        //se muestra en el formulario
         if($mensaje){
            echo $mensaje;
        }    
        echo'</fieldset>';  
        
    echo'</section>';
 //fin  displayStep2()   
}
 
function displayStep3($missingFields){
    global $mensaje;
        
            
    echo'<section id="form_registro_3" class="fuenteFormulario, generalFormularios">';
                echo'<h4>Introduzca sus datos</h4>';
    echo'<form name="registro" action="registrarse.php" method="POST" id="registro_3">';
        echo'<fieldset>';
        	echo'<legend>Formulario de Registro ya casi estamos</legend>';
    echo"<input type='hidden' name='step' value='step3'>";
    echo'<label for="calle">Nombre de la calle o vía:</label>';
    echo'<input type="text" name="calle" id="calle" placeholder="Escribe el nombre de la calle"  value= ';if(isset($_SESSION['usuario']['calle'])){echo $_SESSION['usuario']['calle'];} echo ">";     
    echo'<label for="numeroPortal">Número del portal:</label>';
    echo'<input type="text" name="numeroPortal" id="numeroPortal" placeholder="Escribe el número del portal" maxlength= "10" value= ';if(isset($_SESSION['usuario']['numeroPortal'])){echo $_SESSION['usuario']['numeroPortal'];} echo ">";     
    echo'<label for="ptr">Puerta:</label>';
    echo'<input type="text" name="ptr" id="ptr" placeholder="Escribe el número de la puerta"  maxlength= "10" value= ';if(isset($_SESSION['usuario']['ptr'])){echo $_SESSION['usuario']['ptr'];} echo ">";     
    echo'<label for="ciudad">Ciudad:</label><span class="obligatorio"><img src="../img/obligado.png" alt="campo obligatorio" title="obligatorio"></span>';
    echo'<input type="text" name="ciudad" id="ciudad" placeholder="Nombre de tu Localidad" maxlength= "25" value= ';if(isset($_SESSION['usuario']['ciudad'])){echo $_SESSION['usuario']['ciudad'];} echo ">";     
    echo'<label for="codPostal">Código Postal:</label><span class="obligatorio"><img src="../img/obligado.png" alt="campo obligatorio" title="obligatorio"></span>';
    echo'<input type="text" name="codPostal" id="codPostal" placeholder="Escribe el número del código postal"  maxlength="5" value= ';if(isset($_SESSION['usuario']['codPostal'])){echo $_SESSION['usuario']['codPostal'];} echo ">";     
    
    echo'<label for="provincia">Provincia:</label>';
 
	echo'<select name="provincia" id="provincia">';
           
               echo'</select>'; 

                echo'<br>';
                echo'<br>';
                
    echo'<label for="pais">Pais:</label>'; 
	echo'<input type="text" name="pais" id="pais" placeholder="España"  maxlength= "25"/>';		
    
     
    echo '<section id="btns_registrar">';
                        echo"<input type='submit' name='terceroReg' id='terceroSigReg'  value='Siguiente'>";
                        echo"<input type='submit' name='terceroReg' id='terceroAtrReg' value='Atras' >";
                        echo"<input type='submit' name='terceroReg' id='terceroSalReg' value='Salir' >";
    echo"</section>";
                    
            echo "</form>";
          
        echo'</fieldset>';
     //En caso de error 
        //se muestra en el formulario   
    if($mensaje){
            echo $mensaje;
        }       
    echo'</section>';
 //fin  displayStep3()   
}

function displayStep4($missingFields){
    global $mensaje;
        
    echo'<section id="form_registro_4" class="fuenteFormulario, generalFormularios">';
                echo'<h4>Introduzca sus datos</h4>';
    echo'<form name="registro" action="registrarse.php" method="POST" id="registro_4" enctype="multipart/form-data">';
        echo'<fieldset>';
        	echo'<legend>Personaliza tu perfil, sube una foto tuya si quieres.</legend>';
    echo"<input type='hidden' name='step' value='step4'>";
    //Modificamos en php.ini y en el formulario el maximo tamaño del archivo
    echo'<input type="hidden" name="MAX_FILE_SIZE" value="50000" />';
    echo'<label for="photo">Solo fotos .jpg</label>';
            
            echo'<input type="file" name="photo" id="photo" value="" />';
            
 echo"<input type='submit' name='cuartoReg' id='cuartoSigReg'  value='Siguiente' accept='image/jpeg'>";   
 echo"<input type='submit' name='cuartoReg' id='cuartoAtrReg'  value='Atras'>";                   
            echo "</form>";
         
        echo'</fieldset>';
    
    
    
     //En caso de error 
        //se muestra en el formulario    
    if($mensaje){
            echo $mensaje;
        }       
    echo'</section>';
    
    
//fin displayStep4    
}

function displayStep5(){
    
    echo '<script type="text/javascript">';
               echo "agregarFormularioCondiciones();";         
    echo '</script>';
       
//fin displayStep5    
}

function confirmarRegistro(){
    echo '<section id="confirmarRegistro">';
        echo '<h2>Has sido registrado correctamente</h2>';
        echo '<h3>Ahora podras logearte con tu usuario y contraseña</h3>';
            echo "<section id='form_registro_5' class='fuenteFormulario, generalFormularios'>";
                echo'<form name="registro" action="registrarse.php" method="POST" id="registro">';
                    echo '<section id="btns_registrar">';
                        echo"<input type='submit' name='registroConfirmado' id='registroConReg' value='Aceptar'>";
                    echo '</section>';
            echo "</form>";
    echo "</section>";
    
//Fin confirmar registro    
}

/**
     * Una vez validado todos los campos 
     * Instanciamos un objeto usuario y
     * hacemos la insercion.
     */
    function ingresarUsuario(){
        global $user;
        $repElimarPhotos = false;
        $repElimarDatosUsuario = false;
        $repEliminarVideos = false;
       
        
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
                
            $testInsert = $user->insert();
            
              $objMandarEmails = new mandarEmails();
            //comparamos con 111 print que 
            // es la respuesta esperada
            //Como medida de seguridad 'no siempre un true es buena idea'
       
            if($testInsert == '111'){
             //Si todo va bien le mandamos a la pagina para confirmar registro
                //y le mandamos un email de bienvenida
               confirmarRegistro();
               //este metodo destruye el objeto $user
              $objMandarEmails->mandarEmailWelcome($user);
            //Si algo ha ido mal le mandamos a la pagina mostrar Error
               //Y nos mandamos un email con los datos introducidos por el usuario  y el error SQL
              if(isset($_SESSION['errorArchivos'])){
                  unset($_SESSION['errorArchivos']);
              }
              
            }else{
               
                //Destruimos las carpetas que se creaban para almacenar sus datos
                $repElimarPhotos = Directorios::eliminarDirectorioRegistro("../photos/".$_SESSION['usuario']['nick']);
                $repElimarDatosUsuario = Directorios::eliminarDirectorioRegistro("../datos_usuario/".$_SESSION['usuario']['nick']);
                $repEliminarVideos = Directorios::eliminarDirectorioRegistro("../Videos/".$_SESSION['usuario']['nick']);
                 //Redirigimos a la pagina error en caso de error
                $_SESSION['error'] = ERROR_INGRESAR_USUARIO;
                mostrarError();
                
                $testTxt = Directorios::escribirErrorValidacion($user, $testInsert, $repElimarDatosUsuario, $repElimarPhotos, $repEliminarVideos);
               
                if($testTxt) {
                     $objMandarEmails->mandarEmailProblemasRegistro($testInsert); 
                }
                  //Destruimos el objeto user
                    unset($user);
                 
            }
           
            
    //fin ingresarUsuario    
    }

function processForm($requiredFields, $st){
    
    //Array para almacenar los campos no rellenados y obligatorios
        global $missingFields;
        global $user;
        global $errores;
        $missingFields = array();   
        //Segun el paso vamos rellenando la variable de session  de usuario
    
        switch ($st){
            case "step1":                                                           
                $_SESSION['usuario']["nick"] = isset($_POST["nick"]) ? preg_replace("/[^\-\_a-zA-Z0-9ñÑ]/", "", $_POST["nick"]) : "";
                $_SESSION['usuario']["password"] = isset($_POST["password"]) ? preg_replace("/[^\-\_a-zA-Z0-9ñÑ]/", "", $_POST["password"]) : "";  
                $_SESSION['usuario']["email"] = isset($_POST["email"]) ? preg_replace("/[^\@\.\-\_a-zA-Z0-9ñÑ]/", "", $_POST["email"]) : "";
                    break;
            case "step2":
                $_SESSION['usuario']["nombre"] = isset($_POST["nombre"])  ? preg_replace("/[^\-\_a-zA-Z.,`'´ñÑ]/", "", $_POST["nombre"]) : "";
                $_SESSION['usuario']["apellido_1"] = isset($_POST["apellido_1"]) ? preg_replace("/[^\-\_a-zA-Z.,`'´ñÑ]/", "", $_POST["apellido_1"]) : "";
                $_SESSION['usuario']["apellido_2"] = isset($_POST["apellido_2"]) ? preg_replace("/[^\-\_a-zA-Z.,`'´ñÑ]/", "", $_POST["apellido_2"]) : "";
                $_SESSION['usuario']["telefono"] = isset($_POST["telefono"]) ?  $_POST["telefono"] : "";
                $_SESSION['usuario']["genero"] = isset($_POST["genero"]) ? $_POST['genero'] : "" ;
                    break;
            case "step3":
                $_SESSION['usuario']["calle"] = isset($_POST['calle']) ? preg_replace("/[^\-\_a-zA-Z0-9.,`'´ñÑ]/", "", $_POST["calle"]) : "";
                $_SESSION['usuario']["numeroPortal"] = isset($_POST['numeroPortal']) ? preg_replace("/[^\-\_0-9]/", "", $_POST["numeroPortal"]) : "";
                $_SESSION['usuario']["ptr"] = isset($_POST['ptr']) ? preg_replace("/[^\-\_a-zA-Z0-9ñÑ]/", "", $_POST["ptr"]) : "";
                $_SESSION['usuario']["ciudad"] = isset($_POST['ciudad']) ? preg_replace("/[^\-\_a-zA-Z0-9.,`'´ñÑ]/", "", $_POST["ciudad"]) : "";
                $_SESSION['usuario']["codPostal"] = isset($_POST['codPostal']) ? preg_replace("/[^\-\_0-9]/", "", $_POST["codPostal"]) : "";
                $_SESSION['usuario']["provincia"] = isset($_POST['provincia']) ? $_POST['provincia'] : "";
                $_SESSION['usuario']["pais"] = isset($_POST['pais']) ? preg_replace("/[^\-\_a-z-Z0-9.,`'´ñÑ]/", "", $_POST["pais"]) : "";
                //cerramos escritura sobre variable de sesion
                session_write_close();
                
                    break; 
            case "step4":
                
                //En este paso no hacemos nada
                //Aqui copiamos la imagen subida por el
                //usuario o sino sube una ponemos la de default
                    break;
        
             case "step5":
                
                //En este paso no hacemos nada
                    break;
        }
            
       
    foreach($requiredFields as $requiredField){
        if(!$_SESSION['usuario'][$requiredField]){
            $missingFields[] = $requiredField;
        }
    }
    
    
    
   
    //Mandamos a validar al metodo anterior los campos segun 
    //cada paso del formulario
    switch ($st){
   
        case 'step1':
            //Si ha habido algun error volvemos a mostrar el paso del formulario
            //  correcto y un mensaje con los campos correspondientes
            if($missingFields || (!$errores->validarCamposRegistro($st, $user))){
                displayStep1($missingFields);
            } else{
                displayStep2(array());
            }
            break;
        case 'step2':
            //Si ha habido algun error volvemos a mostrar el paso del formulario
            //  correcto y un mensaje con los campos correspondientes
            if($missingFields || (!$errores->validarCamposRegistro($st, $user))){
                displayStep2($missingFields);
            } else{
                displayStep3(array());
            }
            break;
     
     
        case 'step3':
            //Si ha habido algun error volvemos a mostrar el paso del formulario
            //  correcto y un mensaje con los campos correspondientes
            if($missingFields || (!$errores->validarCamposRegistro($st, $user))){
                displayStep3($missingFields);
            } else{
                displayStep4(array());
            }
            break;
            
        case 'step4':
            
            if(!$errores->validarCamposRegistro($st, $user)){
                displayStep4($missingFields);
            }else{
                displayStep5();
            }
           
            break;
            
        case 'step5':
            //finalmente si todo ha ido bien mandamos a
                // ingresar el usuario. En caso de error lo
                //redirigimos a una página para hacerselo saber
                // y darle la oportunidad de intentarlo otra vez.                       
                ingresarUsuario();
    }
//fin processForm
}
   
    
    /*section contenedor*/
    echo'</section>';     
    
  
   echo'</body>';
echo'</html>';
