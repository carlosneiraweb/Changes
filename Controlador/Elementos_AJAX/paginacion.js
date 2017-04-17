
var tmpLi, liPinchado, ultimoLi;

/**
 * @description
 *  Cada vez que pulsamos un <li> de navegacion se muestran los siguientes Posts
 *  o posteriores. En cada pagina se muestran x posts,
 *  Esto se limitan con la constante PHP PAGE_SIZE.
 *  Modificando esta constante podemos cambiar el numero de 
 *  posts mostrados en cada pagina.
 *  
 *  @augments {type} integer
 *  li enteros
 *  Recive el li pinchado
 *  
 */


function navegarPorPosts(li){
    
     
            inicio = li * PAGESIZE;
            
            //Tambien nos aseguramos de recargar la lista <li> con los numeros 
            //adecuados a cada paso, sea del 1-10 o 110-120
            //En el metodo cargarPost del archivo mostrarPosts
            //hay un bucle for con la variable numeroEnLi de contador
            //que carga los <li> de la paginacion.
            
            tmpLi = parseInt($('.pagina').last().html())+ 1;
            numeroEnLi = parseInt($('.pagina').first().html());
           
            cargarPeticion("PPS", "opcion=PPS&inicio="+inicio); //Cargar Post
            
       
//fin navegarPorPosts    
}


/**
 * @description 
 * Este metodo muestra el siguiente rango de
 * numeros de <li> si el usuario pincha el
 * boton siguiente.
 * Si estamos en el <li> con valor 9 
 * mostrara los posts dentro del rango de
 * los <li> del 10 al 19.
 * Modificamos 2 variables del bucle for del metodo
 * cargarPosts del archivo mostrarPosts
 * que muestra los <li>
 * tmpLi => variable que hace de tope en el for
 * numeroEnLi => el numero que parece en el <li>
 * numLi => Numero de posts totales
 */
function  mostrarSiguienteRango(){
    
         ultimoLi = parseInt($('.pagina').last().html()) +1;
        
            if(numLi <= ultimoLi){
                tmpLi = numLi;
                inicio = 0;
                
                }else{
                    //En caso que el numero de post
                    //Sea mayor que el siguiente rango
                    //Osea que el siguiente rango por ejemplo
                    //valla del 30 al 40 
                    //y hay mas de 40 posts
                    if (numLi >= PAGESIZE * (ultimoLi + 1)) {
                            tmpLi = ultimoLi + 10;
                            numeroEnLi = ultimoLi; 
                            inicio =  (parseInt($('.pagina').last().html()) + 1) * PAGESIZE;
                        //Este ultimo caso es que ya no queden mas
                        //posts para mostrar un rango mas.
                        //Osea haya 35 posts y nos encontremos en el rango 
                        // del 20 al 30. Si pulsamos siguiente
                        // no podemos mostrar <lis> del 30 al 40
                        //Sino del 30 al ?? Dependiendo de la variable PAGESIZE
                        }else{  
                            tmpLi = totalPost / PAGESIZE; //Numeros de <li>
                            //Si al dividir sale decimal le sumamos un <li>
                                if (tmpLi % 1 !== 0){
                                    tmpLi++;
                                    }
                        //Parseamos a Integer y ya tenemos el total de <li> a mostrar
                            tmpLi = parseInt(tmpLi);
                            numeroEnLi = ultimoLi; 
                            inicio =  (parseInt($('.pagina').last().html()) + 1) * PAGESIZE;
                    }
                
                
                
                //inicio = inicio + (PAGESIZE * 10);
                cargarPeticion("PPS", "opcion=PPS&inicio="+inicio); //Peticion CargarPost
            } 
    
    
//fin mostrarSiguienteRango    
}

/**
 * Este metodo muestra el rango de <li> anterior
 * cuando se pulsa el boton Atras de la lista de <li>
 * Modifica dos variables del for del metodo cargarPosts
 * del archivo mostrarPosts
 * 
 */
function mostrarAnteriorRango(){
    
    primerLi = parseInt($('.pagina').first().html());
         
            if(primerLi >= 10){
                tmpLi = primerLi;
                numeroEnLi = primerLi - 10;
                inicio = (primerLi - 10) * PAGESIZE;
                 //Peticion CargarPost
            }else{
                inicio = 0; 
            }
    cargarPeticion("PPS", "opcion=PPS&inicio="+inicio);
//fin mostrarAnteriorRango    
}