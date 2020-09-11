
function buscarComentarios(idPost, totalPost){
    
    
       $.ajax({
                    data: { idComentariosBuscar : idPost,
                            totalPost : totalPost
                           },
                    type: "POST",
                    dataType: 'json',
                    url: "../Controlador/Elementos_AJAX/buscarComentarios.php"
                }).complete(function( data, textStatus, jqXHR ) {
                     cargarComentarios(data);
                        
                })
    
    
    
   
    
    
    
    
    
//fin buscarComentarios    
}