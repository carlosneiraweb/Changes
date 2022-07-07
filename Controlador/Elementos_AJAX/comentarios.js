

function mostrarComentarios(id){

    $.ajax({
                    data: {
                           tituloComentario : tituloComentario,
                           idPostComentado : idPostComentado,
                           comentario : comentario
                           },
                    type: "POST",
                    url: "../Controlador/Elementos_AJAX/subirComentarios.php"
                }).done(function( data, textStatus, jqXHR ) {
                    alert(data);
                        if ( console && console.log ) {
                        console.log( "La solicitud se ha completado correctamente." );
                       
                }
                }).fail(function( jqXHR, textStatus, errorThrown ) {
                        if ( console && console.log ) {
                        console.log( "La solicitud a fallado: " + textStatus);
                }
                }); 
    
    
    
    
    
    
    
//fin mostrarComentarios    
}