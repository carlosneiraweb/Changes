
function buscarComentarios(idPost, totalComent){
    
    
       $.ajax({
                    data: { idComentariosBuscar : idPost,
                            totalComent : totalComent
                           },
                    type: "POST",
                    dataType: 'json',
                    url: "../Controlador/Elementos_AJAX/buscarComentarios.php"
                }).done(function( data, textStatus, jqXHR ) {
                    //console.log(data);
                    //alert(data.length);
                     cargarComentarios(data);
                        
                });
    
    
    
   
    
    
    
    
    
//fin buscarComentarios    
}