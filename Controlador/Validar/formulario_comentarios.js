




$(document).ready(function(){
    
    
    //salimos del formulario
    $('#btn_salir_comentario').click(function(){
        $('#form_comentario').addClass('oculto');
        $('#ocultar').addClass('oculto');
        $('#imgResultComentVerde').addClass('oculto');
        $('#imgResultComentRojo').addClass('oculto');
        cargarContenidoPorSeccion();
    });
    
    //$("#btn_mandar_comentario").attr('disabled', 'disabled');   

            /**
            * @description Validamos el titulo
            *  del comentario
            */                                               
            function validarTitulo(){
                
		//Comprobamos que los campos no estan vacios
                    if($('#tituloComentario').val() === ""){
                        $('#tituloComentario').focus();
                        $("label[for='tituloComentario']").css('color', 'red');//addClass("errorPHP");
			
                        return false;
                    } else{ 
                        return true;
                    }
        //fin validarComentario
            }
            
            
            /**
            * @description Validamos el comentario
            *  del comentario
            */                                               
            function validarComentario(){
                
		//Comprobamos que los campos no estan vacios
                    if($('#comentarioPost').val() === ""){
                        $('#comentarioPost').focus();
                        $("label[for='comentarioPost']").css('color', 'red');//addClass("errorPHP");
			
                        return false;
                    } else{ 
                        return true;
                    }
        //fin validarComentario
            }
            
        $('#btn_mandar_comentario').on('mouseover',function(){
          
            if(validarTitulo() && validarComentario()){
              
             // $("#btn_mandar_comentario").removeAttr('disabled');
           }

        });
   

        /**
             * Metodo que cambia color de la label
             * al no dar por buena la validacion
             * 
             */
         
            $('#tituloComentario').keydown(function() { 
                $(this).prev().css('color', 'black');
            });
            $('#comentarioPost').keydown(function() { 
                $(this).prev().prev().css('color', 'black');
            });
       
            /**
             * Metodo que cambia al
             * color original la label
             */
       
            $('#tituloComentario').blur(function() { 
               
                $(this).prev().css('color','#0C0792' );//
               
            });
            $('#comentarioPost').blur(function() { 
               
                $(this).prev().prev().css('color','#0C0792' );//
              
            });
            
            
    
    
});           
    
   