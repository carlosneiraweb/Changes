<?php
/**
 * @author Carlos Neira Sanchez
 * @mail arj.123@hotmail.es
 * @telefono ""
 * @nameAndExt busquedas.php
 * @fecha 04-oct-2016
 */

/**
 *    De esta clase derivan todos los objetos
 *    Su constructor crea un array con
 *    las propiedades del objeto.
 *    
 */

abstract class DataObj {
    
    protected  $data = array();

    /**
     * Constructor public
     * @param type $data
     */
    public function __construct($data){ 
        //Para comprobar que se instancia el array $data
            //cada vez que se instancia un objeto usuario
             //var_dump($this->data);

        
        foreach ($data as $k => $v){
           // echo 'Clave: '.$k. ': valor: '.$v.'<br>';
            if(array_key_exists($k, $this->data)){ //si $k existe en la tabla data, important!!!           
                $this->data[$k] = $v;
            }
            //xdebug_debug_zval( 'data' );
        }
    }
   
    /**
     * Metodo destructor
     */
    public function __destruct() {
        
    }
   
    /**
     * metodo public
     * Acepta un valor de campo y devuelve su valor.
     * Este metodo puede ser usado qn cualquier 
     * clase que extienda DataObj
     * @param type $field
     * @return type
     */
    public function getValue($field){
        try{
        
        if(array_key_exists($field, $this->data)){
            return $this->data[$field];
        } else{
            echo $field;
            die(" Field not found");
        }
            
        } catch (Exception $ex) {
            echo "el campo pasado no existe en el objeto";
        }
    }
    
    /**
     * metodo public
     * Acepta un valor de campo y setea su valor.
     * Este metodo puede ser usado qn cualquier 
     * clase que extienda DataObj
     * @param $field <br>
     * Campo a cambiar valor. <br>
     * @param $dato <br>
     * Valor a setear en el campo del array <br>
     * 
     */
    public function setValue($field, $dato){
        
        try{
            if(array_key_exists($field, $this->data)){
            $this->data[$field] = $dato;
        } 
        } catch (Exception $ex) {
            echo "el campo pasado no existe en el objeto";

        }
        
    }

    /**
     * Metodo public
     * Metodo usado para devolver el valor de un campo
     * pedido por codigo externo.
     * Evitamos codigo malicioso
     * @param type $field
     * @return type
     */
    public function getValueEncoded($field){
        return htmlspecialchars($this->getValue($field));
    }
    
    
    
    
//fin DataObj    
}
