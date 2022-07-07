

 var it = 0;
/**
 * Muestra un h5 en caso que
 * el script actualizarDatos.php
 * de algún error
 * 
 */
function mostrarErrorModificarUsuPHP(){
  
    $('#errorPedirDatos').remove();
    $('#formPedirDatosActualizar').append($('<h5>',{
        html : 'Lo sentimos pero por alguna causa. '+'<br/>'+
        'No podemos realizar'+'<br/>'+
        ' en este momento tú solicitud.'
                    }));
    
    
//mostrarErrorModificarUsuPHP    
}

/**
 * 
 * Actualizamos los datos del usuario
 * Instanciaremos una variable de sesion
 * con los viejos datos que tenia el usuario
 * para ir mostrandolos en todos los pasos.
 */
function actualizarDatosUsuario(){
  
    $.ajax({
                    data: { actualizarUsu : 'usuActualizar',
                            opcion : 'recuperar'
                            
                           },
                    type: "POST",
                    dataType: 'JSON',   
                    url: "../Controlador/Elementos_AJAX/actualizarDatos.php"
                }).done(function( data) {
                     
                    var respuesta = data.respuesta;
                    //alert(respuesta);
                        if( respuesta === 'OK'){
                             //location.href = 'registrarse.php';
                            $(location).attr('href','registrarse.php');     

                        } else if(respuesta === 'DOWN'){
                            
                            if(it == 0){
                                //LLama metodo mismo archivo
                                 mostrarErrorModificarUsuPHP();
                            }
                        it++;   
                            
                        }
                        
                        
                    }).fail(function(){
                        
                        if(it == 0){
                                //actualizarDatos
                                 mostrarErrorModificarUsuPHP();
                            }
                        it++;  
                        
                });
//fin   actualizarDatosUsuario() 
};
                        
/**
 * Pedimos viejo password
 * y el correo electronico 
 * para validar el usuario
 * antes de hacer cualquier 
 * update de sus datos
 */                        
                        
function pedirDatosParaActualizar(correo, pass){
   
    
    $.ajax({
                    data: { correo : correo,
                            pass : pass,
                            opcion : 'comprobar'
                            
                            
                           },
                    type: "POST",
                    dataType: 'JSON',   
                    url: "../Controlador/Elementos_AJAX/actualizarDatos.php"
                }).done(function( data) {
                   // alert(data.result);
                    if(data.result === '11'){
                        //LLama metodo del mismo archivo
                        actualizarDatosUsuario();
                        
                    }else{
                        $("#errorPedirDatos").remove();
                        $('#formPedirDatosActualizar').append($('<h5>',{
                            id : 'errorPedirDatos',
                            html : 'Has cometido un error. '+'<br/>'+
                                    'Revisa los datos introducidos.'
                                    
                        }));
                    }
   
                    });
    

//pedirDatosParaActualizar   
}

