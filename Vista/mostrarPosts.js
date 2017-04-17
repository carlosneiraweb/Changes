/* global nombre */
/**
 * @author Carlos Neira Sanchez
 * @mail arj.123@hotmail.es
 * @telefono ""
 * @nameAndExt mostrarPosts.js
 * @fecha 01-oct-2017
 */

//Variables globales para los <li> de navegacion
var numLi, totalPost;

/**
* @description description
 * Metodo que inserta los posts
 * @param {type} objPost
  */
function cargarPost(objPost){
    
    //Eliminamos los posts ya mostrados y el h3 donde se muestra el total de posts
    //Limpiamos la pantalla salvo en los primeros
    //posts a mostrar
    if (inicio !== 1) {
        $(".cont_post").remove();
    }    
    
    //Cargamos el total de resultados y los mostrados en cada pagina
    //Se agrega antes del contenedor posts
   $('#contenedor>h3').remove();  
   $('#publi').after('<h3>Se muestran desde '+(inicio)+' al '+(inicio + PAGESIZE)+'º De un total de '+(parseInt(objPost[0].totalRows[0]) + 1)+' posts encontrados </h3>'); 
     
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
                value: 'Comentar'
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
                if(logeoParaComentar === 'logeado') {
                    $('.capaBoton').addClass('oculto');
                    //Activamos el boton
                    $('#btnComentar').prop('disabled', "");        
                }
//fin for 
    }
    
   
    
   
    //Calculamos el total de <li> que se van a mostrar para navegar por el conjunto de resultados
    totalPost = parseInt(objPost[0].totalRows[0]); //total posts
    
                /**IMPORTANTE**/
    //Solo instanciamos las variables que muestran el NUMERO DE <LI> cuando
        //se carga la pagina por primera vez
    if(inicio === 0){
        numLi = totalPost / PAGESIZE; //Numeros de <li>
        //Si al dividir sale decimal le sumamos un <li>
            if (numLi % 1 !== 0){
                numLi++;
            }
            
        //Parseamos a Integer y ya tenemos el total de <li> a mostrar
            numLi = parseInt(numLi);
        //Queremos limitar el numero de <li> a 10 por pagina
            //En caso de que numLi sea mayor a 10 * PAGESIZE
            if (numLi > PAGESIZE * 10){
                tmpLi = 10;
            }else{
                tmpLi = numLi;
            }
    //Inicializamos la variable del for que muestra los posts a 0
        //Mas tarde cuando el usuario pulse los botones siguiente o atras 
        //se ira modificando
        numeroEnLi = 0;
    }
    
    
    //Mostramos los primeros <li>, empezamos en 0 y el limite de PAGESIZE
    //Las variables nu
    var listaLi = '<ul class="listaLis"><li class="atras">Atras</li>';
        for (numeroEnLi ; numeroEnLi < tmpLi; numeroEnLi++){
            listaLi += '<li class="pagina">'+numeroEnLi+'</li>';
        }
            listaLi +='<li class="siguiente">Siguiente</li></ul>';
            //btn_navegacion.innerHTML = listaLi;
                $('#btn_navegacion').html(listaLi);
            
//fin cargarPost    
}


