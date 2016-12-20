<?php
//METER EN ARCHIVO APARTE
define("DB_DNS", "mysql:dbname=portal");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "");
define("EMAIL_PASSWORD", "dkssa.26???"); 
define("EMAIL_USERNAME", "administracion@ichangeityou.com");
define("EMAIL_SMTPAUTH", 1);
define("EMAIL_SMTPSECURE", "ssl");
define("EMAIL_HOST", "smtp.strato.com");
define("EMAIL_PORT_EMAIL", 465);
define("TXT_ERROR_VALIDACION", "../Errores/ErroresValidacion.txt");

///////////////////

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
define("NOMBRE_USUARIO_EXISTE", '<h5>El nombre de usuario ya existe</h5>');
define("PASSWORD_INCORRECTO", '<h5>El password introducido no cumple las normas</h5>'.
                                "<h5>Recuerda que solo acepta letras y números</h5>".
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
//Constante con la fecha actual 
$fecha_actual = getdate();
define("FECHA_DIA", "Día $fecha_actual[mday], de $fecha_actual[month], del año $fecha_actual[year], a la hora $fecha_actual[hours]: $fecha_actual[minutes]" );

/*
 * Constantes para email
 */

define("EMAIL_CABECERA", '<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1"/>

        <style type="text/css">
            header, section{ display: block;}
            body {
                    font-family: Arial, Helvetica, Verdana;
                    font-size: 1em;
                    max-width: 95%;
                    border-left: 2px blue solid;
                    border-right: 2px blue solid;
                    margin: 5px auto;
                    position:relative; 
            }
            header,section#contenedor{
                    background-color: #fff;
                    margin: 1em auto;
                    max-width: 960px;
                    min-width: 470px;
                    padding: .25em;
            }
            section#cabecera, section#contenedor{ 
                    width: 90%;
                    margin: 1em auto;
            }
            section#cabecera h3{
                    margin: .25em auto;
                    text-align: center;
            }    
            section#saludo{
                    width: 80%;
                    margin: 1em auto;
                    font-size: 1.5em;
                    color: black;
            }
            h1{
                    font-family:Arial, Helvetica, Verdana;
                    font-size: 4.5em;
                    text-align: center;
                    color:#FF5917;
                    text-shadow: 5px 5px 5px rgba(000,000,000,0.7);
            }
                .especial{
                color: #FF5917;
                font-size: 1.5em;
                }
        
        </style>
        </head>
        <header>
        <section id="cabecera">
        <h1>Te lo cambio</h1>
        <h3>Miles de personas compartiendo te est&aacuten esperando.</h3>
        </section>
        </header>
        <body>
        <section id="contenedor">'
        );
define("EMAIL_FOOTER", '</section><footer></footer></body></html>');


define("EMAIL_FROM", "administracion@ichangeityou.com");
define("EMAIL_FROM_NAME", "Administración de Te lo cambio.");
define("EMAIL_SUBJECT_REGISTER", "Email de TE LO CAMBIO");

//constantes para el cuerpo de cada email
$xc = '<section id="saludo">
            <h4>Enhorabuena carlos por registrarte en <span class="especial">Te Lo Cambio</h4></span>
            </section>
            <p>Ahora podr&aacutes cambiar con nuestro usuarios.</p> <br />
            <p>Recuerda que tu usuario es: ******** </p>
            <p>Y tu contraseña es: ********** </p>';