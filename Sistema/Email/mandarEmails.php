<?php


/**
 * Description of mandarEmails
 *
 * @author carlos
 */
use PHPMailer\PHPMailer\PHPMailer;
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
 


 final function comprobarEmail($emailUsu,$id){
     
    
     try{
         
     
        
        $cuerpoEmail = '<section id=comprobarEmail>' .
                        
                        '<fieldset>'.
                        '<legend>Enlace activar cuenta</legend>'.
                        '<h4> Solo te queda pulsar en el enlace para validar tú email.</h4>'.
                
                        "<a link href='http://37.221.239.142:8080/Changes/Controlador/Elementos_AJAX/validarEmail.php?email=$emailUsu&id=$id' >Aqui</a>".
                            

                        '</fieldset>'
                        .'</section> ';
                
        $emailAcabado = EMAIL_CABECERA.$cuerpoEmail.EMAIL_FOOTER;
                //$emailAcabado = utf8_decode($emailAcabado);
        $email = new Email($emailAcabado);
                //MANDAMOS EL EMAIL
            
        $correo = $email->mandarEmail($_SESSION["usuRegistro"]->getValue("email"));
        $test = $correo->send();       
                
         if(!$test){throw new Exception("No se pudo contruir el email para activar al usuario",0);}
                    
         
         
     } catch (Exception $ex) {
         
        $excepciones = new MisExcepciones(CONST_ERROR_CONSTRUIR_DARSE_ALTA[1],CONST_ERROR_CONSTRUIR_DARSE_ALTA[0],$ex);
        $excepciones->redirigirPorErrorSistema("ProblemaEmail",true);

    }catch (\Exception $e) { //The leading slash means the Global PHP Exception class will be caught
                //echo $e->getMessage(); //Boring error messages from anything else!
    }finally{
        unset($correo);
    }  
     
     
     
     
     
    //fin comprobarEmail
     
 } 
 
final function mandarEmailWelcome(){
    

     
            //Creamos el objeto email con los datos
            //Que necesitamos de $user para el cuerpo del email
            //La cabecera y el footer son dos constantes
            try{
                $cuerpoEmail = '<section id="saludo">
                        <h4>Enhorabuena '.$_SESSION["usuRegistro"]->getValue("nombre").' por registrarte en <span class="especial">Te Lo Cambio</h4></span>
                        </section>
                        <p>Ahora podrás cambiar con nuestro usuarios.</p> <br />
                        <p>Recuerda que tú usuario es: '.$_SESSION["usuRegistro"]->getValue("nick").' </p>
                        <p>Y tu password es: '.$_SESSION["usuRegistro"]->getValue("password").'</p>';
                ////
                $emailAcabado = EMAIL_CABECERA.$cuerpoEmail.EMAIL_FOOTER;
                //$emailAcabado = utf8_decode($emailAcabado);
                $email = new Email($emailAcabado);
                //MANDAMOS EL EMAIL
                
                $correo = $email->mandarEmail($_SESSION["usuRegistro"]->getValue("email"));
                $test =  $correo->send();
                
                if(!$test){throw new Exception("No se pudo contruir el email welcome o la direccion de email no existe",0);}
                
            }catch (Exception $ex){    
                
                $excepciones = new MisExcepciones(CONST_ERROR_CONSTRUIR_DARSE_ALTA[1],CONST_ERROR_CONSTRUIR_DARSE_ALTA[0],$ex);
                $excepciones->redirigirPorErrorSistema("ProblemaEmail",false);
            
                
            }catch (\Exception $e) { //The leading slash means the Global PHP Exception class will be caught
                //echo $e->getMessage(); //Boring error messages from anything else!
            }finally{
                unset($correo);
            }                        
                           
   //FIN  mandarEmailWelcome 
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
               if(!$test){throw new Exception("NO se pudo construir email palabras buscadas",0);}
                   
            }catch (Exception $ex){
                
                $excepciones = new MisExcepciones(CONST_ERROR_CONSTRUIR_PALABRAS_BUSCADAS[1],CONST_ERROR_CONSTRUIR_PALABRAS_BUSCADAS[0],$ex);
                $excepciones->redirigirPorErrorSistema('ProblemaEmail',false);
                
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
             
            $excepciones = new MisExcepciones(CONST_ERROR_CONSTRUIR_DARSE_BAJA[1],CONST_ERROR_CONSTRUIR_DARSE_BAJA[0],$ex);   
             $excepciones->redirigirPorErrorSistema("ProblemaEmail",false);
        
             
        }finally{
              unset($correoBaja);
        }















     //mandarEmailBajaUsuario    
 }   
    
    
//fin clase
}
  



    
    
    
    
    

