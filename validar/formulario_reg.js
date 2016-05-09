$(document).ready(function(){
     
	var emailReg = /^([a-zA-Z0-9_.-])+@(([a-zA-Z0-9-])+.)+([a-zA-Z0-9]{2,4})+$/;
	var passReg = /^[0-9a-zA-Z]{6,10}$/;
        
        $("#registrar").on('click', mostrarCapaOpaca); 
       
        //Validar Nombre Usuario
        function validarNombreUsuario(){
            $(".error").remove();
                if($("#nameReg").val() === ""){
			$("#nameReg").focus().after("<span class='error'><p>Introduce un nombre</p></span>");
			$('#nameReg').addClass('borderColor');
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
                        $("#nickReg").focus().after("<span class='error'><p>Introduce un nick</p></span>");
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
    
                if($("#passReg1").val() === "" || !passReg.test($("#passReg1").val())){
			$("#passReg1").focus().after("<span class='error'><p>El password solo puede tener letras y números, minimo 6 y máximo 10</p></span>");
			$('#passReg1').addClass('borderColor');
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
			$("#passReg2").focus().after("<span class='error'><p>El password solo puede tener letras y números, minimo 6 y máximo 10</p></span>");
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
            
                if ($('#passReg1').val() !== $('#passReg2').val()){
			$("#passReg1").focus().after("<span class='error'><p>Lo sentimos pero los password no coinciden</p></span>");
			$('#passReg1').addClass('borderColor');
                    return false;
                } else{
                    return true;
                }
 
        //fin validarIgualdadPass    
        }
        
        //Validamos el email
        function validarEmail(){
            $(".error").remove();
            
                if($("#emailReg").val() === "" || !emailReg.test($("#emailReg").val()) ){
			$("#emailReg").focus().after("<span class='error'><p>Introduce un email correcto</p></span>");
			$('#emailReg').addClass('borderColor');
                        return false;
                    }else{
                        return true;
                    }
            
        //fin validarEmail    
        }
        
        
        $('#btn_registro').on('mouseover',function(){
            if(validarNombreUsuario() && validarNickReg() && validarPassReg1() && validarPassReg2() && validarIgualdadPass() && validarEmail()){
            //Se ponen dos false por que no queremos 
            //enviar el formulario hasta que lo valide PHP
                $('#form_registro').submit(function(){ 
                    
                    $("#form_registro").removeClass('mostrar_formulario registro_form_tamanyo').addClass('oculto');	
                    $("#ocultar").removeClass('mostrar_tranparencia').addClass('oculto');      
                      
                });
                     return false;
           } else{
                $('#form_registro').submit(function(){                     
                     return false;                   
               });
           }
      });
       
         

        //Estos dos métodos nos sirven para mostrar
	//o ocultar la capa semitransparente de fondo
	function mostrarCapaOpaca(){
	$("#ocultar").removeClass('oculto').addClass('mostrar_transparencia');
        mostrarRegistroForm();
								
	}
	
        //Metodos que nos permiten mostrar los formularios
        function mostrarRegistroForm(){
	$('#form_registro').removeClass('oculto').addClass('mostrar_formulario');    
        //fin mostrarLogin
    }
    
    

        
//fin formulario_reg        
});


