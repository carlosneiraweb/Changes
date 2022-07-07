
/**
 * 
 * Redirige a abandonar sesion 
 * para eliminar todas las variables de sesion
 * El usuario ya no podra logearse
 */
function redirigirInicio(){
    
    setTimeout("location.href = 'abandonar_sesion.php'", 8000);  
}
/**
 * 
 * Metodo que da de baja totalmente
 * a un usuario. Elimina todo rastro 
 * del portal
 */
function darseBajaDefinitivamente(){
    $.ajax({
                    data: { opcion : 'Definitivamente'       
                           },
                    type: "POST",
                    dataType: 'json',
                    url: "../Controlador/Elementos_AJAX/darBajaUsuario.php"
                }).done(function( data ) {
                   var result = data.resultadoTotal;
                   
                   if(result == 'true'){
                       
                       $('#baja').empty();
                       $("#baja").append($('<h4>',{
                        text : 'Tú baja ha sido cursada correctamente.',
                        class : 'rsTotal'
                        })).append($('<h5>',{
                        text: 'Recuerda que puedes darte de alta cuando tú quieras',
                        class: 'rsTotal'
                        })).append($('<h5>',{
                        text: 'Vas a ser redirigido al inicio del portal',
                        class: 'rsTotal'
                    }));
                        
                        redirigirInicio();
                        
                    }     
                });
     
    
    
//fin darseBajaDefinitivamente    
}


/**
 * 
 * Metodo que bloquea al usuario
 * parcialmente. Sus post podran seguir siendo vistos
 * pero no podra loguearse
 */
function darseBajaParcialmente(){

    $.ajax({
                    data: { opcion : 'parcialmente'       
                           },
                    type: "POST",
                    dataType: 'json',
                    url: "../Controlador/Elementos_AJAX/darBajaUsuario.php"
                }).done(function( data ) {
                   var test = data.resultado;
                  
                   if(test === true){
                       $('#baja').empty();
                       $("#baja").append($('<h4>',{
                        text : 'Tú baja ha sido cursada correctamente.',
                        class : 'rsParcial'
                        })).append($('<h5>',{
                        text: 'Recuerda que para recuperar tú cuenta',
                        class: 'rsParcial'
                        })).append($('<h5>',{
                        text: 'deberás ponerte en contacto con el administrador.',       
                        class: 'rsParcial'
                    })).append($('<h5>',{
                        text: 'Vas a ser redirigido al inicio del portal',
                        class: 'rsParcial'
                    }));
                        
                        redirigirInicio();
                        
                   }
                        
                });
     
    
    
//fin     darseBajaParcialmente
}