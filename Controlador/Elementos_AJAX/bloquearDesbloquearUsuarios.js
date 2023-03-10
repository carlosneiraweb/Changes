/**
 * @description 
 * Metodo que nos redirige <br/>
 * a la pagina mostrar error <br/>
 * en caso de ocurrir un error <br/>
 * en el proceso de bloquear un usuario
 * 
 */

function errorBloqueo() {
    
    $('#bloquearUsuarios').empty();
    location.href = "mostrar_error.php";
}




/**
 * Metodo que muestra los usuarios bloqueados </br>
 * @param {type} data array  </br>
 * Metodo que muestra los usarios bloqueados </b>
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
  
    if(data[0].length !== 0){
            var i = 0;
        for(i; i < data[0].length; i++ ){
            $("#bloqueadosTotal").append($("<p>",{
                html : data[0][i].bloqueadoTotal+'</br>'
            }));
        }
        
    }else{
        
        $("#bloqueadosTotal").append($("<p>",{
            html : 'No tienes usuarios bloqueados'
        }));
        
    }
        
    if(data[1].length !== 0){
         
        var i = 0;
        for(i; i < data[1].length; i++ ){
            $("#bloqueadosParcial").append($("<p>",{
                html : data[1][i].bloqueadoParcial+'</br>'
            }));
        }
        
    }else{
        
        $("#bloqueadosParcial").append($("<p>",{
            html : "No tienes usuarios bloqueados"
            }));
        
    }
    
    
    //mostrarUsuariosBloqueados
}



/**
 * Metodo que muestra el resultado </br>
 * al bloquear un usuario. </br>
 * @param {type} texto <br/>
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
 * Metodo que bloquea a los usuarios <br/>
 * @param {type} nickBloquear <br/>
 * @param {type} opc <br/>
 * @returns elementos HTML
 */

function bloquear(nickBloquear,opc){
    
 
    
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
                        texto = "Este usuario ya lo tienes bloqueado totalmente.";
                     }else if(test === "USUARIO_YA_BLOQUEADO_PARCIALMENTE"){
                        texto = "Este usuario ya lo tienes bloqueado parcialmente.";
                    }else if(test === 'OK'){
                        texto = "El usuario ha sido bloqueado";
                    
                    }else if(test === 'ERROR'){
                         
                        
                            errorBloqueo();
                    }

                    
                        mostrarResultados(texto);

            });
//fin bloquear                    
   
};


/**
 * Metodo que desbloquea a los usuarios <br/>   
 * @param {type} nickUsuDesbloquear <br/>
 * @param {type} total <br/>
 * @param {type} parcial <br/>
 * 
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
                    }else if(test === 'OK'){
                        texto = "El usuario ha sido desbloqueado.";
                    }else if(test === 'ERROR'){
                        errorBloqueo();
                    }
                        mostrarResultados(texto);

            });
    
//fin desbloquear    
}


/**
 * Metodo que muestra los usuarios bloqueados <br/>
 * y el tipo de bloqueo <br/>
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
                }).done(function(data){

                   // alert(data[0][0].bloqueadoTotal);
                    if(data === 'ERROR'){
                        errorBloqueo();
                    }else{
                       mostrarUsuariosBloqueados(data);
                    }
                    
                });
            
           
    
    
//verUsuariosBloqueados    
}
    



