/* global nombre */
/**
 * @author Carlos Neira Sanchez
 * @mail arj.123@hotmail.es
 * @telefono ""
 * @nameAndExt principal.js
 * @fecha 26-oct-2020
 */

var petPost, objPost, objPostSeleccionado, petPostSeleccionado,
    petVolver, objVolver, PAGESIZE, banderaCambioSeccion = false,
     opcionSwitchVolver, opcionPeticionVolver, peticionVolver,
        tmpLiVolver, numeroEnLiVolver, numLiVolver, inicioVolver,
        opcionMenu = "", buscador = false, petComent,
        objComent, idPostComentar;


var fecha = new Date();

var Conexion;

            //Creamos una instancia de la clase CONEXION_AJAX
            //Nos devuelve una conexion AJAX y propiedades 
                    var ConElementos  = new Conexion();
                    
                  
//Inicializamos la variable inicio que mostrara por el numero por donde empezar a mostrar los posts
//La variable mostrar define que secciones mostrar 
    //Comprobamos si ya se ha inicializado, sino cada vez que el script
    //se instanciase recargaria su valor.
    if(typeof(inicio) === "undefined"){ inicio = 0; }
    
    //alert(inicio);
    //Aqui guardaremos la ultima peticion JSON en un array
    //Para volver a ese punto cuando lo necesitemos
    //Osea queramos mostrar el slider del post seleccionado,
    //o estemos en la paginacion de una seccion y salgamos de ella
    //y queremos volver donde estabamos. 
    //De inicio ponemos que empieze en la seccion de inicio
    //y a la variable de inicio le damos un 0
    //Mostrara los ultimos posts publicados de cada seccion
   
    //Es el 4º parametro del array jsonVolver
    //Es una bandera que usamos para guardar la ultima peticion JSON
    //Para cuando el usuario quiera salir de paginacion o de mostrar un post seleccionado
    if(typeof(vistaIndependiente ) === "undefined"){ vistaIndependiente  = true; } 
    if(typeof(jsonVolver) === "undefined"){ jsonVolver = ["PPS", "opcion=PPS&inicio="+inicio, '', '']; };
              
    //Si el usuario no esta logeado inicializamos la variable 
    //logeoParaComentar a null
    //Mas adelante la utilizamos para mostrar un boton
    //Que se utiliza para poder subir un comentario a un post
        if(typeof(logeoParaComentar) === "undefined"){ 
                 logeoParaComentar = null;
            };
        
    
window.onload=function(){          

 
 //Creamos la seccion del buscador por jquery
        insertarBuscador();
        

        
//Esta llamada a JSON solo se realiza en la primera carga del script
    //Despues se iran mostrando los posts a traves de los botones 
       
        if(inicio === 0 && PPS === true){
            cargarPeticion("PPS", "opcion=PPS&inicio="+inicio); //Cargar Post
        }
   
    /*      METODO QUE LANZA EL SLIDER CON EL 
     *      CONTENIDO DEL POST SELECCIONADO POR
     *      EL USUARIO AL HACER CLICK SOBRE LA IMAGEN
     */
        //Capturamos la img sobre la que se ha hecho click
        //Para mostrar el slider con los datos de esta
        
        $('#cuerpo').on('click','.lanzar', function(e){
                var src = $(this).children().attr('src');
              // alert("opcion=SLD&srcImg="+src+"&inicio="+inicio);
                cargarPeticion("SLD", "opcion=SLD&srcImg="+src+"&inicio="+inicio);
               
            });
            
        
        
    
    $('#cuerpo').on('click','li.pagina', function(e){
       //Llamamos al metodo que nos 
       //permite desplazarnos por los <li> 
       // hacia delante o atras
       liPinchado = parseInt($(this).text());
       //paginacion
       navegarPorPosts(liPinchado);
    });
    
    //Activamos los botones de Siguiente y Atras de paginacion
    //paginacion.js
    $('#btn_navegacion').on('click', 'ul.listaLis>li.siguiente', mostrarSiguienteRango);
    $('#btn_navegacion').on('click', 'ul.listaLis>li.atras', mostrarAnteriorRango);
    
    
    //Metodo que nos devuelve a la seccion y posicion inicial
    //con la ultima peticion JSON hecha al cambiar de seccion,
    //en la paginacion, etc
    //$('#btn_navegacion').on('click', '#btn_volver', volverAnteriorJSON);
    
    
    
     //Recuperamos la opcion del menu
        $('#cuerpo').on('click', '.separarLetras', function(){
                inicio = 0;
                buscador = false;
                opcionMenu = $(this).text();
                parametrosMenu = "opcion="+opcionMenu+"&inicio="+inicio;
                jsonVolver[0] = opcionMenu;
                //alert(jsonVolver[0]);
                //menu.js
                        cargarPeticionMenu(opcionMenu, parametrosMenu);
                
            });
            
         //Mostramos el formulario para comentarios  
        $('#cuerpo').on('click','#btnComentar', function(){
           idPostComentar = $(this).attr('class');
           $("#ocultar").removeClass('oculto').addClass('mostrar_transparencia');
           $("#form_comentario").removeClass('oculto').addClass('mostrar_formulario_comentarios');
        });
        
        //Mandamos insertar comentario
        //subirComentarios
        $('#form_comentario').on('click','#btn_mandar_comentario', function(){
            
            insertarComentario(idPostComentar);
        });

         //Mostramos comentarios
        $('#cuerpo').on('click','#mostrarTotalComentarios', function(){
            
            totalComent = ($(this).next().text());
            totalComent = parseInt(totalComent);
            
            idPost = ($(this).attr('class'));
          
            if(totalComent > 0){
               //buscarComentarios 
               buscarComentarios(idPost, totalComent);
            }
 
         });
         
        //Salimos de los comentarios
        $('#posts').on('click','#finComentarios', function(){
            //mostrarComentarios
           salirDeComentarios();
        });
        
        
        //Mostramos Menu
        $("#btns_sesion").on('click','#menu', function(){
            
            mostrarMenu();
            
        });

    
     
//fin onload    
};      





function cargarPeticion(tipo, parametros){
//alert('Estamos en cargarPeticionPrincipal y tipo vale: ' +tipo+ ' parametros vale: ' +parametros);
    //para comprobar el tipo de peticion
   
    switch(tipo){
        
        case('PPS'):
           petPost = ConElementos.conection();
           petPost.onreadystatechange = procesaRespuesta;
           petPost.open('POST', "../Controlador/Elementos_AJAX/json.php?", true);
           petPost.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
           petPost.send(parametros);
                break;   
        case('SLD'):
           petPostSeleccionado = ConElementos.conection();
           petPostSeleccionado.onreadystatechange = procesaRespuesta;
           petPostSeleccionado.open('POST', "../Controlador/Elementos_AJAX/json.php?", true);
           petPostSeleccionado.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
           petPostSeleccionado.send(parametros);
                break;
        
        default:
           // alert('Error');
    //fin switch
    }
    
    function procesaRespuesta(){
      
       if(this.readyState === ConElementos.READY_STATE_COMPLETE && this.status === 200){
         
            try{
                if(tipo === 'PPS'){
                    objPost= JSON.parse(petPost.responseText);
                     //Eliminamos el objeto conexion
                    delete ConElementos;
                } else if(tipo === 'SLD'){
                    //alert('ruta es: '+objPostSelecconado[0][1].ruta);
                    objPostSeleccionado = JSON.parse(petPostSeleccionado.responseText);
                    //alert(objPostSeleccionado[0][0].ruta);
                    //Eliminamos el objeto conexion
                    delete ConElementos;
                }
                    
                
                
                
            } catch(e){
                switch(tipo){        

                    default:
                        location.href= 'index.php';
                }
            //fin catch
            }
            
            switch (tipo){
                
                case 'PPS':
                    //banderaCambioSeccion = true;
                    //Tenemos que resetear todas las variables
                    //de paginacion cada vez que cambiamos de seccion
                    vistaIndependiente = true;
                    var totalPostEnconrados = (parseInt(objPost[0].totalRows[0]) - 1);
                    if(banderaCambioSeccion){resetearValoresDePaginacion(totalPostEnconrados);};
                    //mostrarPost
                    cargarPost(objPost);
                        break;
                case 'SLD':
                    
                    cargarPostSeleccionado(objPostSeleccionado);
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
 * @description 
 * Dependiendo de la variable a mostrar
 * manda al metodo adecuado la peticion JSON
 * con los parametros adecuados.
 * @param {string} opcion
 * Es el encargado de mandar al script indicado
 * la url con la peticion JSON adecuada
 * cuando estamos paginando.
 */
function cargarContenidoPorSeccion(){
 
       //alert(jsonVolver[0]);
        if(jsonVolver[0] === 'ENCONTRADO'){
            opcion = jsonVolver[0];
        }else if(jsonVolver[0] === "PPS"){
            opcion = jsonVolver[0];
        }else{
            opcion = jsonVolver[0];
        }
        
        
        
        switch (opcion){
                case 'PPS':
                    
                    cargarPeticion(opcion, "opcion=PPS&inicio="+inicio);
                        break;
                        
                case 'ENCONTRADO':
                    //alert("opcion=ENCONTRADO&ENCONTRAR="+textoElegido+"&tabla="+radioBusqueda+"&inicio="+inicio);
                    cargarPeticionBuscador(opcion, "opcion=ENCONTRADO&ENCONTRAR="+textoElegido+"&tabla="+radioBusqueda+"&inicio="+inicio);
                        break;
                        
                default:
                   // alert(opcionMenu +'opcion='+opcion+'&inicio='+inicio);
                   //menu.js
                    cargarPeticionMenu(opcionMenu, 'opcion='+opcion+'&inicio='+inicio);   
            }
    
    
//cargarContenidoPorSeccion    
}



/**
 * @description 
 * Al cambiar de seccion hay que volver a asignar
 * valores a los elementos de paginacion.
 * No hay los mismos posts en cada seccion
 * @param {type} posts
 * Entero con el numero total de posts encontrandos
 */
function resetearValoresDePaginacion(posts){

    numLi = posts / PAGESIZE; //Numeros de <li>
        //Si al dividir sale decimal le sumamos un <li>
            if ((numLi % 2 ) !== 0){
                numLi++;
            }
  
     
    numeroEnLi = 0;
    
    //inicio = 0;
    
       
        //Parseamos a Integer y ya tenemos el total de <li> a mostrar
            numLi = parseInt(numLi);
           // alert('en resetear numLi '+numLi+ ' inicio '+inicio);     
        //Queremos limitar el numero de <li> a 10 por pagina
            //En caso de que numLi sea mayor a 10 * PAGESIZE
            if (numLi > PAGESIZE * 10){
                tmpLi = numeroEnLi + 10;
            }else{
                tmpLi = numLi;
            }
            
            //Queremos limitar el numero de <li> a 10 por pagina
            //En caso de que numLi sea mayor a 10 * PAGESIZE
            if (numLi > PAGESIZE * 10 ){
                tmpLi = numeroEnLi + 10; //pasamos de  10, osea 11,21,31
            }else if(numLi > 10){
                tmpLi = 10;//del 0 al 9
            }else{
                tmpLi = numLi;
            }
    
            
    //alert('Resetear numLi '+numLi+ ' tmpLi '+tmpLi+ ' numeroEnLi '+numeroEnLi);      
    
    //Ponemos a bandera false por que no
    //queremos que se vuelvan a reseter las variables de los <li>
    banderaCambioSeccion = false;  
   
    
//fin   resetearValoresDePaginacion  
}



    
   

