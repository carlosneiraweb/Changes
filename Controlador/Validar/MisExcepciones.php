<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MisExcepciones
 *
 * @author carlos
 */
class MisExcepciones extends Exception{

    public function __construct($message, $code, Throwable $previous = null) {
         parent::__construct($message, $code, $previous);
    }

    
 
    
    
 /**
    * Metodo que elimina variables de sesion
    * cuando un usuario ha acabado de subir 
    * un post
    */

public function eliminarVariablesSesionPostAcabado(){
  
    if(isset($_SESSION['imgTMP'])){
            unset($_SESSION['imgTMP']);
        }
        
    if(isset($_SESSION['atras'])){
            unset($_SESSION['atras']);
        }
    
    if(isset($_SESSION['contador'])){
            unset($_SESSION['contador']);
        }   
      
    if(isset($_SESSION['error'])){
            unset($_SESSION['error']);
        }       
           
    
             
    //fin eliminarVariablesSesionPostAcabado()         
    }


    /**
     * Este metodo manda a EliminarPost de la clase Post,
     * cuando un usuario quiere subir un post 
     * y a mitad de proceso se sale y no
     * acaba publicandolo
     */

 public function eliminarPostAlPublicar(){
    
   //SubDirectorio que se creo para ir subiendo los post
     //../photos/carlos/10
   $usuario =  $_SESSION['userTMP']->getValue('nick');
   
   $tmp=  $_SESSION['nuevoSubdirectorio'];//de fotos
   $errores = array("usuario" => $usuario,
                    "postId"=> "",
                    "subDirectorio" => "",
                    "urlImagenes" => "",
                    "paQueridas" => "",
                    "paOfrecidas" => "");
   //$tmpSubdirectorio = preg_split("~[\\\/]~", $tmp);
   //El id del post a eliminar
    $idPost = $_SESSION['lastId'][0]; 
    
        if($idPost !== null){

            try{
                $testPostId = Post::eliminarPostId($idPost);
                    //echo "eliminar post ".$testPostId.'<br>';
                if(!$testPostId){
                    $errores[1] = $idPost;
                }
            } catch (Exception $ex){}


            try{
                $testDir = Directorios::eliminarDirectorioRegistro($tmp);
                
                if(!$testDir){
                    $errores[2]= $tmp;
                }
            }catch (Exception $ex){}

            try {
                $testImgPost = Post::eliminarImagenesPost($idPost);
             
                if(!$testImgPost){
                    $errores[3] = $idPost;
                }
            } catch (Exception $exc) {}

            try {
                $testElimQueri = Post::eliminarPalabrasQueridas($idPost);
               
                if(!$testElimQueri){
                    $errores[4] = "No se han podido eliminar las palabras buscadas.";
                }

            } catch (Exception $exc) {}

            try {
                 $testElimOfre = Post::eliminarPalabrasOfrecidas($idPost);
                  
                if(!$testElimOfre){
                    $errores[5] =  "No se han podido eliminar las palabras ofrecidas.";
                }   

            } catch (Exception $exc) {}

       }
      
   //Escribimos el error en el archivo
   Directorios::errorEliminarPost($errores);
}

    
 /**
     * Metodo que en caso de error al
     * trabajar con archivos cuando se sube un Post nos redirige 
     * a la pagina correspondiente y
     * elimina todas las variables de sesion
     */
   
public  function redirigirPorErrorTrabajosEnArchivosSubirPost(){
      
        
        $this->eliminarPostAlPublicar();
        $this->eliminarVariablesSesionPostAcabado();
        $_SESSION["paginaError"] = "index.php";
        $_SESSION['error'] = ERROR_INSERTAR_ARTICULO;
        $_SESSION['errorArchivos'] = "existo";
        mostrarError();
        exit();
    
     
//redirigirPorErrorArchivos    
}


/**
 * Metodo que elimina los directorios creados
 * al registrarse un usuario
 */

public function eliminarDirectoriosAlRegistrarseUsuario() {
 
    try {
        Directorios::eliminarDirectorioRegistro("../photos/".$_SESSION['usuario']['nick']);
    } catch (Exception $exc) {echo $exc->getCode();}

    try{
        Directorios::eliminarDirectorioRegistro("../datos_usuario/".$_SESSION['usuario']['nick']);
    } catch (Exception $ex) {echo $exc->getCode();}

    try{
         Directorios::eliminarDirectorioRegistro("../Videos/".$_SESSION['usuario']['nick']); 
    } catch (Exception $ex){echo $exc->getCode();}

//fin eliminarDirectoriosAlRegistrarseUsuario    
}

/**
 * Metodo que es llamado cuando se produce un error
 * al trabajar con archivos cuando un usuario se esta registrando
 */

public function redirigirPorErrorTrabajosEnArchivosAlRegistrarse(){
    //echo 'mis excepciones <br>';
    $this->eliminarDirectoriosAlRegistrarseUsuario();
    $_SESSION["paginaError"] = "registrarse.php";
    $_SESSION['error'] = ERROR_INGRESAR_USUARIO;
    $_SESSION['errorArchivos'] = "existo";
        mostrarError();
        exit();
    
    
    
    
//fin redirigirPorErrorTrabajosEnArchivosAlRegistrarse    
}
   
    
//fin mis excepciones    
}
