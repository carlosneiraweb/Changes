
/**
 * 
 * Redirige a abandonar sesion <br/>
 * para eliminar todas las variables de sesion <br/>
 * El usuario ya no podra logearse <br/>
 */
function redirigirInicio(){
    
    location.href = 'abandonar_sesion.php';  
}
/**
 * 
 * Metodo que da de baja totalmente <br/>
 * a un usuario. Elimina todo rastro <br/>
 * del portal.
 */
function darseBajaDefinitivamente(){
    
    
    $.ajax({
                data: { opcion : 'Definitivamente'       
                },
                    type: "POST",
                    dataType: 'json',
                    url: "../Controlador/Elementos_AJAX/darBajaUsuario.php"
                }).done(function( data ) {
                   var test = data;
                 
                   if(test === "OK"){

                        $('#baja').empty();
                            $('#baja').append($('<h4>',{
                                text : 'Tú baja ha sido cursada correctamente.',
                                class : 'rsTotal'
                            })).append($('<h5>',{
                                text: 'Recuerda que puedes darte de alta cuando tú quieras',
                                class: 'rsTotal'
                            })).append($('<h5>',{
                                text: 'Vas a ser redirigido al inicio del portal',
                                class: 'rsTotal'
                            
                           
                    }));
                        
                         setTimeout(redirigirInicio,5000);
                        
                    } 
                    
                    
                });
     
    
    
//fin darseBajaDefinitivamente    
}


/**
 * 
 * Metodo que bloquea al usuario <br/>
 * parcialmente. Sus post podran seguir siendo vistos <br/>
 * pero no podra loguearse.
 * 
 */
function darseBajaParcialmente(){

    $.ajax({
                    data: { opcion : 'parcialmente'       
                           },
                    type: "POST",
                    dataType: 'json',
                    url: "../Controlador/Elementos_AJAX/darBajaUsuario.php"
                }).done(function( data ) {
                   var test = data;
                  alert("test dice "+test);
                   if(test === "OK"){
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
                        
                         setTimeout(redirigirInicio,5000);
                        
                   }
                        
                });
     
    
    
//fin     darseBajaParcialmente
}