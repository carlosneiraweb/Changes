<?php



define("METHOD_ENCRIPT",array('aes-256-cbc'));
define("IV_LENGTH",openssl_cipher_iv_length(METHOD_ENCRIPT[0]));
define("CLAVE_ENCRIPT",array('hiofjñhneyhnvpywthvp9yvhpwvvpwyvnwnmpygewweygmy8yv'));
define("IV_ENCRIPT",base64_encode(openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc')-4)));
define("OPTIONS_ENCRIPT",0);
define("KEY_ENCRIPT", hash('sha256', "changes.todo.mundo"));


/**url a mostrar_error desde /Changes*/
define("MOSTRAR_PAGINA_ERROR",'Location: /Changes/Vista/mostrar_error.php');
/**url a index desde /Changes*/
define("MOSTRAR_PAGINA_INDEX",'Location: /Changes/Vista/index.php');
/**url a abandonar sesion desde /Changes*/
define("MOSTRAR_PAGINA_SALIR_SESION",'Location: /Changes/Vista/abandonar_sesion.php');
/**Constante sapara combinar con el basename*/
define("MOSTRAR_PAGINA_BASENAME", 'Location: /Changes/Vista/');


