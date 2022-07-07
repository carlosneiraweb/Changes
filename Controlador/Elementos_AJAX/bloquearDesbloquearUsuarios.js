/**
 * 
 * Prueba git
 */

/**
 * Metodo que muestra los usuarios bloqueados</br>
 * @param {type} data array  </br>
 * Metodo que muestra los usarios bloqueados</b>
 * por el usuario 
 *  @returns {html}
 */

function mostrarUsuariosBloqueados(data){

    var cont = $("<section>",{
        id : 'contMostrarBloqueados'});
    $("h4.desbloquear").before(cont);
    
    $("#contMostrarBloqueados").append($("<section>",{
        id : 'bloqueadosTotal'
    }).append($("<h3>",{
        text : 'Usuarios bloqueados Totalmente' 
    }))).append($("<section>",{
        id : 'bloqueadosParcial'
    }).append($("<h3>",{
        text  : 'Usuarios bloqueados Parcialmente'
    })));
    
    if(data !== 'NO_BLOQUEADOS'){
            var i = 0;
        for(i; i < data[0].length; i++ ){
            $("#bloqueadosTotal").append($("<p>",{
                html : data[0][i]+'</br>'
            }));
        }

         var i = 0;
        for(i; i < data[1].length; i++ ){
            $("#bloqueadosParcial").append($("<p>",{
                html : data[1][i]+'</br>'
            }));
        }
    }else{
        $("#bloqueadosTotal").append($("<p>",{
            html : 'No tienes usuarios bloqueados'
        }));
        $("#bloqueadosParcial").append($("<p>",{
            html : "No tienes usuarios bloqueados"
            }));
        
    }
    //mostrarUsuariosBloqueados
}



/**
 * Metodo que muestra el resultado</br>
 * al bloquear un usuario.</br>
 * @param {type} texto
 * variable con el texto a mostrar
 * @returns {html}
 */


function mostrarResultados(texto){
    
    
    $('#bloquearUsuarios').empty();
                    $('#bloquearUsuarios').append($('<h5>',{
                        text : texto,
                        class : 'bloqueoTotal'
                    })).append($('<section>',{
                        id: 'botonRecargar'
                    }).append($('<input>',{
                        type : 'button',
                        id : 'bRecargar',
                        value : 'Aceptar'
                    }))).on('click','#bRecargar',function(){
                    
                        $('#menuUsuario').remove();
                        mostrarMenu();
                        
                    });
                        
//fin mostrarResultados    
    
}



/**
 * Metodo que bloquea a los usuarios
 * @param {type} nickBloquear
 * @param {type} opc
 * @returns elementos HTML
 */

function bloquear(nickBloquear,opc){
    
 //alert(nickBloquear + opc);
    
   $.ajax({
                    data: { 
                        
                        nickBloquear : nickBloquear,
                        opcion : opc 
                            
                           },
                    type: "POST",
                    dataType: 'JSON',   
                    url: "../Controlador/Elementos_AJAX/bloquearUsuarios.php"
                }).done(function( data) {
                    var test = data;
                    var texto;
                    if(test === "NO_EXISTE_USUARIO"){
                        texto = "Parece ser que el usuario introducido no existe.";
                    }else if(test === "YA_BLOQUEADO_TOTAL"){
                        texto = "Ya tienes bloqueado a este usuario totalmente.";
                     }else if(test === "USUARIO_YA_BLOQUEADO_PARCIALMENTE"){
                        texto = "Este usuario ya lo tienes bloqueado parcialmente.";
                    }else if(test === 'OK'){
                        texto = "El usuario ha sido bloqueado";
                    }else if(test === 'NO_OK'){
                        texto = "Hemos tenido un pequeño problema </br>"+
                                "Intentalo más tarde.";
                    }
                    
                        mostrarResultados(texto);

            });
//fin bloquear                    
   
};


/**
 * Metodo que desbloquea a los usuarios
 * @param {type} nickUsuDesbloquear
 * @param {type} total
 * @param {type} parcial
 * @returns {undefined}
 */
function desbloquearUsuarios(nickUsuDesbloquear,total,parcial){
    
   // alert(nickUsuDesbloquear+total+parcial);
    
     $.ajax({
                    data: { 
                        
                        nickDesbloquear : nickUsuDesbloquear,
                        total : total,
                        parcial : parcial,
                        opcion : 'desbloquear'
                            
                           },
                    type: "POST",
                    dataType: 'JSON',   
                    url: "../Controlador/Elementos_AJAX/bloquearUsuarios.php"
                }).done(function( data) {
                    var test = data;
                   
                    var texto;
                    
                    if(test === "NO_EXISTE_USUARIO"){
                        texto = "Parece ser que el usuario introducido no existe.";
                    }else if(test ==="NO_BLOQUEADO_TOTAL"){
                        texto = "Este usuario no estaba bloqueado total.";
                    }else if(test ==="NO_BLOQUEADO_PARCIAL"){
                        texto = "Este usuario no estaba bloqueado parcialmente.";
                    }else if(test === "NO_SELECCION_BLOQUEO"){
                        texto = "No has selecionado ninguna opción.";
                    }else if(test === 'OK'){
                        texto = "El usuario ha sido desbloqueado.";
                    }else if(test ==="USUARIO_NO_BLOQUEADO"){
                        texto = "No tenías bloqueado este usuario.";
                    }else if(test === 'NO_OK'){
                        texto = "Hemos tenido un pequeño problema </br>"+
                                "Intentalo más tarde.";
                    }
                        mostrarResultados(texto);

            });
    
//fin desbloquear    
}


/**
 * Metodo que muestra los usuarios bloqueados
 * y el tipo de bloqueo
 * @returns muestra html
 */

function verUsuariosBloqueados(){
    
    $.ajax({
                    data: { 
                        
                        opcion : 'mostrarBloqueos'
                       
                            
                           },
                    type: "POST",
                    dataType: 'JSON',   
                    url: "../Controlador/Elementos_AJAX/bloquearUsuarios.php"
                }).done(function( data) {

                  
                    if(data[0] !== 'NO_BLOQUEADOS' && data[1] !== 'NO_BLOQUEADOS'){
                        mostrarUsuariosBloqueados(data);
                    }else{
                       mostrarUsuariosBloqueados('NO_BLOQUEADOS');
                    }
                    
                }
    
    )
    
//verUsuariosBloqueados    
}
    



