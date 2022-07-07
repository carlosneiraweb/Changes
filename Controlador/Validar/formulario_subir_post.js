
$(document).ready(function(){
   

/**
 * @description 
 * Este metodo valida que el campo
 * titulo no esta vacio
 */
function validarTituloPost() {
     
    if($("#tituloSubirPost").val() === ""){
        
			$("#tituloSubirPost").focus();
                        $("label[for='tituloSubirPost']").css('color', 'red');
                            return false;
                    }else{
                            return true;
                    }    
//fin validarComentarios    
}

/**
 * @description 
 * Este metodo valida que el campo
 * comentario @description subir un Post no esta vacio
 */
function validarComentarioPost() {
  
    if($("#comentarioSubirPost").val() === ""){
			$("#comentarioSubirPost").focus();
			$("label[for='comentarioSubirPost']").css('color', 'red');
                            return false;
                    }else{
                            return true;
                    }
    
    
    
//fin validarComentarios    
}
 
/**
 * Metodo valida precio no sea
 * un campo vacio
 */

function validoPrecioPost(){
     if($("#precioSubirPost").val() === ""){
			$("#precioSubirPost").focus();
			$("label[for='precioSubirPost']").css('color', 'red');
                            return false;
                    }else{
                            return true;
                    }      
}
 
 
 
    /**
     * @description 
     * Este metodo valida que en el campo
     * precio solo se  introducen digitos.
     * Lo hacemos descartando la tecla pulsada.
     */
   
   $("#precioSubirPost").keydown(function(event) {
        
   if(event.shiftKey)
   {
        
        $("#precioSubirPost").focus();
        $("label[for='precioSubirPost']").css('color', 'red');
           
        event.preventDefault();
   }
 
   if (event.keyCode === 46 || event.keyCode === 8)    {
   }
   else {
        if (event.keyCode < 95) {
          if (event.keyCode < 48 || event.keyCode > 57) {
                
                $("#precioSubirPost");
                $("label[for='precioSubirPost']").css('color', 'red');
                event.preventDefault();
          }
        } 
        else {
              if (event.keyCode < 96 || event.keyCode > 105) {
                 
                  $("#precioSubirPost").focus();
                  $("label[for='precioSubirPost']").css('color', 'red');
                  event.preventDefault();
              }
        }
      }
  
});
    //*************************fin validar precio**********************//


$("#form_post_1").on('mouseover', '#primeroSubirPost',function(){
            if (validarTituloPost()) {
                if (validarComentarioPost()) {
                    if(validoPrecioPost()){
                        
                    }
                }
            }    
        
           
      });
      
      
            /**
             * Metodo que cambia color de la label
             * al no dar por buena la validacion
             * 
             */
            
         
            $('#tituloSubirPost').keydown(function() { 
            $(this).prev().prev().css('color', 'black');
            });

            
       
            $('#tituloSubirPost').blur(function() { 
            $(this).prev().prev().css('color', '#0C0792');
            });
         
           
            $('#comentarioSubirPost').keydown(function() { 
            $(this).prev().prev().css('color', 'black');
            });

            
       
            $('#comentarioSubirPost').blur(function() { 
            $(this).prev().prev().css('color', '#0C0792');
            });
           
            
            $("#precioSubirPost").keydown(function() { 
                //alert('oooo');
                $("label[for='precioSubirPost']").css('color', 'black');
            });
         
            $('#precioSubirPost').blur(function() { 
            $(this).prev().prev().css('color', '#0C0792');
            });
         
           
            
     
});