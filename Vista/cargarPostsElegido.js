/**
 * @author Carlos Neira Sanchez
 * @mail arj.123@hotmail.es
 * @telefono ""
 * @nameAndExt cargarPostsElegido.js
 * @fecha 26-oct-2016
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
       // alert(objPost[0][0].ruta);
        //Agregamos las imagenes al Slider 
        
        $("#ocultar").removeClass('oculto').addClass('mostrar_transparencia');
        $("#mostrarPostSeleccionado").removeClass('oculto');
        ///Creamos elementos
         $("#mostrarPostSeleccionado").append($("<section>",{
             id : 'contPostSeleccionado'
         }).append($('<figure>',{
            id : 'sliderIMG',
            class : 'slider-wrapper-IMG'
        }).append($('<img>',{
            src : "../photos/"+objPost[0][0].ruta+".jpg"
        })).append($('<div>',{
                class : 'caption',
                text : objPost[0][0].texto
            }))
                
        ).append($('<ul>', {
            class : 'slider-controls',
            id : 'slider-controls'
        }).append($('<li>',{
                    
        }).on('click',function(){
            $('#sliderIMG img').remove();
            $('#sliderIMG div').remove();
            $('#sliderIMG').append($('<img>',{
            src : "../photos/"+objPost[0][0].ruta+".jpg"
            })).append($('<div>',{
                class : 'caption',
                text : objPost[0][0].texto
            }));
           
        })
        ).append($('<li>',{
                    
            }).on('click',function(){
                $('#sliderIMG img').remove();
                $('#sliderIMG div').remove();
                $('#sliderIMG').append($('<img>',{
                src : "../photos/"+objPost[0][1].ruta+".jpg"
            })).append($('<div>',{
                class : 'caption',
                text : objPost[0][1].texto
            }));
         })    
            
       ).append($('<li>',{
                    
            }).on('click',function(){
                $('#sliderIMG img').remove();
                $('#sliderIMG div').remove();
                $('#sliderIMG').append($('<img>',{
                src : "../photos/"+objPost[0][2].ruta+".jpg"
            })).append($('<div>',{
                class : 'caption',
                text : objPost[0][2].texto
            }));
        })        
            
            
        ).append($('<li>',{
                    
            }).on('click',function(){
                $('#sliderIMG img').remove();
                $('#sliderIMG div').remove();
                $('#sliderIMG').append($('<img>',{
                src : "../photos/"+objPost[0][3].ruta+".jpg"
            })).append($('<div>',{
                class : 'caption',
                text : objPost[0][3].texto
            }));
        })     
                
        ).append($('<li>',{
                    
            }).on('click',function(){
                $('#sliderIMG img').remove();
                $('#sliderIMG div').remove();
                $('#sliderIMG').append($('<img>',{
                src : "../photos/"+objPost[0][4].ruta+".jpg"
            })).append($('<div>',{
                class : 'caption',
                text : objPost[0][4].texto
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
                   jsonVolver[6] = "POST-SELECCIONADO";
                   vistaIndependiente = false;
                   $("#contPostSeleccionado").remove();
                   $("#mostrarPostSeleccionado").addClass('oculto');
                   $("#ocultar").addClass('oculto');
                   cargarContenidoPorSeccion();
            })));

        var tmp = "";
          
           for (var i =0; i < objPost[1].length; i++){
           tmp += '<li>'+objPost[1][i].pbsQueridas+'</li>'; 
                }
           $('#lista>ol').append(tmp);
           
            
//fin cargarSlider    
}



