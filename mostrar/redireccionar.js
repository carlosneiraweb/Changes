$(document).ready(function(){
    
   $('#publicar').on('click', redirecionar);
   $('#volver_intentar').on('click', volverAnterior);
   function redirecionar(){
       location.href= 'subir_posts.php';
   }
    
   function volverAnterior(){
      
       history.back();
   } 
    
});


