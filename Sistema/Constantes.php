<?php

define("DB_DNS", "mysql:dbname=portal");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "");
define("PAGE_SIZE", 7);
define("TBL_DATOS_USUARIO", "datos_usuario");
define("TBL_DIRECCION", "direccion");
define("TBL_GENERO", "genero");
define("TBL_PBS_OFRECIDAS", "pbs_ofrecidas");
define("TBL_PBS_QUERIDAS", "pbs_queridas");
define("TBL_POST", "post");
define("TBL_PROVINCIAS", "provincias");
define("TBL_SECCIONES", "secciones");
define("TBL_USUARIO", "usuario");
define("TBL_IMAGENES", "imagenes");
define("TBL_TIEMPO_CAMBIO", "tiempo_cambio");
define("ERROR", "Revisa tu formulario.");
define("ERROR_VALIDACION_LOGIN", '<h5>El usuario o la contraseña <br> <strong>no son validos</strong>.</h5>');
define("PASSWORD_EXISTE", '<h5>El nombre de usuario ya existe</h5>');
define("PASSWORD_INCORRECTO", '<h5>El password introducido no cumple las normas<h5>'.
                                "<h5>Recuerda que solo acepta letras y números/<h5>".
                                "<h5>Un minimo de 6 y máximo 12 caracteres.</h5>");
define("IGUALDAD_PASSWORD", '<h5>Los passwords no son iguales</h5>'.
                                '<h5>Por favor revisalos</h5>');
define("EMAIL_EXISTE", '<h5>El email utilizado ya existe</h5>');
define("EMAIL_INCORRECTO", '<h5>El email no es valido</h5>'.
                          '<h5>Por favor compruebalo.</h5>');
define("TELEFONO_INCORRECTO", '<h5>El teléfono introducido es incorrecto.</h5>');
define("CODIGO_POSTAL", "<h5>El código postal no es correcto.</h5>");
define("ERROR_FORMATO_FOTO", '<h5>Únicamente aceptamos imagenes .jpg</h5>');
define("ERROR_TAMAÑO_FOTO", '<h5>El tamaño de la foto supera el máximo permitido.</h5>');
define("ERROR_FOTO_NO_ELIGIDA", '<h5>Parece que no has seleccionado una imagen.</h5>');
define("ERROR_ELIMINAR_FOTO", '<h5>Hemos tenido un problema al eliminar la foto</h5>');
define("ERROR_INSERTAR_FOTO", '<h5>Hemos tenido un problema al insertar tu foto.</h5>');
define("ERROR_FOTO_GENERAL", "<h5>Lo sentimos hemos tenido un problema al subir la foto.</h5>");
define("ERROR_INSERTAR_ARTICULO", "<h5>No hemos podido insertar tu articulo.</h5>");
