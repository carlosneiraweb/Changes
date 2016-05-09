<?php 
session_start();
$usuario = $_SESSION['user'];
$url = $_SESSION["url"];
echo "usuario es: $usuario y session es: ".$url."<br>";

?>


<!DOCTYPE html>
<!--
 author Carlos Neira Sanchez
 mail arj.123@hotmail.es
 telefono ""
 nameAndExt subir_archivos_servidor.php
 fecha 17-abr-2016
-->

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    <a href="subir_posts.php"></a>
        <link rel='stylesheet' type='text/css' href="css/estilos.css"/>
    </head>
    <body>
        
        
        <?php
        global $nuevoDirectorio;
        global $count;
        $count = 0;
       
        
        if(isset($_POST['sendPhoto'])){
            
            processForm();
        }else{
            displayForm();
        }
        
        function processForm(){
            global $count;
            global $usuario;
            $count++;
            global $nuevoDirectorio;
            if($count === 1){
   
            $nuevoDirectorio = contarDirectorios($usuario);
            }
            
            
            if(isset($_FILES['photo']) and $_FILES['photo']['error'] == UPLOAD_ERR_OK){
           
                if($_FILES['photo']['type'] != 'image/jpeg'){
                    echo"<p>JPEG photos only, thanks!</p>";
                }elseif(!move_uploaded_file($_FILES['photo']['tmp_name'], $nuevoDirectorio.'/'.  basename($_FILES['photo']['name']))){
                    echo"<p>Sorry, there was  a problem uploading that photo.</p>".$_FILES['photo']['error'];
                }else{
                    $nombreArchivo = $nuevoDirectorio.'/'.  basename($_FILES['photo']['name']);
                    $nuevoName = renombrarArchivo( $nombreArchivo,$nuevoDirectorio);
                }
   
                //segunda imagen
                if (isset($_FILES['photo2']) and $_FILES['photo2']['error'] == UPLOAD_ERR_OK) {
                if($_FILES['photo2']['type'] != 'image/jpeg'){
                    echo"<p>JPEG photos only, thanks!</p>";
                }elseif(!move_uploaded_file($_FILES['photo2']['tmp_name'], $nuevoDirectorio.'/'.  basename($_FILES['photo2']['name']))){
                    echo"<p>Sorry, there was  a problem uploading that photo2.</p>".$_FILES['photo2']['error'];
                }else{
                    $nombreArchivo = $nuevoDirectorio.'/'.  basename($_FILES['photo2']['name']);
                    $nuevoName = renombrarArchivo($nombreArchivo, $nuevoDirectorio);
                }
                }
                //tercera imagen
                if (isset($_FILES['photo3']) and $_FILES['photo3']['error'] == UPLOAD_ERR_OK) {
                if($_FILES['photo3']['type'] != 'image/jpeg'){
                    echo"<p>JPEG photos only, thanks!</p>";
                }elseif(!move_uploaded_file($_FILES['photo3']['tmp_name'], $nuevoDirectorio.'/'.  basename($_FILES['photo3']['name']))){
                    echo"<p>Sorry, there was  a problem uploading that photo2.</p>".$_FILES['photo3']['error'];
                }else{
                    $nombreArchivo = $nuevoDirectorio.'/'.  basename($_FILES['photo3']['name']);
                    $nuevoName = renombrarArchivo($nombreArchivo, $nuevoDirectorio);
                }
                }
                //cuarta imagen
                if (isset($_FILES['photo4']) and $_FILES['photo4']['error'] == UPLOAD_ERR_OK) {
                if($_FILES['photo4']['type'] != 'image/jpeg'){
                    echo"<p>JPEG photos only, thanks!</p>";
                }elseif(!move_uploaded_file($_FILES['photo4']['tmp_name'], $nuevoDirectorio.'/'.  basename($_FILES['photo4']['name']))){
                    echo"<p>Sorry, there was  a problem uploading that photo2.</p>".$_FILES['photo4']['error'];
                }else{
                    $nombreArchivo = $nuevoDirectorio.'/'.  basename($_FILES['photo4']['name']);
                    $nuevoName = renombrarArchivo($nombreArchivo, $nuevoDirectorio);
                }
                }
                //quinta imagen
                if (isset($_FILES['photo5']) and $_FILES['photo5']['error'] == UPLOAD_ERR_OK) {
                if($_FILES['photo5']['type'] != 'image/jpeg'){
                    echo"<p>JPEG photos only, thanks!</p>";
                }elseif(!move_uploaded_file($_FILES['photo5']['tmp_name'], $nuevoDirectorio.'/'.  basename($_FILES['photo5']['name']))){
                    echo"<p>Sorry, there was  a problem uploading that photo5.</p>".$_FILES['photo5']['error'];
                }else{
                    $nombreArchivo = $nuevoDirectorio.'/'.  basename($_FILES['photo5']['name']);
                    try{
                    $nuevoName = renombrarArchivo($nombreArchivo, $nuevoDirectorio);
                    }catch(Exception $e){
                        header("Location : mostrar_error.php");
                    }
                }
                }
                
            
            }else{
                switch ($_FILES['photo']['error']){
                    case UPLOAD_ERR_INI_SIZE:
                        $message = "The photo is larger than the server allows";
                            break;
                    case UPLOAD_ERR_FORM_SIZE:
                        $message = "The photo is larger than the script allows.";
                            break;
                    case UPLOAD_ERR_NO_FILE:
                        $message = "No file was uploaded. Make sure you chose a file to upload.";
                            break;
                    default:
                        $message = "Please contact your server administrator for help.";
                }
                echo 'Sorry, there was a ploblem uploading that photo'.'<br>';
                echo $message;
            }
            
            //Antes de volver a la página que estabamos
            //validamos que se ha subido correctamente una foto
            if(isset($_FILES['photo']) and $_FILES['photo']['error'] == UPLOAD_ERR_OK){
            global $url;
            header("Location: $url");
                
            }
        //fin processForm    
        }
        
        
        function displayForm(){
            
            echo '<h1>Uploading a Photo</h1>';
             
            echo '<p>Please enter your name and choose a photo to upload, then click Send Photo</p>';
            
            echo '<form action="subir_posts.php" method="post" enctype="multipart/form-data">';
                echo'<div style="width: 30em;">';
            echo'<input type="hidden" name="MAX_FILE_SIZE" value="50000" />';
            echo'<label for="visitorName">Your name</label>';
            echo'<input type="text" name="visitorName" id="visitorName" value="" />';
            echo'<label for="photo">Your photo</label>';
            
            echo'<input type="file" name="photo" id="photo" value="" />';
            echo'<input type="file" name="photo2" id="photo2" value="" />';
            echo'<input type="file" name="photo3" id="photo3" value="" />';
            echo'<input type="file" name="photo4" id="photo4" value="" />';
            echo'<input type="file" name="photo5" id="photo5" value="" />';
            
                echo'<div style="clear:both;">';
                    echo'<input type="submit" name="sendPhoto" value="sendPhoto" />';
                echo'</div>';
                
                echo '</div>';
            echo'</form>';
            
            
       //fin displayForm     
        }
   /////////////////////////////////////////////     
        function volverOrigen(){
              
                echo '<script>';
                echo 'window.history.go(-2)';
                echo '</script>';
                exit();       
        //fin volverOrigen            
        }
   ////////////////////////////////////////////////         
            
            function contarDirectorios($usuario){
              
                global $nuevoDirectorio;
          
                $dir = 'photos/'.$usuario;
                $count = 0;
                if(!($handle = opendir($dir))) die("Cannot open $dir.");
             
                while($file = readdir($handle)){
                    if($file != "." && $file != ".."){
                        if(is_dir('photos/'.$usuario.'/'.$file)){
                            $count++;
                        }
                    }
                }
            
            if($count === 0){
                mkdir('photos/'.$usuario.'/1');
                $nuevoDir = 'photos/'.$usuario.'/1';
                
            }else{
                $nuevo= $count+1;
                mkdir('photos/'.$usuario.'/'.$nuevo); 
                $nuevoDir = 'photos/'.$usuario.'/'.$nuevo; 
                
            }
          
            return $nuevoDir;
                
            //fin contarDirectorios    
            }
            
            function renombrarArchivo($nombreViejo, $nuevoDirectorio){
            
                if($nombreViejo != null and $nuevoDirectorio != null){
                $original = basename($nombreViejo);
                //Extraemos el directorio donde esta el archivo 
                $tmp = strstr($nombreViejo, $original, true);
                //Contamos cuantos archivos hay
                $total_imagenes = count(glob($nuevoDirectorio."/{*.jpg}",GLOB_BRACE)); 
                //Renombramos el archivo con el ultimo
                $nombreNuevo = $tmp.$total_imagenes.'.jpg';
                rename($nombreViejo, $nombreNuevo);    
                }
                 $total_imagenes++;
                return $nombreNuevo;
               
       
            }
        ?>
    </body>
</html>
