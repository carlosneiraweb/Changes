



function mostrarMenu(){
    
    $('.cont_post').hide();
    $('#totalResultados').hide();
    $('#buscar_datos').hide();
    $('#btn_navegacion').hide();
    
    
    $('#posts').append($('<section>',{
        id: 'menuUsuario',
        class : "cont_post"
    }).append($('<section>',{
        id : 'baja',
        class : "cont_post"    
    }).append($('<h4>',{
        text : 'Darse de Baja'
    }))).on('click','#baja', function darseBaja(){
        $('#menuUsuario').off('click','#baja', darseBaja);
        $('#baja').append($('<h5>',{
            text : 'Darse de baja definitivamente.',
            id : 'definitivo'
        })).on('click','#definitivo', function bajaDefinitiva(){
                $('#baja').off('click','#definitivo', bajaDefinitiva);
                //$('#dejarPosts').hide();
                $('#sectionDefinitivo').remove();
                $('#baja').append($('<section>',{
                    id : 'sectionDefinitivo',
                    html : 'Este proceso puede llevarnos'+'<br/>'+
                           ' un par de dias para que sea visible '+'<br/>'+
                           ' sus resultados.'+'<br/>'+
                           ' No te asustes si ves que no se realiza '+'<br/>'+
                           ' inmediatamanente.'+'<br/>'+
                           ' Gracias por comprendernos.'+'<br/>'+
                           ' Recibirás un email de nuestro equipo. '+'<br/>'
                           
                }).append($('<input>',{
                    type : 'button',
                    value : 'Aceptar',
                    id : 'btnDefinitivo'
                })).on('click', '#btnDefinitivo',function(){
                    darseBajaDefinitivamente();
                    $("#definitivo").remove();
                    $('#dejarPosts').remove();
                    $("#sectionDefinitivo").remove();
                    $('#menuUsuario').off('click','#baja', darseBaja);
                     //redirigimos al index
                   
                }).append($('<input>',{
                    type : 'button',
                    id : 'btnSalirDefinitivo',
                    value : 'Cancelar'  
                })).on('click','#btnSalirDefinitivo', function(){
                           
                      $('#sectionDefinitivo').remove();
//                    $('#dejarPosts').show();
                    $("#definitivo").remove();
                    $('#dejarPosts').remove();
                    
                    $('#menuUsuario').on('click','#baja', darseBaja);
                    $("#baja").on('click','#definitivo', bajaDefinitiva);
                     
                   
                }));  
        
        });
         
       
        $('#baja').append($('<h5>',{
            text : 'Dejar tus Posts y darse de baja.',
            id : 'dejarPosts'
        })).on('click','#dejarPosts', function bajaDejandoPosts(){
                $('#baja').off('click','#dejarPosts', bajaDejandoPosts); 
                $('#sectionParcial').remove();
                $('#baja').append($('<section>',{
                    id : 'sectionParcial',
                    html : 'De esta forma dejarás tus Posts'+'<br/>'+
                           ' la gente podrá seguir viendolos y podrá '+'<br/>'+
                           ' seguir poniendose en contacto contigo. '+'<br/>'
                    
                }).append($('<input>',{
                    type : 'button',
                    value : 'Aceptar',
                    id : 'btnParcial'
                })).on('click', '#btnParcial',function (){
                    $("#definitivo").remove();
                    $('#dejarPosts').remove();
                    $("#sectionParcial").remove();
                   // $('#baja').off('click','#btnParcial', bajaParcial); 
                    darseBajaParcialmente();
                    //location.href = 'abandonar_sesion.php';
                }).append($('<input>',{
                    type : 'button',
                    id : 'btnSalirParcial',
                    value : 'Cancelar'  
                })).on('click','#btnSalirParcial', function(){
                    $('#menuUsuario').on('click','#baja', darseBaja);
                    $("#menuUsuario").on('click','#dejarPosts', bajaDejandoPosts);
                    //$("#definitivo").show();
                    $("#definitivo").remove();
                    $('#dejarPosts').remove();
                    $('#sectionParcial').remove();
                    
                }));  
     
     
       
    });
           
      
        
    }).append($('<section>',{
        id : 'cambioDatos',
        class : "cont_post",    
    }).append($('<h4>',{
        text : 'CambiarDatos'
    }))).on('click','#cambioDatos', function actualizar(){
        $('#menuUsuario').off('click','#cambioDatos', actualizar);
        
        $('#cambioDatos').append($('<section>',{
        id : 'formPedirDatosActualizar'
    }).append($('<p>',{
       id: 'pedirCorreo',
       text : 'Debes introducir tú correo'
    })).append($('<input>',{
        type : 'text',
        id : 'correoActualizar',
        class : 'border'
    })).append($('<p>',{
        id: 'pedirPassword',
        text : 'Debes introducir tú viejo password'
    })).append($('<input>',{
        type: 'password',
        id: 'passActualizar'
    })).append($('<section>',{
        id : 'btnPedirActualizar'
    }).append($('<input>',{
        type : 'button',
        id : 'bActualizar',
        value : 'Aceptar'
    })).on('click','#bActualizar',function(){
            var correo = $('#correoActualizar').val();
            var pass = $('#passActualizar').val();
            //Llama metodo del archivo actualizarDatos.js
            pedirDatosParaActualizar(correo,pass);  
    }).append($('<input>',{
        type : 'button',
        id : 'bActualizarSalir',
        value : 'Salir'
    })).on('click','#bActualizarSalir',function(){
        $('#formPedirDatosActualizar').remove();
        $('#menuUsuario').on('click','#cambioDatos', actualizar);
        
    })));

    }).append($('<section>',{
        id : 'bloquearUsuarios',
        class : "cont_post"    
    }).append($('<h4>',{
        text : 'Bloquear Usuarios'
    }))).on('click','#bloquearUsuarios', function bloquearUsuarios(){
        $('#menuUsuario').off('click','#bloquearUsuarios', bloquearUsuarios); 
        $('#bloquearUsuarios').append($('<h5>',{
            text : 'Bloqueo Total el usuario no verá tus posts.',
            class : 'bloqueoTotal'
        })).append($('<h4>',{
            text : "Introduce su nick.",
            class : 'bloqueoTotal'
        })).append($('<input>',{
            type : 'text',
            id : 'inputTotal',
            class : 'border'
        })).append($('<section>',{
            id: 'botonTotal'
        }).append($('<input>',{
            type : 'button',
            id : 'bTotal',
            value : 'Aceptar'
        }))).on('click','#bTotal',function(){
            var nickBloquear = $('#inputTotal').val();
           //Llama al metodo del archivo bloquearUsuarios
           bloquear(nickBloquear, "bloqueoTotal");  
        }) .append($('<h5>',{
            text : 'Bloqueo Parcial, no podrá comentar tus Posts.',
            id : 'bloqueoParcial'
        })).append($('<h4>',{
            text : "Introduce su nick.",
            class : 'bloqueoParcial'
        })).append($('<input>',{
            type : 'text',
            class : 'border',
            id : 'inputParcial'
        })).append($('<section>',{
            id: 'botonParcial'
        }).append($('<input>',{
            type : 'button',
            id : 'bParcial',
            value : 'Aceptar'
        }))).on("click","#bParcial",function(){
            var nickBloquear = $("#inputParcial").val();
            bloquear(nickBloquear,"bloqueoParcial");
        }).append($('<h5>',{
            text : "Desbloquear un usuario.",
            id : 'desbloquear'
        })).append($('<h4>',{
            text : 'Introduce su nick',
            class : 'desbloquear'
        })).append($("<input>",{
            type : 'text',
            class : 'border',
            id : 'nickDesbloqueo'
        })).append($("<section>",{
            id : 'contDesbloqueo'
        }).append($("<span>",{
            id : 'desTotal',
            text : 'Desbloqueo Total'
        }).append($("<input>",{
            type : 'checkbox',
            class : 'desCheck',
            id : 'desBloqueoTotal'
        }))).append($("<span>",{
            id : 'desParcial',
            text : 'Desbloqueo Parcial'
        }).append($("<input>",{
            type : 'checkbox',
            class : 'desCheck',
            id : 'desBloqueoParcial'
        })))).append($("<section>",{
             id : 'contBtnDesbloqueo'
        }).append($('<input>',{
            type : 'button',
            id : 'bDesbloquear',
            value : 'Desbloquear'
        })).on("click","#bDesbloquear",function(){
            var nickDesbloquear = $("#nickDesbloqueo").val();
            var total = $("#desBloqueoTotal").prop('checked');
            var parcial = $("#desBloqueoParcial").prop('checked');
            desbloquearUsuarios(nickDesbloquear,total,parcial);
        }).append($('<input>',{
            type : 'button',
            id : 'bVerBloqueos',
            value : 'Ver Bloqueos'
        })).on("click","#bVerBloqueos",function verBloqueos(){
            $('#contBtnDesbloqueo').off('click','#bVerBloqueos', verBloqueos); 
            verUsuariosBloqueados();
        })).append($('<section>',{
            id: 'salirBloqueo'
        }).append($('<input>',{
            type : 'button',
            value : 'Salir',
            id: 'salirBloqueo'
        })).on('click','#salirBloqueo',function(){
            $('#bloquearUsuarios').empty();
            $('#bloquearUsuarios').append($('<h4>',{
                text : 'BloquearUsuarios'
                }));
            $('#menuUsuario').on('click','#bloquearUsuarios', bloquearUsuarios);
           // $('#menuUsuario').on('click','#bVerBloqueos', verBloqueos);
        }));
    
    //
    //$('#menuUsuario').off('click','#bloquearUsuarios', bloquearUsuarios); 
    
    
    
        
    }).append($('<section>',{
        id : 'modificarPost',
        class : "cont_post",    
    }).append($('<h4>',{
        text : 'Modificar tus Posts'
    }))).on('click','#modificarPost', function(){
        
        /////////////////
        //////////////
        
        
    }).append($('<section>',{
        id : 'salirMenu',
        class : "cont_post"
    }).append($('<input>',{
        type : 'button',
        id : 'bSalirMenu',
        value : 'Aceptar'
    })).on('click','#bSalirMenu',function(){
        $('#menuUsuario').fadeOut(500, function(){ $(this).remove();});   
        //$("#menuUsuario").remove();
        $('.cont_post').fadeIn(700, function(){ $(this).show();});
        $('#totalResultados').fadeIn(700, function(){ $(this).show();});
        $('#buscar_datos').fadeIn(700, function(){ $(this).show();});
        $('#btn_navegacion').fadeIn(700, function(){ $(this).show();});
        cargarContenidoPorSeccion();
    })));
   
    
    
    
 
   
    
    
//fin mostrarMenu   
}
    