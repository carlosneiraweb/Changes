<?php

/**
 * @author Carlos Neira Sanchez
 * @mail arj.123@hotmail.es
 * @telefono ""
 * @nameAndExt busquedas.php
 * @fecha 04-oct-2016
 */

/**
 *  Clase que define el objeto email.
 *  Crea un objeto de email
 */
require_once('../Sistema/Constantes.php');
require_once("../Sistema/Email/class.phpmailer.php");
require_once("../Sistema/Email/class.smtp.php");

class Email {
   
    private $email = "";
    
    /**
     * Constructor de emails
     * @param type $emailAcabado
     */
    public function __construct($emailAcabado) {
          $this->email = $emailAcabado;    
    }
    
    public function mandarEmail($destino){
        
//$cabeceras = "MIME-Version: 1.0\r\n";
//$cabeceras .= "Content-type: text/html; charset=iso-8859-1\r\n";
//$cabeceras .= "From: $de \r\n";
	
        try{
            $mail = new PHPMailer(); //creo un objeto de tipo PHPMailer
            $mail->IsSMTP(); //protocolo SMTP
            $mail->SMTPAuth = true;//autenticaci�n en el SMTP
            $mail->SMTPSecure =  EMAIL_SMTPSECURE;//SSL security socket layer
            $mail->Host = EMAIL_HOST;//servidor de SMTP de gmail
            $mail->Port = EMAIL_PORT_EMAIL;//puerto seguro del servidor SMTP de gmail
            $mail->From = EMAIL_FROM;//Remitente del correo
            $mail->FromName = "Te lo cambio.";
            $mail->AddAddress($destino);// Destinatario
            $mail->Username = EMAIL_USERNAME;//"administracion@ichangeityou.com";//;Aqui pon tu correo de gmail// //
            $mail->Password = EMAIL_PASSWORD;//Aqui pon tu contrase�a de gmail
            $mail->IsHTML(true);
            //$mail->AltBody = "Usted esta viendo este mensaje simple debido a que su servidor de correo no admite formato HTML.";
            $mail->Subject = "Correo de TE LO CAMBIO"; //Asunto del correo
            //$mail->Body = $cuerpoEmail;
            $mail->WordWrap = 50; //No. de columnas
            $mail->MsgHTML($this->email);//Se indica que el cuerpo del correo tendr� formato html
            //$mail->AddAttachment($destino); //accedemos al archivo que se subio al servidor y lo adjuntamos

                if($mail->Send()){ //enviamos el correo por PHPMailer
                    return true;
                } else{
                    return $mail->ErrorInfo;
                }
                
        }  catch (Exception $ex){
            echo $ex->getLine().'<br>';
            echo $ex->getFile().'<br>';
            die();
            
        }

    }
    
//fin clase    
}
