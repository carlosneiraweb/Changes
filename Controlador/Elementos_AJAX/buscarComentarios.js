
function buscarComentarios(idPost, totalPost){
    
    
       $.ajax({
                    data: { idComentariosBuscar : idPost,
                            totalPost : totalPost
                           },
                    type: "POST",
                    dataType: 'json',
                    url: "../Controlador/Elementos_AJAX/buscarComentarios.php"
                }).done(function( data, textStatus, jqXHR ) {
                        if ( console && console.log ) {
                            
                            cargarComentarios(data);
                            
                            
//                        console.log( "La solicitud se ha completado correctamente." );
//                        if(data == 'true'){
//                            $('#imgResultComentVerde').removeClass('oculto');
//                        }else{
//                            $('#imgResultComentRojo').removeClass('oculto');
//                        }
                }
                }).fail(function( jqXHR, textStatus, errorThrown ) {
                        if ( console && console.log ) {
                        console.log( "La solicitud a fallado: " + textStatus);
                }
                }); 
    
    
    
    
   
    
    
    
    
    
//fin buscarComentarios    
}