<!DOCTYPE html>
<!--
 author Carlos Neira Sanchez
 mail arj.123@hotmail.es
 telefono ""
 nameAndExt prueba.php
 fecha 24-abr-2016
-->

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
    
    
         spl_autoload_register( 'autoload' );

  function autoload( $class, $dir = null ) {
      echo 'la clase es. '.$class.'<br>';//para saber lo que recive
    if ( is_null( $dir ) ){
      $dir = 'C:\xampp\htdocs\Proyecto-Final';
    }
    
    foreach ( scandir( $dir ) as $file ) {
 
      // directory?
      if ( is_dir( $dir.$file ) && substr( $file, 0, 1 ) !== '.' )
     //Esto lo he modificado ya que estoy hacienda las pruebas en wampServer Windows
        autoload( $class, $dir.$file.'\\' );
 
      // php file?
      if ( substr( $file, 0, 2 ) !== '._' && preg_match( "/.php$/i" , $file ) ) {
 
        // filename matches class?
        if ( str_replace( '.php', '', $file ) == $class || str_replace( '.class.php', '', $file ) == $class ) {
 
            include $dir . $file;
        }
      }
      // throw new Exception('Imposible encontrar clase');
    }
   
  }
  
  /**
     * Creamos una clase que hereda
     * de la clase que nos va a permitir 
     * validar el formulario.
     */    
    class prueba extends ValidoForm {
         public function __construct($a,$b){
         parent::__construct($a,$b);
         } 
    }
  
  //try{
     //Este script esta en la raiz del proyecto
     //$obj es un objeto de la clase prueba que extiende a la clase ValidaForm,
     //ValidaForm implementa la interfaz Interf_comprobar, Ambas estan en la ruta /validar/ValidoForm.php y /validar/Interf_comprobar.php
     $objValidar = new prueba(array(), array());
     //$objUsu; Objeto de la clase usuarios su ruta es: /entidades/Usuarios
     $objUsu = new Usuarios();
//     }catch (Exceptio $e){
//        echo $e->getMessage()."\n"; 
//     }
        ?>
    </body>
</html>
