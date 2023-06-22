<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Conne.php');

 if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
   

/**
 * @Description
 * Esta clase es extendida por <br/>
 * MisExcepcionesUsuario <br/>
 * MisExcepcionesPost <br/>
 * Recoje los datos de los errores<br/>
 * y los inserta en la bbdd
 * 
 */


class MetodosInfoExcepciones {
 
    
    protected $misExcepciones = Array();
    
    
    
    public function __construct($mensaje,$codigo,&$ex) {
        $this->misExcepciones[0] = $mensaje;
        $this->misExcepciones[1] = $codigo;
        $this->misExcepciones[2] = $ex->getMessage();
        $this->misExcepciones[3] = $ex->getCode();
        $this->misExcepciones[4] = $ex->getFile();
        $this->misExcepciones[5] = $ex->getLine();
        $this->misExcepciones[6] = $ex->getTraceAsString();
        
       if(isset($ex)){unset($ex);}
        
    }
    
    function __destruct() {
        
    }

    private function mostrarError(){
        header('Location: mostrar_error.php');
            
    }
    
    private function redirirgirFalloNoCritico(){
        $url = $_SESSION["paginaError"];
         header("Location: $url");
    }
    


/**
 * Metodo que convierte a string <br/>
 * los datos que ha ido introduciento el usuario <br/>
 * durante el proceso de registro, actualizacion <br/>
 * @param String $opc Opcion para recuperar los <br/>
 * datos de la variable de sesion para insertar en la bbdd <br/>
 * @return  string
 */
private function convertirStringDatosSesion($opc){
    
    $datosSesion;
   
    if(isset($_SESSION['emailNoActivado'])){
        
        $datosSesion= $opc.PHP_EOL;
        $datosSesion .= "No se pudo desbloquear usuario en validarEmail.php";
        echo PHP_EOL;
        $datosSesion .= "El nick del usuario es ".$_SESSION['emailNoActivado'];
        
            unset($_SESSION['emailNoActivado']);
        
    }else if(isset($_SESSION['actualizo'])){    
        
                
                $datosSesion = $opc.PHP_EOL;
                $datosSesion .= "Datos antes de la actualizacion ".PHP_EOL;
               // var_dump($_SESSION['actualizo']);
            foreach ( $_SESSION['actualizo'] as $k => $v){
            
                $datosSesion .= $k. " => ".$v;
                $datosSesion .= PHP_EOL;
            }
            $datosSesion.= PHP_EOL;
            $datosSesion .= "Datos introducidos por el usuario al actualizar.".PHP_EOL;
            
            foreach ($_SESSION['usuario'] as $k => $v){
                
                $datosSesion .=  $k. " => ".$v;
                $datosSesion .= PHP_EOL;
            }
            
    }else if(isset($_SESSION['usuario'])){      
            
        $datosSesion = $opc;
        $datosSesion .= "Datos introducidos por el usuario al registrar.".PHP_EOL;
            
            foreach ($_SESSION['usuario'] as $k => $v){
                
                $datosSesion .=  $k. " => ".$v;
                $datosSesion .= PHP_EOL;
            }
            
    } else if(isset($_SESSION['userTMP'])){
            
            $datosSesion = "El usuario ".$_SESSION['userTMP']->getValue('nick');
            $datosSesion .= PHP_EOL;
            $datosSesion .=$opc;
            foreach ($_SESSION['post'] as $k => $v){
                
                if(is_array($v)){
                        
                        foreach ($v as $x => $y){
                            $datosSesion .= $x. "=>" .$y;
                            $datosSesion .= PHP_EOL;
                        }
                        continue;
                    }
                    
                $datosSesion .=  $k. " => ".$v;
                    
            }
            
    } else {
         
        $datosSesion = "No dispongo datos";
    }      
    
    
    return $datosSesion;
}    
    
/**
 * Metodo que inserta los errores <br/>
 * en la tabla correspondiente de <br/>
 * la bbdd. <br/>
 * @param  $datosUsuario <br/> 
 * Type String <br/>
 *  Contiene los datos que ha introducido el usuario.<br/>
 * @param String opc <br/>
 * Tipo de error
 * @param type $name Description
 */


private function insertarErroresBBDD( $opc,$datosSesion){
    
     $con = Conne::connect();
     
     
     try {
         
        $sqlInsError = " Insert into ".TBL_INSERTAR_ERROR.
                "(motivo, codigo,usuario,fechaError,mensaje,mensajePHP,codigoPHP,fichero,linea,trace,DatosIntroducidos)".
                " VALUES (:motivo,:codigo, :usuario, :fechaError, :mensaje, :mensajePHP,:codigoPHP, :fichero, :linea,:trace, :DatosIntroducidos);";
        $date = date("Y-m-d H:i:s");
        
        $stError = $con->prepare($sqlInsError);
        $stError->bindValue(":motivo", $opc, PDO::PARAM_STR);
        $stError->bindValue(":codigo", $this->misExcepciones[1], PDO::PARAM_STR);//
        if(isset($_SESSION["userTMP"])){
            //Esta actualizando
            $stError->bindValue(":usuario", $_SESSION["userTMP"]->getValue('nick'), PDO::PARAM_STR); 
        }else{
            //O si el usuario se esta registrado
               if(isset($_SESSION['usuario'])){
                    $stError->bindValue(":usuario", $_SESSION['usuario']['nick'], PDO::PARAM_STR); 
               }else{
                    $stError->bindValue(":usuario", "desconocido", PDO::PARAM_STR);   
               }
        }
        
        $stError->bindValue(":fechaError", $date, PDO::PARAM_STR);
        $stError->bindValue(":mensaje", $this->misExcepciones[0], PDO::PARAM_STR);//
        $stError->bindValue(":mensajePHP", $this->misExcepciones[2], PDO::PARAM_STR);
        $stError->bindValue(":codigoPHP", $this->misExcepciones[3], PDO::PARAM_STR);
        $stError->bindValue(":fichero", $this->misExcepciones[4], PDO::PARAM_STR);
        $stError->bindValue(":linea", $this->misExcepciones[5], PDO::PARAM_STR);
        $stError->bindValue(":trace", $this->misExcepciones[6], PDO::PARAM_STR);
        $stError->bindValue(":DatosIntroducidos", $datosSesion, PDO::PARAM_STR);
        
        $stError->execute();
        
        Conne::disconnect($con);
        
     } catch (Exception $exc) {
         Conne::disconnect($con);
         
         echo "codigo".$exc->getCode();
         echo PHP_EOL;
         echo "Archivo".$exc->getFile();
         echo PHP_EOL;
         echo "mensage".$exc->getMessage();
         echo PHP_EOL;
         echo "linea ".$exc->getLine();
         
     }finally{
         if(isset($_SESSION["datos"])){unset($_SESSION["datos"]);}
         if(isset($_SESSION["usuRegistro"])){unset($_SESSION["usuRegistro"]);}
     }
     
    
    
}


/**
 * Este metodo se encarga de tratar <br/>
 * los datos cuando hay un error. <br/>
 * LLama a varios metodos de la clase, <br/>
 * errorMessage, covertirStringDatosSesion, <br/>
 * insertarErroresBBDD. <br/>
 * @param String $comentario <br/>
 * Opcion para tratar el error <br/>
 * @param Boolean $grado  <br/>
 * Grado del error para aplicar salida
 * 
 */
protected function tratarDatosErrores($comentario,$grado){
    
    $datosSesion = $this->convertirStringDatosSesion($comentario);
        //Los insertammos en la bbdd
    
    $this->insertarErroresBBDD( $comentario,$datosSesion);
    
    if($grado){
        
        $this->mostrarError();
    
    }elseif (!$grado) {
        // No redirigimos ya lo hemos hecho antes
    }else{
        
        $this->redirirgirFalloNoCritico();
    }
    
    //fin tratarDatosErrores()
     
     
}

  
//fin de     MetodosInfoExcepciones
}
