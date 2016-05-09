<?php 
session_start(); 
$_SESSION["url"] = "index.php";
//echo 'usuario: '.$_SESSION["user"].'<br>';
if(isset($_SESSION["user"])){
$user = $_SESSION["user"];
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
        <script src="mostrar/menu.js"></script>					
        <script src="validar/formulario_login.js"></script>
        <script src="validar/formulario_reg.js"></script>
        <script src="mostrar/elementos.js"></script>
        <script src="mostrar/publicar.js"></script>	
    <!--Para navegadores viejos-->
        <!--[if lt IE 9]>
            <script
        src="//html5shiv.googlecode.com/svn/trunk/html5.js">
        </script>
        <![endif]-->
        
   </head>
   <body id="cuerpo">
       
        <?php
        /**
         * Metodo que permite la autocarga de cualquier clase
         * pasada como parametro.
         * Con str_replace evitamos que puedan subir por los directorios.
         * @param type $class
         */
        
        spl_autoload_register(function($class){
            $class = str_replace("..", "", $class);
            set_include_path('.;validar;.;entidades');
            require_once("/$class.php");
            //throw new Exception("Imposible cargar $class.");
        });
         
    
    /**
     * Creamos una clase que hereda
     * de la clase que nos va a permitir 
     * validar el formulario.
      */
    class prueba extends ValidoForm {
         public function __construct($a,$b){
         parent::__construct($a,$b);
         
         } 
        
     }
     
    
    /**
     * Este metodo instancia los arrays para los campos requeridos
     * y otro array para los campos que no se han rellenado.
     * Recive como parametro el tipo de formulario que mostrar
     * @param type $tipo
     */
    function arrayCamposRequeridos($tipo){
        if($tipo === 'logeo'){
            $camposRequeridos = array("nickLogin", "passLogin");
        } else{
            $camposRequeridos = array('nameReg','telefono','emailReg','nickReg', 'passReg1', 'passReg2','condiciones');
        }
        $noRecividos = array();
           
        processForm($camposRequeridos, $noRecividos, $tipo); 
    }
     
     //Objeto global para todo el script
     global $objValidar;
     //objeto para cargar los datos del usuario
     global $objUsu;
     try{
     $objValidar = new prueba(array(), array());
     $objUsu = new Usuarios();
     }catch (Exceptio $e){
        echo $e->getMessage()."\n"; 
     }
      
     
     /*****************************************************/
     /**
     * Recojemos los datos de la url
     * Dependiendo de que boton se apriete
     * se muestra un formulario u otro
     */  
    if(isset($_POST["logeo"])){
        arrayCamposRequeridos('logeo');
        
        //Creamos una cookie con el nombre de usuario
        //Esto es necesario para el proceso de creacion de los posts
        
                $objUsu->nick= $_POST['nickLogin'];
                $objUsu->password_1 = $_POST['passLogin'];
                if($objValidar->validarEntrada($objUsu)){
                //setcookie("usuario", $objUsu->nick ,0,'/','',false, true);
               $_SESSION["user"]= $objUsu->nick;
               session_write_close();
                    header("Location: index.php"); 
                } else{
                    header("Location: mostrar_error.php"); 
                }
                
           
        //eliminamos el objeto 
        $objValidar->eliminarObjeto($objUsu);        
        $objValidar->eliminarObjeto($objValidar);
       
    }elseif (isset($_POST['registrar']) ){
            arrayCamposRequeridos('registrar');
              
                $objUsu->nombre = $_POST['nameReg'];
                $objUsu->email = $_POST['emailReg'];
                $objUsu->telefono = $_POST['telefono'];
                $objUsu->nick = $_POST['nickReg'];
                $objUsu->password_1 = $_POST['passReg1'];
                $objUsu->password_2 = $_POST['passReg2'];
                $objUsu->string = $_POST['condiciones'];
            
           
                if($objValidar->validoRegistro($objUsu)){
                    header("Location: index.php"); 
                }else{
                    header("Location: mostrar_error.php"); 
                }
            
         //Eliminamos objetos       
        $objValidar->eliminarObjeto($objUsu);        
        $objValidar->eliminarObjeto($objValidar);
        
        
    } else{
        //Si no se ha pulsado el boton de enviar se muestra por primera vez el formulario 'vacio'

            $noRecividosLogeo = array();
            $noRecividosReg = array();
            displayFormLogeo($noRecividosLogeo);
            displayFormRegistro($noRecividosReg);
            
        }

        function processForm($camposRequeridos, $noRecividos, $tipo){
      
        foreach($camposRequeridos as $requiredField){
            if(!isset($_POST[$requiredField]) or !$_POST[$requiredField]){
                    $noRecividos[] = $requiredField;
                }
            }
       
       global $objValidar;
                
       $objValidar = new prueba($camposRequeridos,$noRecividos);
      
            if(count($objValidar->getPerdidos()) !== 0){
                if($tipo === 'logeo'){
                    displayFormLogeo($noRecividos);
                    return false;
                } elseif ($tipo === 'registrar') {
                    displayFormRegistro($noRecividos);
                }  
            }
        //eliminamos el objeto    
        $objValidar->eliminarObjeto($objValidar);    
        //fin processForm      
        }
    

      
    echo'<header>';
	echo'<figure id="logo" class="fade">';
		echo'<img src="img/logo.png" alt="Logo del portal"/>';
		echo'<figcaption id="titulo">Cambia todo lo que ya no uses.</figcaption>';
	echo'</figure>';
	echo'<section id="cabecera">';
			echo'<h1>Te lo cambio</h1>';
			echo'<h3>Miles de personas compartiendo te están esperando.</h3>';
		echo'<section id="btns_logueo">';
			echo'<input type="button" id="ingresar" name="ingresar" value="Ingresar"/>';
			echo'<input type="button" id="registrar" name="registrar" value="Registrarse"/>';
                        
		echo'</section>';
                echo '<section id="btn_desloguear">';
                global $user;
                    if($user){
                        echo'<a href="abandonar_sesion.php">Salir Sesión</a>';
                    }
                
                echo '</section>';
                
	echo'</section>';
     
    echo'</header>';
    
      echo'<div id="ocultar" class="oculto"> </div>';
      
      
     
  function displayFormLogeo($elementos){
        global $objValidar;
             
             
         echo'<section id="login_form" class="oculto login_form_tamanyo" >';
    	 echo'<h4>Introduzca sus datos</h4>';
    echo'<form name="logeo" action="index.php" method="post" id="form_login">';
        echo'<fieldset>';
  
            echo'<legend>Formulario de ingreso</legend>';
echo'<label '.$objValidar->validateField("nickLogin", $elementos).' for="nickLogin" >Introduce nombre de usuario:</label><span class="obligatorio"><img src="img/obligado.png" ></span>';
echo'<input type="text" name="nickLogin" id="nickLogin" autofocus placeholder="Escribe tú nick" value='.$objValidar->setValue('nickLogin').' ><br></br>';            
echo'<label '.$objValidar->validateField("passLogin", $elementos).' for="passLogin">Introduce tú password</label><span class="obligatorio"><img src="img/obligado.png" ></span>';
echo'<input type="password" name="passLogin" id="passLogin" placeholder="Escribe tú password" value='.$objValidar->setValue('passLogin').' ><br><br>';
 echo'<input type="submit" id="btn_login" name="logeo" value="aceptar" />';          


        echo'</fieldset>';
                echo'</form>';
        echo'</section>';
    
       //Eliminamos el objeto
      $objValidar->eliminarObjeto($objValidar);
    //fin formLogeo   
    }
    
    
   function displayFormRegistro($elementos){
       global $objValidar;
  
   echo'<section id="form_registro" class="oculto registro_form_tamanyo">';
    	echo'<h4>Introduzca sus datos</h4>';
    echo'<form name="registro" action="index.php" method="post" id="form_registro">';
        echo'<fieldset>';
        	echo'<legend>Formulario de Registro</legend>';
    echo'<label '.$objValidar->validateField("nameReg", $elementos). ' for="nameReg">Nombre:</label>';
    echo'<input type="text" name="nameReg" id="nameReg" autofocus  placeholder="Escribe tú nombre" value='.$objValidar->setValue('nameReg').'>';
    echo'<label for="primer_apellido">Primer Apellido:</label>';
    echo'<input type="text" name="primer_apellido" id="primer_apellido" placeholder="Escribe tú apellido"  />';
    echo'<label for="segundo_apellido">Segundo Apellido:</label>';
    echo'<input type="text" name="segundo_apellido" id="segundo_apellido" placeholder="Escribe tú apellido"  />';
    echo'<label for="calle">Calle:</label>';
    echo'<input type="text" name="calle" id="calle" placeholder="Calle donde vives"  />';		
    echo'<label for="numero">Número:</label>'; 
    echo'<input type="text" name="numero" id="numero" placeholder="Número de tú casa"  />';
    echo'<label for="ciudad">Ciudad:</label>';
    echo'<input type="text" name="ciudad" id="ciudad" placeholder="Ciudad de residencia"  />';

   echo'<label for="provincia">Provincia:</label>';
 
	echo'<select name="provincia" id="provincia">';
           
               echo'</select>'; 

                echo'<br>';
                echo'<br>';
                
    echo'<label for="pais">Pais:</label>'; 
	echo'<input type="text" name="pais" id="pais" placeholder="España"  />';		
			
    echo'<label for="genero">Selecciona tu sexo:</label>';
		echo'<select name="genero" id="genero">';			
		echo'</select>';
	
                echo'<br>';
              
  
    echo'<label '.$objValidar->validateField("telefono", $elementos). ' for="telefono">Teléfono:</label>';
    echo'<input type="text" name="telefono" id="telefono" placeholder="No será mostrado" value='.$objValidar->setValue('telefono').' >';
    echo'<label '.$objValidar->validateField("emailReg", $elementos).' for="emailReg">Email:</label> ';
    echo'<input type="text" name="emailReg" id="emailReg" placeholder="info@developerji.com" value='.$objValidar->setValue('emailReg').'>'; 
    echo'<label '.$objValidar->validateField("nickReg", $elementos).' for="nickReg">Introduce nombre de usuario:</label> ';
    echo'<input type="text" name="nickReg" id="nickReg" placeholder="Tú nombre usuario"  value='.$objValidar->setValue('nickReg').'>';
    echo'<label '.$objValidar->validateField("passReg1", $elementos). ' for="passReg1">Introduce tú password</label>';
    echo'<input type="password" name="passReg1" class="passReg1" id="passReg1" value='.$objValidar->setValue('passReg1').' >';	
    echo'<label '.$objValidar->validateField("passReg2", $elementos). ' for="passReg2">Repite el password</label>';
    echo'<input type="password" name="passReg2" class="passReg2" id="passReg2" value='.$objValidar->setValue('passReg2').'>';
    echo'<label ' .$objValidar->validateField("condiciones", $elementos).' for="condiciones">Acepta las condiciones</label> ';
    echo'<input type="checkbox" name="condiciones" id="condiciones" value="1"/>';
    echo'<input type="submit" id="btn_registro" name="registrar" value="aceptar"/>';
        echo'</fieldset>';
                echo'</form>';
        echo'</section>';
    
     //Eliminamos el objeto
      $objValidar->eliminarObjeto($objValidar);   
    //fin displayFormRegistro
   }
   
  
 
        
     

    echo'<nav class="slider-container">';
	echo'<figure id="derecha">';
		echo'<img src="img/derecha.png" class="activar" alt="Botones de desplazamiento"/>';
	echo'</figure>';
	
        echo'<figure id="arriba" class="noOcupar">';
		echo'<img src="img/arriba.png" class="activar" alt="Botones de desplazamiento"/>';
        echo'</figure>';	
	
            echo'<ul id="slider" class="slider-wrapper">';
                echo'<li class="slide-current"><a href="index.php">Inicio</a><a href="index.php">Servicios</a><a href="index.php">Automoción</a><a href="index.php">Ocio</a></li>';
		echo'<li><a href="index.php">Inicio</a><a href="index.php">Bricolage</a><a href="index.php">Electrónica</a><a href="index.php">Moda</a></li>';
		echo'<li><a href="index.php">Inicio</a><a href="index.php">Hogar</a><a href="index.php">Hospedaje</a><a href="index.php">Cultura</a></li>';
            echo'</ul>';
	
	echo'<figure id="abajo" class="noOcupar">';
		echo'<img src="img/abajo.png" class="activar" alt="Botones de desplazamiento"/>';
	echo'</figure>';
	echo'<figure id="izquierda" class="slider-controls, ocultar">';
		echo'<img src="img/izquierda.png" class="activar"  alt="Botones de desplazamiento"/>';
	echo'</figure>';
    echo'</nav>';
   
 
    echo'<section id="contenedor">';
    echo'<section id="posts">';
            echo'<div>';
            
        echo'<input type="button" id="publicar" name="publicar" value="Publicar"/>';

            echo'</div>';
        echo'</section>';
        echo'<aside id="publi">';
		echo'<p>Aqui va la publicidad</p>';
	echo'</aside>';
	
    
    echo'</section>';
               
            ?>
      <footer>
    
        <script src="http://platform.twitter.com/widgets.js"></script>
            <a href="http://twitter.com/share" class="twitter-share-button"
                data-text="#te lo cambio.es | Portal de intercambio de objetos entre particulares"
                data-url="https://telocambio.es" >Twittear</a>
                    <br/>
        
    </footer>
  
   </body>
</html>
