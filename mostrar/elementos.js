/* global nombre */

var READY_STATE_UNINITIALIZED = 0;
var READY_STATE_LOADING = 1;
var READY_STATE_LOADED = 2;
var READY_STATE_INTERACTIVE = 3;
var READY_STATE_COMPLETE = 4;

var objPro, petPro, objGen, petGen, objSeccion, petSeccion, objTiempoCambio, petTiempoCambio;
var fecha = new Date();

window.onload=function(){   
     provincias = document.getElementById('provincia');
     genero = document.getElementById('genero');
     seccion = document.getElementById('seccion');

     cargarPeticion("PP", "opcion=PP");
     cargarPeticion("PG", "opcion=PG");
     cargarPeticion("PS", "opcion=PS");
     cargarPeticion("PT", "opcion=PT");
    
};


function cargarPeticion(tipo, parametros){
//alert('Estamos en cargarPeticion y tipo vale: ' +tipo+ ' parametros vale: ' +parametros);
    //para comprobar el tipo de peticion
    switch(tipo){
        case('PP'):
           petPro = inicializaPeticion();
           petPro.onreadystatechange = procesaRespuesta;
           petPro.open('POST', "./central/json.php?", true);
           petPro.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
           petPro.send(parametros);
                break;
        case('PG'):
           petGen = inicializaPeticion();
           petGen.onreadystatechange = procesaRespuesta;
           petGen.open('POST', "./central/json.php?", true);
           petGen.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
           petGen.send(parametros);
                break;
        case('PS'):
           petSeccion = inicializaPeticion();
           petSeccion.onreadystatechange = procesaRespuesta;
           petSeccion.open('POST', "./central/json.php?", true);
           petSeccion.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
           petSeccion.send(parametros);
                break;        
        case('PT'):
           petTiempoCambio = inicializaPeticion();
           petTiempoCambio.onreadystatechange = procesaRespuesta;
           petTiempoCambio.open('POST', "./central/json.php?", true);
           petTiempoCambio.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
           petTiempoCambio.send(parametros);
                break; 
    //fin switch
    }
    
    function procesaRespuesta(){
       
       if(this.readyState === READY_STATE_COMPLETE && this.status === 200){
            try{
               if(tipo === 'PP'){
                    objPro = JSON.parse(petPro.responseText);
                } else if(tipo === 'PG'){
                    objGen = JSON.parse(petGen.responseText);
                } else if(tipo === 'PS'){
                    objSeccion = JSON.parse(petSeccion.responseText);
                } else if(tipo === 'PT'){
                    objTiempoCambio = JSON.parse(petTiempoCambio.responseText);
                }
                
                
            } catch(e){
                switch(tipo){        
                    case 'PP':
                            break;
                    default:
                        alert("No recuperación datos");
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

/*Cargamos las provincias*/
function cargarProvincias(objProv){
    for(var i = 0; i < objProv.length; i++){
        var objTmpP = objProv[i];
      provincias.options.add(new Option(objTmpP.nombre));
    }   
}
/*Cargamos los tipos de genero*/   
function cargarGenero(objGene){
    for(var i = 0; i < objGene.length; i++){
        var objTmpG = objGene[i];
      genero.options.add(new Option(objTmpG.genero));
    }  
//fin cargarProvincias    
}

/*Cargamos las secciones de los artículos*/   
function cargarSecciones(objSeccion){
    for(var i = 0; i < objSeccion.length; i++){
        var objTmpS = objSeccion[i];
      seccion.options.add(new Option(objTmpS.nombre_seccion));
    }  
//fin cargarSecciones   
}

/*Cargamos el tiempo para el cambio*/   
function cargarTiempoDeCambio(objTiempoCambio){
    for(var i = 0; i < objTiempoCambio.length; i++){
        var objTmpTiempoCambio = objTiempoCambio[i];
      tiempoCambio.options.add(new Option(objTmpTiempoCambio.tiempo));
    }  
//fin cargarSecciones   
}



//------------------------funcion inicializa peticion ----------------------------
  function inicializaPeticion(){
         var peticion;
        if(window.XMLHttpRequest){
            peticion = new XMLHttpRequest(); 
        }else if (window.ActiveXObject){
                peticion= new ActiveXObject('Microsoft.XMLHTTP'); 
            }
             return peticion;
      }  
    