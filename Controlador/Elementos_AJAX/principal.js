/* global nombre */
/**
 * @author Carlos Neira Sanchez
 * @mail arj.123@hotmail.es
 * @telefono ""
 * @nameAndExt principal.js
 * @fecha 26-oct-2016
 */

var petPost, objPost, objPostSeleccionado, petPostSeleccionado, PAGESIZE;


var fecha = new Date();

var Conexion;

            //Creamos una instancia de la clase CONEXION_AJAX
            //Nos devuelve una conexion AJAX y propiedades 
                    var ConElementos  = new Conexion();
                    
                  
//Inicializamos la variable inicio que mostrara por donde empezar a mostrar los posts
    //Comprobamos sin ya se ha inicializado, sino cada vez que el script
    //se cargara machacaria su valor.
    if(typeof(inicio) === "undefined"){ inicio = 0; };
                
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
   
    
    $('#cuerpo').on('click','.pagina', function(e){
       //Llamamos al metodo que nos 
       //permite desplazarnos por los <li> 
       // hacia delante o atras
       //alert('mandamos inicio a navegarPorPost '+inicio);
       liPinchado = parseInt($(this).text());
       navegarPorPosts(liPinchado);
      // alert('Recivimos inicio de navegar por posts '+inicio);
    });
    
    //Metodo que nos muestra el siguiente conjunto de <li> que hay, si los hubiera
        //Al pulsar el <li> con la clase siguiente
    $('#cuerpo').on('click', '.siguiente', function(e){
        mostrarSiguienteRango();
    });
    
    //Metodo que nos muestra el anterior conjunto de <li> 
            //Al pulsar el <li> con la clase Atras
    $('#cuerpo').on('click', '.atras', function(e){  
        mostrarAnteriorRango(); 
        
    });  
    
//fin onload    
};      


function cargarPeticion(tipo, parametros){
alert('Estamos en cargarPeticion y tipo vale: ' +tipo+ ' parametros vale: ' +parametros);
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





