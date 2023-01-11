<?php



define("METHOD_ENCRIPT",array('aes-256-cbc'));
define("IV_LENGTH",openssl_cipher_iv_length(METHOD_ENCRIPT[0]));
define("CLAVE_ENCRIPT",array('hiofjñhneyhnvpywthvp9yvhpwvvpwyvnwnmpygewweygmy8yv'));
define("IV_ENCRIPT",base64_encode(openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc')-4)));
define("OPTIONS_ENCRIPT",0);
define("KEY_ENCRIPT", hash('sha256', "changes.todo.mundo"));

define("MOSTRAR_PAGINA_ERROR","Location: http://37.221.239.142:8080/Changes/Vista/mostrar_error.php");
define("MOSTRAR_PAGINA_INDEX","Location: http://37.221.239.142:8080/Changes/Vista/index.php");
define("MOSTRAR_PAGINA_SALIR_SESION","Location: http://37.221.239.142:8080/Changes/Vista/abandonar_sesion.php");
