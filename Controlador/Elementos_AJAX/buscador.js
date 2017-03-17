/**
 * @author Carlos Neira Sanchez
 * @mail arj.123@hotmail.es
 * @telefono ""
 * @nameAndExt elementos.js
 * @fecha 26-oct-2016
 */


/*     ELEMENTOS PARA EL BUSCADOR      */
   

 var   objPro, petPro, objTiempoCambio, petTiempoCambio,
         objBuscador, petBuscador, objEncontrado, petEncontrado,
         porProvincia, porPrecio, porTiempoCambio, txtBuscar,
         buscarPorProvincia, buscarPorPrecio, buscarPorTiempoCambio;
 
     //Creamos una instancia de la clase CONEXION_AJAX
            //Nos devuelve una conexion AJAX y propiedades 
                    var ConBuscador  = new Conexion();
                /*ELEMENTOS PAGINACION     */
    //Inicializamos la variable inicio que mostrara por donde empezar a mostrar los posts
    //Comprobamos sin ya se ha inicializado, sino cada vez que el script
    //se cargara machacaria su valor.
    if(typeof(inicio) === "undefined"){ inicio = 0; };
    
    
 function insertarBuscador() {
        
        $('.slider-container').after ('<section id="buscar_datos"></section>');
        $("#buscar_datos").append($('<h3>',{
            text : 'Selecciona una opción de busqueda'
        })).append($('<label>',{
            for : 'busco',
            text : "Cosas que tú buscas."
        })).append($('<input>',{
            type : 'radio',
            name : 'busqueda',
            id   : 'busco',
            value : 'busco',
            checked : 'checked'
        })).append($('<label>',{
            for : 'ofrezco',
            text : "Cosas que tú ofreces y la gente podría querer."
        })).append($('<input>',{
            type : 'radio',
            name : 'busqueda',
            id   : 'ofrezco',
            value : 'buscan'
        })).append($('</br>')).append($('<label>',{
            for : 'porProvincia',
            text : 'Selecciona la provincia:'
        })).append($('<select>',{
            name : 'selectProvincia',
            id   :  'porProvincia'
        })).append($('<label>',{
            for : 'porPrecio',
            text : 'Selecciona precio:'
        })).append($('<select>',{
            name : 'selectPrecio',
            id   :  'porPrecio'
        }).append($('<option>',{
            text : 'No importa'
        })).append($('<option>',{
            text : 'Hasta 500 €'
        })).append($('<option>',{
            text : 'Hasta 3000 €'
        })).append($('<option>',{
            text : 'Más de 3000 €'
        }))).append($('<label>',{
            for : 'porTiempoCambio',
            text : 'Selecciona el tiempo de cambio:'
        })).append($('<select>',{
            name : 'selectTiempoCambio',
            id : 'porTiempoCambio'
        })).append($('<input>',{
            type : 'text',
            id : 'buscador',
            class : 'buscador'
        })).append($('<section>',{
            id : 'mostrarResultados'
        })).append($('<ul>',{
            id : 'contenidoBuscado'
        }));   
        
        //Cargamos los combos una vez creados por jquery
        cargarSelectsBuscador();
 //fin insertar Buscardor
    }
    
/**
 * Esta funcion carga los selects del buscador
 */
    function cargarSelectsBuscador() {
        
        cargarPeticionBuscador("PP", "opcion=PP");
        cargarPeticionBuscador("PT", "opcion=PT");
        activarBuscador();
    //cargarSelectsBuscador    
    }
    
/*Cargamos las provincias, tanto para cuando un usuario se registra
* como para el filtro del buscador*/
function cargarProvincias(objProv){
   
    for(var i = 0; i < objProv.length; i++){
        var objTmpP = objProv[i];
     $('#porProvincia').append($('<option>',{
         text : objTmpP.nombre 
     }));
 }
 //fin cargarProvincias
}

/*Cargamos el tiempo para el cambio, tanto cuando el usuario sube un Post
* como para el buscador
* */   
function cargarTiempoDeCambio(objTiempoCambio){
    //alert(objTiempoCambio);
    for(var i = 0; i < objTiempoCambio.length; i++){
        var objTmpTiempoCambio = objTiempoCambio[i];
        $('#porTiempoCambio').append($('<option>',{
            text : objTmpTiempoCambio.tiempo
        }));
      
    }  
//fin cargarTiempoDeCambio   
}

/*******************************METODOS DEL BUSCADOR*************************/
/**
 * Activa el buscador
 */
function activarBuscador() {
    

    $('#buscar_datos').on('keyup', '.buscador', function(e){
          
    
         //Algunas teclas dan problemas como el ir hacia atras <- 
         //Por eso anulamos el evento si se pulsan
         //En este caso solo he anulado esta
         if(e.which !== 8){
            //Primero eliminamos las busquedas anteriores
        $('#contenidoBuscado li').remove();
         
        //Recuperamos el valor de los filtros de busqueda
        
        radioBusqueda = $('input:radio[name=busqueda]:checked').val();
        
        buscarPorProvincia = $('#porProvincia').val();
        
        indice = $('#porPrecio').prop('selectedIndex');//$(this).index();
        
             if(indice === 0){buscarPorPrecio = "No importa";};
             if(indice === 1){buscarPorPrecio = 500;};
             if(indice === 2){buscarPorPrecio = 3000;};
             if(indice === 3){buscarPorPrecio = 3001;};
           
        buscarPorTiempoCambio = $('#porTiempoCambio').val();;
            
        if(buscarPorProvincia === "No importa"){  buscarPorProvincia = 0; };
        if(buscarPorPrecio === "No importa"){ buscarPorPrecio = 0; };
        if(buscarPorTiempoCambio === "No importa"){  buscarPorTiempoCambio = 0; };
        //alert('provincia'+buscarPorProvincia+" por precio "+buscarPorPrecio+ " por tiempo "+buscarPorTiempoCambio);
        
        
    
        //Recuperamos los que el usuario ha escrito en el campo
        txtBuscar = $(this).val();
        //alert('BUSCADOR'+"opcion=BUSCADOR&BUSCAR="+txtBuscar+"&tabla="+radioBusqueda+"&buscarPorProvincia="+buscarPorProvincia+
        //        '&buscarPorPrecio='+buscarPorPrecio+'&buscarPorTiempoCambio='+buscarPorTiempoCambio);
        cargarPeticionBuscador('BUSCADOR', "opcion=BUSCADOR&BUSCAR="+txtBuscar+"&tabla="+radioBusqueda+"&buscarPorProvincia="+buscarPorProvincia+
                '&buscarPorPrecio='+buscarPorPrecio+'&buscarPorTiempoCambio='+buscarPorTiempoCambio);
         }
         
          //Recuperamos el contenido del li que se ha pulsado
        $('#contenidoBuscado').on('click','.d',function() {
        var textoElegido = $(this).text();
        
        //Ahora hacemos un select de todos los Posts donde tengan ese texto
        //En sus palabras de busquedas o queridas
        cargarPeticionBuscador('ENCONTRADO', "opcion=ENCONTRADO&ENCONTRAR="+textoElegido+"&tabla="+radioBusqueda+"&inicio="+inicio);
        });
        
	});
        
    //fin activarBuscador    
    }


/**
 * Metodo que carga los resultados del buscador
 * en los <li> va mostrando los resultados segun escribe el usuario
 * @returns {ActiveXObject|XMLHttpRequest} */
function cargarBuscador(objBuscador){
    
    //alert(objBuscador[0].palabra);
    var vacio = "<li>No se han encontrado resultados con la busqueda <strong>"+txtBuscar+"</strong></li>";
    //alert(vacio);
    if(typeof objBuscador[0] === "undefined"){
        $('#contenidoBuscado').append('<li class="d">'+vacio+'</li>'); 
    }else{
         
     
        for(var b = 0; b < objBuscador.length; b++){
        
        $('#contenidoBuscado').append('<li class="d">'+objBuscador[b].palabra+'</li>');
        
        }
    }
//fin cargarBuscador      
}

function cargarPeticionBuscador(tipo, parametros){
//alert('Estamos en cargarPeticion y tipo vale: ' +tipo+ ' parametros vale: ' +parametros);
    //para comprobar el tipo de peticion
    switch(tipo){
        case('PP'):
           petPro = ConBuscador.conection();
           petPro.onreadystatechange = procesaRespuesta;
           petPro.open('POST', "../Controlador/Elementos_AJAX/cargarElementos.php?", true);
           petPro.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
           petPro.send(parametros);
                break;
        case('PT'):
           petTiempoCambio = ConBuscador.conection();
           petTiempoCambio.onreadystatechange = procesaRespuesta;
           petTiempoCambio.open('POST', "../Controlador/Elementos_AJAX/cargarElementos.php?", true);
           petTiempoCambio.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
           petTiempoCambio.send(parametros);
                break;
        case('BUSCADOR'):
           petBuscador = ConBuscador.conection();
           petBuscador.onreadystatechange = procesaRespuesta;
           petBuscador.open('POST', "../Controlador/Elementos_AJAX/busquedas.php?", true);
           petBuscador.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
           petBuscador.send(parametros);
                break; 
        case('ENCONTRADO'):
           petEncontrado = ConBuscador.conection();
           petEncontrado.onreadystatechange = procesaRespuesta;
           petEncontrado.open('POST', "../Controlador/Elementos_AJAX/busquedas.php?", true);
           petEncontrado.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
           petEncontrado.send(parametros);
                break;  
        
    //fin switch
    }
    
    function procesaRespuesta(){
       
       if(this.readyState === ConBuscador.READY_STATE_COMPLETE && this.status === 200){
            try{
                if(tipo === 'PP'){
                    objPro = JSON.parse(petPro.responseText);
                    //Eliminamos el objeto conexion
                    delete ConBuscador;
                }else if(tipo === 'PT'){
                    objTiempoCambio = JSON.parse(petTiempoCambio.responseText);
                    //Eliminamos el objeto conexion
                    delete ConBuscador;
                } else if(tipo === 'BUSCADOR'){
                    objBuscador = JSON.parse(petBuscador.responseText);
                    //Eliminamos el objeto conexion
                    delete ConBuscador;
                } else if(tipo === 'ENCONTRADO'){
                    objEncontrado = JSON.parse(petEncontrado.responseText);
                    //Eliminamos el objeto conexion
                    delete ConBuscador;
                }
                
            } catch(e){
                switch(tipo){        

                    default:
                       // location.href= 'mostrar_error.php';
                }
            //fin catch
            }
            
            switch (tipo){
                case 'PP':
                    cargarProvincias(objPro);
                        break;
                case 'PT':
                    cargarTiempoDeCambio(objTiempoCambio);
                        break;
                case 'BUSCADOR':
                    cargarBuscador(objBuscador);
                        break;
                case 'ENCONTRADO':
                    cargarPost(objEncontrado);
                    
                        break;
                        
            //fin switch
            }
        //fin if
        }
    //fin procesaRespuesta    
    }
    
//fin cargarPeticionBuscador
}
    


