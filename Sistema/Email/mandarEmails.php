<?php


/**
 * Description of mandarEmails
 *
 * @author carlos
 */

use PHPMailer\PHPMailer\Exception;


require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/Usuarios.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/DataObj.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Constantes/ConstantesBbdd.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Constantes/ConstantesEmail.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Constantes/ConstantesErrores.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/Email.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Controlador/Validar/MisExcepcionesUsuario.php');

    if(!isset($_SESSION)){
    
        session_start();

    }
    


class mandarEmails {
 

    public static function mandarEmailWelcome($nick){
        

     
            //Creamos el objeto email con los datos
            //Que necesitamos de $user para el cuerpo del email
            //La cabecera y el footer son dos constantes
            try{
                
                $user = Usuarios::getByUsername($nick);
                
                if($user === null){throw new Exception("No pudimos recuperar al objeto usuario \r"
                        . " con el nick pasado");}
                
                
                $cuerpoEmail = "<section id='saludo'>
                        <h4>Enhorabuena $nick por registrarte en <span class='especial'>Te Lo Cambio</h4></span>
                        </section>
                        <p>Ahora podrás cambiar con nuestro usuarios.</p> <br /></p>";
                        
                ////
                $emailAcabado = EMAIL_CABECERA.$cuerpoEmail.EMAIL_FOOTER;
                //$emailAcabado = utf8_decode($emailAcabado);
                $email = new Email($emailAcabado);
                //MANDAMOS EL EMAIL
                
                $correo = $email->mandarEmail($user->getValue("email"));
                $test =  $correo->send();
                
                if(!$test){throw new Exception($correo->ErrorInfo,0);}
                
            }catch (Exception $ex){    
                
                $excepciones = new MisExcepcionesUsuario(CONST_ERROR_CONSTRUIR_DARSE_ALTA[1],CONST_ERROR_CONSTRUIR_DARSE_ALTA[0],$ex);
                $excepciones->redirigirPorErrorSistemaUsuario("emailBienvenida",false);
            
                
            }finally{
                unset($correo);
                unset($user);
            }             
    //fin mandarEmailBienvenida
    }
    
    
    /**
     * Metodo que manda un email<br/>
     * para activar la cuenta.<br>
     * 
     * @param type String <br>
     * nick del usuario para mandar el email<br>
      * @throws Exception
     * 
     */

    final function mandarEmailActivacion($nick){
     
   
        try{
         
      
            $url =   URL_EMAIL_ACTIVACION."$nick";
            
                $cuerpoEmail = '<section id=comprobarEmail>' .

                                '<fieldset>'.
                                '<legend>Enlace activar cuenta</legend>'.
                                "<h4> $nick solo te queda pulsar en el enlace para validar tú email.</h4>".

                                "<a link href='$url' >Aqui</a>".


                                '</fieldset>'
                                .'</section> ';

                $emailAcabado = EMAIL_CABECERA.$cuerpoEmail.EMAIL_FOOTER;
                        //$emailAcabado = utf8_decode($emailAcabado);
                $email = new Email($emailAcabado);
                        //MANDAMOS EL EMAIL

                $correo = $email->mandarEmail($_SESSION["usuRegistro"]->getValue("email"));

                $test = $correo->send();       
                
        if(!$test){
            throw new Exception("No se pudo contruir el email para activar al usuario  ", 0);
            
        }
            
         
         
    } catch (Exception $ex) {
         
        $excepciones = new MisExcepcionesUsuario(CONST_ERROR_CONSTRUIR_DARSE_ALTA[1],CONST_ERROR_CONSTRUIR_DARSE_ALTA[0],$ex);
        $excepciones->redirigirPorErrorSistemaUsuario("mandarEmailActivacion",true);

    }finally{
        unset($correo);
    }  
     
     
     
     
     
    //fin comprobarEmail
     
 } 
 
           
   


 /**
  * palabras buscadas por el usuario
  * @param type array
  */
  final function mandarEmailPalabrasBuscadas($datosPost,$usuInteresados,$correo,$provinciaUsuPublica,$ruta){

      
    // echo "/Changes/photos/".$ruta[0].'/'.$ruta[1].".jpg";
       //Creamos el objeto email con los datos
            //Que necesitamos de $user para el cuerpo del email
            //La cabecera y el footer son dos constantes

            $urlImagen = base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT']."/Changes/photos/".$ruta[0].'/'.$ruta[1].".jpg"));
            $urlImagen = "data:image/jpeg;base64,$urlImagen";
            //$urlImagen = 'data: '.mime_content_type($urlImagen).';base64,';
            
            //echo $_SERVER['DOCUMENT_ROOT']."/Changes/photos/".$datosPalabras[6].".jpg";
            //src='cid:prueba'
            try{
                $cuerpoEmail = "<section id='saludo'>
                    
                       
                        <h4>Enhorabuena ".$usuInteresados[0][2]."  </h4>
                        <span class='usuPublica'>".$datosPost[2]." de "
                        .$provinciaUsuPublica["provinciaPublica"]. " a publicado este Post.</span>".
                        "<figure id='imgEmailBuscado'><img src='".$urlImagen."'  alt='prueba'><figcaption>Imagen publicada</figcaption></figure>"    
                        ."<h3>Esta persona esta interesada en cambiarlo por: "
                        
                        ."<li>".$datosPost[0][0]."</li>"
                        ."<li>".$datosPost[0][1]."</li>"
                        ."<li>".$datosPost[0][2]."</li>"
                        ."<li>".$datosPost[0][3]."</li>"
                        
                        ."<h4>Si quieres ver lo completamenta podrás encontrarlo en la"
                        . " sección  de  ".$datosPost[1]. ".".
                        "<h5>Saludos del equipo.</h5>".
                                $usuInteresados[0][2].
                        "</section>";
                        

                $emailAcabado = EMAIL_CABECERA.$cuerpoEmail.EMAIL_FOOTER;
                //$emailAcabado = utf8_decode($emailAcabado);
                
                
                $email = new Email($emailAcabado);
                
                //MANDAMOS EL EMAIL
               
               $correoPalabras = $email->mandarEmail($correo);
               $test = $correoPalabras->send();
               if(!$test){throw new Exception("NO se pudo construir email palabras buscadas ",0);}
                   
            }catch (Exception $ex){
                
                $excepciones = new MisExcepcionesUsuario(CONST_ERROR_CONSTRUIR_PALABRAS_BUSCADAS[1],CONST_ERROR_CONSTRUIR_PALABRAS_BUSCADAS[0],$ex);
                $excepciones->redirigirPorErrorSistemaUsuario('ProblemaEmail',false);
                
            } finally {
                unset($correoPalabras);
                     
            }                        
                            
        
        
        
  //fin mandarEmailPalabrasBucadas      
    }
    
    
final function mandarEmailBajaUsuario($nick,$mail){
    
          
         // echo $nick.' '.$mail;
        try {

           

            $cuerpoEmail = "<section id='emailBaja'>";
            $cuerpoEmail .=  "<h2> Hola ".$nick." tú baja ha sido realizada con exito</h2>";
            $cuerpoEmail .=  "<p>Esperamos volver a verte pronto por aqui.</p>";
            $cuerpoEmail .= "<h4> Saludos del equipo de Te lo Cambio</h4>";


                $emailAcabado = EMAIL_CABECERA.$cuerpoEmail.EMAIL_FOOTER;
                   //$emailAcabado = utf8_decode($emailAcabado);


                $email = new Email($emailAcabado);
                $correoBaja = $email->mandarEmail($mail);
                    //MANDAMOS EL EMAIL
               $test = $correoBaja->send();
                
                if(!$test){throw new Exception("No se pudo mandar email de baja usuario",0);}

           
        } catch (Exception $ex) {
             
            $excepciones = new MisExcepcionesUsuario(CONST_ERROR_CONSTRUIR_DARSE_BAJA[1],CONST_ERROR_CONSTRUIR_DARSE_BAJA[0],$ex);   
             $excepciones->redirigirPorErrorSistemaUsuario("ProblemaEmail",false);
        
             
        }finally{
              unset($correoBaja);
        }



     //mandarEmailBajaUsuario    
 }   
    
    
//fin clase
}
  



    
    
    
    
    

