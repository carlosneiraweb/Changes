


    
   
function insertarComentario(idPost){
     
    //Los datos se reciven perfectamente    
    idPostComentado = idPost;
   
    tituloComentario = $('#tituloComentario').val();
    comentario = $('#comentarioPost').val();
    //alert(idPostComentado+" "+tituloComentario+" "+comentario);
   
                $.ajax({
                    data: {tituloComentario : tituloComentario,
                           idPostComentado : idPostComentado,
                           comentario : comentario
                           },
                    type: "POST",
                    url: "../Controlador/Elementos_AJAX/subirComentarios.php"
                }).done(function( data, textStatus, jqXHR ) {
                   
                        if ( console && console.log ) {
                        //console.log( data);
                        if(data == 'true'){
                            $('#imgResultComentVerde').removeClass('oculto');
                            var tmp = parseInt($('#totalComentarios').text());
                            tmp++;
                            $('#totalComentarios').text(tmp);
                        }else{
                            $('#imgResultComentRojo').removeClass('oculto');
                        }
                }
                }).fail(function( jqXHR, textStatus, errorThrown ) {
                        if ( console && console.log ) {
                        console.log( "La solicitud a fallado: " + textStatus);
                }
                }); 
    
   //fin insertarComentario 
    }

