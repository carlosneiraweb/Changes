<?php
//Constantes copiar directorio
define("CONST_YA_EXISTE_DIRECTORIO", array(0, "Ya existe el directorio o archivo a copiar"));
define("CONST_ERROR_ABRIR_DIRECTORIO", array(1, "No se pudo abrir directorio"));
define("CONST_ERROR_CREAR_DIRECTORIO", array(2, "No se pudo crear directorio"));
define("CONST_COPIAR_DIRECTORIO", array(3, "No se pudo copiar directorio "));
define("CONST_COPIAR_ARCHIVO", array(4, "No se pudo copiar archivo o imagen"));
define("CONST_ERROR_NO_EXISTE_DIRECTORIO",array(5, "El directorio no existe"));
//error eliminar archivo
define("CONST_ERROR_ELIMINAR_ARCHIVO", array(6,"No se pudo eliminar una imagen"));
//error eliminar directorio
define("CONST_ERROR_ELIMINAR_DIRECTORIO", array(7,"No se pudo eliminar directorio en el registro"));
//error renombrar foto actualizar
define("CONST_ERROR_RENOMBRAR_FOTO_ACTUALIZARSE", array(8, "No se pudo renombrar la foto  al actualizarse"));
//error renombrar directorios actualizar
define("CONST_ERROR_RENOMBRAR_DIRECTORIOS_ACTUALIZAR", array(9, "Error al renombrar los directorios en la actualiación"));
//error mover imagen
define("CONST_ERROR_MOVER_IMAGEN",array(10,"La imagen no se ha podido mover"));
//error al crear directorio padre en TMP_ACTUALIZAR
define("CONST_ERROR_CREAR_DIRECTORIO_PADRE_TMP", array(11,"No se pudo crear el directorio padre TMP"));
//error ya existe el directorio a copiar en DIRECTORIO_PADRE_TMP
define("CONST_ERROR_CREAR_DIRECTORIO_PADRE_TMP_YA_EXISTE_DIRECTORIO", array(12,"No se pudo crear el directorio padre TMP, ya existe"));
//error en la bbdd al actualizar un usuario
define("CONST_ERROR_BBDD_ACTUALIZAR_USUARIO", array(13,"Hubo un problema en bbdd al actualizar el usuario"));
//error al ingresar un usuario
define("CONST_ERROR_BBDD_REGISTRAR_USUARIO", array(14,"Hubo un problema en bbdd al registrar el usuario"));
//error al crear el subdirectorio
define("CONST_ERROR_CREAR_SUBDIRECTORIO_POST", array(15,"No se pudo crear el subdirectorio donde almacenar el nuevo post"));
//error al registrar un post
define("CONST_ERROR_BBDD_REGISTRAR_POST", array(16,"Hubo un problema en bbdd al registrar el post"));
//error al eliminar un post al registrarlo
define("CONST_ERROR_ELIMINAR_POST_AL_REGISTRARLO", array(17,"No se pudo eliminar el post cuando el usuario intentaba registrarlo"));
//error en la bbdd al actualizar un POST
define("CONST_ERROR_BBDD_ACTUALIZAR_POST", array(18,"Hubo un problema en bbdd al actualizar el post"));
//error al eliminar las imagenes al haber un fallo en la bbdd al registrar un post
define("CONST_ERROR_BBDD_ELIMINAR_IMG_POST", array(19,"Hubo un problema al eliminar las imagenes de un post y hubo un fallo en la bbdd"));
//error al eliminar todas las imagenes al subir un post
define("CONST_ERROR_BBDD_BORRAR_IMG_ELIMINANDO_UN_POST",array(20,"Hubo un error al tratar de eliminar todas las imagenes de un post al borrar este"));
//error al ingresar imagen demo al subir un post
define("CONST_ERROR_BBDD_INGRESAR_IMG_DEMO_SUBIR_POST",array(21,"Hubo un problema al ingresar la imagen demo al subir un post en la bbdd"));
//error al ingresar palabras ofrecidas
define("CONST_ERROR_BBDD_INGRESAR_PALABRAS_OFRECIDAS", array(22,"Hubo un problema al ingresar las palabras ofrecidas"));
//error al ingresar las palabras queridas
define("CONST_ERROR_BBDD_INGRESAR_PALABRAS_QUERIDAS", array(23,"Hubo un problema al ingresar las palabras queridas"));
//error al actualizar texto de una imagen cuando se sube un post
define("CONST_ERROR_BBDD_ACTUALIZAR_TEXT_IMG_SUBIR_POST",array(24,"Hubo un problema al actualizar el texto de una img al subir post"));
//error al consultar los usuarios bloqueados
define("CONST_ERROR_BBDD_CONSULTAR_USUARIOS_BLOQUEADOS", array(25,"Hubo un error al consultar los usuarios bloqueados"));
//error al dar de baja un usuario
define("CONST_ERROR_BBDD_DAR_BAJA_USUARIO_DEFINITIVAMENTE", array(26,"Hubo un error al dar de baja al usuario definitivamente"));
//error bloquear parcialmente un usuario
define("CONST_ERROR_BBDD_DAR_BAJA_USUARIO_PARCIAL", array(27,"Hubo un error al dar de baja parcial a un usuario"));
//error al construir el email palabras buscadas
define("CONST_ERROR_CONSTRUIR_PALABRAS_BUSCADAS",array(28,"No se pudo contruir el email palabras buscadas"));
//error al mandar email darse baja
define("CONST_ERROR_CONSTRUIR_DARSE_BAJA",array(29,"No se pudo contruir el email cuando un usuario se da de baja"));
//error al mandar email darse alta
define("CONST_ERROR_CONSTRUIR_DARSE_ALTA",array(30,"No se pudo contruir el email cuando un usuario se da de alta"));
//error al renombrar una img cuando el usuario ha eliminado una img al subir un post
define("CONST_ERROR_RENOMBRAR_IMG_AL_ELIMINARLA_DEL_POST",array(31,"No se pudo renombrar la imagen cuando un usuario elimino una subiendo un post y subio otra"));
//error al renombrar una img cuando un usuario sube una a un post
define("CONST_ERROR_RENOMBRAR_IMG_AL_SUBIR_UN_POST",array(32,"No se pudo renombrar la imagen cuando un usuario  subiendo una imagen a un post"));
//error al contar los archivos para renombrar las fotos
define("CONST_ERROR_CONTAR_ARCHIVOS",array(33, "Hubo un error al contar los archivos"));
//error al ingresar img en la bbdd al subir post
define("CONST_ERROR_BBDD_AL_SUBIR_UNA_IMG_SUBIENDO_POST",array(34,"No se pudo subir imagen registrando un post en la bbdd"));
//errorbuscar usuarios palabras email
define("CONST_ERROR_BBDD_BUSCAR_USUARIOS_EMAIL",array(35,"Hubo un error al buscar usuarios interesados en las palabras tabla email"));
//error actualizar palabras queridas
define("CONST_ERROR_BBDD_ACTUALIZAR_PBS_QUERIDAS",array(36,"Hubo un error al actualizar las palabras buscadas por el usuario"));
//error actualizar palabras ofrecidas
define("CONST_ERROR_BBDD_ACTUALIZAR_PBS_OFRECIDAS",array(37,"Hubo un error al actualizar las palabras ofrecidas por el usuario"));
//error al devolver id de las palabras buscadas/ofrecidas
define("CONST_ERROR_BBDD_DEVOLVER_ID_PALABRAS_AL_ACTUALIZAR",array(38,"Hubo un error en la bbdd al devolver el id de las palabras buscadas/ofrecidas al actualizar un post"));
//error al cambiar nombre img que sube el usuario al registrarse
define("CONST_ERROR_RENOMBRAR_IMG_REGISTRARSE",array(39,"Hubo un error al renombrar la img del usuario cuando se registraba"));








define("ERROR", "<h5>Revisa tu formulario.</h5><h6>Parece que hay algun campo vacio</h6>");
define("ERROR_VALIDACION_LOGIN", '<h5>El usuario o la contraseña <br> <strong>no son validos</strong>.</h5>');
define("ERROR_VALIDACION_NO_ACTIVO","<h5>Tú cuenta no esta activa.</h5><h5>Ponte en contacto con un administrador</h5>");
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
define("ERROR_FORMATO_FOTO", '<h5>Únicamente aceptamos imagenes .jpg</h5>');
define("ERROR_TAMAÑO_FOTO", '<h5>El tamaño de la foto supera el máximo permitido.</h5>');
define("ERROR_FOTO_NO_ELIGIDA", '<h5>Parece que no has seleccionado una imagen.</h5>');
define("ERROR_ELIMINAR_FOTO", '<h5>Hemos tenido un problema al eliminar la foto</h5>');
define("ERROR_INSERTAR_FOTO", '<h5>Hemos tenido un problema al insertar tu foto.</h5>');
define("ERROR_FOTO_GENERAL", "<h5>Lo sentimos hemos tenido un problema al subir la foto.</h5>");
define("ERROR_INSERTAR_ARTICULO", "<h5>No hemos podido insertar tú anuncio.</h5>");
define("ERROR_INGRESAR_USUARIO", "<h5>Hemos tenido un problema al ingresarte.</h5>");
define("ERROR_REGISTRAR_USUARIO", "<h5>Hemos tenido un problema al tratar de registrarte.</h5>");
define("ERROR_ACTUALIZAR_USUARIO", "<h5>Hemos tenido un problema al actualizarte.</h5><h6>Puedes volver a intentarlo o  ponerte en contacto con nosotros.</h6>");
define("TXT_ERROR_VALIDACION", "../Errores/ErroresValidacion.txt");
define("TXT_ERROR_ELIMINAR_POST", "../Errores/ErroresEliminarPost.txt");
define("ERROR_MANDAR_EMAIL","<h5>Lo sentimos pero por alguna razon no se te ha mandado un email</h5>");
define("ERROR_ACTUALIZAR_POST","<h5>Hemos tenido un problema al actualizar tú Post</h5><h4>Debes ingresarlo otra vez</h4><h3>Lo sentimos de verdad</h3>");






define("ACTUALIZAR", 1);
define("ERROR_ARCHIVOS", "<h5>Parece que hemos tenido un problema.</h5><h5>Lo sentimos, intentalo otra vez.</h5>");
//Constante con la fecha actual 
$fecha_actual = getdate();
define("FECHA_DIA", "Día $fecha_actual[mday], de $fecha_actual[month], del año $fecha_actual[year], a la hora $fecha_actual[hours]: $fecha_actual[minutes]" );
define("ERROR_SUBIR_COMENTARIO", "<h5>Hemos tenido un problema. Revisa todos los campos.</h5>");

