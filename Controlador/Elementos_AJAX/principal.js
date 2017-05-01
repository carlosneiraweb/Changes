/* global nombre */
/**
 * @author Carlos Neira Sanchez
 * @mail arj.123@hotmail.es
 * @telefono ""
 * @nameAndExt principal.js
 * @fecha 26-oct-2016
 */

var petPost, objPost, objPostSeleccionado, petPostSeleccionado,
    petVolver, objVolver, PAGESIZE;


var fecha = new Date();

var Conexion;

            //Creamos una instancia de la clase CONEXION_AJAX
            //Nos devuelve una conexion AJAX y propiedades 
                    var ConElementos  = new Conexion();
                    
                  
//Inicializamos la variable inicio que mostrara por el numero por donde empezar a mostrar los posts
//La variable mostrar define que secciones mostrar 
    //Comprobamos sin ya se ha inicializado, sino cada vez que el script
    //se instanciase recargaria su valor.
    if(typeof(inicio) === "undefined"){ inicio = 0; };  
    //Aqui guardaremos la ultima peticion JSON en un array
    //Para volver a ese punto cuando lo necesitemos
    //Osea queramos mostrar el slider del post seleccionado,
    //o estemos en la paginacion de una seccion y salgamos de ella
    //y queremos volver donde estabamos. 
    //De inicio ponemos que empieze en la seccion de inicio
    //y a la variable de inicio le damos un 0
    //Mostrara los ultimos posts publicados de cada seccion
   
    //Es el 6º parametro del array jsonVolver
    //Es una bandera que usamos para guardar la ultima peticion JSON
    //Para cuando el usuarioo quiera salir de paginacion o de mostrar un post seleccionado
    if(typeof(vistaIndependiente ) === "undefined"){ vistaIndependiente  = true; } 
    if(typeof(jsonVolver) === "undefined"){ jsonVolver = ["PPS",'', "opcion=PPS&inicio="+inicio, '','','',vistaIndependiente]; };
    
    
    
window.onload=function(){          

 
 //Creamos la seccion del buscador por jquery
        insertarBuscador();
        
          
//Si el usuario no esta logeado inicializamos la variable 
//logeoParaComentar a null
//Mas adelante la utilizamos para mostrar un boton
//Que se utiliza para poder subir un comentario a un post
    if(typeof(logeoParaComentar) === "undefined"){ 
             logeoParaComentar = null;
        };
        
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
                cargarPeticion("SLD", "opcion=SLD&srcImg="+src, inicio);
            });
   
    
    $('#cuerpo').on('click','li.pagina', function(e){
       //Llamamos al metodo que nos 
       //permite desplazarnos por los <li> 
       // hacia delante o atras
       liPinchado = parseInt($(this).text());
       navegarPorPosts(liPinchado);
    });
    
    //Activamos los botones de Siguiente y Atras de paginacion
    $('#btn_navegacion').on('click', 'ul.listaLis>li.siguiente', mostrarSiguienteRango);
    $('#btn_navegacion').on('click', 'ul.listaLis>li.atras', mostrarAnteriorRango);
    
    
    //Metodo que nos devuelve a la seccion y posicion inicial
    //con la ultima peticion JSON hecha al cambiar de seccion,
    //en la paginacion, etc
    $('#btn_navegacion').on('click', '#btn_volver', volverAnteriorJSON);
    
    
    
//fin onload    
};      


function cargarPeticion(tipo, parametros){
    alert('Estamos enllll cargarPeticion y tipo vale: ' +tipo+ ' parametros vale: ' +parametros);
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
//        case(null):
//            petVolver = ConElementos.conection();
//            petVolver.onreadystatechange = procesaRespuesta();
//            petVolver.open('POST', "../Controlador/Elementos_AJAX/json.php?", true);
//            petVolver.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
//            petVolver.send(parametros);
//                break;
        default:
            alert('Error');
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
                    objPostSeleccionado = JSON.parse(petPostSeleccionado.responseText);
                    //Eliminamos el objeto conexion
                    delete ConElementos;
                } else if(tipo === null){
                    objVolver = null;
                }
                
                
            } catch(e){
                switch(tipo){        

                    default:
                       // location.href= 'mostrar_error.php';
                }
            //fin catch
            }
            
            switch (tipo){
                
                case 'PPS':
                    //Tenemos que resetear todas las variables
                    //de paginacion cada vez que cambiamos de seccion
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

function volverAnteriorJSON(){
    
    vistaIndependiente = true;
    numLi = parseInt(jsonVolver[5])
    numeroEnLi = parseInt(jsonVolver[4]);
    tmpLi = parseInt(jsonVolver[3]);
    
    cargarPeticion(jsonVolver[0], jsonVolver[2]);
    
}



