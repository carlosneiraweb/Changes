/* global nombre */
/**
 * @author Carlos Neira Sanchez
 * @mail arj.123@hotmail.es
 * @telefono ""
 * @nameAndExt elementos.js
 * @fecha 26-oct-2016
 */

var X, post, petPost, objPost, objSlider, petSlider;
//Variables globales para los <li> de navegacion
var z, tmpLi, liPinchado, ultimoLi;

var fecha = new Date();
var Conexion;

            //Creamos una instancia de la clase CONEXION_AJAX
            //Nos devuelve una conexion AJAX y propiedades 
                    var ConElementos  = new Conexion();

window.onload=function(){
  
    
     X = document.getElementById('cuerpo');
     post = document.getElementById('posts');
     
     
    //Creamos la seccion del buscador por jquery
        insertarBuscador();
        
     /***********************/
     lista = document.getElementById('lista');
    
    
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
     

            /*      ELEMENTOS PAGINACION     */
    //Inicializamos la variable inicio que mostrara por donde empezar a mostrar los posts
    //Comprobamos sin ya se ha inicializado, sino cada vez que el script
    //se cargara machacaria su valor.
    if(typeof(inicio) === "undefined"){ inicio = 0; };
     
     
     //Mostramos el total de elementos encontrados
     resultados = document.getElementById('resultados');
     //Botonera para desplazarnos por las paginas de resultados
     btn_navegacion = document.getElementById('btn_navegacion');
         //Esta llamada a JSON solo se realiza en la primera carga del script
         //Despues se iran mostrando los posts a traves de los botones 
            if(inicio === 0 && PPS === true){
                cargarPeticion("PPS", "opcion=PPS&inicio="+inicio); //Cargar Post
            }
            
         //Cada vez que pulsamos un <li> de navegacion se muestran los siguientes Posts
            //Esto se limitan con la constante PHP PAGE_SIZE
        $('#cuerpo').on('click', '.pagina', function(e){
            liPinchado = parseInt($(this).text());
            inicio = liPinchado * PAGESIZE;
            inicioTmp = inicio;
            //alert('cuando se pincha'+inicioTmp);
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
                inicioTmp = inicio;
            }else{
                inicio = inicio - (liPinchado - parseInt($('.pagina').first().html())) * 5;
                inicioTmp = inicio;
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
                inicioTmp = inicio;
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
                inicioTmp = inicio;
                cargarPeticion("PPS", "opcion=PPS&inicio="+inicio); //Peticion CargarPost
            }
        });   
        
                    /* FIN PAGINACION */
};




function cargarPeticion(tipo, parametros){
//alert('Estamos en cargarPeticion y tipo vale: ' +tipo+ ' parametros vale: ' +parametros);
    //para comprobar el tipo de peticion
    switch(tipo){
        
        case('PPS'):
           petPost = ConElementos.conection();
           petPost.onreadystatechange = procesaRespuesta;
           petPost.open('POST', "../Controlador/Elementos_AJAX/json.php?", true);
           petPost.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
           petPost.send(parametros);
                break;   
        case('SLD'):
           petSlider = ConElementos.conection();
           petSlider.onreadystatechange = procesaRespuesta;
           petSlider.open('POST', "../Controlador/Elementos_AJAX/json.php?", true);
           petSlider.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
           petSlider.send(parametros);
                break;
        
    //fin switch
    }
    
    function procesaRespuesta(){
       
       if(this.readyState === ConElementos.READY_STATE_COMPLETE && this.status === 200){
            try{
                if(tipo === 'PPS'){
                    objPost= JSON.parse(petPost.responseText);
                     //Eliminamos el objeto conexion
                    delete ConElementos;
                } else if(tipo === 'SLD'){
                    objSlider = JSON.parse(petSlider.responseText);
                    //Eliminamos el objeto conexion
                    delete ConElementos;
                } 
                
                
            } catch(e){
                switch(tipo){        

                    default:
                       // location.href= 'mostrar_error.php';
                }
            //fin catch
            }
            
            switch (tipo){
                
                case 'PPS':
                    cargarPost(objPost);
                        break;
                case 'SLD':
                    cargarSlider(objSlider);
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
 * Metodo que inserta los posts
 * @param {type} objPost
 * @returns {undefined} */
function cargarPost(objPost){
    
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
     
    resultados.innerHTML = '<h3>Se muestran desde '+(inicio + 1)+' al '+(inicio+ PAGESIZE)+' De un total de '+objPost[0].totalRows[0]+' posts encontrados </h3>';
   
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


