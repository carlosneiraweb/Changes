
var  liPinchado, ultimoLi;

/**
 * @description
 *  Cada vez que pulsamos un <li> de navegacion se muestran los siguientes Posts
 *  o posteriores. En cada pagina se muestran 'n' posts,
 *  Esto se limitan con la constante PHP PAGE_SIZE.
 *  Modificando esta constante podemos cambiar el numero de 
 *  posts mostrados en cada pagina facilmente.
 *  
 *  @augments {type} integer
 *  li enteros
 *  Recive el li pinchado
 *  
 */


function navegarPorPosts(li){
    
            //Tambien nos aseguramos de recargar la lista <li> con los numeros 
            //adecuados a cada paso, sea del 1-10 o 110-120
            //En el metodo cargarPost del archivo mostrarPosts.js
            //hay un bucle for con la variable numeroEnLi de contador
            //que carga los <li> de la paginacion.
            
            tmpLi = parseInt($('.pagina').last().html())+ 1;
            numeroEnLi = parseInt($('.pagina').first().html());
            
            
            inicio = li * PAGESIZE;
            cargarContenidoPorSeccion();
            
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
 * cargarPosts del archivo mostrarPosts.js
 * que muestra los <li>
 * tmpLi => variable que hace de tope en el for
 * numeroEnLi => el numero que parece en el <li>
 * numLi => Total de post encontrados en la busqueda
 */
function  mostrarSiguienteRango(e){
        
   
        ultimoLi = parseInt($('.pagina').last().html()) +1;
        
       
            if(numLi <= ultimoLi){
                tmpLi = numLi;
                inicio = 0;
                $('#btn_navegacion').off('click', 'ul>li.siguiente', mostrarSiguienteRango);
               
                }else{
                
                    //En caso que el numero de post
                    //Sea mayor que el siguiente rango
                    //Osea que el siguiente rango por ejemplo
                    //valla del 30 al 40 
                    //y hay mas de 40 posts
                    if (numLi >= (ultimoLi + 10)) {
                        
                            tmpLi = ultimoLi + 10;
                            numeroEnLi = ultimoLi; 
                            inicio =  (parseInt($('.pagina').last().html()) + 1) * PAGESIZE;
                        //Este ultimo caso es que ya no queden mas
                        //posts para mostrar un rango mas completo.
                        //Osea haya 35 posts y nos encontremos en el rango 
                        // del 20 al 30. Si pulsamos siguiente
                        // no podemos mostrar <lis> del 30 al 40
                        //Sino del 30 al xxx Dependiendo de la variable PAGESIZE
                        }else{  
                           
                            tmpLi = (totalPost / PAGESIZE) ; //Numeros de <li>
                            //Si al dividir sale decimal le sumamos un <li>
                                if (tmpLi % 2 !== 0){
                                    tmpLi++;
                                    }
                           
                        //Parseamos a Integer y ya tenemos el total de <li> a mostrar
                            tmpLi = parseInt(tmpLi);
                           
                            inicio =  (parseInt($('.pagina').last().html()) + 1) * PAGESIZE;
                    }
                   
                //Mandamos la peticion JSON segun la seccion
                //en la que estemos
                cargarContenidoPorSeccion();    
               
            } 
            
    
    
    //Cuando ya no hay mas rangos completos posibles de posts
        //desabilitamos al boton siguiente el evento de llamada a la funcion
        primerLi = parseInt($('.pagina').first().html());
        if((((primerLi + 19) * PAGESIZE)) > totalPost){ 
           $('#btn_navegacion').off('click', 'ul>li.siguiente', mostrarSiguienteRango);     
        }
         
//fin mostrarSiguienteRango    
}

/**
 * @description 
 * Este metodo muestra el rango de <li> anterior
 * cuando se pulsa el boton Atras de la lista de <li>
 * Modifica dos variables del for del metodo cargarPosts
 * del archivo mostrarPosts.js
 * tmpLi => variable que hace de tope en el for
 * numeroEnLi => el numero que parece en el <li>
 * 
 */
function mostrarAnteriorRango(){
    
    
    //IMPORTANTE desanclar el evento por que si el usuario 
    //aprieta dos veces el boton de siguiente se asocia
    //el mismo numero de veces el evento que llama a esta funcion
    $('#btn_navegacion').off('click', 'ul.listaLis li.atras', mostrarAnteriorRango);
    
    
    primerLi = parseInt($('.pagina').first().html());
    //Evitamos el evento si el primerLi es 0
    if(primerLi !== 0){     
            if(primerLi >= 10){
                tmpLi = primerLi;
                numeroEnLi = primerLi - 10;
                inicio = (primerLi - 10) * PAGESIZE;
                 
            }else{
                tmpLi = 10;
                numeroEnLi = 0;
                inicio = 0; 
            }
   
    //Mandamos la peticion JSON segun la seccion
    //en la que estemos
    cargarContenidoPorSeccion();
    
    }
//fin mostrarAnteriorRango    
}


/**
 * @description 
 * Dependiendo de la variable mostrar
 * manda al metodo adecuado la peticion JSON
 * con los parametros adecuados.
 * @param {string} opcion
 * Es el encargado de mandar al script indicado
 * la url con la peticion JSON adecuada
 * cuando estamos paginando.
 */
function cargarContenidoPorSeccion(){
        if(jsonVolver[6] === 'ENCONTRADO'){
            opcion = jsonVolver[6];
        }else if(jsonVolver[6] === "POST-SELECCIONADO"){
            opcion = jsonVolver[6];
        }else{
            opcion = jsonVolver[0];
        }
        
        
        switch (opcion){
                case 'PPS':
                    cargarPeticion("PPS", "opcion=PPS&inicio="+inicio);
                        break;
                case 'ENCONTRADO':
                    cargarPeticionBuscador('ENCONTRADO', "opcion=ENCONTRADO&ENCONTRAR="+textoElegido+"&tabla="+radioBusqueda+"&inicio="+inicio);
                        break;
                case 'POST-SELECCIONADO':
                     
            }
    
    
//cargarContenidoPorSeccion    
}



/**
 * @description 
 * Al cambiar de seccion hay que volver a asignar
 * valores a los elementos de paginacion.
 * No hay los mismos posts en cada seccion
 * @param {type} posts
 * Entero con el numero total de posts encontrandos
 */
function resetearValoresDePaginacion(posts){
    
    numLi = (posts) / PAGESIZE; //Numeros de <li>
        //Si al dividir sale decimal le sumamos un <li>
            if ((numLi % 2 ) !== 0){
                numLi++;
            }
    numeroEnLi = 0;
    inicio = 0;
    
       
        //Parseamos a Integer y ya tenemos el total de <li> a mostrar
            numLi = parseInt(numLi);
           // alert('en resetear numLi '+numLi+ ' inicio '+inicio);     
        //Queremos limitar el numero de <li> a 10 por pagina
            //En caso de que numLi sea mayor a 10 * PAGESIZE
            if (numLi > PAGESIZE * 10){
                tmpLi = numeroEnLi + 10;
            }else{
                tmpLi = numLi;
            }        
    
      
//fin   resetearValoresDePaginacion  
}