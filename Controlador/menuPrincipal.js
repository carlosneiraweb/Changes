    
$(function(){
       currentSlider = 0,
       nextSlider = 1,
       pos = currentSlider;
      
       //Añadimos eventos         
       $('#cuerpo').on('mouseover','#derecha, #izquierda',function(){
        pb = {};
		pb.el = $('#slider');
		pb.items = {
		panel: pb.el.find('li')
		}
       lengthSlider = pb.items.panel.length;
       $('#derecha').click(function(e){
           
			changePanel(1);
		});
       $('#izquierda').click(function(e){
			 
			changePanel(-1);
		});
       //Nos deplazamos por los li segun parametro que le mandemos
                function changePanel(test){
		var panels = pb.items.panel; //importante
		if(test > 0){
                    if(nextSlider <= (lengthSlider -1)){
			pos +=1
			mostrarDelante();
			$('#izquierda').removeClass('ocultar');
                            if(currentSlider == 2){
                                $('#derecha').addClass('ocultar');
                            }
                        }
		}
			
		if(test < 0){
                    if(currentSlider > 0){
			$('#derecha').removeClass('ocultar');
                        pos = pos -1;
			mostrarAtras();
                            if(currentSlider === 0){
                                $('#izquierda').addClass('ocultar');
                            }
                    }
		}
	
		function mostrarAtras(){	
		//Efectos
                nextSlider = pos+1;
		currentSlider = pos;
		panels.eq(currentSlider).fadeIn('slow');
		panels.eq(nextSlider).fadeOut('slow');
                //Eliminamos el evento 'Importante'
                $('#cuerpo').off('mouseover','#derecha, izquierda');
		}
			
		function mostrarDelante(){	
		//Efectos
		panels.eq(currentSlider).fadeOut('slow');
		panels.eq(nextSlider).fadeIn('slow');
                currentSlider = pos;
                nextSlider = pos+1;
                //Eliminamos el evento 'Importante'
                $('#cuerpo').off('mouseover','#derecha, #izquierda');	  	
                }
	
		
            //fin changePanel	
                }
     //fin añadirEventos
       });   
       
   
//fin script
});

