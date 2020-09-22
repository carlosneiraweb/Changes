

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
                    url: "../Controlador/Elementos_AJAX/menuUsuario.php"
                }).done(function( data, textStatus, jqXHR ) {
                    
                   
                        
                });
     
    
    
//fin darseBajaDefinitivamente    
}


/**
 * 
 * Metodo que elimana todos los datos 
 * del usuario del portal
 * pero sige dejandando los Posts
 */
function darseBajaParcialmente(){
   
   
    
    
//fin     darseBajaParcialmente
}