$(document).ready(function(){
        ///[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/
        //
	var emailReg = /^([a-zA-ZñÑ0-9_.-])+@(([a-zA-Z0-9-])+.)+([a-zA-Z0-9]{2,4})+$/;
	var passReg = /^[0-9a-zA-ZñÑ]{6,12}$/;
        var telefReg = /^[9|7|6 ]{1}([\d]{2}[-]*){3}[\d]{2}$/;  
        var codPostal = /^([1-9]{2}|[0-9][1-9]|[1-9][0-9])[0-9]{3}$/;
        
              /**
       * @description Validamos el nick  usuario no este vacio
       * @returns {Boolean}
       */                                            
        function validarNickReg(){         
            //$(".error").remove();
		//Comprobamos que los campos no estan vacios
                var nick = $("#nick").val();
                    if( nick === ""){
                        $("#nick").focus();
			$("label[for='nick']").css('color', 'red');
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
           
                var contenido = $("#password").val();
                   if (!contenido.match(passReg)) {
                       $("#password").focus();
                       $("label[for='password']").css('color', 'red');
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
           
                var contenido = $("#passReg2").val();
                   if (!contenido.match(passReg)) {
                       $("#passReg2").focus();
                       $("label[for='passReg2']").css('color', 'red');
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
			$("#password").focus();
			$("label[for='password']").css('color', 'red');
                    return false;
                } else{
                    return true;
                }
         
        //fin validarIgualdadPass    
        }
        
         /**
         * @description Validamos que el nombre de  
         * usuario no este vacio
         * @returns {Boolean}
         */
        function validarNombreUsuario(){
           
                if($("#nombre").val() === ""){
			$("#nombre").focus();
			$("label[for='nombre']").css('color', 'red');
                        return false;
                    }else{
                        return true;
                    }
            
        //fin validarNombreUsuario    
        }
        
        
        
        /**
         * @description Validamos el email
         * @returns {Boolean}
         */
        function validarEmail(){
            //$(".error").remove();
            //emailReg.test($("#email").val()
                if($("#email").val() === "" || !emailReg.test($('#email').val().trim()) ){
			$("#email").focus();
                        $("label[for='email']").css('color', 'red');
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
                        $("#nombre").focus();
			$("label[for='nombre']").css('color', 'red');
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
                        $("#ciudad").focus();
                        $("label[for='ciudad']").css('color', 'red');
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
                    $("#codPostal").focus();
                    $("label[for='codPostal']").css('color', 'red');
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
        var numeroTmp = numero.substr(0,1);
        
        if($("#telefono").val() === "" || !telefReg.test(numero)  || numeroTmp === '8'){
           
                $("#telefono").focus();
                $("label[for='telefono']").css('color', 'red');
                        return false;
                    }else{
                        return true;
                    }
   
        //fin validarTelefono    
        }
            
         
        $("#primeroSigReg").on('mouseover',function(){
            if(validarNickReg()){
                if (validarPassReg1()) {
                    if(validarPassReg2()){
                        if(validarNombreUsuario()){
                            if(validarIgualdadPass()){
                                if(validarEmail()){
                                }  
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
        
        
               /**
             * Metodo que cambia color de la label
             * al no dar por buena la validacion
             * 
             */
         
            $('#form_registro_1, input').keydown(function() { 
            $(this).prev().prev().css('color', 'black');
            });
       
            /**
             * Metodo que cambia al
             * color original la label
             */
       
            $('#form_registro_1, input').blur(function() { 
            $(this).prev().prev().css('color', '#0C0792');
            });
        
//fin formulario_reg        
});


