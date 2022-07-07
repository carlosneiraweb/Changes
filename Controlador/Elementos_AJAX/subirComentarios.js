
function redirigirInicioComentario(){
    
    setTimeout("location.href = 'index.php'", 1000);  
}
    
    
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
                    dataType: 'JSON',   
                    url: "../Controlador/Elementos_AJAX/subirComentarios.php"
                }).done(function(data) {
                    var test = data.res;
                    
                        if ( console && console.log ) {
                        //console.log( data);
                        if(test === true){
                            $('#imgResultComentVerde').removeClass('oculto');
                            $("#btn_mandar_comentario").addClass('oculto');
                            var tmp = parseInt($('#totalComentarios').text());
                            tmp++;
                            $('#totalComentarios').text(tmp);
                            $('#cuerpo').on('click','#btn_salir_comentario',function(){
                            $("#tituloComentario").val("");
                            $("#comentarioPost").val("");
                              
                              $("#btn_mandar_comentario").removeClass('oculto');
                              cargarContenidoPorSeccion();
                              //redirigirInicioComentario();  
                            });
                            
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

