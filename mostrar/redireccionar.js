$(document).ready(function(){
    
   $('#publicar').on('click', redireccionarSubirPost);
   $('#volver_intentar').on('click', volverAnterior);
   $("#registrar").on('click', redireccionarRegistrarse);
   function redireccionarSubirPost(){
       location.href= 'subir_posts.php';
   }
    
   function volverAnterior(){
      
       history.back();
   } 
   
   function redireccionarRegistrarse(){
       location.href = 'register.php';
   }
   
   
});


