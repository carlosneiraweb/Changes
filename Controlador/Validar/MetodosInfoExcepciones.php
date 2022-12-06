<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Conne.php');

 if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
   

/**
 * 
 * ControlErroresSistemaEnArchivosPost y
 * ControlErroresSistemaEnArchivosUsuario
 * para trabajar con los metodos 
 * de la clase Exception
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
        //$url = $_SESSION["paginaError"];
         header("Location: index.php");
    }
    


/**
 * Metodo que convierte a string
 * los datos que ha ido introduciento el usuario
 * durante el proceso de registro, actualizacion
 * @param String $opc Opcion para recuperar los
 * datos de la variable de sesion para insertar en la bbdd
 * @return  string
 */
private function convertirStringDatosSesion($opc){
    
    $datosSesion;
    
    
    if(isset($_SESSION['actualizo'])){    
        
                
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
            
    } else{
            
            $datosSesion = "El usuario ".$_SESSION['userTMP']->getValue('nick');
            $datosSesion .= PHP_EOL;
            $datosSesion .=$opc;
            foreach ($_SESSION['post'] as $k => $v){
                
                if(is_array($v)){
                        
                        foreach ($v as $x => $y){
                            $datosSesion .= $x. "=>" .$y;
                            $datosSesion .= PHP_EOL;
                        }
                    }
                    continue;
                $datosSesion .=  $k. " => ".$v;
                    
            }
            
    }       
    
    if(isset($_SESSION['post'])){
            unset($_SESSION['post']);
    }
    
    return $datosSesion;
}    
    
    /**
 * Metodo que inserta los errores 
 * en la tabla correspondiente de 
 * la bbdd. 
 * @param  string,  errorInterno, Muestra los errorres de la clase padre con los metodos get <br />
 * @param  String,  datosUsuario, Muestra los datos que ha introducido el usuario
 * @param String $opc Tipo de error
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
        $stError->bindValue(":codigo", $this->misExcepciones[1], PDO::PARAM_INT);//
        if(isset($_SESSION["userTMP"])){
            //Esta actualizando
            $stError->bindValue(":usuario", $_SESSION["userTMP"]->getValue('nick'), PDO::PARAM_STR); 
        }else{
            //O si el usuario se esta registrado
               $stError->bindValue(":usuario", $_SESSION['usuario']['nick'], PDO::PARAM_STR); 
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
 * Este metodo se encarga de tratar 
 * los datos cuando hay un error.
 * LLama a varios metodos de la clase,
 * errorMessage, covertirStringDatosSesion,
 * insertarErroresBBDD.
 * @param type $opc Description
 *  String
 * $opc
 * Opcion para tratar el errror
 */
protected function tratarDatosErrores($opc,$grado){
    
   // var_dump($excep);
    $datosSesion = $this->convertirStringDatosSesion($opc);
        //Los insertammos en la bbdd
    
    $this->insertarErroresBBDD( $opc,$datosSesion);
    
    if($grado){
        
        $this->mostrarError();
    }else{
        $this->redirirgirFalloNoCritico();
    }
    
    //fin tratarDatosErrores()
     
     
}

  
//fin de     MetodosInfoExcepciones
}
