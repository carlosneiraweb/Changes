<?php

//$de = $_POST["de_txt"];
//$para = $_POST["para_txt"];
//$asunto = $_POST["asunto_txt"];
//$mensaje = $_POST["mensaje_txa"];
//$cabeceras = "MIME-Version: 1.0\r\n";
//$cabeceras .= "Content-type: text/html; charset=iso-8859-1\r\n";
//$cabeceras .= "From: $de \r\n";



//$archivo = $_FILES["archivo_fls"]["tmp_name"];
//$destino = $_FILES["archivo_fls"]["name"];

//if(move_uploaded_file($archivo,$destino)){
//	//incluyo la clase phpmailer
//    
//}	
      	include_once("../Constantes.php");
        include_once("Email.php");
	include_once("class.phpmailer.php");
	include_once("class.smtp.php");
	
	$mail = new PHPMailer(); //creo un objeto de tipo PHPMailer
	$mail->IsSMTP(); //protocolo SMTP
	$mail->SMTPAuth = true;//autenticaci�n en el SMTP
	$mail->SMTPSecure = "ssl";//SSL security socket layer
	$mail->Host = "smtp.strato.com";//servidor de SMTP de gmail
	$mail->Port = 465;//puerto seguro del servidor SMTP de gmail
	$mail->From = "administracion@ichangeityou.com"; //Remitente del correo
        $mail->FromName = "Te lo cambio.";
	$mail->AddAddress("carlosneirasanchez@gmail.com");// Destinatario
	$mail->Username ="administracion@ichangeityou.com";//"administracion@ichangeityou.com";//;Aqui pon tu correo de gmail// //
	$mail->Password = EMAIL_PASSWORD;//Aqui pon tu contrase�a de gmail
        $mail->IsHTML(true);
        $mail->CharSet = 'UTF-8';
        //$mail->AltBody = "Usted esta viendo este mensaje simple debido a que su servidor de correo no admite formato HTML.";
	$mail->Subject = "Email de TE LO CAMBIO"; //Asunto del correo
	//$mail->Body = $cuerpoEmail;
	$mail->WordWrap = 50; //No. de columnas
	$mail->MsgHTML("CARLOS");//Se indica que el cuerpo del correo tendr� formato html
	//$mail->AddAttachment($destino); //accedemos al archivo que se subio al servidor y lo adjuntamos
	
	if($mail->Send()){ //enviamos el correo por PHPMailer
		$respuesta = "El mensaje ha sido enviado con la clase PHPMailer y tu cuenta de gmail =)";
	} else{
		$respuesta = "El mensaje no se pudo enviar con la clase PHPMailer y tu cuenta de gmail =(";
	   	$respuesta .= " Error: ".$mail->ErrorInfo;
                //echo $respuesta;
	}
        
         
        //POR SI SE SUBEN ARCHIVOS
//} else {
//	$respuesta = "Ocurrio un error al subir el archivo adjunto =(";
//}

//unlink($destino); //borramos el archivo del servidor

//header("Location: formulario-phpmailer.php?respuesta=$respuesta");
