/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function(){
    
    //Validamos que solo se introducen números en el campo
    //precio cuando un uusuario sube un post
   $("#precio").keydown(function(event) {
       $(".error").remove();
   if(event.shiftKey)
   {
        
        $("#precio").focus().after("<span class='error'><p>Solo puedes introducir numeros</p></span>");
        $('#precio').addClass('borderColor');
        event.preventDefault();
   }
 
   if (event.keyCode == 46 || event.keyCode == 8)    {
   }
   else {
        if (event.keyCode < 95) {
          if (event.keyCode < 48 || event.keyCode > 57) {
                
                $("#precio").focus().after("<span class='error'><p>Solo puedes introducir numeros</p></span>");
                $('#precio').addClass('borderColor');
                event.preventDefault();
          }
        } 
        else {
              if (event.keyCode < 96 || event.keyCode > 105) {
                 
                  $("#precio").focus().after("<span class='error'><p>Solo puedes introducir numeros</p></span>");
                  $('#precio').addClass('borderColor');
                  event.preventDefault();
              }
        }
      }
   });
    
    
});


