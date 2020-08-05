/**
 * @author Carlos Neira Sanchez
 * @mail arj.123@hotmail.es
 * @telefono ""
 * @nameAndExt buscador.js
 * @fecha 26-oct-2016
 */
   

 var   objPro, petPro, objTiempoCambio, petTiempoCambio,
         objBuscador, petBuscador, objEncontrado, petEncontrado,
         objPalabraBuscada, petPalabraBuscada,
         porProvincia, porPrecio, porTiempoCambio, txtBuscar,
         buscarPorProvincia, buscarPorPrecio, buscarPorTiempoCambio;
 


     //Creamos una instancia de la clase CONEXION_AJAX
            //Nos devuelve una conexion AJAX y propiedades 
                    var ConBuscador  = new Conexion();
                
 /**
  * @description
  * Inserta el buscador en la pagina con JQUERY
  * Este script es el encargado de mandar los parametros de las consultas 
  * que el usuario hace.
  */  
 function insertarBuscador() {
        
       
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
            value : 'ofrezco'
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
            id : 'porTiempoCambio',
            class : 'porTiempoCambio'
        })).append($('</br>')).append($('<input>',{
            type : 'text',
            id : 'buscador',
            class : 'buscador'
        })).append($('<section>',{
            id : 'mostrarResultados'
        })).append($('<ul>',{
            id : 'contenidoBuscado'
        }));   
        
        //Cargamos los combos una vez creados por jquery
       
        cargarPeticionBuscador("PP", "&opcion=PP");
        cargarPeticionBuscador("PT", "&opcion=PT");
         
         
        //Al pulsar una letra mandamos una busqueda
        // a busquedas.php donde nos devolvra el resultado
        //
    $('#buscar_datos').on('keyup', '.buscador', function(e){
        
         
         //Algunas teclas dan problemas como el ir hacia atras <- 
         //Por eso anulamos el evento si se pulsan
         //En este caso solo he anulado esta
         if(e.which !== 8){
            //Primero eliminamos las busquedas anteriores
        $('#contenidoBuscado li').remove();
         
        //Recuperamos el valor de los filtros de busqueda
        
        
        //Tablas buscados y ofrecidas
        radioBusqueda = $('input:radio[name=busqueda]:checked').val();
        
        buscarPorProvincia = $('#porProvincia').val();
        
        indice = $('#porPrecio').prop('selectedIndex');//$(this).index();
        
             if(indice === 0){buscarPorPrecio = "No importa";};
             if(indice === 1){buscarPorPrecio = 500;};
             if(indice === 2){buscarPorPrecio = 3000;};
             if(indice === 3){buscarPorPrecio = 3001;};
           
        buscarPorTiempoCambio = $('#porTiempoCambio').val();;
            
        if(buscarPorProvincia === "No importa"){  buscarPorProvincia = '1'; };
        if(buscarPorPrecio === "No importa"){ buscarPorPrecio = '0'; };
        if(buscarPorTiempoCambio == "No importa"){  buscarPorTiempoCambio = '0'; };
        
    
        //Recuperamos los que el usuario ha escrito en el campo para buscar
        txtBuscar = $(this).val();
        inputTmp = $(this);
        
        //alert('BUSCADOR'+"&opcion=BUSCADOR&BUSCAR="+txtBuscar+"&tabla="+radioBusqueda+"&buscarPorProvincia="+buscarPorProvincia+
               //'&buscarPorPrecio='+buscarPorPrecio+'&buscarPorTiempoCambio='+buscarPorTiempoCambio);
            cargarPeticionBuscador('BUSCADOR', "opcion=BUSCADOR&BUSCAR="+txtBuscar+"&tabla="+radioBusqueda+"&buscarPorProvincia="+buscarPorProvincia+
                    '&buscarPorPrecio='+buscarPorPrecio+'&buscarPorTiempoCambio='+buscarPorTiempoCambio);
             }
             
        
                    //Si se encuentra un resultado y el usuario pincha sobre el    
                    //Recuperamos el contenido del li que se ha pulsado
                        $('#contenidoBuscado').on('click','.li',mostrarEncontrado);
                            
                            function mostrarEncontrado(){
                                //Eliminamos el evento, sino se repite mas de una vez
                                $('#contenidoBuscado').off('click','.li',mostrarEncontrado);
                                 //Eliminamos los li de las busquedas
                            $('#contenidoBuscado li').remove();
                                textoElegido = $(this).text();
                                inputTmp.val("");
                                inicio = 0;
                                    //Esta variable la usaremos luego 
                                    //para que solo se reinicien las variables de paginacion
                                    //la primera vez que se cambia de seccion
                                    banderaCambioSeccion = true;
                                        //Ahora hacemos un select de todos los Posts donde tengan ese texto
                                        //En sus palabras de busquedas o queridas
                                        
                                cargarPeticionBuscador('ENCONTRADO', "&opcion=ENCONTRADO&ENCONTRAR="+textoElegido+"&tabla="+radioBusqueda+"&inicio="+inicio);
                                
                                
                                //fin mostrarEncontrado               
                            }
                           
                                
                       
            
    });
            
 //fin insertar Buscardor
    }
    
/*
 * @description 
 * Cargamos las provincias, tanto para cuando un usuario se registra
* como para el filtro del buscador
* */
function cargarProvincias(objProv){
   
    for(var i = 0; i < objProv.length; i++){
        var objTmpP = objProv[i];
            if (objTmpP.nombre === "Desconocido") {
                $('#porProvincia').append($('<option>',{    
                    text : "No importa"
                }));
            }else{
                $('#porProvincia').append($('<option>',{    
                    text : objTmpP.nombre 
                }));
            }
                
 }
 //fin cargarProvincias
}

/*
 * @description 
 * Cargamos el tiempo para el cambio, tanto cuando el usuario sube un Post
* como para el buscador
* */   
function cargarTiempoDeCambio(objTiempoCambio){
   
    for(var i = 0; i < objTiempoCambio.length; i++){
        var objTmpTiempoCambio = objTiempoCambio[i];
        $('#porTiempoCambio').append($('<option>',{
            text : objTmpTiempoCambio.tiempo
        }));
      
    }  
//fin cargarTiempoDeCambio   
}

/**
 * Metodo que muestra el formulario 
 * para guardar busquedas que no se han encontrado.
 * Cuando un usuario mas adelante las registre se 
 * recivira un email.
 */
function mostrarFormularioGuardarBusquedas(){
       $('#ocultar').removeClass('oculto');
       $('#ocultar').addClass('mostrar_transparencia');
       
       $('#busquedasPersonales').append($('<section>',{
            id : 'busquedaPalabrasPersonales'
        }).append($('<h3>', {
            text : 'Inserta las palabras de busqueda'
        })).append($('<input>',{
            type : 'text'
        })).append($('<input>',{
            type : 'button',
            id : 'buttonBusquedasPersonales',
            value : 'Aceptar'
        })).on('click', function(){
                alert('saludos');
        }));
    
    //fin mostrarFormularioGuardarBusquedas
}



/**
 * @description 
 * Metodo que carga los resultados del buscador
 * en los <li>. Va mostrando los resultados segun escribe el usuario
 * @returns {ActiveXObject|XMLHttpRequest} 
 * */
function cargarBuscador(objBuscador){
    
    
    var vacio = "<li>No se han encontrado resultados con la busqueda <strong>"+txtBuscar+"</strong></li>";
          
    
    if(typeof objBuscador[0].palabras == "undefined"){
        $('#contenidoBuscado').append(vacio);
           // alert(logeoParaComentar);
                //Solo si el usuario se ha registrado
            if(logeoParaComentar !== null){
                
                $('#contenidoBuscado').append($('<li>',{
                    id : "insertarMisBusquedas",
                    text : "Pincha aqui para recibir un email,"            
                }).on('click', function(){
                        mostrarFormularioGuardarBusquedas();
                        
                    //cargarPeticionBuscador('PEMP', "opcion=PEMP&usuario="+user+"&tabla="+radioBusqueda);           
                })).append($('<li>',{
                    text : "Si alguien publica un anuncio,"         
                })).append($('<li>',{
                    text : "con esas palabras."
                }));
            }else{
               
               $('#contenidoBuscado').append($('<li>',{
                    text : "Si te logeas podras recibir avisos en tú email."            
                })).append($('<li>',{
                    text : "Cuando alguien publique un post,"            
                })).append($('<li>',{
                    text : "con esas palabras."            
                }));
            }
    }else{
       
        for( var b = 0; b < objBuscador.length; b++){
           
               $('#contenidoBuscado').append('<li class="li">'+objBuscador[b].palabras+'</li>');
            
        }
      
    }
                
//fin cargarBuscador      
}


function cargarPeticionBuscador(tipo, parametros){
alert('Estamos en cargarPeticionBuscador y tipo vale: ' +tipo+ ' parametros vale: ' +parametros);
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
        case('PIPB'):
            petPalabraBuscada = ConBuscador.conection();
            petPalabraBuscada.onreadystatechange = procesaRespuesta;
            petPalabraBuscada.open('POST', "../Controlador/Elementos_AJAX/busquedas.php?", true);
            petPalabraBuscada.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
            petPalabraBuscada.send(parametros);
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
                    //Tenemos que resetear todas las variables
                    //de paginacion cada vez que cambiamos de seccion
                    var totalPostEnconrados = (parseInt(objEncontrado[0].totalRows[0]) - 1);
                    if(banderaCambioSeccion){resetearValoresDePaginacion(totalPostEnconrados);};
                    jsonVolver[6] = "ENCONTRADO";
                    vistaIndependiente = false;
                    //Modificado 17/07
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
    


