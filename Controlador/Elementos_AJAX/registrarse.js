
/**
 * @author Carlos Neira Sanchez
 * @mail arj.123@hotmail.es
 * @telefono ""
 * @nameAndExt busquedas.php
 * @fecha 04-oct-2016
 */
    var  petGeReg, objGeReg, petProReg, objProReg, PGR, PPR;

                //Creamos una instancia de la clase CONEXION_AJAX
                //Nos devuelve una conexion AJAX y propiedades 
                    var ConRegistrarse  = new Conexion();
                    
window.onload=function(){
    cargarPeticionRegistrarse('PGR', 'opcion=PG');
    cargarPeticionRegistrarse('PPR', 'opcion=PP');
};                
                    
                    
                    
function cargarPeticionRegistrarse(tipo, parametros){
//alert('Estamos en cargarPeticion y tipo vale: ' +tipo+ ' parametros vale: ' +parametros);
    //para comprobar el tipo de peticion
    switch(tipo){
        
        case('PGR'):
           petGeReg = ConRegistrarse.conection();
           petGeReg.onreadystatechange = procesaRespuestaRegistrarse;
           petGeReg.open('POST', "../Controlador/Elementos_AJAX/cargarElementos.php?", true);
           petGeReg.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
           petGeReg.send(parametros);
                break;   
        case('PPR'):
           petProReg = ConRegistrarse.conection();
           petProReg.onreadystatechange = procesaRespuestaRegistrarse;
           petProReg.open('POST', "../Controlador/Elementos_AJAX/cargarElementos.php?", true);
           petProReg.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
           petProReg.send(parametros);
                break;   
        
    //fin switch
    }
    
    function procesaRespuestaRegistrarse(){
      
       if(this.readyState === ConRegistrarse.READY_STATE_COMPLETE && this.status === 200){
            try{
                if(tipo === 'PGR'){
                    objGeReg = JSON.parse(petGeReg.responseText);
                     //Eliminamos el objeto conexion
                    delete ConRegistrarse;
                } else if(tipo === 'PPR'){
                    objProReg = JSON.parse(petProReg.responseText);
                     //Eliminamos el objeto conexion
                    delete ConRegistrarse;
                } 
                
            } catch(e){
                switch(tipo){        

                    default:
                       // location.href= 'mostrar_error.php';
                }
            //fin catch
            }
            
            switch (tipo){
                
                case 'PGR':
                    cargarGeneroRegistrarse(objGeReg);
                        break;
                 case 'PPR':
                    cargarProvinciasRegistrarse(objProReg);
                        break;
                        
            //fin switch
            }
        //fin if
        }
    //fin procesaRespuesta    
    }   
//fin cargarPeticion    
}
                    
                 
/**
* Este metodo carga los combos de
* genero en el registro
 * @returns {undefined} */
function cargarGeneroRegistrarse(objGeneroRegistrarse){
    
    for(var i = 0; i < objGeneroRegistrarse.length; i++){
        var objTmpGeneroRegistrarse = objGeneroRegistrarse[i];
            $('#genero').append($('<option>',{
            text : objTmpGeneroRegistrarse.genero
        }));
    
    }
    
    //fin cargarGeneroRegistrarse
    }
    
/**
* Este metodo carga las provincias
* en el registro
 */    
function cargarProvinciasRegistrarse(objProvinciasRegistrarse){
    
     //alert(objProvinciasRegistrarse);
    for(var i = 0; i < objProvinciasRegistrarse.length; i++){
        var objTmpProvinciasRegistrarse = objProvinciasRegistrarse[i];
            $('#provincia').append($('<option>',{
            text : objTmpProvinciasRegistrarse.nombre
        }));
    
    }
    
    //fin cargarGeneroRegistrarse
    }