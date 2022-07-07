
    //Comprobamos que urlVolverError
    //esta instanciada con la url de la pagina 
    //en la que se ha producido el error.
    // Esta variable se instancia en el archivo mostrar_error.php
    if(typeof(urlVolverError) === "undefined"){ urlVolverError = 0; };  
    //alert('en redirecionar '+urlVolverError);
    
$(document).ready(function(){
    
   
   $('#volver_intentar').on('click', volverAnterior);
   $("#salirSlider").on('click', recargarPagina);
   $("#salirSesion").on('click', salirDeSesion);
   $("#registrar").on('click', redireccionarRegistrarse);
   $('#publicar').on('click', redireccionarSubirPost);
    
   function volverAnterior(){
      
       location.href= urlVolverError;
       //urlVolverError = null;
       //history.back();
   } 
   
    function redireccionarRegistrarse(){
       location.href = 'registrarse.php';
   }
   
    function redireccionarSubirPost(){
       
       location.href= 'subir_posts.php';
   }
   
   
   function recargarPagina(){
       location.reload();
   }
   
   function salirDeSesion(){
       location.href = "abandonar_sesion.php";
   }
   
});


