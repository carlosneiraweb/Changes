/**
 * @author Carlos Neira Sanchez
 * @mail arj.123@hotmail.es
 * @telefono ""
 * @nameAndExt registrarse.js
 * @fecha 04-oct-2016
 */

    
    var  petGeReg, objGeReg, petProReg, objProReg, PGR, PPR;
            

                //Creamos una instancia de la clase CONEXION_AJAX
                //Nos devuelve una conexion AJAX y propiedades 
                    var ConRegistrarse  = new Conexion();
   
  

window.onload=function(){
    
   cargarPeticionRegistrarse('PGR', 'opcion=PG');
   cargarPeticionRegistrarse('PPR', 'opcion=PP'); 
  
function cargarPeticionRegistrarse(tipo, parametros){
//alert('Estamos en cargarPeticion y tipo vale: ' +tipo+ ' parametros vale: ' +parametros);
    //para comprobar el tipo de peticion
    switch(tipo){
        
        case('PGR'):
           petGeReg = ConRegistrarse.conection();
           petGeReg.onreadystatechange = procesaRespuestaRegistrarse;
           petGeReg.open('POST', "../Controlador/Elementos_AJAX/cargarElementos.php?", true);
           petGeReg.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
           petGeReg.send(parametros);
                break;   
        case('PPR'):
           petProReg = ConRegistrarse.conection();
           petProReg.onreadystatechange = procesaRespuestaRegistrarse;
           petProReg.open('POST', "../Controlador/Elementos_AJAX/cargarElementos.php?", true);
           petProReg.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
           petProReg.send(parametros);
                break;   
         
    //fin switch
    }
    
    function procesaRespuestaRegistrarse(){
      
       if(this.readyState === ConRegistrarse.READY_STATE_COMPLETE && this.status === 200){
            try{
                if(tipo === 'PGR'){
                    objGeReg = JSON.parse(petGeReg.responseText);
                     //Eliminamos el objeto conexion
                    delete ConRegistrarse;
                } else if(tipo === 'PPR'){
                    objProReg = JSON.parse(petProReg.responseText);
                     //Eliminamos el objeto conexion
                    delete ConRegistrarse;
                } 
                
            } catch(e){
                switch(tipo){        

                    default:
                       // location.href= 'mostrar_error.php';
                }
            //fin catch
            }
            
            switch (tipo){
                
                case 'PGR':
                    cargarGeneroRegistrarse(objGeReg);
                        break;
                 case 'PPR':
                    cargarProvinciasRegistrarse(objProReg);
                        break;
                        
            //fin switch
            }
        //fin if
        }
    //fin procesaRespuesta    
    }   
//fin cargarPeticion    
}
              
                 
/**
* Este metodo carga los combos de
* genero en el registro
 * @returns {undefined} */
function cargarGeneroRegistrarse(objGeneroRegistrarse){
   // alert(objGeneroRegistrarse);
    for(var i = 0; i < objGeneroRegistrarse.length; i++){
        var objTmpGeneroRegistrarse = objGeneroRegistrarse[i];
            $('#genero').append($('<option>',{
            text : objTmpGeneroRegistrarse.genero
        }));
    
    }
    
    //fin cargarGeneroRegistrarse
    }
    
/**
* Este metodo carga las provincias
* en el registro
 */    
function cargarProvinciasRegistrarse(objProvinciasRegistrarse){
    
     //alert(objProvinciasRegistrarse);
    for(var i = 0; i < objProvinciasRegistrarse.length; i++){
        var objTmpProvinciasRegistrarse = objProvinciasRegistrarse[i];
            $('#provincia').append($('<option>',{
            text : objTmpProvinciasRegistrarse.nombre
        }));
    
    }
    
    //fin cargarGeneroRegistrarse
}


 
    /**
     * @description
     * Al mover barra de scroll
     * desactivamos una capa oculta que impedia el uso del 
     * boton de  siguiente
     */
  
    $('#cuerpo').on('mouseover','#contenedorCondiciones',function(){
       
        $('#textAreaCondiciones').scroll(function(){   
            $('.capaBoton').addClass('oculto');
                //Activamos el botones
            $('#btnAceptarCondiciones').prop('disabled', ""); 
            $('#btnNoAceptarCondiciones').prop('disabled', ""); 
        });
    });  
    

 //fin onload   
}; 

/**
* @description 
* Metodo que carga la seccion
* donde  muestra las condiciones de uso del
* portal
* */
function agregarFormularioCondiciones(){
    
   
    $('header').after('<section id="verificarCondiciones" class="mostrar_formulario"></section>');
 
    $("#verificarCondiciones").append($('<h3>',{
            text : 'Lee detenidamente las condiciones'
    })).append($('<section >',{
            id : 'contenedorCondiciones'
    }).append($('<section>',{
            id : 'textAreaCondiciones'
    })));  
    //Agregamos las condiciones del registro
    $('#textAreaCondiciones').html('No debes usar nuestros Servicios de forma inadecuada. <br />'+
'No debes  acceder desde un método distinto a la interfaz y a las instrucciones proporcionadas. <br />'+
'Para publicar, opinar o usar cualquier otro servicio debes de estar registrado.'+'Nuestro servicio consiste en la inserción de anuncios en el portal por parte de usuarios registrados.'+ 
'No nos hacemos responsables del contenido publicado por nuestros usuarios.Este contenido es responsabilidad exclusiva de las personas físicas que lo publican. <br />'+
'El usuario es el único responsable del uso que se haga de su cuenta. Ten la contraseña en alta confidencialidad.Si detectas un uso de tú cuenta diferente del tuyo, ponte en contacto'+ 
' de inmediato con un administrador mandando un correo a esta dirección: administracion@xxxx.com. <br />'+
'Podemos usar o  compartir información personal con empresas, organizaciones o particulares que no tengan relación con nosotros. <br />'+
'Tienes que ser mayor de edad para usar este portal.  <br />'+
'No nos hacemos responsables de ninguna perdida, estafa, robo etc al usar este portal entre los usuarios del mismo.'+
'En ningún caso se responderá por otro tipo de daños, ya sean efectivos, indirectos, o de cualquier otro tipo, ni por el lucro que hubiera podido sufrir el anunciante.  <br />'+
'NO ESTAMOS obligados a revisar previamente el contenido de ningún Anuncio, y cualquier revisión o aprobación efectuada por nosotros.'+
'Nos reservamos el derecho de anular o eliminar cualquier anuncio que incumpla nuestras normas morales, legales o de uso del portal.'+
'No se publicarán Anuncios con números de teléfono de contacto de pago extra tipo 80x.  <br />');

    $('#contenedorCondiciones').after($('<section>',{
            id : 'contenedorBotonAceptarCondiciones'
    }).append($('<form>',{
            name : 'formAceptaCondiciones',
            action : 'registrarse.php',
            method : 'POST',
            id : 'formRegistroCondiciones'
    })));
    
    
    $('#formRegistroCondiciones').append($('<input>',{
            type : 'hidden',
            value : '5',
            name : 'step'
    })).append($('<input>',{
            type : 'submit',
            id  : 'btnAceptarCondiciones',
            value : 'aceptaCondiciones',
            name : 'aceptaCondicionesReg',
            disabled : 'disabled'
    })).append($('<input>',{
            type : 'submit',
            id  : 'btnNoAceptarCondiciones',
            value : 'noAceptaCondiciones',
            name : 'noAceptaCondicionesReg',
            disabled : 'disabled'
    }));
//    
    $('#contenedorBotonAceptarCondiciones').append($('<section>',{
            class : 'capaBoton'
    }));
     
     
    //fin agregarFormularioCondiciones
}



