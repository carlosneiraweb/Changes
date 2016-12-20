$(document).ready(function(){
        ///[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/
        //
	var emailReg = /^([a-zA-ZñÑ0-9_.-])+@(([a-zA-Z0-9-])+.)+([a-zA-Z0-9]{2,4})+$/;
	var passReg = /^[0-9a-zA-ZñÑ]{6,10}$/;
        var telefReg = /^[0-9-()+]{3,20}/;
        var codPostal = /^([1-9]{2}|[0-9][1-9]|[1-9][0-9])[0-9]{3}$/;
        
        //Validar Nombre Usuario
        function validarNombreUsuario(){
            $(".error").remove();
                if($("#nick").val() === ""){
			$("#nick").focus().after("<span class='error'><p>Introduce un nombre de usuario.</p></span>");
			$('#nick').addClass('borderColor');
                        return false;
                    }else{
                        return true;
                    }
            
        //fin validarNombreUsuario    
        }
        
        //validamos formulario login                                               
        function validarNickReg(){         
            $(".error").remove();
		//Comprobamos que los campos no estan vacios
                    if($("#nickReg").val() === ""){
                        $("#nickReg").focus().after("<span class='error'><p>Introduce un nick de usuario.</p></span>");
			$('#nickReg').addClass('borderColor');
                        return false;
                    } else{ 
                        return true;
                    }
        //fin validarNick
        }
        
        //Validamos el password
        function validarPassReg1(){
            $(".error").remove();
    
                if($("#passord").val() === "" || !passReg.test($("#password").val())){
			$("#password").focus().after("<span class='error'><p>El password solo puede tener letras y números, minimo 6 y máximo 12.</p></span>");
			$('#password').addClass('borderColor');
                        return false;
                    }else{
                        return true;
                    }
            
        //fin validarPassReg1    
        }
        
       
        //Validamos el password2
        function validarPassReg2(){
            $(".error").remove();
            
                if($("#passReg2").val() === "" || !passReg.test($("#passReg2").val())){
			$("#passReg2").focus().after("<span class='error'><p>El password solo puede tener letras y números, minimo 6 y máximo 12.</p></span>");
			$('#passReg2').addClass('borderColor');
                        return false;
                    }else{
                        return true;
                    }
            
        //fin validarPasswordReg    
        }
        
         //Validamos que los paswords sean identicos
        function validarIgualdadPass(){
            $(".error").remove();
            
                if ($('#password').val() !== $('#passReg2').val()){
			$("#password").focus().after("<span class='error'><p>Lo sentimos pero los password no coinciden.</p></span>");
			$('#password').addClass('borderColor');
                    return false;
                } else{
                    return true;
                }
 
        //fin validarIgualdadPass    
        }
        
        //Validamos el email
        function validarEmail(){
            $(".error").remove();
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
        
        
    function validarTelefono(){
       
        $(".error").remove();
        
        if($("#telefono").val() === "" || !telefReg.test($("#telefono").val())){
           
            $("#telefono").focus().after("<span class='error'><p>Introduce un telefono correcto.</p></span>");
			$('#telefono').addClass('borderColor');
                        return false;
                    }else{
                        return true;
                    }
   
        //fin validarTelefono    
        }
            
    
        $('#primero').on('mouseover',function(){
            if(validarNombreUsuario() && validarNickReg() && validarPassReg1() && validarPassReg2() && validarIgualdadPass() && validarEmail()){    
           }
      });
      
        $('#segundo').on("mouseover", function(){
           if(validarNombre() && validarTelefono()){           
           }  
        });
        
        $('#tercero').on("mouseover", function(){
           if(validarCiudad() && validarCodigoPostal()){}
            
        });
        
//fin formulario_reg        
});


