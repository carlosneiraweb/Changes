


/**
* @description 
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



