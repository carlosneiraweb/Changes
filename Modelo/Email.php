<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Email
 *
 * @author carlos
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
            $mail->SMTPSecure = "ssl";//SSL security socket layer
            $mail->Host = "smtp.strato.com";//servidor de SMTP de gmail
            $mail->Port = 465;//puerto seguro del servidor SMTP de gmail
            $mail->From = "administracion@ichangeityou.com"; //Remitente del correo
            $mail->FromName = "Te lo cambio.";
            $mail->AddAddress($destino);// Destinatario
            $mail->Username ="administracion@ichangeityou.com";//"administracion@ichangeityou.com";//;Aqui pon tu correo de gmail// //
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
                
        }  catch (Exception $e){
            echo $ex->getLine().'<br>';
            echo $ex->getFile().'<br>';
            die("Error crear EMAIL: ".$ex->getMessage());
            
        }
	
                
                
	
        
         
        //POR SI SE SUBEN ARCHIVOS
//} else {
//	$respuesta = "Ocurrio un error al subir el archivo adjunto =(";
//}

//unlink($destino); //borramos el archivo del servidor

//header("Location: formulario-phpmailer.php?respuesta=$respuesta");
    }
    
//fin clase    
}
