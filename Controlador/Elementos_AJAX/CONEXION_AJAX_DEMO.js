/*
 author Carlos Neira Sanchez
 mail arj.123@hotmail.es
 telefono ""
 nameAndExt subir_archivos_servidor.php
 fecha 17-abr-2016
*/

/**
 * Este ARCHIVO  crea una conexion AJAX
 * nos devuelve un objeto JSON con los datos pedidos
 * @returns {Conexion}
 */
    var READY_STATE_UNINITIALIZED = 0;
    var READY_STATE_LOADING = 1;
    var READY_STATE_LOADED = 2;
    var READY_STATE_INTERACTIVE = 3;
    var READY_STATE_COMPLETE = 4;  
    var peticionCargar = null;
    var objCarg;
    
function ConexionAJAX(archivo, opcion) {
            
     this.crearConexionAJAX =  function devuelvoConexionAJAX() {
      
            if(window.XMLHttpRequest){
                peticion = new XMLHttpRequest(); 
            }else if (window.ActiveXObject){
                peticion= new ActiveXObject('Microsoft.XMLHTTP'); 
                }
            
            peticionCargar =  peticion;
            peticionCargar.onreadystatechange = cargoDatosConexion;
            peticionCargar.open("POST", "../Controlador/Elementos_AJAX/"+archivo, true);
            peticionCargar.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
            peticionCargar.send(opcion);
            
           
        function cargoDatosConexion() {
                          
            if(this.readyState === READY_STATE_COMPLETE && this.status === 200){
                
                try{
                    objCarg = JSON.parse(peticionCargar.responseText);
                    
                } catch(e){
                    
            }
                
            }  
            
                        
        }
             //alert('dddd');
            return objCarg;
    };
           
   };
    
    
    
    