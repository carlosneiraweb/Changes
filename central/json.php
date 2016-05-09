<?php
function __autoload($class){
    $class = str_replace("..", "", $class);
    require_once("../Conexion/$class.php");
}

  // -------  cabeceras indicando que se envian datos JSON.
  header('Content-Type: application/json');
  header('Cache-Control: no-cache, must-revalidate');
  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

  // -------   Crear la conexión al servidor y ejecutar la consulta.
  $conexion=mysql_connect(Constantes::getSV(),Constantes::getUS(),Constantes::getPASS()) or die(mysql_error());
  mysql_query("SET NAMES 'utf8'",$conexion);
  mysql_select_db(Constantes::getBD(),$conexion) or die(mysql_error());
  
  // -------- párametro opción para determinar la select a realizar -------
  if (isset($_POST['opcion'])) 
      $opc=$_POST['opcion'];
  else
     if (isset($_POST['opcion'])) 
        $opc=$_POST['opcion'];
    
   
  // ------- pámetro nombre usuario para realizar la consulta de usuarios registrados       
   if (isset($_POST['pro'])) 
      $nom=$_POST['pro'];
  else
     if (isset($_POST['pro'])) 
        $nom=$_POST['pro'];
 
switch ($opc) {
    case "PP":
        $sql="select nombre from provincias";     
            break;
    case "PG":
        $sql = "select genero from genero";
            break;
            }

  $resultados=mysql_query($sql,$conexion) or die(mysql_error());
  if ($resultados != null){ 
     while ( $fila = mysql_fetch_array($resultados, MYSQL_ASSOC))
     {
         $datos[]=$fila; // Almacenar en un array cada filas del recordset.
        
      }
     echo json_encode($datos);// función de PHP que convierte a formato JSON el array.
  }
  
  mysql_close($conexion);