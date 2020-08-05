/* global nombre */
/**
 * @author Carlos Neira Sanchez
 * @mail arj.123@hotmail.es
 * @telefono ""
 * @nameAndExt mostrarPosts.js
 * @fecha 01-oct-2017
 */

//Variables globales para los <li> de navegacion
var numLi, totalPost, final;

/**
* @description description
 * Recive un objeto JSON con los posts a mostrar, ademas es el
 * encargado de mostrar una serie de <li> con los numeros en el que estamos
 * en la paginacion.
 * Estos <li> al ser pulsados recuperaremos su valor html 
 * en cada una de las secciones, y ese valor sera pasado como 
 * la variable inicio para mostrar el rango adecuado de post
 * en la peticion JSON.
 * VARIABLES IMPORTANTES
 *  Ambas variables son modificadas en paginacion.js segun
 *  el usuario va pulsando cada uno de los <li> a delante y atras
 * tmpLi => Es la variable de comparacion en el bucle for que muestra los <li>
 * numeroEnLi => Es el numero que aparece en cada <li>
 * @param {type} objPost
  */
function cargarPost(objPost){
   //alert("estamos en cargarPost"+objPost);
    //Eliminamos los posts ya mostrados y el h3 donde se muestra el total de posts
    //alert(inicio);
    if (inicio !== 1) {
        $(".cont_post").remove();
    }    
    
    //Aqui calculamos el numero final de posts mostrados
    //que aparecera en el h3 
    
    if((inicio + PAGESIZE) >= parseInt(objPost[0].totalRows[0])){
        final = parseInt(objPost[0].totalRows[0]);
    }else{
        final = (inicio + PAGESIZE);
    }
    
    
    //Cargamos el total de resultados y los mostrados en cada pagina
    //Se agrega antes del contenedor posts
   $('#contenedor>h3').remove(); 
   $('#publi').after('<h3>Se muestran desde '+(inicio)+' al '+(final)+'º De un total de '+(parseInt(objPost[0].totalRows[0]))+' posts encontrados </h3>'); 
    
    
    for(var i = 1; i < objPost.length; i++){
       
        $("#posts").append($('<section>',{
                class : "cont_post",
                id : objPost[i].idPost
            }).append($('<h2>',{
                text : objPost[i].titulo
            })).append($('<section>',{
                class  : 'cont_usuario'
            }).append($('<span>',{
                class : 'usuario',
                text : "El usuario: "
            }).append($('<span>',{
                class : 'resaltar',
                text : objPost[i].nick
            })).append($('<span>',{
                class : 'lugar',
                text : "  de  "+objPost[i].provincia
            }))).append($('<span>',{
                class : 'tiempo_cambio',
                text : "Tiempo del cambio "
            }).append($('<span>',{
                class : 'resaltar',
                text : objPost[i].tiempoCambio
            })))).append($('<figure>',{
                class : 'lanzar'
            }).append($('<img>',{
                src : "../photos/"+objPost[i].ruta+".jpg",
                alt : "Foto del articulo a cambiar"
            }))).append($('<section>',{
                class : 'comentario'
            }).append($('<textarea>',{
                class : 'texto_comentario',
                text : objPost[i].comentario
            }))).append($('<span>',{
                class : 'piePost'
            }).append($('<span>',{
                class : 'contBotonComentario'
            }).append($('<input>',{
                id : 'btnComentar',
                type : 'button',
                disabled : 'disabled',
                value: 'Comentar', 
                class : objPost[i].idPost
            })).append($('<section>',{
                class : 'capaBoton'
            }))).append($('<span>',{
                text : 'Fecha del Post'
            }).append($('<span>',{
                class : 'date',
                text : objPost[i].fecha
            })))));

            
            //Verificamos que el usuario se ha logeado
            //Para habilitar el boton para poder comentar
                if(logeoParaComentar === 'logeado'){
         
                    $('.capaBoton').addClass('oculto');
                    $("."+objPost[i].idPost).removeAttr('disabled');
                     
                }
                
                    //Si el usario ha sido bloqueado parcialmente
                    //eliminamos el boton de comentar con JAVASCRIPT
                    if(objPost[i].coment == 1){
                        $("."+objPost[i].idPost).hide();
                       
                        };
                            
                        
                    
//fin for 
    }
    
    
    
            //Calculamos el total de <li> que se van a mostrar 
    //para navegar por el conjunto de resultados
    //Este resultado lo sacamos de la consulta sql
    totalPost = parseInt(objPost[0].totalRows[0]); //total posts
    cargarLis();
//fin cargarPost    
}
    
   
/**
 * @description 
 * Este metodo carga los <li> con su correspondiente numero
 * para la paginacion ejemplo 1-10, 10-20
 * Luego al pinchar en cada uno de los <li> o adelante y atras
 * recuperaremos en paginacion.js el valor que contenga
 * y lo usaremos para ir modificando su valor
 * 
 */    
function cargarLis(){
   
    //Inicializamos la variable del for que muestra el numero que hay 
    //en cada <li>
    //Mas tarde cuando el usuario pulse los botones siguiente o atras 
    //se ira modificando
    if(typeof(numeroEnLi) === "undefined"){ numeroEnLi = 0; }; 
   
    //numLi es el numero real de <li> que salen
    if(typeof(numLi) === "undefined"){      
        numLi = totalPost / PAGESIZE; //Numeros de <li> 
        //Si al dividir sale decimal le sumamos un <li>
            if ((numLi % 2 ) !== 0){
                numLi++;
            }
            
        //Parseamos a Integer y ya tenemos el total de <li> a mostrar
            numLi = parseInt(numLi);
        //Queremos limitar el numero de <li> a 10 por pagina
            //En caso de que numLi sea mayor a 10 * PAGESIZE
            if (numLi > PAGESIZE * 10 ){
                tmpLi = numeroEnLi + 10; //pasamos de  10, osea 11,21,31
            }else if(numLi > 10){
                tmpLi = 10;//del 0 al 9
            }else{
                tmpLi = numLi + 1;
            }
    }
    
    //En caso de paginacion o mostrar un post seleccionado en el slider
    //Recuperamos la url anterior para mostrar el punto anterior 
    
    if(vistaIndependiente){jsonVolver = ["PPS",'', "opcion=PPS&inicio="+inicio, tmpLi, numeroEnLi, numLi, vistaIndependiente];}
    
                    //Mostramos los <li>
    var listaLi = '<ul class="listaLis"><li class="atras">Atras</li>';
        //Fijarse que numeroEnLi es global
        //Recuerda los incrementos del bucle for
        for (numeroEnLi ; numeroEnLi < tmpLi; numeroEnLi++){
            listaLi += '<li class="pagina">'+numeroEnLi+'</li>';
        }
            listaLi +='<li class="siguiente">Siguiente</li></ul>';
            
            //Si hemos buscado en el buscador algo y hemos estamos
            //navegando por los posts de la busqueda queremos volver 
            //a donde estabamos antes de la busqueda
            //Este boton nos permite hacer eso.
        if (vistaIndependiente === false) {
            listaLi += '<span id="cont_volver">';
            listaLi += '<input type="button" id="btn_volver" value="Salir"></span>';
        }
        
                $('#btn_navegacion').html(listaLi);
        
       
//fin cargarLis
} 
