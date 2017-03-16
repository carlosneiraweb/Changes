
/**
 * @author Carlos Neira Sanchez
 * @mail arj.123@hotmail.es
 * @telefono ""
 * @nameAndExt busquedas.php
 * @fecha 04-oct-2016
 */




var objLastImg, petLastImg, idPost, petImgEliminar, objImgEliminar, imgCargar;

      //Creamos una instancia de la clase CONEXION_AJAX
    //Nos devuelve una conexion AJAX y propiedades 
        var ConSubPost  = new Conexion();
        
function cargarImagenesSubirPost(verImgSubidasEnPostNuevo, tipo, parametro){
    
    imgCargar = verImgSubidasEnPostNuevo;
    pedido = parametro;
   
    
    switch(tipo){
            case('UI'):
                cargarPeticionImgSubirPost("UI", parametro); //Peticion provincias para busquedas y hacer login
                    break;  
        }    
};


function cargarPeticionImgSubirPost(tipo, parametros){
//alert('Estamos en cargarPeticionImgSubirPost y tipo vale: ' +tipo+ ' parametros vale: ' +parametros);
    //para comprobar el tipo de peticion
  
    switch(tipo){
       
        case('UI'):
           petLastImg = ConSubPost.conection();
           petLastImg.onreadystatechange = procesaRespuestaPeticionElementos;
           petLastImg.open('POST', "../Controlador/Elementos_AJAX/imagenesAlSubirPost.php?", true);
           petLastImg.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
           petLastImg.send(parametros);
                break;
        case('PMI'):
           petImgEliminar = ConSubPost.conection();
           petImgEliminar.onreadystatechange = procesaRespuestaPeticionElementos;
           petImgEliminar.open('POST', "../Controlador/Elementos_AJAX/imagenesAlSubirPost.php?", true);
           petImgEliminar.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
           petImgEliminar.send(parametros);
                break;
        
    //fin switch
    }
    
    function procesaRespuestaPeticionElementos(){
       
       if(this.readyState === ConSubPost.READY_STATE_COMPLETE && this.status === 200){
            try{
                
               if(tipo === 'UI'){
                    objLastImg = JSON.parse(petLastImg.responseText);
                    //Eliminamos el objeto conexion
                    delete ConSubPost;
                }else if(tipo === 'PMI'){
                    objImgEliminar = JSON.parse(petImgEliminar.responseText);
                    //Eliminamos el objeto conexion
                    delete ConSubPost;
                } 
                
            } catch(e){
                switch(tipo){        
                   case 'UI':
                         imgCargar.innerHTML = "<h3>No hemos podido recuperar esa imagen</h3>";
                            break;
                   
                    default:
                        //location.href= 'mostrar_error.php';
                }
            //fin catch
            }
            
            switch (tipo){
                case 'UI':
                    cargarUltimaImagen(objLastImg);
                        break;
                 case 'PMI':
                    cargarImgEliminar(objImgEliminar);
                        break;
               
            //fin switch
            }
        //fin if
        }
    //fin procesaRespuesta    
    }
    
    
 
    
//fin cargarPeticion    
}

/*  Metodo que recive el id del post y el id de la imagen
 *      para mostrar por si el usuario quiere eliminar o modificar la descripcion
 *  Los parametros se los mandamos una vez se muestra al usuario la imagen
 *      desde el metodo cargarUltimaImgen
 */
function mandarId(id){
    
    cargarPeticionImgSubirPost("PMI", "opcion=PMI&idPost="+idPost+"&ruta="+id); //Peticion IMAGEN A ELIMINAR
}


/**
* Metodo que muestra la ultima imagen subida por el usuario

 * @param {type} objLastImg
 * @returns {undefined} */
function cargarUltimaImagen(objLastImg){
    
      
        var sep = '<section id="capturar" class="contenedor_imagenes" >';
        for (var i= 0 ; i < objLastImg.length; i++){
            //Evitamos cualquier posible error
                if(objLastImg[i].ruta === "/demo"){
                   //No mostramos la imagen /demo. Esta imagen aqui es opaca al usuario
                   //Solo se muestra en la pagina principal si el usuario no
                   //Ha subido ninguna foto al Post.
                    continue;
                }else{
                    sep += "<figure class='img_usuario_tmp'>";
                    sep += '<img src="../photos/'+objLastImg[i].ruta+'.jpg" id="'+objLastImg[i].ruta+'" alt="imagen subida por el usuario" title="Pinchame para ver la información.">';
                    sep += '</figure>';
                }
                               
            }
        sep += '</section>';
        imgCargar.innerHTML = "";
        imgCargar.innerHTML += sep;
        
        /* Si el usuario hace click sobre una imagen le mostramos la imagen y descripcion
         * Por si desea eliminar o actualizar
         */
        //$(".img_usuario_tmp").click(function(){
         $('#cuerpo').on('click','.img_usuario_tmp', function(e){
            var id = $(this).children('img').attr('id');
            mandarId(id);
            
    });

    
//  cargarUltimaImagen  
}



/**
* Metodo que muestra la imagen seleccionada por el usuario
* Para poder modificar la descripcion o eliminar la imagen
* del post
 * @param {type} objImgEliminar
 * @returns {undefined} 
 * */
function cargarImgEliminar(objImgEliminar){
        //alert('objEliminar'+objImgEliminar[0]);
    //Mostramos la capa opca de fondo
    $("#ocultar").removeClass('oculto').addClass('mostrar_transparencia');
    $("#form_post").addClass('noOcupar');
    //Creamos los elementos para mostrar la imagen y el texto
    
    
    $('#mostrarImgSeleccionada')
        .append($('<form>',{
            name : 'eliminarImagen',
            action : 'subir_posts.php',
            method : 'POST',
            id : 'eliminarImagen'
        })
        .append($('<fieldset>')
        .append($('<legend>',{
            text : "Elimina la imagen o modifica la descrición."
        }))
        .append($('<input>',{
            type : "hidden",
            name : "step",
            value : "1"
        }))
        .append($('<input>',{
            type : "hidden",
            name : "ruta",
            value : objImgEliminar[0].ruta
        }))
        .append($('<figure>',{
            class : "img_usuario_tmp"
        }).append($('<img>',{
            src : "../photos/"+objImgEliminar[0].ruta+".jpg",
            alt : "Imagen subida por el usuario.",
            title : "Puedes modificar la descripción y eliminar la imagen."
        })))
        .append($('<section>',{
            class : "contenedor"
        }).append($('<label>',{
            for : "txtModificar",
            text : "Modifica la descripcion y dale a OK"
        }))
        .append($('<input>',{
             type : "text",
             name : "txtModificar",
             id : "txtModificar",
             maxlength : "70",
             value : objImgEliminar[0].texto
                
        }))
        .append($('<label>',{    
        })
        .append($('<span>',{  
            class : "cnt",
            text : "0"
        }))))//section     
        .append($('<section>',{
            id : "btns_registrar"        
        })
        .append($('<input>',{
            type : "submit",
            name : "modificar",
            id : "modificar",
            value : "OK"        
        }))
        .append($('<input>',{
            type : "submit",
            name : "modificar",
            id : "modificar",
            value : "Borrar"        
        })))//section    
        )//fieldset
        );//form;
//fin cargarImgEliminar    
}
