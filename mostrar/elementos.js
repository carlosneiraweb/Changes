/* global nombre */

var READY_STATE_UNINITIALIZED = 0;
var READY_STATE_LOADING = 1;
var READY_STATE_LOADED = 2;
var READY_STATE_INTERACTIVE = 3;
var READY_STATE_COMPLETE = 4;

var objPro, petPro, objGen, petGen, objSeccion, petSeccion, objTiempoCambio, petTiempoCambio,
        objLastImg, petLastImg, idPost, petImgEliminar, objImgEliminar;

var fecha = new Date();

window.onload=function(){
   
     
     provincias = document.getElementById('provincia');
     genero = document.getElementById('genero');
     seccion = document.getElementById('seccion');
     tiempoCambio = document.getElementById('tiempoCambio');
     verImagenes = document.getElementById('cnt_img');
     imgSeleccionada = document.getElementById('mostrarImgSeleccionada');
     
     
     
     cargarPeticion("PP", "opcion=PP");
     cargarPeticion("PG", "opcion=PG");
     cargarPeticion("PS", "opcion=PS");
     cargarPeticion("PT", "opcion=PT");
     cargarPeticion("UI", "opcion=UI&idPost="+idPost);
     
     
};

/*  Metodo que recive el id del post y el id de la imagen
 *      para mostrar por si el usuario quiere eliminar o modificar la descripcion
 *  Los parametros se los mandamos una vez se muestra al usuario la imagen
 *      desde el metodo cargarUltimaImgen
 */
function mandarId(id){
    cargarPeticion("PMI", "opcion=PMI&idPost="+idPost+"&ruta="+id);
}


function cargarPeticion(tipo, parametros){
//alert('Estamos en cargarPeticion y tipo vale: ' +tipo+ ' parametros vale: ' +parametros);
    //para comprobar el tipo de peticion
    switch(tipo){
        case('PP'):
           petPro = inicializaPeticion();
           petPro.onreadystatechange = procesaRespuesta;
           petPro.open('POST', "./central/json.php?", true);
           petPro.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
           petPro.send(parametros);
                break;
        case('PG'):
           petGen = inicializaPeticion();
           petGen.onreadystatechange = procesaRespuesta;
           petGen.open('POST', "./central/json.php?", true);
           petGen.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
           petGen.send(parametros);
                break;
        case('PS'):
           petSeccion = inicializaPeticion();
           petSeccion.onreadystatechange = procesaRespuesta;
           petSeccion.open('POST', "./central/json.php?", true);
           petSeccion.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
           petSeccion.send(parametros);
                break;        
        case('PT'):
           petTiempoCambio = inicializaPeticion();
           petTiempoCambio.onreadystatechange = procesaRespuesta;
           petTiempoCambio.open('POST', "./central/json.php?", true);
           petTiempoCambio.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
           petTiempoCambio.send(parametros);
                break;
        case('UI'):
           petLastImg = inicializaPeticion();
           petLastImg.onreadystatechange = procesaRespuesta;
           petLastImg.open('POST', "./central/json.php?", true);
           petLastImg.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
           petLastImg.send(parametros);
                break;
        case('PMI'):
           petImgEliminar = inicializaPeticion();
           petImgEliminar.onreadystatechange = procesaRespuesta;
           petImgEliminar.open('POST', "./central/json.php?", true);
           petImgEliminar.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
           petImgEliminar.send(parametros);
                break;    
    //fin switch
    }
    
    function procesaRespuesta(){
       
       if(this.readyState === READY_STATE_COMPLETE && this.status === 200){
            try{
               if(tipo === 'PP'){
                    objPro = JSON.parse(petPro.responseText);
                } else if(tipo === 'PG'){
                    objGen = JSON.parse(petGen.responseText);
                } else if(tipo === 'PS'){
                    objSeccion = JSON.parse(petSeccion.responseText);
                } else if(tipo === 'PT'){
                    objTiempoCambio = JSON.parse(petTiempoCambio.responseText);
                } else if(tipo === 'UI'){
                    objLastImg = JSON.parse(petLastImg.responseText);
                } else if(tipo === 'PMI'){
                    objImgEliminar = JSON.parse(petImgEliminar.responseText);
                }
                
                
            } catch(e){
                switch(tipo){        
                    case 'PP':
                            break;
                    case 'UI':
                        fotos.innerHTML = "<h3>No hemos podido recuperar esa imagen</h3>";
                            break;
                    default:
                       // location.href= 'mostrar_error.php';
                }
            //fin catch
            }
            
            switch (tipo){
                case 'PP':
                    cargarProvincias(objPro);
                        break;
                case 'PG':
                    cargarGenero(objGen);
                        break;
                case 'PS':
                    cargarSecciones(objSeccion);
                        break;
                case 'PT':
                    cargarTiempoDeCambio(objTiempoCambio);
                        break;
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

/*Cargamos las provincias*/
function cargarProvincias(objProv){
    for(var i = 0; i < objProv.length; i++){
        var objTmpP = objProv[i];
      provincias.options.add(new Option(objTmpP.nombre));
    }   
}
/*Cargamos los tipos de genero*/   
function cargarGenero(objGene){
    for(var i = 0; i < objGene.length; i++){
        var objTmpG = objGene[i];
      genero.options.add(new Option(objTmpG.genero));
    }  
//fin cargarProvincias    
}

/*Cargamos las secciones de los artículos*/   
function cargarSecciones(objSeccion){
    for(var i = 0; i < objSeccion.length; i++){
        var objTmpS = objSeccion[i];
      seccion.options.add(new Option(objTmpS.nombre_seccion));
    }  
//fin cargarSecciones   
}

/*Cargamos el tiempo para el cambio*/   
function cargarTiempoDeCambio(objTiempoCambio){
    for(var i = 0; i < objTiempoCambio.length; i++){
        var objTmpTiempoCambio = objTiempoCambio[i];
      tiempoCambio.options.add(new Option(objTmpTiempoCambio.tiempo));
    }  
//fin cargarSecciones   
}

/*Cargamos la ultima imagen selecionada por el usuario*/
function cargarUltimaImagen(objLastImg){
        
        var sep = '<section id="capturar" class="contenedor_imagenes" >';
        for (var i= 0 ; i < objLastImg.length; i++){
          
                sep += "<figure class='img_usuario_tmp'>";
                sep += '<img src="photos'+objLastImg[i].ruta+'.jpg" id="'+objLastImg[i].ruta+'" alt="imagen subida por el usuario" title="Pinchame para ver la información.">';
                sep += '</figure>';
                               
            }
        sep += '</section>';
        verImagenes.innerHTML = "";
        verImagenes.innerHTML += sep;
        
        /* Si el usuario hace click sobre una imagen le mostramos la imagen y descripcion
         * Por si desea eliminar o actualizar
         */
        $(".img_usuario_tmp").click(function(){
            var id = $(this).children('img').attr('id');
            mandarId(id);
    });
//  cargarUltimaImagen  
}


function cargarImgEliminar(objImgEliminar){
    //Mostramos la capa opca de fondo
    $("#ocultar").removeClass('oculto').addClass('mostrar_transparencia');
    $("#form_post").addClass('noOcupar');
    //Creamos los elementos para mostrar la imagen y el texto
    
    var form = '<h4>Elimina la imagen o modifica la descrición.</h4>'+
               '<form name="eliminarImagen" action="subir_posts.php" method="POST" id="eliminarImagen" >'+
               '<fieldset>'+
               '<legend>Rellena los campos</legend>'+
               '<input type="hidden" name="step" value="1">'+
               '<input type="hidden" name="ruta" value="'+objImgEliminar[0].ruta+'">'+
               '<figure class="img_usuario_tmp">'+
               '<img src="photos'+objImgEliminar[0].ruta+'.jpg" alt="Puedes modificar la imagen o el texto" title="Puedes modificar la descripción y eliminar la imagen.">'+
               '</figure>'+
               '<section class="contenedor">'+
               '<label for="txtModificar" >Modifica la descripcion y dale a OK</label>'+
               '<input type="text" name="txtModificar" id="txtModificar" maxlength="25" value="'+objImgEliminar[0].texto+'">'+
               '<label><span class="cnt">0</span></label>'+
               '</section>'+
               '<section id="btns_registrar">'+
               '<input type="submit" name="modificar" id="modificar"  value="OK">'+
               '<input type="submit" name="modificar" id="modificar"  value="Borrar">'+
               '</fieldset>'+
               '</from>';
       
    imgSeleccionada.innerHTML = "";
    imgSeleccionada.innerHTML = form;
//fin cargarImgEliminar    
}
//------------------------funcion inicializa peticion ----------------------------
  function inicializaPeticion(){
         var peticion;
        if(window.XMLHttpRequest){
            peticion = new XMLHttpRequest(); 
        }else if (window.ActiveXObject){
                peticion= new ActiveXObject('Microsoft.XMLHTTP'); 
            }
             return peticion;
      }  
    