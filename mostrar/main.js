$(function(){
   //funcion autoejecutable
   var SliderModule = (function(){
       //creamos objeto contiene el slider
       var pb = {};
       //le agregamos el slider
       pb.el = $('#sliderImg');
       //le agregamos los elementos items
       pb.items = {
           //los items van en un array
           panel: pb.el.find('li')
       }
       
       //Variables
       var  SliderInterval,
            currentSlider = 0,
            nextSlider = 1,
            lengthSlider = pb.items.panel.length;
            
       //creamos el constructor
       pb.init = function(settings){
           //Activamos el slider
          SliderInit();
           //Cada vez que se pulsa un li se ejecuta
           $('#slider-controls').on('click', 'li', function(e){
               var $this = $(this);
               //si el slider actual no se esta mostrando
              // alert($this.index());
               changePanel($this.index());
               if(currentSlider !== $this.index()){
                   
           };
           });
       }
       
       var SliderInit = function(){
          //Inicializamos el intervalo
         // SliderInterval = setInterval(pb.startSlider, 1000); //El objeto llama a una funcion
       }
       
       
       pb.startSlider = function(){
           var panels = pb.items.panel, //cojemos los paneles
                controls = $('#slider-controls li');
           
           if(nextSlider >= lengthSlider){
               nextSlider = 0;
               currentSlider = lengthSlider -1;
           }
          //Efectos
          controls.removeClass('active').eq(currentSlider).addClass('active');
          panels.eq(currentSlider).fadeOut('slow');
          panels.eq(nextSlider).fadeIn('slow');
          
           //Actualizar datos
           currentSlider = nextSlider;
           nextSlider += 1;
           
       }
       
       
       //Funcion para los controladores
       var changePanel = function(id){
           //clearInterval(SliderInterval);
           var panels = pb.items.panel,
               controls = $('#slider-controls li');
           //Comprobamos el id
           if(id >= lengthSlider){
               id = lengthSlider -1;
           }else if(id <=0){
               id = 0;
           }
        controls.removeClass('active').eq(id).addClass('active');
        panels.eq(currentSlider).fadeOut('slow');
        panels.eq(id).fadeIn('slow');  
           //Actualizamos datos
           currentSlider = id;
           nextSlider = id+1;
           
           // SliderInit(); //REACTIVAMOS EL SLIDER
       }
       
       
       //retornamos el objeto
       return pb;
   }());
    
    SliderModule.init();
    
    
    
    
    
});