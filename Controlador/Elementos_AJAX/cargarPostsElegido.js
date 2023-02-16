/**
 * @author Carlos Neira Sanchez
 * @mail arj.123@hotmail.es
 * @telefono ""
 * @nameAndExt cargarPostsElegido.js
 * @fecha 26-oct-2020
 */



/**
 * @description description
 * Metodo que muestra un post cuando un usuario
 * pincha sobre la foto que hay en la pagina principal
 * @param {type} objPost
 * objeto tipo post
 * 
 */

function cargarPostSeleccionado(objPost){
  //alert("../photos/"+objPost[0].directorio+".jpg");     
        //Agregamos las imagenes al Slider 
       // alert("TEXTO"+objPost[1][0].pbsQueridas);
        
        $("#ocultar").removeClass('oculto').addClass('mostrar_transparencia');
        $("#mostrarPostSeleccionado").removeClass('oculto');
       
        ///Creamos elementos
         $("#mostrarPostSeleccionado").append($("<section>",{
             id : 'contPostSeleccionado'
         }).append($("<h1>",{
             text: "Te lo cambio" 
         })).append($('<figure>',{
            id : 'sliderIMG',
            class : 'slider-wrapper-IMG'
        }).append($('<img>',{
            class : 'imgSlider',
            src : "../photos/"+objPost[0].directorio+".jpg"   
        })).append($("<figcaption>",{
            html : objPost[0].texto
        }))
                
        ));
                
  
        $("#contPostSeleccionado").append($('<ul>', {
            class : 'slider-controls',
            id : 'slider-controls'
        }).append($('<li>',{
              class : 'elegir',
             text : '1'       
        }).on('click',function(){
            $('#sliderIMG').empty();
           // $('#sliderIMG img').remove();
            //$('#sliderIMG  .pCaption').remove();
            
           
            $('#sliderIMG').append($('<img>',{
                class : 'imgSlider',
            src : "../photos/"+objPost[0].directorio+".jpg"
            })).append($('<figcaption>',{
                class: 'pCaption',
                text : objPost[0].texto
                    }));
        })
        ).append($('<li>',{
            class : 'elegir',
            text : '2'
                    
            }).on('click',function(){
                
                    $('#sliderIMG').empty();
                   // $('#sliderIMG .pCaption').remove();
                   // $('#sliderIMG img').remove();

                    $('#sliderIMG').append($('<img>',{
                        class : 'imgSlider',
                    src : "../photos/"+objPost[1].directorio+".jpg"
                    })).append($('<figcaption>',{
                    class : 'pCaption',
                    text : objPost[1].texto
                        }));
                
         })    
            
       ).append($('<li>',{
           class : 'elegir',
           text : '3'
                    
            }).on('click',function(){
                $('#sliderIMG').empty();
                //$('#sliderIMG img').remove();
                //$('#sliderIMG  .pCaption').remove();
                $('#sliderIMG').append($('<img>',{
                class : 'imgSlider',
                src : "../photos/"+objPost[2].directorio+".jpg"
            })).append($('<figcaption>',{
                class : 'pCaption',
                text : objPost[2].texto
                    }));
        })        
            
            
        ).append($('<li>',{
            class : 'elegir',
            text: '4'
            }).on('click',function(){
                $('#sliderIMG').empty();
                //$('#sliderIMG img').remove();
                //$('#sliderIMG  .pCaption').remove();
                $('#sliderIMG').append($('<img>',{
                    class : 'imgSlider',
                src : "../photos/"+objPost[3].directorio+".jpg"
            })).append($('<figcaption>',{
                class : 'pCaption',
                text : objPost[3].texto
                    }));
        })     
                
        ).append($('<li>',{
            class : 'elegir',
            text : '5' 
            }).on('click',function(){
                $('#sliderIMG').empty();
                //$('#sliderIMG img').remove();
                //$('#sliderIMG  .pCaption').remove();
                $('#sliderIMG').append($('<img>',{
                    class : 'imgSlider',
                src : "../photos/"+objPost[4].directorio+".jpg"
            })).append($('<figcaption>',{
                class : 'pCaption',
                text : objPost[4].texto
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
       
        )).append($('<section>',{
            id : 'salir'
        }).append($('<input>',{
            type : 'button',
            id : 'salirSlider',
            value : 'salir'
        })).on('click',function(){
                   $("#contPostSeleccionado").remove();
                   $("#mostrarPostSeleccionado").addClass('oculto');
                   $("#ocultar").addClass('oculto');
                   cargarContenidoPorSeccion();
            }));

        var tmp = "";
          
           for (var i =0; i < objPost[5].length; i++){
                if(objPost[5][i].pbsQueridas === ''){
                   continue;   
                }else{
                    tmp += '<li>'+objPost[5][i].pbsQueridas+'</li>';
                }
            }
           $('#lista>ol').append(tmp);
           
           
       $('#cuerpo').on('click','.elegir',function(){
          $(this).css('color' ,'#ed7016');
         
       }); 
       
       
            
            
//fin cargarSlider    
}




