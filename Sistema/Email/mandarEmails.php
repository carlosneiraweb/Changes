<?php


/**
 * Description of mandarEmails
 *
 * @author carlos
 */

require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/Usuarios.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/DataObj.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Constantes.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/Email.php');



/**
 * Esta clase tiene metodos concretos
 *  para mandar emails segun el caso
 * Siempre destruyen los objetos que recive DataObject
 * 
 */
class mandarEmails {
 

  final function mandarEmailProblemasRegistro($mensaje){
      
    try{
        //Creamos el cuerpo del email
        $cuerpoEmail = '<section id="mensaje">
            <h3> Con fecha:'. FECHA_DIA. ' Ha habido un problema de registro de un usuario.</h3>
            <h4>El error es: '.$mensaje. '</h4>';

            $emailAcabado = EMAIL_CABECERA.$cuerpoEmail.EMAIL_FOOTER;
    
        $email = new Email($emailAcabado);
        //MANDAMOS EL EMAIL
            $email->mandarEmail(EMAIL_USERNAME);
            unset ($email);
    }catch(Exception $ex){
        echo "Error al mandar email problemas de registro. ".$ex->getMessage();
    }
       
     
 }
 
final function mandarEmailWelcome(DataObj $obj){

        
            //Creamos el objeto email con los datos
            //Que necesitamos de $user para el cuerpo del email
            //La cabecera y el footer son dos constantes
            try{
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
                        //Si el email no ha podido ser mandado por algun motivo
                        //Destruimos el objeto email y creamos uno nuevo
                        //Con el error que nos ha devuelto el metodo email
                        unset($email);
                        $errorEmailMandar = '<section id="errorEmailRegistro">
                                        <h3>Ha habído un error al mandar el email de registro al usuario'.$obj->getValue('nick'). '</h3>
                                        <h4>Con error' .$test.'</h4>
                                        <h4> Y fecha: '.FECHA_DIA.'</h4>
                                        </section>'; 
                        $emailAcabado = EMAIL_CABECERA.$errorEmailMandar.EMAIL_FOOTER; 
                        //Creamos un nuevo email y lo mandamos al administrador
                        $email = new Email($emailAcabado);
                        $email->mandarEmail(EMAIL_USERNAME);
                        //Acabamos destruyendo el objeto user
                        unset($obj);
                        unset($email);    
                    }
            }catch (Exception $ex){
                echo "Error al mandar email welcome ".$ex->getMessage().'<br>';
                echo "El codigo es: ".$ex->getCode().'<br>';
                echo "La traza es: ".$ex->getTrace().'<br>';
            }                        
                           
   //FIN  mandarEmailWelcome 
    }


 /**
  * palabras buscadas por el usuario
  * @param type array
  */
  final function mandarEmailPalabrasBuscadas($datosPalabras){
        
       //Creamos el objeto email con los datos
            //Que necesitamos de $user para el cuerpo del email
            //La cabecera y el footer son dos constantes
      
            
            
            $urlImagen = base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT']."/Changes/photos/".$datosPalabras[6].".jpg"));
            $urlImagen = "data:image/jpeg;base64,$urlImagen";
            //$urlImagen = 'data: '.mime_content_type($urlImagen).';base64,';
            
            //echo $_SERVER['DOCUMENT_ROOT']."/Changes/photos/".$datosPalabras[6].".jpg";

            try{
                $cuerpoEmail = "<section id='saludo'>
                    
                       //src='cid:prueba'
                        <h4>Enhorabuena ".$datosPalabras[5]['nickRecibeEmail']."  </h4>
                        <span class='usuPublica'>".$datosPalabras[3]." de "
                        .$datosPalabras[4] ["provinciaPublica"]. " a publicado este Post.</span>".
                        "<figure id='imgEmailBuscado'><img src='".$urlImagen."'  alt='prueba'><figcaption>Imagen publicada</figcaption></figure>"    
                        ."<h3>Esta persona esta interesada en cambiarlo por: "
                        
                        ."<li>".$datosPalabras[0][0]."</li>"
                        ."<li>".$datosPalabras[0][1]."</li>"
                        ."<li>".$datosPalabras[0][2]."</li>"
                        ."<li>".$datosPalabras[0][3]."</li>"
                        
                        ."<h4>Si quieres verlo completamenta podrás encontrarlo en la"
                        . "sección  de ".$datosPalabras[2]. ".";
                        "<h5>Saludos del equipo.</h5>".
                                $datosPalabras[5]["emailUsuBusca"].
                        "</section>";
                        

                $emailAcabado = EMAIL_CABECERA.$cuerpoEmail.EMAIL_FOOTER;
                $emailAcabado = utf8_decode($emailAcabado);
                
                
                $email = new Email($emailAcabado);
                
                //MANDAMOS EL EMAIL
                $test = $email->mandarEmail('carlosneirasanchez@gmail.com');
                //echo $test.'<br />';
                    if($test === true) {
                        //Si todo ha ido bien eliminamos los objetos user y email
                       
                        unset($email);
                     
                    }
            }catch (Exception $ex){
                echo "Error al mandar email welcome ".$ex->getMessage().'<br>';
                echo "El codigo es: ".$ex->getCode().'<br>';
                echo "La traza es: ".$ex->getTrace().'<br>';
            }                        
                            
        
        
        
  //fin mandarEmailPalabrasBucadas      
    }
    
    
    
    
    
//fin clase
}
  



    
    
    
    
    

