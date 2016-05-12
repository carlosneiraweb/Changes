<?php 
session_start(); 
$_SESSION["url"] = "index.php";

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
    
    echo'<section id="contenedor" class="cambiarPosition">';
    echo'<div id="ocultar" class = "mostrar_transparencia"></div>';
    
if(isset($_GET["step"]) and $_GET["step"] >= 1 and $_GET["step"] <=3 ){
            call_user_func("processStep" . (int)$_GET["step"]);
        }else{
            displayStep1(array(), new Usuarios(array()));
        }
        
    /*Mandamos a comprobar los campos del primer formulario*/
    if(isset($_GET['primero']) and $_GET['primero'] == "Next >"){
        echo 'entramos';
        $requiredFields = array('nick', 'password', 'email');
        processForm($requiredFields, "step1");
    }    
        
    function processStep1(){
             displayStep2();
        }
        
        
        function processStep2(){
            if(isset($_GET["submitButton"]) and $_GET["submitButton"] == "< Back" ){
                displayStep1();
            }else{
                displayStep3();
            }
        }
        
        function processStep3(){
           
            if(isset($_GET["submitButton"]) and $_GET["submitButton"] == "< Back"){
                displayStep2();
            }else{
                displayThanks();
            }
        }

     
function displayStep1($missingFields, $user){
    global $valido;
    echo'<section id="form_registro">';
                echo'<h4>Introduzca sus datos</h4>';
    echo'<form name="registro" action="register.php" method="get" id="registro">';
        echo'<fieldset>';
        	echo'<legend>Formulario de Registro</legend>';
            echo"<form action='hidden.php' method='get'>";
            echo"<input type='hidden' name='step' value='1'>";
    
    echo'<label '.$valido->validateField("nick", $missingFields).' for="nick">Introduce nombre de usuario:</label> ';
    echo'<input type="text" name="nick" id="nick" placeholder="Tú nombre usuario"  value="'.$user->getValueEncoded('nick').'">';        
    echo'<label '.$valido->validateField("password", $missingFields). ' for="password">Introduce tú password</label>';
    echo'<input type="password" name="password" class="passReg1" id="password" value="" >';	
    echo'<label '.$valido->validateField("passReg2", $missingFields). ' for="passReg2">Repite el password</label>';
    echo'<input type="password" name="passReg2" class="passReg2" id="passReg2" value="">';       
    echo'<label '.$valido->validateField("email", $missingFields).' for="email">Email:</label> ';
    echo'<input type="email" name="email" id="email" placeholder="info@developerji.com" value='.$user->getValueEncoded('email').'>'; 
            
                echo"<input type='submit' name='primero' id='primero' value='Next &gt;' >";
                    
            echo"</form><br>";
            
        echo'</fieldset>';   
    echo'</section>';
 //fin displayStep1
}

function displayStep2(){
    echo 'paso dos';
 //fin  displayStep2()   
}
 
function displayStep3(){
    echo 'paso tres';
 //fin  displayStep3()   
}
function processForm($requiredFields, $step){
    //Array para almacenar los campos no rellenados y obligatorios
            $missingFields = array();
    
    $user = new Usuarios(
            array(
                "nick" => isset($_GET["nick"]) ? preg_replace("/[^\-\_a-zAZ0-9]/", "", $_GET["nick"]) : "",
                "password" => isset($_GET["password"]) ? preg_replace("/[^\-\_a-zAZ0-9]/", "", $_GET["password"]) : "",          
                "email" => isset($_GET["email"]) ? preg_replace("/[^\@\.\-\_a-zA-Z0-9]/", "", $_GET["email"]) : "",
            )
            );
    
    foreach($requiredFields as $requiredField){
        if(!$user->getValue($requiredField)){
            $missingFields[] = $requiredField;
        }
    }
   
    if($missingFields and $step == "step1"){
        displayStep1($missingFields, $user);
    } elseif(!$loggedInMember = $user->insert()) {
       $test = false;
       displayStep1($missingFields, $user);
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
