<?php
/**
 * @author Carlos Neira Sanchez
 * @mail arj.123@hotmail.es
 * @telefono ""
 * @nameAndExt validoForm.php
 * @fecha 04-oct-2016
 */



/**
 * Clase encargada de validar los datos introducidos
 * por el usuario con PHP.
 * En esta clase la mayoria de metodos son final para evitar que un 
 * programador pueda por error sobreescribir el metodo
 * y static por que nos evitamos tener que instanciar un objeto de la clase;
 */
    require_once ($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/Usuarios.php');
    require_once ($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/DataObj.php');
    require_once ($_SERVER['DOCUMENT_ROOT'].'/Changes/Controlador/Validar/Interf_comprobar.php');   
       
    class ValidoForm implements Interf_comprobar{
        
    /**
     * Metodo que recive un string y 
     * nos aseguramos que todo los caracteres 
     * son convertidos a caracteres html.
     * Antes quitamos los posibles espacios en blanco
     * Evita ataques Java al no ejecutarse como codigo
     */
    final static function htmlCaracteres($string){
        $cadena = trim($string);
        $cadena = htmlspecialchars($cadena, ENT_QUOTES, 'UTF-8');
   
        return $cadena;
          
    }
   
    /*
     * Metodo que valida que el campo 
     * recivido no se encuentrar en el array 
     * de elementos no rellenados.
     * retorna un string con class="error"
     * Se define como final para evitar la sobreescritura,
     * medida de seguridad.
     * @param type array con los campos no rellenados
     * @param type string con el nombre del campo a comprobar
     */
    final static function validateField($nombreCampo, $camposPerdidos){
          
            if(in_array($nombreCampo, $camposPerdidos)){
                return 'class="errorPHP"';
            }
        }
        
      /**
       * Validamos los datos introducidos para logearse.
       * Recibe objeto de la clase usuario
       * Devuelve true o false.
       * No lo usamos pues queremos aplicar la clase errosPHP
       * al campo concreto.
       * Utilizamos la indicación para decirle al método que va 
       * a recivir un objeto de DataObj
       * @param type DataObj
       */  
    final function validarEntrada(DataObj $obj){
          
          $nick = $this->htmlCaracteres($obj->getValue('nick'));
          $pass = $this->htmlCaracteres($obj->getValue('password')); 
        if($this->campoVacio($nick) and $this->campoVacio($pass) and $this->validarPassword($pass)){        
                return true;
            }else{ 
                return false;
            }
        }
      
      
      /**
       * Metodo que recive un password
       * para ser validado.
       * Se require entre 6 y 12 digitos.
       * Solo acepta letras y numeros 
       */
    final static function validarPassword($cadena){
        //0-9a-zA-ZñÑ
                        
         $patron = "/^[_a-zA-ZñÑ0-9-]{6,12}$/";
         $result = preg_match($patron,$cadena);
             return $result;
        
      //fin validarPassword 
      }
      
      /**
       * Metodo que valida que los passwords
       * son iguales.
       * Recive dos strings.
       * SE HACE DISTINCIÓN ENTRE MAYUSCULAS Y MINUSCULAS
       */
    final static function validarIgualdadPasswords($pass1, $pass2){
       
        $result = strcmp ($pass1 ,$pass2 ); 
        if($result === 0){
            return false;
        }else{
            return true;
        }
          
          
      //fin validarIgualdadPasswords    
      }
      
      
      /**
       * Metodo valida un teléfono
       * Debe empezar por 9,6,7, y tener 9 caracteres
       * Ademas los caracteres tienen que ser números
       */
      
    final static  function validaTelefono($tel){
          
          $expresion = '/^[9|6|7][0-9]{8}$/';
         if(preg_match($expresion, $tel) and ctype_digit($tel)){
            return true;
         }else{
            return false;
         }
       //fin validaTelefono   
      }
      
      
      /**
       * Esta metodo valida que el campo pasado no este vacío
       * @param type $elemento
       * @return boolean
       */  
    final static function campoVacio($elemento){
        
            if(empty($elemento)){
                return false;
            }else{
                return true;
            }
        }
        
        
        
      /**
       * Metodo que recive un email
       * para validar
       * @param type $elemento
       */
    final static function validarEmail($elemento){
          
        $expresion = "/^[_a-zA-ZñÑ0-9-]+(.[_a-zÑñ0-9-]+)*@[a-zñÑ0-9-]+(.[a-zÑñ0-9-]+)*(.[a-z]{2,4})$/";
        
        if(filter_var($elemento, FILTER_VALIDATE_EMAIL)){
            $result = preg_match($expresion, $elemento);
        }else{
            $result = false;
        }
        
        
            return $result;

//fin validarEmail     
      }

    /**
     * Metodo que valida el codigo postal
     * Tiene que tener 5 caracteres y poder ser 
     * casteado a números
     */
      
      final static function validarCodPostal($elemento){
          $test = false;
          if(is_numeric($elemento) && strlen($elemento) == 5){
              $test = true;
          }else{
              $test = false;
          }
          return $test;
          
      }
    /**
     * Metodo para dejar checkeado los campos 
     * que el usuario a checked.
     * Recive el nombre del campo y su valor
     * @param type $nombreCampo
     * @param type $campoValor
     */
    final static function setChecked($nombreCampo, $campoValor){
            if(isset($_POST[$nombreCampo]) and $_POST[$nombreCampo] == $campoValor){
                //echo 'Valor de $_post= '.$_POST['gender'];
                return 'checked="checked"';
            }
        }
         
    /**
     * Metodo que deja seleccionado el campo elegido en un select
     * Recive como parametro el nombre del campo y su valor
     * @param type $nombreCampo
     * @param type $valorCampo
     */    
    final static function setSelected($nombreCampo, $valorCampo){
            if(isset($_POST[$nombreCampo]) and $_POST[$nombreCampo] == $valorCampo){
                return 'selected="selected"';
            }
        }
        
    /**
     * Metodo para asegurarnos que el usuario ha aceptado
     * las condiciones. 
     * Se define final para evitar la sobreescritura. 
     */
    final static function comprobarCheck($nombreCampo){
        //echo 'el valor de condiciones vale: '.$_POST[$nombreCampo].'<br>';
        if(!isset($_POST[$nombreCampo]) or $_POST[$nombreCampo] != '1'){
            return false;
        }else{
            return true;
        }  
  
    }

   
    
    
 
    
    
    
    
    
    
    //fin clase
    }
       
   
    

