/**
 * @author Carlos Neira Sanchez
 * @mail arj.123@hotmail.es
 * @telefono ""
 * @nameAndExt formulario_login.js
 */


$(document).ready(function(){
       
	var passReg = /^[0-9a-zA-Z]{6,12}$/;
	//Recojemos valores
        var nick = $("#nick");
        var pass = $("#password");
         
    $("#ingresar").on('click', mostrarCapaOpaca);
    $('#btn_salir').click(function(){
    $(this).addClass("oculto");
    });
  
   
           /**
            * @description Validamos que el nick de 
            * login no este vacio
            * @returns {Boolean}
            */                                               
            function validarNick(){
                
		//Comprobamos que los campos no estan vacios
                    if(nick.val() === ""){
                        $("#nick").focus();
                        $("label[for='nick']").css('color', 'red');//addClass("errorPHP");
			
                        return false;
                    } else{ 
                        return true;
                    }
        //fin validarNick
            }
            
            /**
             * @description Validamos password
             * del login
             * @returns {Boolean}
             */
            function validarPassword(){
           
		    if(pass.val() === "" || !passReg.test(pass.val())){
                        $("label[for='password']").css('color', 'red');//.addClass("errorPHP");
                        $("#password").focus();
                                
                    return false;
                    } else{
                        return true;
                    }
        //fin validar password
            }
            
            
            /**
             * Metodo que cambia color de la label
             * al no dar por buena la validacion
             * 
             */
         
            $('#nick').keydown(function() { 
                $(this).prev().prev().css('color', 'black');
            });
            $('#password').keydown(function() { 
                $(this).prev().prev().css('color', 'black');
            });
       
            /**
             * Metodo que cambia al
             * color original la label
             */
       
            $('#nick').blur(function() { 
               
                $(this).prev().prev().css('color','#0C0792' );//
               //$('#login_form,  type="text"').css('color' , 'black');
            });
            $('#password').blur(function() { 
               
                $(this).prev().prev().css('color','#0C0792' );//
               //$('#login_form,  type="text"').css('color' , 'black');
            });
            
            
            
            
        
            
       $('#btn_login').on('mouseover',function(){
          
            if(validarNick() && validarPassword()){
               
           }

        });
   
  
        /**
         * @description Elimina la clase oculto 
         * y añade la clase mostrar_transparencia  del formulario de login
         * @returns {undefined}
         */
	function mostrarCapaOpaca(){
	$("#ocultar").removeClass('oculto').addClass('mostrar_transparencia');
        mostrarLogin();
								
	}
	
        /**
         * @description Elimina la clase oculto y añade
         * la clase mostrar_formulario 
         * @returns {undefined}
         */
        function mostrarLogin(){
	$("#login_form").removeClass('oculto').addClass('mostrar_formulario');
        //fin mostrar Login
    }

//fin cuerpo  

});



