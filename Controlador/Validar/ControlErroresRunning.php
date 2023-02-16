<?php


/**
 * Description of ControlErroresRunning <br/>
 * Clase que extiende MetodoInfoExcepciones <br/>
 * Para insertar en la bbdd los errores <br/>
 * que se producen en la ejecucion.
 * @author carlos
 */
class ControlErroresRunning extends MetodosInfoExcepciones{
   
    
    
    
public function ErroresRunning($opc,$stringError){
    
    
    
    switch($opc){
    
        case "bloquear":
            
            $this->insertarErroresBBDD($opc, $stringError);
            
            die();
            break;
        
        default:
            //nothing
            
    
    
    }


//fin ErroresRunning
}    
    
    //fin ControlErroresRunning
}
