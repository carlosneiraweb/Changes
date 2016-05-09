<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Validate
 *
 * @author Carlos Neira Sanchez
 */
 class ValidoForm implements Interf_comprobar{
    private $requeridos = array();
    private $perdidos = array();
   
    
    public function __construct($req, $miss) {
       
        $tmpReq ="";
        $tmpPer ="";
        foreach ($req as $tmpReq){
            array_push($this->requeridos, $tmpReq);
        }
        
        foreach ($miss as $tmpPer){
            array_push($this->perdidos, $tmpPer);
        }
        
    }
    function getRequeridos() {
        return $this->requeridos;
    }

    function getPerdidos() {
        return $this->perdidos;
    }

    function setRequeridos($requeridos) {
        $this->requeridos = $requeridos;
    }

    function setPerdidos($perdidos) {
        $this->perdidos = $perdidos;
    }
    
     
    /**
     * Metodo que recive un string y 
     * nos aseguramos que todo los caracteres 
     * son convertidos a caracteres html.
     * Antes quitamos los posibles espacios en blanco
     * 
     */
    public function htmlCaracteres($string){
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
      final  public function validateField($nombreCampo, $camposPerdidos){
          
            if(in_array($nombreCampo, $camposPerdidos)){
                return 'class="error"';
            }
        }
        
      /**
       * Validamos los datos introducidos para logearse.
       * Recibe objeto de la clase usuario
       * Devuelve true o false.
       * @param type $obj
       */  
      public function validarEntrada($obj){
          $nick = $this->htmlCaracteres($obj->nick);
          $pass = $this->htmlCaracteres($obj->password_1);
        if($this->campoVacio($nick) and $this->campoVacio($pass) and $this->validarPassword($pass)){   
                    return true;
            }else{
                    return false;
            }
        }
      
      /**
       * Metodo que valida el registro de un usuario
       */
      public function validoRegistro($objUsu){
          
          $nombre = $this->htmlCaracteres($objUsu->nombre);
          $telefono = $this->htmlCaracteres($objUsu->telefono);
          $email = $this->htmlCaracteres($objUsu->email);
          $nick = $this->htmlCaracteres($objUsu->nick);
          $pass_1 = $this->htmlCaracteres($objUsu->password_1);
          $pass_2 = $this->htmlCaracteres($objUsu->password_2);
          $valor = $this->htmlCaracteres($objUsu->string);
        
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
      private function validarPassword($cadena){
          
         
         $patron = "/^[0-9a-zA-Z]{6,10}$/";
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
      function validarIgualdadPasswords($pass1, $pass2){
          
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
      
      function validaTelefono($tel){
          
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
      public function campoVacio($elemento){
        
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
    final public function validarEmail($elemento){
          
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
    public function setValue($nombreCampo){
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
    final public function comprobarCheck($nombreCampo){
        //echo 'el valor de condiciones vale: '.$_POST[$nombreCampo].'<br>';
        if(!isset($_POST[$nombreCampo]) or $_POST[$nombreCampo] != '1'){
            return false;
        }else{
            return true;
        }  
  
    }    
  
    /**
     * Metodo que elimina el objeto pasado
     * @param type $obj
     */
    final public function eliminarObjeto($obj) {
        unset($obj);
    }    
    

    /**
     * 
     * Metodo que para mostrar un objeto de la clase
     * @return string
     */
    public function __toString()
    {
        $tmpReq ="";
        $tmpMi ="";
        
        foreach ($this->requeridos as $ele){
            $tmpReq .= "Elemento requerido: ".$ele.'<br>';
        }
        
        foreach($this->perdidos as $miss){
            $tmpMi .= "Elemento perdido: ".$miss.'<br>';
        }
        $mostrar = $tmpReq.'<br>'.$tmpMi;
        return $mostrar;
    }
       
   
    
}
