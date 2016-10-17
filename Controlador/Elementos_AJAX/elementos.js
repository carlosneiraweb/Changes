/* global nombre */

var READY_STATE_UNINITIALIZED = 0;
var READY_STATE_LOADING = 1;
var READY_STATE_LOADED = 2;
var READY_STATE_INTERACTIVE = 3;
var READY_STATE_COMPLETE = 4;

var objPro, petPro, objGen, petGen, objSeccion, petSeccion, objTiempoCambio, petTiempoCambio,
        objPorPrecio, petPorPrecio,objLastImg, petLastImg, idPost, petImgEliminar, 
        objImgEliminar, petPost, objPost,
        objSlider, petSlider, objBuscador, petBuscador, objEncontrado, petEncontrado;
//Variables globales para los <li> de navegacion
var z, tmpLi, liPinchado, ultimoLi;

var fecha = new Date();

var X, post, provincias, porProvincia, porPrecio, porTiempoCambio, genero, seccion, tiempoCambio,
        verImagenes, imgSeleccionada, lista, radioBusqueda, buscarPorProvincia, buscarPorPrecio, buscarPorTiempoCambio;


window.onload=function(){
     X = document.getElementById('cuerpo');
     post = document.getElementById('posts');
     provincias = document.getElementById('provincia');
     porProvincia = document.getElementById('porProvincia');
     porPrecio = document.getElementById('porPrecio');
     porTiempoCambio = document.getElementById('porTiempoCambio');
     genero = document.getElementById('genero');
     seccion = document.getElementById('seccion');
     tiempoCambio = document.getElementById('tiempoCambio');
     verImagenes = document.getElementById('cnt_img');
     imgSeleccionada = document.getElementById('mostrarImgSeleccionada');
     lista = document.getElementById('lista');
     
    
     
     /*     ELEMENTOS PARA EL BUSCADOR      */
     
    $('#buscador').keyup(function(e){
         //Algunas teclas dan problemas como el ir hacia atras <- 
         //Por eso anulamos el evento si se pulsan
         //En este caso solo he anulado esta
         if(e.which !== 8){
            //Primero eliminamos las busquedas anteriores
        $('#contenido_buscado li').remove();
        
        //Recuperamos el valor de los filtros de busqueda
        
        radioBusqueda = $('input:radio[name=busqueda]:checked').val();
        
        buscarPorProvincia = $('#porProvincia').val();
        
        indice = porPrecio.selectedIndex;//$(this).index();
             if(indice === 0){buscarPorPrecio = "No importa";};
             if(indice === 1){buscarPorPrecio = 500;};
             if(indice === 2){buscarPorPrecio = 3000;};
             if(indice === 3){buscarPorPrecio = 3001;};
           
        buscarPorTiempoCambio = $('#porTiempoCambio').val();;
            
        if(buscarPorProvincia === "No importa"){  buscarPorProvincia = 0; };
        if(buscarPorPrecio === "No importa"){ buscarPorPrecio = 0; };
        if(buscarPorTiempoCambio === "No importa"){  buscarPorTiempoCambio = 0; };
        //alert('provincia'+buscarPorProvincia+" por precio "+buscarPorPrecio+ " por tiempo "+buscarPorTiempoCambio);
        
        
    
        //Recuperamos los que el usuario ha escrito en el campo
        txtBuscar = $(this).val();
        //"&provincia="+provincia+"&precio="+precio+"&tiempoCambio="+tiempoCambio
        cargarPeticion('BUSCADOR', "opcion=BUSCADOR&BUSCAR="+txtBuscar+"&tabla="+radioBusqueda+"&buscarPorProvincia="+buscarPorProvincia+
                '&buscarPorPrecio='+buscarPorPrecio+'&buscarPorTiempoCambio='+buscarPorTiempoCambio);
         }
         
          //Recuperamos el contenido del li que se ha pulsado
        $('#mostrar_resultados').on('click','.d',function() {
        var textoElegido = $(this).text();
        
        //Ahora hacemos un select de todos los Posts donde tengan ese texto
        //En sus palabras de busquedas o queridas
        cargarPeticion('ENCONTRADO', "opcion=ENCONTRADO&ENCONTRAR="+textoElegido+"&tabla="+radioBusqueda+"&inicio="+inicio);
        });
        
      
	}); 
    
    
        /*      METODO QUE LANZA EL SLIDER CON EL 
         *      CONTENIDO DEL POST SELECCIONADO POR
         *      EL USUARIO AL HACER CLICK SOBRE LA IMAGEN
         */
     
        //Capturamos la img sobre la que se ha hecho click
        //Para mostrar el slider con los datos de esta
        
         $('#cuerpo').on('click','.lanzar', function(e){
                var src = $(this).children().attr('src');
                cargarPeticion("SLD", "opcion=SLD&srcImg="+src);
            });
     
         
        /*          FIN LANZAR      */
    
       
        cargarPeticion("PP", "opcion=PP"); //Peticion provincias para busquedas
        cargarPeticion("PG", "opcion=PG"); //Peticion Generos
        cargarPeticion("PS", "opcion=PS"); //Peticion Seccion
        cargarPeticion("PT", "opcion=PT"); //Peticion tiempoCambio
        cargarPeticion("PPVP", "opcion=PPVP"); //Peticion por PRECIO
        cargarPeticion("UI", "opcion=UI&idPost="+idPost); //Peticion ultima imagen
        
    
    
    
            /*      ELEMENTOS PAGINACION     */
    //Inicializamos la variable inicio que mostrara por donde empezar a mostrar los posts
    //Comprobamos sin ya se ha inicializado, sino cada vez que el script
    //se cargara machacaria su valor.
    if(typeof(inicio) === "undefined"){  inicio = 0;};
     
     
     //Mostramos el total de elementos encontrados
     resultados = document.getElementById('resultados');
     //Botonera para desplazarnos por las paginas de resultados
     btn_navegacion = document.getElementById('btn_navegacion');
         //Esta llamada a JSON solo se realiza en la primera carga del script
         //Despues se iran mostrando los posts a traves de los botones 
            if(inicio === 0){
                cargarPeticion("PPS", "opcion=PPS&inicio="+inicio); //Cargar Post
            }
            
         //Cada vez que pulsamos un <li> de navegacion se muestran los siguientes Posts
            //Esto se limitan con la constante PHP PAGE_SIZE
        $('#cuerpo').on('click', '.pagina', function(e){
            liPinchado = parseInt($(this).text());
            inicio = liPinchado * PAGESIZE;
            //Tambien nos aseguramos de recargar la lista <li> con los numeros 
                //adecuados a cada paso, sea del 1-10 o 110-120
            tmpLi = parseInt($('.pagina').last().html())+ 1;
            z = tmpLi - 10;
            cargarPeticion("PPS", "opcion=PPS&inicio="+inicio); //Cargar Post
            
           //Reseteamos el inicio otra vez en caso el usuario pulse un <li> para desplazarse
            //en el rango actual. Por ejemplo si pincha el <li> 22 y luego pulsa el <li>
            //siguiente queremos que el rango de <li> empieze en el 30 y valla hasta el 40
            // y no del 22 al 32.
            if(inicio < 50){
                inicio = parseInt($('.pagina').first().html());
            }else{
                inicio = inicio - (liPinchado - parseInt($('.pagina').first().html())) * 5;
            }
            
        });
        
        //Metodo que nos muestra el siguiente conjunto de <li> que hay, si los hubiera
        //Al pulsar el <li> con la clase siguiente
        $('#cuerpo').on('click', '.siguiente', function(e){
            ultimoLi = parseInt($('.pagina').last().html()) +1;
            if(numLi <= ultimoLi){
                tmpLi = numLi;
            }else{
                tmpLi = ultimoLi + 10;
                z = ultimoLi;
                inicio = inicio + 50;
                cargarPeticion("PPS", "opcion=PPS&inicio="+inicio); //Peticion CargarPost
            } 
        });
        
        //Metodo que nos muestra el anterior conjunto de <li> 
            //Al pulsar el <li> con la clase Atras
        $('#cuerpo').on('click', '.atras', function(e){
            var primerLi = parseInt($('.pagina').first().html());
         
            if(primerLi >= 10){
                tmpLi = primerLi;
                z = primerLi - 10;
                inicio = inicio - 50;
                cargarPeticion("PPS", "opcion=PPS&inicio="+inicio); //Peticion CargarPost
            }
        });   
        
                    /* FIN PAGINACION */
};
     

/*  Metodo que recive el id del post y el id de la imagen
 *      para mostrar por si el usuario quiere eliminar o modificar la descripcion
 *  Los parametros se los mandamos una vez se muestra al usuario la imagen
 *      desde el metodo cargarUltimaImgen
 */
function mandarId(id){
    cargarPeticion("PMI", "opcion=PMI&idPost="+idPost+"&ruta="+id); //Peticion IMAGEN A ELIMINAR
}


function cargarPeticion(tipo, parametros){
//alert('Estamos en cargarPeticion y tipo vale: ' +tipo+ ' parametros vale: ' +parametros);
    //para comprobar el tipo de peticion
    switch(tipo){
        case('PP'):
           petPro = inicializaPeticion();
           petPro.onreadystatechange = procesaRespuesta;
           petPro.open('POST', "../Controlador/Elementos_AJAX/json.php?", true);
           petPro.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
           petPro.send(parametros);
                break;
        case('PG'):
           petGen = inicializaPeticion();
           petGen.onreadystatechange = procesaRespuesta;
           petGen.open('POST', "../Controlador/Elementos_AJAX/json.php?", true);
           petGen.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
           petGen.send(parametros);
                break;
        case('PS'):
           petSeccion = inicializaPeticion();
           petSeccion.onreadystatechange = procesaRespuesta;
           petSeccion.open('POST', "../Controlador/Elementos_AJAX/json.php?", true);
           petSeccion.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
           petSeccion.send(parametros);
                break;        
        case('PT'):
           petTiempoCambio = inicializaPeticion();
           petTiempoCambio.onreadystatechange = procesaRespuesta;
           petTiempoCambio.open('POST', "../Controlador/Elementos_AJAX/json.php?", true);
           petTiempoCambio.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
           petTiempoCambio.send(parametros);
                break;
         case('PPVP'):
           petPorPrecio= inicializaPeticion();
           petPorPrecio.onreadystatechange = procesaRespuesta;
           petPorPrecio.open('POST', "../Controlador/Elementos_AJAX/json.php?", true);
           petPorPrecio.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
           petPorPrecio.send(parametros);
                break;
        case('UI'):
           petLastImg = inicializaPeticion();
           petLastImg.onreadystatechange = procesaRespuesta;
           petLastImg.open('POST', "../Controlador/Elementos_AJAX/json.php?", true);
           petLastImg.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
           petLastImg.send(parametros);
                break;
        case('PMI'):
           petImgEliminar = inicializaPeticion();
           petImgEliminar.onreadystatechange = procesaRespuesta;
           petImgEliminar.open('POST', "../Controlador/Elementos_AJAX/json.php?", true);
           petImgEliminar.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
           petImgEliminar.send(parametros);
                break;
        case('PPS'):
           petPost = inicializaPeticion();
           petPost.onreadystatechange = procesaRespuesta;
           petPost.open('POST', "../Controlador/Elementos_AJAX/json.php?", true);
           petPost.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
           petPost.send(parametros);
                break;   
        case('SLD'):
           petSlider = inicializaPeticion();
           petSlider.onreadystatechange = procesaRespuesta;
           petSlider.open('POST', "../Controlador/Elementos_AJAX/json.php?", true);
           petSlider.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
           petSlider.send(parametros);
                break;
        case('BUSCADOR'):
           petBuscador = inicializaPeticion();
           petBuscador.onreadystatechange = procesaRespuesta;
           petBuscador.open('POST', "../Controlador/Elementos_AJAX/busquedas.php?", true);
           petBuscador.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
           petBuscador.send(parametros);
                break; 
        case('ENCONTRADO'):
           petEncontrado = inicializaPeticion();
           petEncontrado.onreadystatechange = procesaRespuesta;
           petEncontrado.open('POST', "../Controlador/Elementos_AJAX/busquedas.php?", true);
           petEncontrado.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
           petEncontrado.send(parametros);
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
                }else if(tipo === 'PPVP'){
                    objPorPrecio = JSON.parse(petPorPrecio.responseText);
                } else if(tipo === 'UI'){
                    objLastImg = JSON.parse(petLastImg.responseText);
                } else if(tipo === 'PMI'){
                    objImgEliminar = JSON.parse(petImgEliminar.responseText);
                } else if(tipo === 'PPS'){
                    objPost= JSON.parse(petPost.responseText);
                } else if(tipo === 'SLD'){
                    objSlider = JSON.parse(petSlider.responseText);
                } else if(tipo === 'BUSCADOR'){
                    objBuscador = JSON.parse(petBuscador.responseText);
                } else if(tipo === 'ENCONTRADO'){
                    objEncontrado = JSON.parse(petEncontrado.responseText);
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
                case 'PPS':
                    cargarPost(objPost);
                        break;
                case 'SLD':
                    cargarSlider(objSlider);
                        break;
                case 'BUSCADOR':
                    cargarBuscador(objBuscador);
                        break;
                case 'ENCONTRADO':
                    cargarPost(objEncontrado);
                    
                        break;
            //fin switch
            }
        //fin if
        }
    //fin procesaRespuesta    
    }
    
    
    
    
//fin cargarPeticion    
}

function cargarPost(objPost){
    //alert(objPost);
    var acumulador ="";    
    
    for(var i = 1; i < objPost.length; i++){
      
       muestro_post = '<section class="cont_post">'+
       '<h2>'+objPost[i].titulo+'</h2>'+
       '<section class="cont_usuario"><span class="usuario"><p>El usuario: <span class="resaltar">'+objPost[i].nick+'</span> de '+objPost[i].provincia+'.</p></span><span class="tiempo_cambio"><p>Tiempo del cambio <span class="resaltar">'+objPost[i].tiempoCambio+'</span></p></span></section>'+
       '<figure  class="lanzar"><img src="../photos/'+objPost[i].ruta+'.jpg" alt="Fotos de intercambio de cosas"/></figure><section id='+objPost[i].ruta+' class="comentario"><textarea class="texto_comentario">'+objPost[i].comentario+'</textarea></section>'+
       '<span class="fecha_post"><p>Fecha del Anuncio<span class="date">'+objPost[i].fecha+'</span></p></span>'+
       '</section>';
       
        acumulador += muestro_post;
    }
    
    post.innerHTML = "";
    post.innerHTML = acumulador;
  
    resultados.innerHTML = '<h3>Se muestran desde '+(inicio+ 1)+' al '+(inicio+ PAGESIZE)+' De un total de '+objPost[0].totalRows[0]+' posts encontrados </h3>';
   
    //Calculamos el total de <li> que se van a mostrar para navegar por el conjunto de resultados
    totalPost = parseInt(objPost[0].totalRows[0]); //total posts
    //Solo instanciamos las variables que muestran el bucle cuando
        //se carga la pagina por primera vez
    if(inicio === 0){
        numLi = totalPost / PAGESIZE; //Numeros de <li>
        //Si al dividir sale decimal le sumamos un <li>
            if (numLi % 1 !== 0){
                numLi++;
            }
        //Parseamos a Integer y ya tenemos el total de <li> a mostrar
            numLi = parseInt(numLi);
        //Queremos limitar el numero de <li> a 10 por pagina
            //En caso de que numLi sea mayor a 50
            if (numLi > 10){
                tmpLi = 10;
            }else{
                tmpLi = numLi;
            }
    //Inicializamos la variable del for que muestra los posts a 0
        //Mas tarde cuando el usuario pulse los botones siguiente o atras 
        //se ira modificando
    z = 0;
    }
    
    
    //Mostramos los primeros <li>, empezamos en 0 y el limite de PAGESIZE
            mostrarLis();
            
//fin cargarPost    
}

//Este metodo muestra los <li> del menu de navegacion
function mostrarLis(){

        var listaLi = '<ul class="listaLis"><li class="atras">Atras</li>';
    for (z ; z < tmpLi; z++){
        listaLi += '<li class="pagina">'+z+'</li>';
    }
        listaLi +='<li class="siguiente">Siguiente</li></ul>';
        btn_navegacion.innerHTML = listaLi;
//fin mostrar posts
}     
    
    
/*Cargamos las provincias, tanto para cuando un usuario se registra
* como para el filtro del buscador*/
function cargarProvincias(objProv){
    //alert(objProv);
   
    for(var i = 0; i < objProv.length; i++){
        var objTmpP = objProv[i];
      if(provincias  !== null){
          //Evitamos insertar el primer valo de la tabla 
          //de provincias en el formulario de registro
          if(i === 0){
                continue;
          }else{
                provincias.options.add(new Option(objTmpP.nombre));
            }
      } else {
        porProvincia.options.add(new Option(objTmpP.nombre));
            }
    }
}
/*Cargamos los tipos de genero*/   
function cargarGenero(objGene){
    //alert(objGene);
    for(var i = 0; i < objGene.length; i++){
        var objTmpG = objGene[i];
      genero.options.add(new Option(objTmpG.genero));
    }  
//fin cargarProvincias    
}

/*Cargamos las secciones de los artículos*/   
function cargarSecciones(objSeccion){
   // alert(objSeccion);
    for(var i = 0; i < objSeccion.length; i++){
        var objTmpS = objSeccion[i];
        
      seccion.options.add(new Option(objTmpS.nombre_seccion));
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
        if(tiempoCambio  !== null){
            tiempoCambio.options.add(new Option(objTmpTiempoCambio.tiempo));
      } else {
            porTiempoCambio.options.add(new Option(objTmpTiempoCambio.tiempo));
            }
      
    }  
//fin cargarSecciones   
}

/**
* Metodo que muestra la ultima imagen subida por el usuario

 * @param {type} objLastImg
 * @returns {undefined} */
function cargarUltimaImagen(objLastImg){
    
        //alert(objLastImg);
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
    
    var form = '<h4>Elimina la imagen o modifica la descrición.</h4>'+
               '<form name="eliminarImagen" action="subir_posts.php" method="POST" id="eliminarImagen" >'+
               '<fieldset>'+
               '<legend>Rellena los campos</legend>'+
               '<input type="hidden" name="step" value="1">'+
               '<input type="hidden" name="ruta" value="'+objImgEliminar[0].ruta+'">'+
               '<figure class="img_usuario_tmp">'+
               '<img src="../photos/'+objImgEliminar[0].ruta+'.jpg" alt="Puedes modificar la imagen o el texto" title="Puedes modificar la descripción y eliminar la imagen.">'+
               '</figure>'+
               '<section class="contenedor">'+
               '<label for="txtModificar" >Modifica la descripcion y dale a OK</label>'+
               '<input type="text" name="txtModificar" id="txtModificar" maxlength="70" value="'+objImgEliminar[0].texto+'">'+
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



/**
    Metodo que muestra todo el slider 
    despues el usuario halla hecho click 
    sobre una imagen
 * @returns {ActiveXObject|XMLHttpRequest} 
 * 
 * */

function cargarSlider(objSlider){
        
        //Agregamos las imagenes al Slider 
        
        $("#ocultar").removeClass('oculto').addClass('mostrar_transparencia');
        $("#mostrarSlider").removeClass('oculto');
        ///Creamos elementos
   
        $(".slider-container-IMG").append($('<figure>',{
            id : 'sliderIMG',
            class : 'slider-wrapper-IMG'
        }).append($('<img>',{
            src : "../photos/"+objSlider[0][0].ruta+".jpg"
        })).append($('<div>',{
                class : 'caption',
                text : objSlider[0][0].texto
            }))
                
        ).append($('<ul>', {
            class : 'slider-controls',
            id : 'slider-controls'
        }).append($('<li>',{
                    
        }).on('click',function(){
            $('#sliderIMG img').remove();
            $('#sliderIMG div').remove();
            $('#sliderIMG').append($('<img>',{
            src : "../photos/"+objSlider[0][0].ruta+".jpg"
            })).append($('<div>',{
                class : 'caption',
                text : objSlider[0][0].texto
            }));
           
        })
        ).append($('<li>',{
                    
            }).on('click',function(){
                $('#sliderIMG img').remove();
                $('#sliderIMG div').remove();
                $('#sliderIMG').append($('<img>',{
                src : "../photos/"+objSlider[0][1].ruta+".jpg"
            })).append($('<div>',{
                class : 'caption',
                text : objSlider[0][1].texto
            }));
         })    
            
       ).append($('<li>',{
                    
            }).on('click',function(){
                $('#sliderIMG img').remove();
                $('#sliderIMG div').remove();
                $('#sliderIMG').append($('<img>',{
                src : "../photos/"+objSlider[0][2].ruta+".jpg"
            })).append($('<div>',{
                class : 'caption',
                text : objSlider[0][2].texto
            }));
        })        
            
            
        ).append($('<li>',{
                    
            }).on('click',function(){
                $('#sliderIMG img').remove();
                $('#sliderIMG div').remove();
                $('#sliderIMG').append($('<img>',{
                src : "../photos/"+objSlider[0][3].ruta+".jpg"
            })).append($('<div>',{
                class : 'caption',
                text : objSlider[0][3].texto
            }));
        })     
                
        ).append($('<li>',{
                    
            }).on('click',function(){
                $('#sliderIMG img').remove();
                $('#sliderIMG div').remove();
                $('#sliderIMG').append($('<img>',{
                src : "../photos/"+objSlider[0][4].ruta+".jpg"
            })).append($('<div>',{
                class : 'caption',
                text : objSlider[0][4].texto
            }));
        })     
               
        )).append($('<section>',{
            id : 'buscadas'
        }).append($('<h3>',{
            text : 'Cosas que podrían interesar'
        })).append( $('<section>', {
            id : 'lista'
            }).append($('<ol>', {
                
            }))
       
        ));
       
           var tmp = "";
          
           for (var i =0; i < objSlider[1].length; i++){
           tmp += '<li>'+objSlider[1][i].pbsQueridas+'</li>'; 
                }
           $('#lista').append(tmp);
            
//fin cargarSlider    
}

/**
 * Metodo que carga los resultados del buscador
 * en los <li> va mostrando los resultados segun escribe el usuario
 * @returns {ActiveXObject|XMLHttpRequest} */
function cargarBuscador(objBuscador){
    
    //alert(objBuscador[0]);
    var vacio = "<li>No se han encontrado resultados con la busqueda <strong>"+txtBuscar+"</strong></li>";
    if(typeof objBuscador[0] === "undefined"){
        $('#mostrar_resultados ul').append(vacio); 
    }else{
         
     
        for(var b = 0; b < objBuscador.length; b++){
        $('#mostrar_resultados ul').append('<li class="d">'+objBuscador[b].palabra+'</li>');
        
        }
    }
    
    
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
    