

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
                    $("#sectionDefinitivo").remove();
                    $('#menuUsuario').on('click','#baja', darseBaja);
                }).append($('<input>',{
                    type : 'button',
                    id : 'btnSalirDefinitivo',
                    value : 'Cancelar'  
                })).on('click','#btnSalirDefinitivo', function(){
                    //$('#dejarPosts').show();
                    $("#definitivo").remove();
                    $("#sectionDefinitivo").remove();
                    $('#menuUsuario').on('click','#baja', darseBaja);
                   // $("#baja").on('click','#definitivo', bajaDefinitiva);
                }));  
        
        });
         
       /*
        $('#baja').append($('<h5>',{
            text : 'Dejar tus Posts y darse de baja.',
            id : 'dejarPosts'
        })).on('click','#dejarPosts', function bajaDejandoPosts(){
                $('#baja').off('click','#dejarPosts', bajaDejandoPosts); 
                $('#definitivo').hide();
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
                   // $('#baja').off('click','#btnParcial', bajaParcial); 
                    darseBajaParcialmente();
                }).append($('<input>',{
                    type : 'button',
                    id : 'btnSalirParcial',
                    value : 'Cancelar'  
                })).on('click','#btnSalirParcial', function(){
                    $("#definitivo").show();
                    $('#sectionParcial').remove();
                    $("#baja").on('click','#dejarPosts', bajaDejandoPosts);
                }));  
     
     
       
    });
           
       */
        
    }).append($('<section>',{
        id : 'cambioDatos',
        class : "cont_post",    
    }).append($('<h4>',{
        text : 'CambiarDatos'
    }))).on('click','#cambioDatos', function(){
        
        
        
    }).append($('<section>',{
        id : 'bloquearUsuarios',
        class : "cont_post",    
    }).append($('<h4>',{
        text : 'BloquearUsuarios'
    }))).on('click','#bloquearUsuarios', function bloquearUsuarios(){
        
        $('#bloquearUsuarios').append($('<h5>',{
            text : 'Bloqueo Total el usuario no verá tus posts.',
            id : 'bloqueoTotal'
        })).append($('<h5>',{
            text : 'Bloqueo Parcial, no podrá comentar tus Posts.',
            id : 'bloqueoParcial'
        }));
    
    $('#menuUsuario').off('click','#bloquearUsuarios', bloquearUsuarios);  
        
    }).append($('<section>',{
        id : 'modificarPost',
        class : "cont_post",    
    }).append($('<h4>',{
        text : 'Modificar tus Posts'
    }))).on('click','#modificarPost', function(){
        
        
        
        
    })
    );
    
    
    
    
    
    
//fin mostrarMenu   
}
    