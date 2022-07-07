
function buscarComentarios(idPost, totalComent){
    
    //alert('idComentariosBuscar'+idPost+'totalComent'+totalComent);
       $.ajax({
                    data: { idComentariosBuscar : idPost,
                            totalComent : totalComent
                           },
                    type: "POST",
                    dataType: 'json',
                    url: "../Controlador/Elementos_AJAX/buscarComentarios.php"
                }).done(function( data, textStatus, jqXHR ) {
                    //console.log(data);
                   // alert(data[0][0][1]);
                     cargarComentarios(data);
                        
                });
    
    
    
   
    
    
    
    
    
//fin buscarComentarios    
}