/*
 author Carlos Neira Sanchez
 mail arj.123@hotmail.es
 telefono ""
 nameAndExt CONEXION_AJAX.js
 fecha 17-abr-2016
*/

/**
 * Esta clase crea una conexion AJAX
 * @returns {Conexion}
 */

function Conexion() {
    
   
    this.READY_STATE_UNINITIALIZED = 0;
    this.READY_STATE_LOADING = 1;
    this.READY_STATE_LOADED = 2;
    this.READY_STATE_INTERACTIVE = 3;
    this.READY_STATE_COMPLETE = 4;
    
    this.conection = function devuelvoConexionAJAX() {
     
            if(window.XMLHttpRequest){
                peticion = new XMLHttpRequest(); 
            }else if (window.ActiveXObject){
                    peticion= new ActiveXObject('Microsoft.XMLHTTP'); 
                }
          
            return peticion;
                 
    
    };


}



