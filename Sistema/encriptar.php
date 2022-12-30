<?php

//$enlane= "<a link href='http://37.221.239.142:8080/Changes/Controlador/Elementos_AJAX/validarEmail.php?email=$emailUsu&id=$id' >Aqui</a>".
$email = "arj.123@hotmail.es";
$emailCod =  base64_encode($email);
echo $emailCod;
$EmailDec = base64_decode("YXJqLjEyM0Bob3RtYWlsLmVz");
echo $EmailDec;