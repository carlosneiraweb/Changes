
$(document).ready(function(){
   

/**
 * @description 
 * Este metodo valida que el campo
 * titulo no esta vacio
 */
function validarTituloPost() {
  
    if($("#tituloSubirPost").val() === ""){
			$("#tituloSubirPost").focus().after("<span class='error'><p>Introduce un título para tú anuncio.</p></span>");
			$('#tituloSubirPost').addClass('borderColor');
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
			$("#comentarioSubirPost").focus().after("<span class='error'><p>Introduce un comentario para tu anuncio.</p></span>");
			$('#comentarioSubirPost').addClass('borderColor');
                        return false;
                    }else{
                        return true;
                    }
    
    
    
//fin validarComentarios    
}
 
    /**
     * @description 
     * Este metodo valida que en el campo
     * precio solo se  introducen digitos.
     * Lo hacemos descartando la tecla pulsada.
     */
   
   $("#precioSubirPost").keydown(function(event) {
       $(".error").remove();
   if(event.shiftKey)
   {
        
        $("#precioSubirPost").focus().after("<span class='error'><p>Solo puedes introducir numeros</p></span>");
        $('#precioSubirPost').addClass('borderColor');
        event.preventDefault();
   }
 
   if (event.keyCode == 46 || event.keyCode == 8)    {
   }
   else {
        if (event.keyCode < 95) {
          if (event.keyCode < 48 || event.keyCode > 57) {
                
                $("#precioSubirPost").focus().after("<span class='error'><p>Solo puedes introducir numeros</p></span>");
                $('#precioSubirPost').addClass('borderColor');
                event.preventDefault();
          }
        } 
        else {
              if (event.keyCode < 96 || event.keyCode > 105) {
                 
                  $("#precioSubirPost").focus().after("<span class='error'><p>Solo puedes introducir numeros</p></span>");
                  $('#precioSubirPost').addClass('borderColor');
                  event.preventDefault();
              }
        }
      }
  
});
    //*************************fin validar precio**********************//


$("#form_post_1").on('mouseover', '#primeroSubirPost',function(){
            if (validarTituloPost()) {
                if (validarComentarioPost()) {
                }
            }    
        
           
      });

$("#form_post").on('keypress', '#comentarioSubirPost',function(){
           $('.error').remove();
      });
$("#form_post").on('keypress', '#tituloSubirPost',function(){
           $('.error').remove();
      });


});