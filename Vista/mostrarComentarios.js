

/**
 * Mostramos los comentarios
 * @param {type} data
 * 
 */

function cargarComentarios(data){
   
    $('.cont_post').hide();
    $('#totalResultados').hide();
    $('#buscar_datos').hide();
    $('#btn_navegacion').hide();
  
  
   
    $('#posts').append($("<section>",{
        id : 'contenedorComentariosPost'
        
    }).append($('<section>',{
                id : 'cabeceraComentarios',
                class : "cont_post"
            }).append($('<section>',{
                id : 'imgUsuarioPublica'
            }).append($('<figure>',{
                id : 'figuPublica'
            }).append($('<figcaption>',{
                id : 'figPublica',
                text : "Publicado por "+data[0][0][0]+ " desde "+data[0][0][1]+" ."
            })).append($('<img>',{
                src : "../datos_usuario/"+data[0][0][0]+"/"+data[0][0][0]+".jpg",
                alt : "Nombre del usuario que ha colgado el anuncio.",
                class : "imgPublica"
            }))))));
            
            $("#cabeceraComentarios").append($("<section>",{
                id: 'contDatosPost'
            }).append($("<h1>",{
                id: "tituloPostComent",
                text: data[0][0][3]
            })).append($("<section>",{
                id: 'comentario',
                text : data[0][0][4]
            })).append($("<section>",{
                id: 'piePostComentario'
            }).append($('<h2>',{
                id : 'h2TotalComentarios',
                text : 'Total Comentarios '+data[0][1]
            })).append($('<h3>',{
                id : "h3FechaPost",
                text : 'Fecha : '+data[0][0][2]
            }))))   
            
                   
    
    var i= 0;
    $.each( data, function( i, item  ) {
        if( i > 0){
        $('#cabeceraComentarios').after($('<section>',{
            id : 'mostrarComentarios',
            class : "cont_post"
        }).append($('<section>',{
                id : 'imgUsuarioComenta'
            }).append($('<figure>',{
                id : 'figuComenta'
            }).append($('<figcaption>',{
                id : 'figComenta',
                text : "Comentario de "+item.nombreComenta+" desde "+item.ciudadComentario
            })).append($('<img>',{
                src : "../datos_usuario/"+item.nombreComenta+"/"+item.nombreComenta+".jpg",
                alt : "Nombre del usuario que ha hecho el comentario.",
                title : "Comentado por "+item.nombreComenta+" .",
                class : "imgPublica"
            })))).append($("<section>",{
                id : "contDatosComentario"
            }).append($('<h2>',{
                id : 'h2Comenta',
                text : item.tituloComentario
            })).append($('<section>',{
                id : "verComentario",
                text : item.comentarioPost
            })).append($("<section>",{
                id : "piePostComentarios"
            }).append($("<h3>",{
                id : "FechaComentario",
                text : 'Fecha del comentario '+item.fechaComentario+"."
            })))) 
            );
        }
    });
    
        $('#posts').append($('<section>',{
            id : 'finComentarios' 
        }).append($('<input>',{
            type : 'button',
            id : 'btnSalirComentarios',
            value : 'Salir'
        })));
    

//fin cargarComentarios
}


/**
 * Salimos de comentarios
 * 
 */
function salirDeComentarios(){
    
    $('section').remove('#cabeceraComentarios');
    $('section').remove('#mostrarComentarios');
    $('section').remove('#finComentarios');
    
    $('#buscar_datos').show();
    $('.cont_post').show();
    $('#totalResultados').show();
    $('#btn_navegacion').show();
    cargarContenidoPorSeccion();
    
    
}
     