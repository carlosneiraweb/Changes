$(document).ready(function(){
        ///[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/
        //
	var emailReg = /^([a-zA-ZñÑ0-9_.-])+@(([a-zA-Z0-9-])+.)+([a-zA-Z0-9]{2,4})+$/;
	var passReg = /^[0-9a-zA-ZñÑ]{6,12}$/;
        var telefReg = /^[0-9]{9,11}/;
        var codPostal = /^([1-9]{2}|[0-9][1-9]|[1-9][0-9])[0-9]{3}$/;
        
        /**
         * @description Validamos que el nombre de  
         * usuario no este vacio
         * @returns {Boolean}
         */
        function validarNombreUsuario(){
            //$(".error").remove();
                if($("#nombre").val() === ""){
			$("#nombre").focus().after("<span class='error'><p>Introduce un nombre de usuario.</p></span>");
			$('#nombre').addClass('borderColor');
                        return false;
                    }else{
                        return true;
                    }
            
        //fin validarNombreUsuario    
        }
        
      /**
       * @description Validamos el nick  usuario no este vacio
       * @returns {Boolean}
       */                                            
        function validarNickReg(){         
            //$(".error").remove();
		//Comprobamos que los campos no estan vacios
                var nick = $("#nick").val();
                    if( nick === ""){
                        $("#nick").focus().after("<span class='error'><p>Introduce un nick de usuario.</p></span>");
			$('#nick').addClass('borderColor');
                        return false;
                    } else{ 
                        return true;
                    }
                
        //fin validarNick
        }
        
        /**
         * @description Validamos el primer password
         * @returns {Boolean}
         */
        function validarPassReg1(){
           // $(".error").remove();
                var contenido = $("#password").val();
                   if (!contenido.match(passReg)) {
                       $("#password").focus().after("<span class='error'><p>El password solo puede tener letras y números, minimo 6 y máximo 12.</p></span>");
                       $('#password').addClass('borderColor');
                        return false;
                    }else{
                        return true;
                       
                    }
             
            
        //fin validarPassReg1    
        }
        
       
        /**
         * @description Validamos el segundo password
         * @returns {Boolean}
         */
        function validarPassReg2(){
            //$(".error").remove();
                var contenido = $("#passReg2").val();
                   if (!contenido.match(passReg)) {
                       $("#passReg2").focus().after("<span class='error'><p>El password solo puede tener letras y números, minimo 6 y máximo 12.</p></span>");
			$('#passReg2').addClass('borderColor');
                        return false;
                    }else{
                        return true;
                      
                    }
           
            
        //fin validarPasswordReg    
        }
        
         /**
          * @description Validamos la igualdad @description los passwords
          * @returns {Boolean}
          */
        function validarIgualdadPass(){
            //$(".error").remove();
                var pass1 = $('#password').val();
                var pass2 = $('#passReg2').val();
                if (pass1 !== pass2){
			$("#password").focus().after("<span class='error'><p>Lo sentimos pero los password no coinciden.</p></span>");
			$('#password').addClass('borderColor');
                    return false;
                } else{
                    return true;
                }
         
        //fin validarIgualdadPass    
        }
        
        /**
         * @description Validamos el email
         * @returns {Boolean}
         */
        function validarEmail(){
            //$(".error").remove();
            //emailReg.test($("#email").val()
                if($("#email").val() === "" || !emailReg.test($('#email').val().trim()) ){
			$("#email").focus().after("<span class='error'><p>Introduce un email correcto.</p></span>");
			$('#email').addClass('borderColor');
                        return false;
                    }else{
                        return true;
                    }
            
        //fin validarEmail    
        }
        
        /**
         * @description Validamos que el campo nombre
         * no este vacio
         * @returns {Boolean}
         */
    
        function validarNombre(){
            $(".error").remove();        
            
                if ($("#nombre").val() === ""){
                    $("#nombre").focus().after("<span class='error'><p>Tienes que introducir un nombre</p></span>");
			$('#nombre').addClass('borderColor');
                        return false;
                    }else{
                        return true;
                    }
                  
            //fin validarNombre    
            }
         /**
          * @description Validamos que el campo ciudad no este vacio
          * @returns {Boolean}
          */   
        function validarCiudad(){
            $(".error").remove();        
            // 

                if ($("#ciudad").val() === "" ){
                    $("#ciudad").focus().after("<span class='error'><p>Tienes que introducir una ciudad o pueblo.</p></span>");
			$('#ciudad').addClass('borderColor');
                        return false;
                    }else{
                        return true;
                    }
         
        //fin  validarCodigoPostal   
        }
        
        /**
         * @description Validamos el codigo postal
         * 4 digitos y que sean numeros
         * @returns {Boolean}
         */
        function validarCodigoPostal(){
            $(".error").remove();        
            // 

                if ($("#codPostal").val() === "" || !codPostal.test($("#codPostal").val())){
                    $("#codPostal").focus().after("<span class='error'><p>Tienes que introducir un código postal correcto.</p></span>");
			$('#codPostal').addClass('borderColor');
                        return false;
                    }else{
                        return true;
                    }
         
        //fin  validarCodigoPostal   
        }
        
    /**
     * @description Validamos que el numero sea correcto y
     * no empieze por un numero  pago tipo 8xxxx
     * @returns {Boolean}
     */    
    function validarTelefono(){
       
        $(".error").remove();
        var numero = $("#telefono").val();
        numero = numero.substr(0,1);

        if($("#telefono").val() === "" || !telefReg.test($("#telefono").val()) || numero === '8'){
           
            $("#telefono").focus().after("<span class='error'><p>Introduce un telefono correcto. No se permiten números de pago del tipo 8xxxx</p></span>");
			$('#telefono').addClass('borderColor');
                        return false;
                    }else{
                        return true;
                    }
   
        //fin validarTelefono    
        }
            
    
        $("#cuerpo").on('mouseover', '#primeroSigReg',function(){
            if (validarNickReg()) {
                if (validarPassReg1()) {
                    if(validarPassReg2()){
                        if(validarIgualdadPass()){
                             if(validarEmail()){
                                  
                             }
                        }
                    }
                }
            }    
        
           
      });
      
        $('#cuerpo').on("mouseover","#segundoSigReg", function(){
            if(validarNombreUsuario()){  
                if (validarTelefono()) {    
            }
           }  
        });
        
        $('#cuerpo').on("mouseover","#terceroSigReg", function(){
           if(validarCiudad()){
               if (validarCodigoPostal()) {   
                }
           }
            
        });
        
        $("#registro_1").on('keypress', '#passReg2',function(){
           $('.error').remove();
      });
        $("#registro_1").on('keypress', '#password',function(){
           $('.error').remove();
      });
        $("#registro_1").on('keypress', '#nick',function(){
           $('.error').remove();
      });
        $("#registro_1").on('keypress', '#email',function(){
           $('.error').remove();
      });
        $("#registro_2").on('keypress', '#nombre',function(){
           $('.error').remove();
      });
        $("#registro_2").on('keypress', '#telefono',function(){
           $('.error').remove();
      });
        $("#registro_3").on('keypress', '#ciudad',function(){
           $('.error').remove();
      });
        $("#registro_3").on('keypress', '#codPostal',function(){
           $('.error').remove();
      });
        
//fin formulario_reg        
});


