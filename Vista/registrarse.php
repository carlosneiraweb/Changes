<?php 
 
 require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/DataObj.php');
 require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/Usuarios.php');
 require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Controlador/Validar/ValidoForm.php');
 require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Controlador/Validar/ControlErroresSistemaEnArchivosUsuarios.php');
 require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Controlador/Validar/MisExcepcionesUsuario.php');
 require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Directorios.php');
 require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Constantes/ConstantesBbdd.php');
 require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Constantes/ConstantesErrores.php');
 require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Email/mandarEmails.php');
 
   

 
 
if(!isset($_SESSION)){
    
 session_start();

}
 


function mostrarErrorRegistro(){
    
    header(MOSTRAR_PAGINA_ERROR);
}

function volverAnterior(){
    global $resulTestReg;
    if(isset($_SESSION['usuario'])){unset($_SESSION['usuario']);}
    if(isset($resulTestReg)){unset($resulTestReg);}
    if(isset($_SESSION['actualizo'])){unset($_SESSION['actualizo']);}
    if(isset($_SESSION["usuRegistro"])){unset($_SESSION["usuRegistro"]);}
    if(isset($_SESSION["datos"])){unset($_SESSION["datos"]);}
    header(MOSTRAR_PAGINA_INDEX);
   
}
function volverPrincipio(){
    if(isset($_SESSION['usuario'])){unset($_SESSION['usuario']);}
    header(MOSTRAR_PAGINA_INDEX);
    
}
function abandonarSession(){
    
    header(MOSTRAR_PAGINA_SALIR_SESION); 
}


//Variable que utiliza la pagina
//Mostrar error para devolvernos a 
//la pagina donde se a producido
$_SESSION["paginaError"] = basename($_SERVER['PHP_SELF']);

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

    //Añadimos el div con la clase oculto
    // echo'<div id="ocultar" class="oculto"> </div>';  
       
        
        //Variable para recuperar
        //el resultado de la validacion
        //y el posible mensaje de error
        global $resulTestReg;
        $resulTestReg = array();
        
        
      
        
    
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
            //se va a registrar
            if(!isset($_POST['step']) && (!isset($_SESSION['userTMP']))){
                displayStep5(array());
            }elseif(!isset($_POST['step']) && (isset($_SESSION['userTMP']))){
                //va actualizar sus datos
                displayStep1(array()); 
            }
            
               
           
    //COntrolamos que no se le muestra a un usuario 
    //que se haga logueado y este actualizando sus datos
    //y haya un error
    
   
        if(isset($_POST['aceptaCondicionesReg']) and $_POST['aceptaCondicionesReg'] == "Acepto"){
            
            displayStep1(array());
            
        }elseif(isset($_POST['noAceptaCondicionesReg']) and $_POST['noAceptaCondicionesReg'] == "Salir"){
            //El usuario no acepta las condiciones
            volverPrincipio();
        
    }elseif(isset($_POST['primeroReg']) and $_POST['primeroReg'] == "Siguiente"){
        $requiredFields = array('nick', 'password', 'email');
        if(isset($_POST['step']) and $_POST['step'] === "step1"){ $paso = 'step1';}
        processFormRegistro($requiredFields, $paso);
    } elseif(isset($_POST['primeroReg']) and $_POST['primeroReg'] == "Salir"){
            volverAnterior();
    } elseif(isset($_POST['segundoReg']) and $_POST['segundoReg'] == "Siguiente"){
        $requiredFields = array('nombre','telefono');
        if(isset($_POST['step']) and $_POST['step'] === "step2"){ $paso = 'step2';}
        processFormRegistro($requiredFields, $paso);
    } elseif(isset($_POST['segundoReg']) and $_POST['segundoReg'] == "Atras"){
        displayStep1(array());
    } elseif(isset($_POST['segundoReg']) and $_POST['segundoReg'] == "Salir"){
        volverAnterior();
    }elseif(isset($_POST['terceroReg']) and $_POST['terceroReg'] == "Siguiente"){
        $requiredFields = array('codPostal', 'ciudad');
        if(isset($_POST['step']) and $_POST['step'] === "step3"){ $paso = 'step3';}
        processFormRegistro($requiredFields, $paso);
    } elseif(isset($_POST['terceroReg']) and $_POST['terceroReg'] == "Atras"){
        displayStep2(array());
    } elseif(isset($_POST['terceroReg']) and $_POST['terceroReg'] == "Salir"){
        volverAnterior();
    }elseif(isset($_POST['cuartoReg']) and $_POST['cuartoReg'] == "Atras"){
        displayStep3(array());
    } elseif(isset($_POST['cuartoReg']) and $_POST['cuartoReg'] == "Siguiente"){
        $requiredFields = array();
        if(isset($_POST['step']) and $_POST['step'] === "step4"){ $paso = 'step4';}
        processFormRegistro($requiredFields, $paso);
    }elseif(isset($_POST['registroConfirmado']) and $_POST['registroConfirmado'] == "Aceptar") {
       
        volverPrincipio();
    }

  
function displayStep1($missingFields){
    
  
    global $resulTestReg;
    
    echo'<section id="form_registro_1" class="inputsRegistro">';
                echo'<h4>Introduzca sus datos</h4>';
    echo'<form name="registro" action="registrarse.php" method="POST" id="registro_1" >';
        echo'<fieldset>';
                echo"<legend>Formulario de ";if(isset($_SESSION['actualizo']) ){echo "Actualizar Primer paso";}else{echo "Registro Primer Paso";}echo "</legend>";
        echo"<input type='hidden' name='step' value='step1'>";
       
    echo '<section class="modificarDirectoriosUsuariocontEtiquetas">';
    echo'<label '.ValidoForm::validateField("nick", $missingFields).' for="nick" class="labelFormulario">Introduce nombre de usuario:</label><span class="obligatorio"><img src="../img/obligado.png" alt="campo obligatorio" title="obligatorio"></span>';
                                                                                                                                      
    echo'<input type="text" name="nick" id="nick" autofocus placeholder="Tú nombre usuario maximo 25 caracteres" maxlength="25" value=';if(isset($_SESSION['usuario']['nick'])){echo $_SESSION['usuario']['nick'];}else{if(isset($_SESSION['actualizo']) && (!isset($_SESSION['usuario']['nick']))){echo $_SESSION['actualizo']->getValue('nick');}}echo ">";      
    echo'</section>';
    
    echo '<section class="contEtiquetas">';
    echo'<label '.ValidoForm::validateField("password", $missingFields). ' for="password">';if(isset($_SESSION['actualizo']) ){echo "Introduce tú password o cambialo";}else{echo "Introduce tú password";}echo '</label><span class="obligatorio"><img src="../img/obligado.png" alt="campo obligatorio" title="obligatorio"></span>';
    echo'<input type="password" name="password" placeholder="Solo puede tener letras y numeros" id="password"  maxlength="12" placeholder="Debe  minimo 6 y máximo 12" >';	
    echo'</section>';
    
    echo '<section class="contEtiquetas">';
    echo'<label '.ValidoForm::validateField("passReg2", $missingFields). ' for="passReg2">Repite el password</label><span class="obligatorio"><img src="../img/obligado.png" alt="campo obligatorio" title="obligatorio"></span>';
    echo'<input type="password" name="passReg2" id="passReg2" maxlength="12"  >';       
    echo'</section>';

    echo '<section class="contEtiquetas">';
    echo'<label '.ValidoForm::validateField("email", $missingFields).' for="email">Email:</label> <span class="obligatorio"><img src="../img/obligado.png" alt="campo obligatorio" title="obligatorio"></span>';
                                                                                                          
    echo'<input type="text" name="email" id="email" placeholder="info@developerji.com" maxlength="45" value=';if(isset($_SESSION['usuario']['email'])){echo $_SESSION['usuario']['email'];} else {if(isset($_SESSION['actualizo']) && (!isset($_SESSION['usuario']['email']))){ echo $_SESSION['actualizo']->getValue('email');}}echo ">";
   
    
    echo '<section id="btns_registrar">';
                echo"<input type='submit' name='primeroReg' id='primeroSigReg'  value='Siguiente' >";
                echo"<input type='submit' name='primeroReg' id='primeroSalReg'  value='Salir' >";
    echo '</section>';
                    
            echo "</form>";
          
        echo'</fieldset>'; 
    //En caso de error 
        //se muestra en el formulario
        
        if(isset($resulTestReg[0]) && $resulTestReg[0] != ""){
                echo $resulTestReg[0];        
        }        

            echo'</section>';
 //fin displayStep1
}



function displayStep2($missingFields){
    
    global $resulTestReg;          
       
    echo'<section id="form_registro_2" class="inputsRegistro">';
                echo'<h4>Introduzca sus datos</h4>';
    echo'<form name="registro" action="registrarse.php" method="POST" id="registro_2">';
        echo'<fieldset>';
        
    echo"<legend>Formulario de ";if(isset($_SESSION['actualizo'])){echo "Actualizar Segundo paso";}else{echo "Registro Registro Paso";}echo "</legend>";
        	
    echo"<input type='hidden' name='step' value='step2'>";
    
    echo '<section class="contEtiquetas">';
    echo'<label '.ValidoForm::validateField("nombre", $missingFields). ' for="nombre">Nombre:</label> <span class="obligatorio"><img src="../img/obligado.png" alt="campo obligatorio" title="obligatorio"></span>';                                                                                                                                      
    echo'<input type="text" name="nombre" maxlength= "25" id="nombre" autofocus  placeholder="Escribe tú nombre" maxlength= "25" value=';if(isset($_SESSION['usuario']['nombre'])){echo $_SESSION['usuario']['nombre'];}else{if(isset($_SESSION['actualizo']) && (!isset($_SESSION['usuario']['nombre']))){echo $_SESSION['actualizo']->getValue('nombre');}}echo ">";
    echo'</section>';
    
    echo '<section class="contEtiquetas">';
    echo'<label for="apellido_1">Primer Apellido:</label>';
                                                                                                                                    
    echo'<input type="text" name="apellido_1" maxlength= "25" id="apellido_1" placeholder="Escribe tú apellido"  maxlength= "25" value=';if(isset($_SESSION['usuario']['apellido_1'])){echo $_SESSION['usuario']['apellido_1'];}else{if(isset($_SESSION['actualizo']) && (!isset($_SESSION['usuario']['apellido_1']))){ echo $_SESSION['actualizo']->getValue("apellido_1");}}echo ">";
    echo'</section>';
    
    echo '<section class="contEtiquetas">';
    echo'<label for="apellido_2">Segundo Apellido:</label>';                                                                                             
    echo'<input type="text" name="apellido_2" maxlength= "25" id="apellido_2" placeholder="Escribe tú segundo apellido" maxlength= "25" value= ';if(isset($_SESSION['usuario']['apellido_2'])){echo $_SESSION['usuario']["apellido_2"];}else{ if(isset($_SESSION['actualizo']) && (!isset($_SESSION['usuario']['apellido_2']))){echo $_SESSION['actualizo']->getValue("apellido_2");}} echo ">";        
    echo'</section>';
    
    echo '<section class="contEtiquetas">';
    echo'<label '.ValidoForm::validateField("telefono", $missingFields). ' for="telefono">Teléfono:</label><span class="obligatorio"><img src="../img/obligado.png" alt="campo obligatorio" title="obligatorio"></span>';
                                                                                                             
    echo'<input type="text" name="telefono" id="telefono" placeholder="Teléfono contacto" maxlength="9" value=';if(isset($_SESSION['usuario']['telefono'])){ echo $_SESSION['usuario']['telefono'];}else{if(isset($_SESSION['actualizo']) && (!isset($_SESSION['usuario']['telefono']))){echo $_SESSION['actualizo']->getValue('telefono');}}echo ">";
    echo'</section>'; 
    
    echo '<section class="contEtiquetas">';
    echo'<label for="genero">Selecciona tu sexo:</label>';
		echo'<select name="genero" id="genero">';			
		echo'</select>';
    echo'</section>';
	
                echo'<br>';        
    
    echo '<section id="btns_registrar">';
                        echo"<input type='submit' name='segundoReg' id='segundoSigReg'  value='Siguiente'>";
                        echo"<input type='submit' name='segundoReg' id='segundoAtrasReg' value='Atras' >";
                        echo"<input type='submit' name='segundoReg' id='segundoSalirReg' value='Salir' >";
    echo"</section>";
                    
            echo "</form>";
         //En caso de error 
        //se muestra en el formulario
        if(isset($resulTestReg[0]) && $resulTestReg[0] != ""){
            echo $resulTestReg[0];        
        }     
            echo'</fieldset>';  
        
    echo'</section>';
 //fin  displayStep2()   
}
 
function displayStep3($missingFields){
    global $resulTestReg;
        
            
    echo'<section id="form_registro_3" class="inputsRegistro">';
                echo'<h4>Introduzca sus datos</h4>';
    echo'<form name="registro" action="registrarse.php" method="POST" id="registro_3">';
        echo'<fieldset>';
    echo"<legend>Formulario de ";if(isset($_SESSION['actualizo'])){echo "Actualizar Tercer paso";}else{echo "Registro Tercer Paso";}echo "</legend>";
        	
    echo"<input type='hidden' name='step' value='step3'>";
    
    echo '<section class="contEtiquetas">';
    echo'<label for="calle">Nombre de la calle o vía:</label>';                                                                                                        
    echo'<input type="text" name="calle" maxlength= "25" id="calle" placeholder="Escribe el nombre de la calle"  value= ';if(isset($_SESSION['usuario']['calle'])){echo $_SESSION['usuario']['calle'];}else{if(isset($_SESSION['actualizo']) && (!isset($_SESSION['usuario']['calle']))){echo $_SESSION['actualizo']->getValue("calle");}}echo ">";     
    echo'</section>';
    
    echo '<section class="contEtiquetas">';
    echo'<label for="numeroPortal">Número del portal:</label>';                                                                       
    echo'<input type="text" name="numeroPortal"  id="numeroPortal" placeholder="Escribe el número del portal" maxlength= "10" value= ';if(isset($_SESSION['usuario']['numeroPortal'])){echo $_SESSION['usuario']['numeroPortal'];}else{if(isset($_SESSION['actualizo']) && (!isset($_SESSION['usuario']['numeroPortal']))){echo $_SESSION['actualizo']->getValue("numeroPortal");}} echo ">";     
    echo'</section>';
    
    echo '<section class="contEtiquetas">';
    echo'<label for="ptr">Puerta:</label>';
                                                                                                                     
    echo'<input type="text" name="ptr" id="ptr"  placeholder="Escribe el número de la puerta"  maxlength= "10" value= ';if(isset($_SESSION['usuario']['ptr'])){echo $_SESSION['usuario']['ptr'];}else{if(isset($_SESSION['actualizo']) && (!isset($_SESSION['usuario']['ptr']))){echo $_SESSION['actualizo']->getValue('ptr');}}echo ">";     
    echo'</section>';
    
    echo '<section class="contEtiquetas">';                                                                                                                                               
    echo'<label '.ValidoForm::validateField("ciudad", $missingFields).'for="ciudad">Ciudad:</label><span class="obligatorio"><img src="../img/obligado.png" alt="campo obligatorio" title="obligatorio"></span>'; 
    
                                                                                                                       
    echo'<input type="text" name="ciudad" id="ciudad" placeholder="Nombre de tu Localidad" maxlength= "25" value= ';if(isset($_SESSION['usuario']['ciudad'])){echo $_SESSION['usuario']['ciudad'];}else{if(isset($_SESSION['actualizo']) && (!isset($_SESSION['usuario']['ciudad']))){echo $_SESSION['actualizo']->getValue("ciudad");}}echo ">";     
    echo '</section>';
    
    echo '<section class="contEtiquetas">';
    echo'<label '.ValidoForm::validateField("codPostal", $missingFields).'for="codPostal">Código Postal:</label><span class="obligatorio"><img src="../img/obligado.png" alt="campo obligatorio" title="obligatorio"></span>';
                                                                                                                                     ////if(isset($_SESSION['usuario']['nombre'])){echo $_SESSION['usuario']['nombre'];}else{if(isset($_SESSION['actualizo']) && (!isset($_SESSION['usuario']['nombre']))){echo $_SESSION['actualizo']->getValue('nombre');}}echo ">";                                          
    echo'<input type="text" name="codPostal" id="codPostal" placeholder="Escribe el número del código postal"  maxlength="5" value= ';if(isset($_SESSION['usuario']['codPostal'])){echo $_SESSION['usuario']['codPostal'];}else{if(isset($_SESSION['actualizo']) && ( !isset($_SESSION['usuario']['codPostal']))){echo $_SESSION['actualizo']->getValue("codigoPostal");}}echo ">";     
    echo'</section>';
    
    echo '<section class="contEtiquetas">';
    echo'<label for="provincia">Provincia:</label>';
 
	echo'<select name="provincia" id="provincia">';
           
               echo'</select>'; 
    echo'</section>';
    
    
                echo'<br>';
                echo'<br>';
    
    echo '<section class="contEtiquetas">';
    echo'<label for="pais">Pais:</label>'; 
                                                                                              
    echo'<input type="text" name="pais" id="pais" placeholder="España"  maxlength= "25" value= '; if(isset($_SESSION['usuario']['pais'])){echo $_SESSION['usuario']['pais'];}else{if(isset($_SESSION['actualizo']) && (!isset($_SESSION['usuario']['pais']))){echo $_SESSION['actualizo']->getValue('pais');}} echo '>';		
    echo'</section>';
     
    echo '<section id="btns_registrar">';
                        echo"<input type='submit' name='terceroReg' id='terceroSigReg'  value='Siguiente'>";
                        echo"<input type='submit' name='terceroReg' id='terceroAtrReg' value='Atras' >";
                        echo"<input type='submit' name='terceroReg' id='terceroSalReg' value='Salir' >";
    echo"</section>";
                    
            echo "</form>";
          
        echo'</fieldset>';
     //En caso de error 
        //se muestra en el formulario   
        if(isset($resulTestReg[0]) && $resulTestReg[0] != ""){
                echo $resulTestReg[0];        
        } 
        
            echo'</section>';
 //fin  displayStep3()   
}

function displayStep4($missingFields){
    global $resulTestReg;
        
    echo'<section id="form_registro_4" class="inputsRegistro">';
                echo'<h4>Introduzca sus datos</h4>';
    echo'<form name="registro" action="registrarse.php" method="POST" id="registro_4" enctype="multipart/form-data">';
        echo'<fieldset>';
    echo"<legend> ";if(isset($_SESSION['actualizo'])){echo "Actualiza tú imagen si quieres";}else{echo "Personaliza tu perfil, sube una foto tuya si quieres";}echo "</legend>";
        	
    echo"<input type='hidden' name='step' value='step4'>";
    //Modificamos en php.ini y en el formulario el maximo tamaño del archivo
     
    //echo'<input type="hidden" name="MAX_FILE_SIZE" value="50000" />';
    echo '<section class="contEtiquetas">';
    echo'<label for="photo">Solo fotos .jpg</label>';
            
            echo'<input type="file" name="photoArticulo" id="photo" value="" />';
    echo'</section>';
    
 if(isset($_SESSION['actualizo'])){          
 echo "<section id='mostrarFotoAntigua'>";
    echo "<figure id='fotoAntigua'>";
        //Utilizamos la variable de SESSION del Login para
        //Mostrar su antigua imagen
         echo '<img src='."../datos_usuario/".$_SESSION["userTMP"]->getValue("idUsuario")."/".$_SESSION["userTMP"]->getValue('idUsuario').".jpg".' alt="imagen del usuario antigua" title="Esta es tú antigua imagen."/>';
         echo "<figcaption>Tú antigua imagen.</figcaption>";
    echo "</figure>";
    echo"</section>";
 }
 
 echo'<section id="btns_registrar">';
 echo"<input type='submit' name='cuartoReg' id='cuartoSigReg'  value='Siguiente' accept='image/jpeg'>";   
 echo"<input type='submit' name='cuartoReg' id='cuartoAtrReg'  value='Atras'>"; 
 echo'</section>';
 
            echo "</form>";
         
        echo'</fieldset>';
    
    
    
     //En caso de error 
        //se muestra en el formulario    
        if(isset($resulTestReg[0]) && $resulTestReg[0] != ""){
                echo $resulTestReg[0];        
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
        echo '<h3>Solo tienes que confirmar tú correo en el email que te hemos enviado </h3>';
        echo '<h3>Dispones de 48 horas</h3>';
            echo "<section id='form_registro_5' class='inputsREgistro'>";
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
    function ingresarActualizarUsuario(){
        
        global $mensajeReg;
        

        $_SESSION["usuRegistro"] = new Usuarios(array(
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
            "bloqueado" => '1'
           
                ));
               
               
            if(!isset($_SESSION["userTMP"])){
                
                $_SESSION["datos"]["id"] = $_SESSION["usuRegistro"]->insert();
               
                    
                        if(isset($_SESSION['error'])){unset($_SESSION['error']);}    
                        if(isset($_POST["step"])){unset($_POST["step"]);}
                        unset($mensajeReg);
                       
                
            }else{
                 
               $_SESSION["usuRegistro"]->actualizoDatosUsuario();
               
                  
            }
           
          
                
          
    //fin ingresarUsuario    
    }

function processFormRegistro($requiredFields, $st){
    
    //Array para almacenar los campos no rellenados y obligatorios
        global $missingFields;
        global $resulTestReg;
        $missingFields = array();   
        
        //Segun el paso vamos rellenando la variable de session  de usuario
       
        switch ($st){
            case "step1":                                                           
                $_SESSION['usuario']["nick"] = $_POST["nick"];
                $_SESSION['usuario']["password"] = $_POST["password"];  
                $_SESSION['usuario']["email"] = $_POST["email"];
                 
                    break;
            case "step2":
                $_SESSION['usuario']["nombre"] = $_POST["nombre"];
                $_SESSION['usuario']["apellido_1"] = $_POST["apellido_1"];
                $_SESSION['usuario']["apellido_2"] = $_POST["apellido_2"];
                $_SESSION['usuario']["telefono"] = $_POST["telefono"];
                $_SESSION['usuario']["genero"] = $_POST["genero"];
                    break;
                
            case "step3":
                $_SESSION['usuario']["calle"] = $_POST['calle'];
                $_SESSION['usuario']["numeroPortal"] = $_POST['numeroPortal'];
                $_SESSION['usuario']["ptr"] = $_POST['ptr'];
                $_SESSION['usuario']["ciudad"] = $_POST['ciudad'];
                $_SESSION['usuario']["codPostal"] = $_POST['codPostal'];
                $_SESSION['usuario']["provincia"] = $_POST['provincia'];
                $_SESSION['usuario']["pais"] = $_POST['pais'];
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
    
    
    //En cada uno de los pasos
    //Mandamos a validar al metodo validarCamposRegistro
    //del archivo ControlErroresSistemaEnArchivos 
    
    switch ($st){
   
        case 'step1':
            
            $resulTestReg = validarCamposRegistro($st);
           
                //Si ha habido algun error volvemos a mostrar el paso del formulario
                //con los campos que ha rellenado el usuario
                //Si todo es correcto mostramos el siguiente paso
                if($missingFields || ($resulTestReg[1] === 0)){
                    displayStep1($missingFields);
                } else{
                    displayStep2(array());
                }
                break;
                
        case 'step2':
            $resulTestReg = validarCamposRegistro($st);
            
                //Si ha habido algun error volvemos a mostrar el paso del formulario
                //  correcto y un mensaje con los campos correspondientes
                if($missingFields || ($resulTestReg[1] === 0)){
                    displayStep2($missingFields);
                } else{
                    displayStep3(array());
                }
                break;
     
     
        case 'step3':
            
           
            $resulTestReg = validarCamposRegistro($st);
            
                //Si ha habido algun error volvemos a mostrar el paso del formulario
                //  correcto y un mensaje con los campos correspondientes
                if($missingFields || ($resulTestReg[1] === 0)){
                    displayStep3($missingFields);
                } else{
                    displayStep4(array());
                    
                }
                break;
            
        case 'step4':
            
            $resulTestReg = validarCamposRegistro($st);
            
            
            if($resulTestReg[1] === 0){
                displayStep4($missingFields);
            }else{
                //Si el usuario se esta registrando se
                //le muestra el formulario de las condiciones
                if(!isset($_SESSION['actualizo'])){
                   confirmarRegistro();
                }
                
            }
           
            break;
            
       
    }   
//fin processForm
}
   
    
    /*section contenedor*/
    echo'</section>';     
    
  
   echo'</body>';
echo'</html>';
