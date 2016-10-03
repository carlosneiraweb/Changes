<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DataObj
 *
 * @author Carlos Neira Sanchez
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
     * metodo public
     * Acepta un valor de campo y devuelve su valor
     * @param type $field
     * @return type
     */
    public function getValue($field){
        
        if(array_key_exists($field, $this->data)){
            return $this->data[$field];
        } else{
            echo $field;
            die(" Field not found");
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
