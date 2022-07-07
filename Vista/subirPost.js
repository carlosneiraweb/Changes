
/**
 * @author Carlos Neira Sanchez
 * @mail arj.123@hotmail.es
 * @telefono ""
 * @nameAndExt subirPost.php
 * @fecha 04-oct-2020
 */

var objSeccion, petSeccion, objTiempoCambio, petTiempoCambio,
    objLastImg, petLastImg, petImgEliminar, objImgEliminar, imgCargar,
    idPost, PS = null, PT = null;

   
                //Creamos una instancia de la clase CONEXION_AJAX
                //Nos devuelve una conexion AJAX y propiedades 
                    var ConSubPost = new Conexion();

window.onload=function(){
   
   
    //Section donde se cargaran las imagenes que el usuario valla subiendo
    imgCargar = document.getElementById('cnt_img');

        if (PS !== null) {
            cargarPeticionSubirPost("PS", "opcion=PS"); //Peticion Seccion 
            PS = null;
        }
        if (PT !== null) {
            cargarPeticionSubirPost("PT", "opcion=PT"); //Peticion tiempoCambio
            PT = null;
        }
        
        
       
   
     //Esta variable se instancia en subir_posts.php
     //Cada vez que subimos una foto nueva en el post
     //Estan se van mostrando en el formulario
     //Para ir recuperandolas de la bbdd necesitamos el idPost
     cargarPeticionSubirPost("ImagenNueva", "opcion=ImagenNueva&idPost="+idPost);   
    
 };



function cargarPeticionSubirPost(tipo, parametros){
//alert('Estamos en cargarPeticionImgSubirPost y tipo vale: ' +tipo+ ' parametros vale: ' +parametros);
    //para comprobar el tipo de peticion
  
    switch(tipo){
       case('PS'):
           petSeccion = ConSubPost.conection();
           petSeccion.onreadystatechange = procesaRespuestaPeticionElementos;
           petSeccion.open('POST', "../Controlador/Elementos_AJAX/cargarElementos.php?", true);
           petSeccion.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
           petSeccion.send(parametros);
                break;        
        case('PT'):
           petTiempoCambio = ConSubPost.conection();
           petTiempoCambio.onreadystatechange = procesaRespuestaPeticionElementos;
           petTiempoCambio.open('POST', "../Controlador/Elementos_AJAX/cargarElementos.php?", true);
           petTiempoCambio.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
           petTiempoCambio.send(parametros);
                break;
        case('ImagenNueva'):
           petLastImg = ConSubPost.conection();
           petLastImg.onreadystatechange = procesaRespuestaPeticionElementos;
           petLastImg.open('POST', "../Controlador/Elementos_AJAX/imagenesAlSubirPost.php?", true);
           petLastImg.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
           petLastImg.send(parametros);
                break;
        case('ImagenEliminarNueva'):
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
                if(tipo === 'PS'){
                    objSeccion = JSON.parse(petSeccion.responseText);
                    //Eliminamos el objeto conexion
                    delete ConSubPost;
                } else if(tipo === 'PT'){
                    objTiempoCambio = JSON.parse(petTiempoCambio.responseText);
                    //Eliminamos el objeto conexion
                    delete ConSubPost;
                }else if(tipo === 'ImagenNueva'){
                    objLastImg = JSON.parse(petLastImg.responseText);
                    //Eliminamos el objeto conexion
                    delete ConSubPost;
                }else if(tipo === 'ImagenEliminarNueva'){
                    objImgEliminar = JSON.parse(petImgEliminar.responseText);
                    //Eliminamos el objeto conexion
                    delete ConSubPost;
                } 
                
            } catch(e){
                switch(tipo){ 
                   
                    case 'ImagenNueva':
                        
                         imgCargar.innerHTML = "<h3>Inserta una imagen nueva.</h3>";
                            break;
                   
                        default:
                        //location.href= 'mostrar_error.php';
                }
            //fin catch
            }
            
            switch (tipo){
                case 'PS':
                    cargarSecciones(objSeccion);
                        break;
                    case 'PT':
                    cargarTiempoDeCambio(objTiempoCambio);
                        break;
                case 'ImagenNueva':
                    cargarUltimaImagen(objLastImg);
                        break;
                 case 'ImagenEliminarNueva':
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

/*
* @description 
* Cargamos las secciones de los artículos
* */   
function cargarSecciones(objSeccion){
    //alert(objSeccion[0].nombre_seccion);
    for(var i = 0; i < objSeccion.length; i++){
        var objTmpSeccion = objSeccion[i];
            $('#seccionSubirPost').append($('<option>',{
            text : objTmpSeccion.nombre_seccion
        }));
      
    }  
//fin cargarSecciones   
}

/*Cargamos el tiempo para el cambio, tanto cuando el usuario sube un Post
* como para el buscador
* */   
function cargarTiempoDeCambio(objTiempoCambio){
    //alert(objTiempoCambio);
    for(var i = 0; i < objTiempoCambio.length; i++){
        var objTmpTiempoCambio = objTiempoCambio[i];
        $('#tiempoCambioSubirPost').append($('<option>',{
            text : objTmpTiempoCambio.tiempo
        }));
      
    }  
//fin cargarSecciones   
}


/*  
* @description 
* Metodo que recive el id del post y el id de la imagen
 *      para mostrar por si el usuario quiere eliminar o modificar la descripcion
 *  Los parametros se los mandamos una vez se muestra al usuario la imagen
 *      desde el metodo cargarUltimaImgen
 */
function mandarId(id){
     //Peticion IMAGEN A ELIMINAR
    cargarPeticionSubirPost("ImagenEliminarNueva", "opcion=ImagenEliminarNueva&idPost="+idPost+"&ruta="+id);
}


/**
* @description 
* Metodo que muestra la ultima imagen subida por el usuario
* al hacer un nuevo Posts. Esta imagen se van insertando en el formulario
* al querer poner un post nuevo
 * @param {type} objLastImg
 * @returns {undefined} */
function cargarUltimaImagen(objLastImg){
      // alert('ddddd'+objLastImg[0].ruta);
        var sep = '<section id="capturar" class="contenedor_imagenes" >';
        for (var i= 0 ; i < objLastImg.length; i++){
            var demo = objLastImg[i].ruta;
            demo = demo.substr(-4,4);
         
            //Evitamos cualquier posible error
            //alert(objLastImg[i].ruta);
                if(demo === "demo"){ 
                   //No mostramos la imagen /demo. Esta imagen aqui es opaca al usuario
                   //Solo se muestra en la pagina principal si el usuario no
                   //Ha subido ninguna foto al Post.
                    continue;
                }else{
                    //Al pinchar sobre este figure se nos abrira un nuevo 
                    //formulario por si debemos modificar o borrar
                    sep += "<figure class='img_usuario_tmp'>";
                    sep += '<img src="../photos/'+objLastImg[i].nick+'/'+objLastImg[i].ruta+'.jpg" id="'+objLastImg[i].ruta+'" alt="imagen subida por el usuario" title="Pinchame para ver la información.">';
                    sep += '</figure>';
                }
                               
            }
        sep += '</section>';
        imgCargar.innerHTML = "";
        imgCargar.innerHTML += sep;
        
        /* Si el usuario hace click sobre una imagen le mostramos la imagen y descripcion
         * Por si desea eliminar o actualizar
         */
       
         $('#cuerpo').on('click','.img_usuario_tmp', function(e){
            var id = $(this).children('img').attr('id');
            //Atributo id campo de bbdd => carlos/54/1,  
            //De esta forma si el usuario elimina la imagen 
            //Nosotros podemos elimarla de la bbdd


            mandarId(id);
            
    });

    
//  cargarUltimaImagen  
}



/**
* Metodo que muestra la imagen seleccionada por el usuario
* Para poder modificar la descripcion o eliminar la imagen
* del post mientras se esta subiendo
 * @param {type} objImgEliminar
 * @returns formulario
 * */
function cargarImgEliminar(objImgEliminar){
   
       //alert('objEliminar    '+"../photos/"+objImgEliminar[0].nick+'/'+objImgEliminar[0].ruta+".jpg");
    //Mostramos la capa opca de fondo
    //$("#ocultar").removeClass('oculto').addClass('mostrar_transparencia');
    $("#form_post").addClass('noOcupar');
    //Eliminamos el formulario donde vamos subiendo
    //las imagenes post 
    $("#form_post_2").remove();
    //Creamos los elementos para mostrar la imagen y el texto
    
    $('<header>').before('#mostrarImgSeleccionada');
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
            value : objImgEliminar[0].nick+'/'+objImgEliminar[0].ruta
        }))
        .append($('<figure>',{
            class : "img_usuario_tmp"
        }).append($('<img>',{
            src : "../photos/"+objImgEliminar[0].nick+'/'+objImgEliminar[0].ruta+".jpg",
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

