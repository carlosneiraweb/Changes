$(document).ready(function(){
       
	var passReg = /^[0-9a-zA-Z]{6,12}$/;
	//Recojemos valores
        var nick = $("#nick");
        var pass = $("#password");
         
    $("#ingresar").on('click', mostrarCapaOpaca); 
      
           /**
            * @description Validamos que el nick de 
            * login no este vacio
            * @returns {Boolean}
            */                                               
            function validarNick(){
                
                $(".error").remove();
            
		//Comprobamos que los campos no estan vacios
                    if(nick.val() === ""){
                        $("#nick").focus().after("<span class='error'><p>Introduce un nick</p></span>");
			$('#nick').addClass('borderColor'); 
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
            //
                $(".error").remove();
		    if(pass.val() === "" || !passReg.test(pass.val())){
                    
                        $("#password").focus().after("<span class='error'><p>El password solo puede tener letras y números, minimo 6 y máximo 12</p></span>");
			$('#password').addClass('borderColor');
                        return false;
                    } else{
                        return true;
                    }
        //fin validar password
            } 
    
    
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
        //fin mostra rLogin
    }

//fin cuerpo  

});
