<?php
                        /**ERRORES DIRECTORIOS*/


/**Error ya existe el directorio*/
define("CONST_YA_EXISTE_DIRECTORIO", array("DIR  0", "Ya existe el directorio o archivo a copiar"));
/**Error abrir directorio*/
define("CONST_ERROR_ABRIR_DIRECTORIO", array("DIR  1", "No se pudo abrir directorio"));
/**Error crear directorio*/
define("CONST_ERROR_CREAR_DIRECTORIO", array("DIR  2", "No se pudo crear directorio"));
/**Error copiar directorio*/
 define("CONST_COPIAR_DIRECTORIO", array("DIR  3", "No se pudo copiar directorio "));
/**Error no existe directorio*/
define("CONST_ERROR_NO_EXISTE_DIRECTORIO",array("DIR  4", "El directorio no existe"));
/**Error eliminar directorio*/
define("CONST_ERROR_ELIMINAR_DIRECTORIO", array("DIR  5","No se pudo eliminar directorio en el registro"));
/**Error renombrar directorios actualizar*/
define("CONST_ERROR_RENOMBRAR_DIRECTORIOS_ACTUALIZAR", array("DIR  6", "Error al renombrar los directorios en la actualiación"));
/**Error al crear el subdirectorio para un Post*/
define("CONST_ERROR_CREAR_SUBDIRECTORIO_POST", array("DIR  7","No se pudo crear el subdirectorio donde almacenar el nuevo post"));
/**Error eliminar directorios al publicar un post y haber un error*/
define( "CONST_ERROR_ELIMINAR_DIR_PUBLICAR_POST",array("DIR  8","No se pudieron eliminar los directorios al haber un error al publicar un post"));
/**Error al eliminar los directorios al darse de baja un usuario*/
define("CONST_ERROR_ELIMINAR_DIRECTORIOS_BAJA",array("DIR  9","No se pudo eliminar los directorios \r\n al dar de baja un usuario. \r\n OJO \r\n Si se dio de baja en la BBDD"));
/**Error al eliminar los directorios que se crearon al intentar hacer el registro de un ususario*/
define("CONST_ERROR_ELIMINAR_DIRECTORIOS_ALTA",array("DIR  10","No se pudo eliminar los directorios al registrar un usuario y ocurrir un error"));






                    /**ERRORES ARCHIVO*/

/**Error copiar archivo*/
define("CONST_COPIAR_ARCHIVO", array("FILE 0", "No se pudo copiar archivo o imagen"));
/**Error eliminar archivo*/
define("CONST_ERROR_ELIMINAR_ARCHIVO", array("FILE 1","No se pudo eliminar una imagen"));
/**Error al eliminar la foto antigua al actualizar a la nueva*/
define("CONST_ERROR_ELIMINAR_FOTO_VIEJA_AL_ACTUALIZAR",array("FILE2 ","No se pudo eliminar la antigua foto al actualizar"));
/**Error renombrar foto actualizar*/
define("CONST_ERROR_RENOMBRAR_FOTO_ACTUALIZARSE", array("FILE 3", "No se pudo renombrar la foto  al actualizarse"));
/**Error al mover imagen al registrarse o actualizar */
define("CONST_ERROR_MOVER_IMAGEN_ACTUALIZAR_REGISTRAR",array("FILE 4","La imagen no se ha podido mover al actualizar o registrar"));
/**Error al mover imagen al subir un Post */
define("CONST_ERROR_MOVER_IMAGEN_SUBIR_POST",array("FILE 5","La imagen no se ha podido mover al subir un post"));
/**Error al renombrar una img cuando el usuario ha eliminado una img al subir un post*/
define("CONST_ERROR_RENOMBRAR_IMG_AL_ELIMINARLA_DEL_POST",array("FILE 6","No se pudo renombrar la imagen cuando un usuario elimino una subiendo un post y subio otra"));
/**Error al renombrar una img cuando un usuario sube una a un post*/
define("CONST_ERROR_RENOMBRAR_IMG_AL_SUBIR_UN_POST",array("FILE 7","No se pudo renombrar la imagen cuando un usuario estaba subiendo una imagen a un post"));
/**Error al contar los archivos para renombrar las fotos*/
define("CONST_ERROR_CONTAR_ARCHIVOS",array("FILE 8", "Hubo un error al contar los archivos"));
/**Error eliminar imagen demo al subir un post*/
define("CONST_ERROR_ELIMINAR_IMG_DEMO_POST_DEL_DIRECTORIO", array("FILE 9","No se pudo eliminar la imagen demo del directorio cuando un usuario subia imagenes"));
/**Error al eliminar una imagen que del directorio esta subiendo un usuario al Post*/
define("CONST_ERROR_ELIMINAR_IMG_SUBIR_POST",array("FILE 10","No se pudo eliminar la img subida al directorio por el usuario al post"));
/**Error al copiar la foto demo al registrarse*/
define("CONST_ERROR_COPIAR_DEMO_REGISTRO", array("FILE 11", "No se pudo copiar la imagen desconocido al registrarse"));
//**Error al copiar imagen DEMO al subir post*/
define("CONST_ERROR_COPIAR_DEMO_POST", array("FILE 12", "No se pudo copiar la imagen DEMO al subir un post."));



                    /**ERRORES BBDD*/


/**Metodo que nos devuelve el ID del usuario a bloquear.<br/> Para saber si ya esta bloqueado*/
define("CONST_ERROR_DEVOLVER_ID_USUARIO_BLOQUEAR", array("BBDD 0","Error al recuperar el id del usuario a bloquear"));
/**Error en la bbdd al actualizar un usuario*/
define("CONST_ERROR_BBDD_ACTUALIZAR_USUARIO", array("BBDD 1","Hubo un problema en bbdd al actualizar el usuario"));
/**Error al ingresar un usuario*/
define("CONST_ERROR_BBDD_REGISTRAR_USUARIO", array("BBDD 2","Hubo un problema en bbdd al registrar el usuario"));
/**Error al registrar un post*/
define("CONST_ERROR_BBDD_REGISTRAR_POST", array("BBDD 3","Hubo un problema en bbdd al registrar el post"));
/**Error al eliminar un post al registrarlo de la bbdd*/
define("CONST_ERROR_ELIMINAR_POST_AL_REGISTRARLO", array("BBDD 4","No se pudo eliminar el post cuando el usuario intentaba registrarlo"));
/**Error en la bbdd al actualizar un POST*/
define("CONST_ERROR_BBDD_ACTUALIZAR_POST", array("BBDD 5","Hubo un problema en bbdd al actualizar el post"));
/**Error al eliminar las imagenes al haber un fallo en la bbdd al registrar un post*/
define("CONST_ERROR_BBDD_ELIMINAR_IMG_POST", array("BBDD 6","Hubo un problema al eliminar las imagenes de un post y hubo un fallo en la bbdd"));
/**Error al eliminar todas las imagenes al subir un post*/
define("CONST_ERROR_BBDD_BORRAR_IMG_ELIMINANDO_UN_POST",array("BBDD 7","Hubo un error al tratar de eliminar todas las imagenes de un post al borrar este"));
/**Error al ingresar imagen demo al subir un post*/
define("CONST_ERROR_BBDD_INGRESAR_IMG_DEMO_SUBIR_POST",array("BBDD 8","Hubo un problema al ingresar la imagen demo al subir un post en la bbdd"));
/**Error al ingresar palabras ofrecidas*/
define("CONST_ERROR_BBDD_INGRESAR_PALABRAS_OFRECIDAS", array("BBDD 9","Hubo un problema al ingresar las palabras ofrecidas"));
/**Error al ingresar las palabras queridas*/
define("CONST_ERROR_BBDD_INGRESAR_PALABRAS_QUERIDAS", array("BBDD 10","Hubo un problema al ingresar las palabras queridas"));
/**Error al actualizar texto de una imagen cuando se sube un post*/
define("CONST_ERROR_BBDD_ACTUALIZAR_TEXT_IMG_SUBIR_POST",array("BBDD 11","Hubo un problema al actualizar el texto de una img al subir post"));
/**Error al consultar los usuarios bloqueados*/
define("CONST_ERROR_BBDD_CONSULTAR_USUARIOS_BLOQUEADOS", array("BBDD 12","Hubo un error al consultar los usuarios bloqueados"));
/**Error al dar de baja un usuario*/
define("CONST_ERROR_BBDD_DAR_BAJA_USUARIO_DEFINITIVAMENTE", array("BBDD 13","Hubo un error al dar de baja al usuario definitivamente"));
/**Error bloquear parcialmente un usuario*/
define("CONST_ERROR_BBDD_DAR_BAJA_USUARIO_PARCIAL", array("BBDD 14","Hubo un error al dar de baja parcial a un usuario"));
/**Error al ingresar img en la bbdd al subir post*/
define("CONST_ERROR_BBDD_AL_SUBIR_UNA_IMG_SUBIENDO_POST",array("BBDD 15","No se pudo subir imagen registrando un post en la bbdd o no se pudo eliminar la imagen demo de la bbdd"));
/**Error buscar usuarios palabras email*/
define("CONST_ERROR_BBDD_BUSCAR_USUARIOS_EMAIL",array("BBDD 16","Hubo un error al buscar usuarios interesados en las palabras tabla email"));
/**Error actualizar palabras queridas*/
define("CONST_ERROR_BBDD_ACTUALIZAR_PBS_QUERIDAS",array("BBDD 17","Hubo un error al actualizar las palabras buscadas por el usuario"));
/**Error actualizar palabras ofrecidas*/
define("CONST_ERROR_BBDD_ACTUALIZAR_PBS_OFRECIDAS",array("BBDD 18","Hubo un error al actualizar las palabras ofrecidas por el usuario"));
/**Error al devolver id de las palabras buscadas/ofrecidas*/
define("CONST_ERROR_BBDD_DEVOLVER_ID_PALABRAS_AL_ACTUALIZAR",array("BBDD 19","Hubo un error en la bbdd al devolver el id de las palabras buscadas/ofrecidas al actualizar un post"));
/**Error eliminar imagen demo al subir un post de la BBDD*/
define("CONST_ERROR_BBDD_ELIMINAR_IMG_DEMO_POST", array("BBDD 20","No se pudo eliminar la imagen demo de la BBDD cuando un usuario subia imagenes"));
/**Error bbdd mostrar img seleccionada*/
define("CONST_ERROR_BBDD_MOSTRAR_IMG_SELECCIONADA",array("BBDD 21","No se pudo mostrar la imagen seleccionada al usuario para modificar o eliminar"));
/**Error al intentar ingresar en la BBDD la imagen demo cuando un usuario eliminaba todas las que estaba subiendo*/
define("CONST_ERROR_BBDD_INGRESAR_IMG_DEMO_AL_ELIMINAR_TODAS_IMG_SUBIR_POST",array("BBDD 22","No se pudo ingresar o mover la imagen demo cuando el usuario subia un post y eliminaba las imagenes"));
/**Error al comprobar que un usuario ya estaba bloqueado total o parcialmente en la BBDD*/
define("CONST_ERROR_BBDD_COMPROBAR_BLOQUEO_USUARIO",array("BBDD 23","No se pudo saber si el usuario ya estaba bloqueado totalmemte o parcialmente"));
/**Error en la BBDD al bloquear al usuario totalmente*/
define("CONST_ERROR_BBDD_BLOQUEAR_TOTAL_USUARIO",array("BBDD 24","No pudimos bloquear totalmente al usuario"));
/**Error  en la BBDD al bloquear parcial un usuario*/
define("CONST_ERROR_BBDD_BLOQUEAR_PARCIAL_USUARIO",array("BBDD 25","No pudimos bloquear parcialmente al usuario"));
/**Error en la BBDD al desbloquear parcialmente un susuario*/
define("CONST_ERROR_BBDD_DESBLOQUEAR_PARCIAL", array("BBDD 26","No pudimos desbloquear parcialmente al usuario"));
/**Error en la BBDD al desbloquear totalmente un usuario*/
define("CONST_ERROR_BBDD_DESBLOQUEAR_TOTALMENTE_USUARIO",array("BBDD 27","No pudimos desbloquear totalmente al usuario"));
/**Error en la BBDD al mostrar los usuarios bloqueados totalmente*/
define("CONST_ERROR_BBDD_MOSTRAR_USUARIOS_BLOQUEADOS_TOTAL",array("BBDD 28","No pudimos mostrar los usuarios bloqueados total"));
/**Error en la BBDD al mostrar los usuarios bloqueados parcialmente*/
define("CONST_ERROR_BBDD_MOSTRAR_USUARIOS_BLOQUEADOS_PARCIAL",array("BBDD 29","No pudimos mostrar los usuarios bloqueados parcial"));
/**Error al desbloquear un usuario*/
define("CONST_ERROR_BBDD_RECUPERAR_DATOS_TABLA_DESBLOQUEAR",array("BBDD 30",'No pudimos recuperar id del usuario  de la tabla desbloquear'));
/**Error al activar la cuenta del usuario al pinchar en el enlace mandado a su email*/
define("CONST_ERROR_BBDD_ACTIVAR_CUENTA_EMAIL",array("BBDD 31","No pudimos activar la cuenta del usuario al activar esta desde el email mandado"));
/**Error al eliminar de la tabla Desbloquear*/
define("CONST_ERROR_BBDD_ELIMINAR_TABLA_DESBLOQUEO", array("BBDD 32", "No se pudo eliminar al usuario de la tabla Desbloquear"));
/**Error al recuperar el id del usuario*/
define("CONST_ERROR_BBDD_RECUPERAR_ID_USUARIO", array("BBDD 33","No recuperamos el id del usuario"));
/**Error eliminar un usuario por id*/
define("CONST_ERROR_BBDD_ELIMINAR_USU_POR_ID",array("BBDD 34","No se pudo eliminar al usuario por ID"));

                        /**ERRORES EMAIL*/
/**Error al construir el email palabras buscadas*/
define("CONST_ERROR_CONSTRUIR_PALABRAS_BUSCADAS",array("EMAIL 0","No se pudo contruir el email palabras buscadas"));
/**error al mandar email darse baja*/
define("CONST_ERROR_CONSTRUIR_DARSE_BAJA",array("EMAIL 1","No se pudo contruir el email cuando un usuario se da de baja"));
/**Error al mandar email darse alta*/
define("CONST_ERROR_CONSTRUIR_DARSE_ALTA",array("EMAIL 2","No se pudo mandar email de Bienvenida"));










/**Error generico para fallos internos del sistema*/
define("ERROR_INTERNO","<h5>Upppss Tuvimos un problema</h5><h4>Intentalo de nuevo</h4>");
define("ERROR", "<h5>Revisa tu formulario.</h5><h6>Parece que hay algun campo vacio</h6>");
define("ERROR_VALIDACION_LOGIN", '<h5>El usuario o la contraseña <br> <strong>no son validos</strong>.</h5>');
define("ERROR_VALIDACION_NO_ACTIVO","<h5>Tú cuenta no esta activa.</h5><h5>Tienes que abrir el enlace que te mandamos a tú email</h5>");
define("ERROR_BAJA_PARCIAL","<h5>Te diste de baja parcial</h5><h5>Para volver a darte de alta nos tienes que escribir un email</h5>");
define("ERROR_NOMBRE_USUARIO_EXISTE", '<h5>El nombre de usuario ya existe</h5>');
define("ERROR_PASSWORD_INCORRECTO", '<h5>El password introducido no cumple las normas</h5>'.
                                "<h5>Recuerda que solo acepta letras y números</h5>".
                                "<h5>Un minimo de 6 y máximo 12 caracteres.</h5>");
define("ERROR_IGUALDAD_PASSWORD", '<h5>Los passwords no son iguales</h5>'.
                                '<h5>Por favor revisalos</h5>');
define("ERROR_EMAIL_EXISTE", '<h5>El email utilizado ya existe</h5>');
define("ERROR_EMAIL_INCORRECTO", '<h5>El email no es valido</h5>'.
                          '<h5>Por favor compruebalo.</h5>');
define("ERROR_TELEFONO_INCORRECTO", '<h5>El teléfono introducido es incorrecto.</h5>');
define("ERROR_CODIGO_POSTAL", "<h5>El código postal no es correcto.</h5>");
define("ACUERDO_CONDICIONES", '<h5>Debes de estar de acuerdo con nuestras condiciones</h5>');
/**Error foto .jpj*/
define("ERROR_FORMATO_FOTO", '<h5>Únicamente aceptamos imagenes .jpg</h5>');
define("ERROR_TAMAÑO_FOTO", '<h5>El tamaño de la foto supera el máximo permitido.</h5>');
define("ERROR_FOTO_NO_ELIGIDA", '<h5>Parece que no has seleccionado una imagen.</h5>');
define("ERROR_ELIMINAR_FOTO", '<h5>Hemos tenido un problema al eliminar la foto</h5>');
define("ERROR_INSERTAR_FOTO", '<h5>Hemos tenido un problema al insertar tu foto.</h5>');
define("ERROR_FOTO_GENERAL", "<h5>Lo sentimos hemos tenido un problema al subir la foto.</h5>");
/**Error generico al insertar un anuncio*/
define("ERROR_INSERTAR_ARTICULO", "<h5>No hemos podido insertar tú anuncio.</h5><h4>Intentalo otra vez</h4>");
define("ERROR_INGRESAR_USUARIO", "<h5>Hemos tenido un problema al ingresarte.</h5>");
define("ERROR_REGISTRAR_USUARIO", "<h5>Hemos tenido un problema al tratar de registrarte.</h5>");
define("ERROR_ACTUALIZAR_USUARIO", "<h5>Hemos tenido un problema al actualizarte.</h5><h6>Puedes volver a intentarlo o  ponerte en contacto con nosotros.</h6>");
define("TXT_ERROR_VALIDACION", "../Errores/ErroresValidacion.txt");
define("TXT_ERROR_ELIMINAR_POST", "../Errores/ErroresEliminarPost.txt");
/**Error generico al mandar un email*/
define("ERROR_MANDAR_EMAIL","<h5>Lo sentimos pero por alguna razon no se te ha mandado un email</h5>");
/**Error al mandar email de validacion*/
define("ERROR_MANDAR_EMAIL_ACTIVACION","<h5>Parece ser que tuvimos un problema al mandar tú email de activación</h5><h4>Intenta registrarte de nuevo</h4><h4>Lo sentimos</h4>");
/**Error al no poder desbloquear al usuario desde el email de activacion*/
define("ERROR_ACTIVAR_CUENTA_EMAIL","<h5>No pudimos activar tú cuenta</h5><h5>Por favor intentalo otra vez o ponte en contacto con nosotros</h5>");
/**Error al actualizar el post del usuario*/
define("ERROR_ACTUALIZAR_POST","<h5>Hemos tenido un problema al actualizar tú Post</h5><h4>Debes ingresarlo otra vez</h4>");
/**Error al desbloquear un usuario*/
define("ERROR_DESBLOQUEAR_USUARIO","<h5>Por algún motivo no hemos podido desbloquearte</h5><h4>Ponte en contacto con nosotros por email</h4>");
/**Error bloquear usuario*/
define("ERROR_BLOQUEAR_USUARIO","<h5>Por algún motivo no hemos podido hacer el bloqueo<h5><h4>Puedes intentarlo de nuevo</h4>");
/**Error mostrar usuarios bloqueados*/
define("ERROR_MOSTRAR_USUARIOS_BLOQUEADOS","<h5>Por algún motivo no podemos mostrarte los usuarios que tienes bloqueados</h5><h4>Puedes intentarlo de nuevo</h4>");
/**Error al eliminar de la BBDD a un usuario*/
define("ERROR_ELIMINAR_USUARIO_BBDD","<h5>No hemos podido darte de baja<h5><h4>Si no puedes volver a logearte</h4><h4>Ponte en contacto con nosotros</h4>");
/**Error  al eliminar los directorios de un usuario al darse de baja*/
define("ERROR_ELIMINAR_DIRECTORIO_BAJA_USUARIO","<h5>Tuvimos un problema al darte de baja</h5><h4>Intentaremos solucionarlo lo antes posible</h4>");
/**Error al eliminar os directorios de un usuario al ocurrir un error al darse de alta*/
define("ERROR_ELIMINAR_DIRECTORIO_ALTA_USUARIO","<h5>Parece que hubo un problema al darte de alta</h5>");
/**Error activar cuenta por email*/
define("ERROR_ACTIVAR_CUENTA_EMAIL","<h5>No pudimos activar tú cuenta.</h5>,<h4>Intentalo otra vez oponte en contacto con nosotros</h4>");











define("ACTUALIZAR", 1);
define("ERROR_ARCHIVOS", "<h5>Parece que hemos tenido un problema.</h5><h5>Lo sentimos, intentalo otra vez.</h5>");
//Constante con la fecha actual 
$fecha_actual = getdate();
define("FECHA_DIA", "Día $fecha_actual[mday], de $fecha_actual[month], del año $fecha_actual[year], a la hora $fecha_actual[hours]: $fecha_actual[minutes]" );
define("ERROR_SUBIR_COMENTARIO", "<h5>Hemos tenido un problema. Revisa todos los campos.</h5>");

