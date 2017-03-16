
/**
 * @author Carlos Neira Sanchez
 * @mail arj.123@hotmail.es
 * @telefono ""
 * @nameAndExt busquedas.php
 * @fecha 04-oct-2016
 */


// Variables para las peticiones JSON
var objPro, petPro, objGen, petGen, objSeccion, petSeccion, objTiempoCambio, petTiempoCambio;
    //Creamos una instancia de la clase CONEXION_AJAX
    //Nos devuelve una conexion AJAX y propiedades 
        var ConCargarElementos  = new Conexion();
        

function cargar(elementos, opcionCargaElementos){
   
    // Variables elementos HTML
    provincias = elementos[0];
    porProvincia = elementos[1];
    porTiempoCambio= elementos[2];
    genero = elementos[3];
    seccion = elementos[4];
    tiempoCambio = elementos[5];
    
        switch(opcionCargaElementos){
            case('PP'):
                cargarPeticionElementos("PP", "opcion=PP"); //Peticion provincias para busquedas y hacer login
                    break;
            case('PG'):
                cargarPeticionElementos("PG", "opcion=PG"); //Peticion Generos
                    break;
            case('PS'):
                cargarPeticionElementos("PS", "opcion=PS"); //Peticion Seccion
                    break;
            case('PT'):
                cargarPeticionElementos("PT", "opcion=PT"); //Peticion tiempoCambio 
                    break;
        }
            
             
    
    
    };
    
    
function cargarPeticionElementos(tipo, parametros){
//alert('Estamos en cargarPeticion y tipo vale: ' +tipo+ ' parametros vale: ' +parametros);
    //para comprobar el tipo de peticion
    switch(tipo){
        
        case('PP'):
           petPro = ConCargarElementos.conection();
           petPro.onreadystatechange = procesaRespuestaPeticionElementos;
           petPro.open('POST', "../Controlador/Elementos_AJAX/cargarElementos.php?", true);
           petPro.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
           petPro.send(parametros);
                  
        case('PG'):
           petGen = ConCargarElementos.conection();
           petGen.onreadystatechange = procesaRespuestaPeticionElementos;
           petGen.open('POST', "../Controlador/Elementos_AJAX/cargarElementos.php?", true);
           petGen.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
           petGen.send(parametros);
                break;
        case('PS'):
           petSeccion = ConCargarElementos.conection();
           petSeccion.onreadystatechange = procesaRespuestaPeticionElementos;
           petSeccion.open('POST', "../Controlador/Elementos_AJAX/cargarElementos.php?", true);
           petSeccion.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
           petSeccion.send(parametros);
                break;        
        case('PT'):
           petTiempoCambio = ConCargarElementos.conection();
           petTiempoCambio.onreadystatechange = procesaRespuestaPeticionElementos;
           petTiempoCambio.open('POST', "../Controlador/Elementos_AJAX/cargarElementos.php?", true);
           petTiempoCambio.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
           petTiempoCambio.send(parametros);
                break;
       
    //fin switch
    }
    
    function procesaRespuestaPeticionElementos(){
       
       if(this.readyState === ConCargarElementos.READY_STATE_COMPLETE && this.status === 200){
            try{
                
               if(tipo === 'PP'){
                    objPro = JSON.parse(petPro.responseText);
                    //Eliminamos el objeto conexion
                    delete ConCargarElementos;
                } else if(tipo === 'PG'){
                    objGen = JSON.parse(petGen.responseText);
                    //Eliminamos el objeto conexion
                    delete ConCargarElementos;
                } else if(tipo === 'PS'){
                    objSeccion = JSON.parse(petSeccion.responseText);
                    //Eliminamos el objeto conexion
                    delete ConCargarElementos;
                } else if(tipo === 'PT'){
                    objTiempoCambio = JSON.parse(petTiempoCambio.responseText);
                    //Eliminamos el objeto conexion
                    delete ConCargarElementos;
                }
                
            } catch(e){
                switch(tipo){        
                    case 'PP':
                            break;
                   
                    default:
                        location.href= 'mostrar_error.php';
                }
            //fin catch
            }
            
            switch (tipo){
                case 'PP':
                    cargarProvincias(objPro);
                        break;
                case 'PG':
                    cargarGenero(objGen);
                        break;
                case 'PS':
                    cargarSecciones(objSeccion);
                        break;
                case 'PT':
                    cargarTiempoDeCambio(objTiempoCambio);
                        break;
               
            //fin switch
            }
        //fin if
        }
    //fin procesaRespuesta    
    }
    
    
    
    
//fin cargarPeticion    
}
    


/*Cargamos las provincias, tanto para cuando un usuario se registra
* como para el filtro del buscador*/
function cargarProvincias(objProv){
    //alert(objProv);
   
    for(var i = 0; i < objProv.length; i++){
        var objTmpP = objProv[i];
      if(provincias  !== null){
          //Evitamos insertar el primer valo de la tabla 
          //de provincias en el formulario de registro
          if(i === 0){
                continue;
          }else{
                provincias.options.add(new Option(objTmpP.nombre));
            }
      } else {
        porProvincia.options.add(new Option(objTmpP.nombre));
            }
    }
}

/*Cargamos los tipos de genero*/   
function cargarGenero(objGene){
    //alert(objGene);
    for(var i = 0; i < objGene.length; i++){
        var objTmpG = objGene[i];
      genero.options.add(new Option(objTmpG.genero));
    }  
//fin cargarProvincias    
}

/*Cargamos las secciones de los artículos*/   
function cargarSecciones(objSeccion){
   // alert(objSeccion);
    for(var i = 0; i < objSeccion.length; i++){
        var objTmpS = objSeccion[i];
        
      seccion.options.add(new Option(objTmpS.nombre_seccion));
    }  
//fin cargarSecciones   
}

/*Cargamos el tiempo para el cambio, tanto cuando el usuario sube un Post
* como para el buscador
* */   
function cargarTiempoDeCambio(objTiempoCambio){
    //alert(objTiempoCambio);
    for(var i = 0; i < objTiempoCambio.length; i++){
        var objTmpTiempoCambio = objTiempoCambio[i];
        if(tiempoCambio  !== null){
            tiempoCambio.options.add(new Option(objTmpTiempoCambio.tiempo));
      } else {
            porTiempoCambio.options.add(new Option(objTmpTiempoCambio.tiempo));
            }
      
    }  
//fin cargarSecciones   
}
