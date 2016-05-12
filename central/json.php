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
  
 
  
  // -------- párametro opción para determinar la select a realizar -------
  if (isset($_POST['opcion'])) 
      $opc=$_POST['opcion'];
  else
     if (isset($_GET['opcion'])) 
        $opc=$_GET['opcion'];
    
   
  // ------- pámetro nombre usuario para realizar la consulta de usuarios registrados       
   if (isset($_POST['pro'])) 
      $nom=$_POST['pro'];
  else
     if (isset($_GET['pro'])) 
        $nom=$_GET['pro'];
 
    switch ($opc) {
        case "PP":
            $sql="select nombre from provincias";     
                break;
        case "PG":
            $sql = "select genero from genero";
            break;
    }
          
    try{
        
        $con= Connection::connect();
        $st = $con->query($sql);
        $resultados= $st->fetchAll();
        Connection::disconnect($con);
    
        
                $datos = $resultados; // Almacenar en un array cada filas del recordset.
           
          echo json_encode($datos);// función de PHP que convierte a formato JSON el array.
  
    }catch(PDOException $ex){
        Connection::disconnect($con);
        die($ex->getMessage());
    }