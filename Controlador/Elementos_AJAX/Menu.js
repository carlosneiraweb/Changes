

var  parametros, petMenu, objMenu;


            //Creamos una instancia de la clase CONEXION_AJAX
            //Nos devuelve una conexion AJAX y propiedades 
                    var ConMenu  = new Conexion();

  


    function cargarPeticionMenu(tipo, parametros){
alert('Estamos en cargarPeticionMenu y tipo vale: ' +tipo+ ' parametros vale: ' +parametros);
    //para comprobar el tipo de peticion
    
            petMenu = ConMenu.conection();
            petMenu.onreadystatechange = procesaRespuesta;
            petMenu.open('POST', "../Controlador/Elementos_AJAX/Menu.php?", true);
            petMenu.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
            petMenu.send(parametros);
               
    
    function procesaRespuesta(){
       
       if(this.readyState === ConMenu.READY_STATE_COMPLETE && this.status === 200){
            try{
               
               objMenu = JSON.parse(petMenu.responseText);
                    //Eliminamos el objeto conexion
                    delete ConMenu;
 
            } catch(e){
                switch(tipo){        

                    default:
                        
                       //location.href= 'index.php';
                }
            //fin catch
            }
            
                    banderaCambioSeccion = true;
                    //Tenemos que resetear todas las variables
                    //de paginacion cada vez que cambiamos de seccion
                    if(jsonVolver[0] != tipo){
                        cargarUrlVolver();
                    }
                        var totalPostEncontrados = (parseInt(objMenu[0].totalRows[0]) - 1);
                        if(banderaCambioSeccion){resetearValoresDePaginacion(totalPostEncontrados);};
                            jsonVolver[0] = tipo;
                            vistaIndependiente = false;
                    alert(objMenu);      
                    cargarPost(objMenu);
                      
                        
          
        //fin if
        }
    //fin procesaRespuesta    
    }
    
     

//fin cargarPeticionMenu
}
    
   
 
    
    
    
    
    
   
    
    
    
    
    
    
    
    
    
    
    
    
