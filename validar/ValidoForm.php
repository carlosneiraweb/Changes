<?php

//use Usuarios;

    require_once 'entidades/Usuarios.php';
    require_once 'entidades/DataObj.php';
        
        
    class ValidoForm{
        
    /**
     * Metodo que recive un string y 
     * nos aseguramos que todo los caracteres 
     * son convertidos a caracteres html.
     * Antes quitamos los posibles espacios en blanco
     * 
     */
    final private function htmlCaracteres($string){
        $cadena = trim($string);
        $cadena = htmlspecialchars($cadena, ENT_QUOTES, 'UTF-8');
        return $cadena;
      
       
    }
   
        /*
     * Metodo que valida que el campo 
     * recivido no se encuentrar en el array 
     * de elementos obligatorios.
     * retorna un string con class="error"
     * Se define como final para evitar la sobreescritura,
     * medida de seguridad.
     * 
     */
    function validateField($nombreCampo, $camposPerdidos){
          
            if(in_array($nombreCampo, $camposPerdidos)){
                return 'class="error"';
            }
        }
        
      /**
       * Validamos los datos introducidos para logearse.
       * Recibe objeto de la clase usuario
       * Devuelve true o false.
       * Utilizamos la indicación para decirle al método que va 
       * a recivir un objeto de Usuarios
       * @param type $obj
       */  
    final function validarEntrada(DataObj $obj){
          
          $nick = $this->htmlCaracteres($obj->getValue('nick'));
          $pass = $this->htmlCaracteres($obj->getValue('password'));
          echo 'en validarEntrada, nick: '.$nick.' pass '.$pass.'<br>';
        if($this->campoVacio($nick) and $this->campoVacio($pass) and $this->validarPassword($pass)){        
            echo 'en validar es true';    
            return true;
            }else{
            echo 'en validar es falso';
                return false;
            }
        }
      
      /**
       * Metodo que valida el registro de un usuario
       */
    final function validoRegistro($objUsu){
          
          $nombre = $this->$objUsu->nombre;
          $telefono = $this->$objUsu->telefono;
          $email = $this->$objUsu->email;
          $nick = $this->$objUsu->nick;
          $pass_1 = $this->$objUsu->password_1;
          $pass_2 = $this->$objUsu->password_2;
          $valor = $this->$objUsu->string;
        
          $miArray = array($nombre, $telefono, $email, $nick, $pass_1, $pass_2, $valor);

        $test = true;
        
            foreach ($miArray as $valor){
                if(!$this->campoVacio($valor)){
                 $test = false;
                 break;
                }
            }
           
        if($test){ 
            if(!$this->validarEmail($email)){
                $test = false;
            }elseif(!$this->validaTelefono($telefono)){
                $test = false;  
            }elseif(!$this->validarPassword($pass_1)){
                $test = false;
            }elseif(!$this->validarPassword($pass_2)){
                $test = false;
            }elseif(!$this->validarIgualdadPasswords($pass_1, $pass_2)){
                $test = false;
            }elseif(!$this->comprobarCheck('condiciones')){
                $test = false;
            }
            

        }
            return $test;
            
      //fin validoRegistro    
      }
      
      /**
       * Metodo que recive un password
       * para ser validado
       */
    final private function validarPassword($cadena){
          
         
         $patron = "/^[0-9a-zA-Z]{6,12}$/";
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
    final private function validarIgualdadPasswords($pass1, $pass2){
          
        $result = strcmp ($pass1 ,$pass2 ); 
        if($result === 0){
            return true;
        }else{
            return false;
        }
          
          
      //fin validarIgualdadPasswords    
      }
      
      
      /**
       * Metodo valida un teléfono
       * Debe empezar por 9,8,6,7, y tener 9 caracteres
       * Ademas los caracteres tienen que ser números
       */
      
    final private  function validaTelefono($tel){
          
          $expresion = '/^[9|8|6|7][0-9]{8}$/';
         if($result = preg_match($expresion, $tel) and ctype_digit($tel)){
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
    final private function campoVacio($elemento){
        
            if($elemento != ""){
               return true;
            } else{
                return false;
            }
        }
      /**
       * Metodo que recive un email
       * para validar
       * @param type $elemento
       */
    final private function validarEmail($elemento){
          
        $expresion = "/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,4})$/";
        $result = preg_match($expresion, $elemento);
            return $result;

//fin validarEmail     
      }

     /**
      * Metodo que recive un parametro $_POST desde el script.
      * si es distinto de null lo devuelve para que se 
      * vuelva a escribir en el formulario
      * @param type $nombreCampo
      */   
    function setValue($nombreCampo){
            if(isset($_POST[$nombreCampo])){
                return $_POST[$nombreCampo];
            }
        }
    
    /**
     * Metodo para dejar checkeado los campos 
     * que el usuario a checked.
     * Recive el nombre del campo y su valor
     * @param type $nombreCampo
     * @param type $campoValor
     */
    function setChecked($nombreCampo, $campoValor){
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
    function setSelected($nombreCampo, $valorCampo){
            if(isset($_POST[$nombreCampo]) and $_POST[$nombreCampo] == $valorCampo){
                return 'selected="selected"';
            }
        }
        
    /**
     * Metodo para asegurarnos que el usuario ha aceptado
     * las condiciones. 
     * Se define final para evitar la sobreescritura. 
     */
     function comprobarCheck($nombreCampo){
        //echo 'el valor de condiciones vale: '.$_POST[$nombreCampo].'<br>';
        if(!isset($_POST[$nombreCampo]) or $_POST[$nombreCampo] != '1'){
            return false;
        }else{
            return true;
        }  
  
    }    
  
    
   //fin clase
    }
       
   
    

