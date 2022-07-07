
/**
 * @author carlos
 * @mail Expression mail is undefined on line 11, column 12 in Templates/Other/javascript.js.
 * @telefono Expression telefono is undefined on line 12, column 16 in Templates/Other/javascript.js.
 * @nameAndExt iconoObligatorio.js
 * @fecha 19-jun-2020
 */

var milisegundos = 700;

if(typeof(t) === "undefined"){ 
        var t = 0;
        setInterval('parpadearSubirLogin()', milisegundos);
    }   
    
    
    
/****METODO PARA HACER PARPADEAR LOS CAMPOS OBLIGATORIOS AL REGISTRARSE*********

/**
 * @description Este metodo oculta el gif  los campos obligatorios
 */

function parpadearSubirLogin() {     
    
    var cociente = t % 2;
    if(cociente === 1){
       $('span.obligatorio').addClass('ocultar');
    } else {
       $('span.obligatorio').removeClass('ocultar'); 
    }
    t++;
    
    
//fin parpadear                    
}