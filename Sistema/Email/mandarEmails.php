<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of mandarEmails
 *
 * @author carlos
 */

require_once('../Modelo/Usuarios.php');
require_once('../Modelo/DataObj.php');
require_once('../Sistema/Constantes.php');
require_once('../Modelo/Email.php');


/**
 * Esta clase tiene metodos concretos
 *  para mandar emails segun el caso
 */
class mandarEmails {
 
 static function mandarEmailProblemasRegistro(DataObj $obj, $mensaje){
     //Creamos el cuerpo del email
     $cuerpoEmail = '<section id="mensaje">
            <h3> Con fecha:'. FECHA_DIA. ' Ha habido un problema de registro de un usuario.</h3>
            <h4>El error es: '.$mensaje. '</h4>
            <h3>Los datos intruducidos por el usuario son: </h3>
            <pre>
            nombre" => '.$obj->getValue("nombre").'
            apellido_1 '.$obj->getValue("apellido_1").'
            apellido_2 '.$obj->getValue("apellido_2").'
            calle '.$obj->getValue("calle").'
            numeroPortal '.$obj->getValue("numeroPortal").'
            ptr '.$obj->getValue("ptr").'
            ciudad '.$obj->getValue("ciudad").'
            codigoPostal '.$obj->getValue("codigoPostal").'
            provincia '.$obj->getValue("provincia").'
            telefono '.$obj->getValue("telefono").'
            pais '.$obj->getValue("pais").'
            genero '.$obj->getValue("genero").'
            email '.$obj->getValue("email").'
            nick '.$obj->getValue("nick").'
            password '.$obj->getValue("password").'
            password_2 '.$obj->getValue("password_2").'
            </pre>';
          
         $emailAcabado = EMAIL_CABECERA.$cuerpoEmail.EMAIL_FOOTER;
    
    $email = new Email($emailAcabado);
    //MANDAMOS EL EMAIL
    $test = $email->mandarEmail("carlosneirasanchez@gmail.com");
    //echo $test.'<br />';
        if($test === true) {
            //Si todo ha ido bien eliminamos los objetos user y email
            unset($obj);
            unset($email);
        }
        llamar();
     
 }
 
 static function mandarEmailWelcome(DataObj $obj){
    
    //Creamos el objeto email con los datos
    //Que necesitamos de $user para el cuerpo del email
    //La cabecera y el footer son dos constantes
    $cuerpoEmail = '<section id="saludo">
            <h4>Enhorabuena '.$obj->getValue("nombre").' por registrarte en <span class="especial">Te Lo Cambio</h4></span>
            </section>
            <p>Ahora podr&aacutes cambiar con nuestro usuarios.</p> <br />
            <p>Recuerda que tu usuario es: '.$obj->getValue("nick").' </p>
            <p>Y tu password es: '.$obj->getValue("password").'</p>';
             
    $emailAcabado = EMAIL_CABECERA.$cuerpoEmail.EMAIL_FOOTER;
    
    $email = new Email($emailAcabado);
    //MANDAMOS EL EMAIL
    $test = $email->mandarEmail($obj->getValue("email"));
    //echo $test.'<br />';
        if($test === true) {
            //Si todo ha ido bien eliminamos los objetos user y email
            unset($obj);
            unset($email);
        } else{
            echo 'No se ha mandado email<br />';
            //Si el email no ha podido ser mandado por algun motivo
            //Destruimos el objeto email y creamos uno nuevo
            //Con el error que nos ha devuelto el metodo email
            unset($email);
            $errorEmailMandar = '<section id="errorEmailRegistro">
                            <h3>Ha habído un error al mandar el email de registro al usuario'.$user->getValue('nick'). '</h3>
                            <h4>Con error' .$test.'</h4>
                            <h4> Y fecha: '.FECHA_DIA.'</h4>
                            </section>'; 
            $emailAcabado = EMAIL_CABECERA.$errorEmailMandar.EMAIL_FOOTER; 
            //Creamos un nuevo email y lo mandamos al administrador
            $email = new Email($emailAcabado);
            $email->mandarEmail(EMAIL_USERNAME);
            //Acabamos destruyendo el objeto user
            unset($user);
                }
   //FIN  mandarEmailWelcome 
    }


    function llamar(){
        echo "<h1>he sido llamado</h1>";
    }

//fin clase
}
  



    
    
    
    
    

