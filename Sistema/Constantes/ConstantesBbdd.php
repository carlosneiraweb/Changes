<?php
//METER EN ARCHIVO APARTE
define("DB_DNS", "mysql:dbname=bbdd_portal_genesis_2022");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "987654321");




define("TBL_DATOS_USUARIO", "datos_usuario");
define("TBL_DIRECCION", "direccion");
define("TBL_GENERO", "genero");
define("TBL_PBS_OFRECIDAS", "busquedas_pbs_ofrecidas");
define("TBL_PBS_QUERIDAS", "busquedas_pbs_buscadas");
define("TBL_POST", "post");
define("TBL_COMENTARIO", "comentarios_post");
define("TBL_PROVINCIAS", "provincias");
define("TBL_SECCIONES", "secciones");
define("TBL_USUARIO", "usuario");
define("TBL_IMAGENES", "imagenes");
define("TBL_TIEMPO_CAMBIO", "tiempo_cambio");
define("TBL_PALABRAS_EMAIL", "palabras_email");
define("TBL_INSERTAR_ERROR", "Errores");
define("TBL_ADMIN", "administradores");
define("TBL_DESBLOQUEAR", "Desbloquear");

define("PAGE_SIZE", 1);
define("LIMIT_RETURN_SEARCH", 25);

/**URL donde se recivira los datos <br/>
 *  mandados por el usuario para activar cuenta*/
define("URL_EMAIL_ACTIVACION","http://37.221.239.142:8080/Changes/Controlador/Elementos_AJAX/validarEmail.php?nick=");

/**Usuario no bloqueado*/
define("NO_BLOQUEADO",0);
/**Bloqueo dar alta email*/
define("BLOQUEO_INICIO",1);
/**Desbloqueo Email*/
define("DESBLOQUEO_EMAIL",0);
/**Bloqueo voluntario usuario*/
define("BLOQUEO_PARCIAL",2);
/**Desbloqueo voluntario usuario*/
define("DESBLOQUEO_PARCIAL",0);

