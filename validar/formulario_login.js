$(document).ready(function(){
   
	var passReg = /^[0-9a-zA-Z]{6,10}$/;
	//Recojemos valores
        var nick = $("#nickLogin");
        var pass = $("#passLogin");
          
    $("#ingresar").on('click', mostrarCapaOpaca); 
      
            //validamos formulario login                                               
            function validarNick(){
                $(".error").remove();
            
		//Comprobamos que los campos no estan vacios
                    if(nick.val() === ""){
                        $("#nickLogin").focus().after("<span class='error'><p>Introduce un nick</p></span>");
			$('#nickLogin').addClass('borderColor'); 
                        return false;
                    } else{ 
                        return true;
                    }
        //fin validarNick
            }
            
            function validarPassword(){
                $(".error").remove();
		    if(pass.val() === "" || !passReg.test(pass.val())){
                    
                        $("#passLogin").focus().after("<span class='error'><p>El password solo puede tener letras y números, minimo 6 y máximo 10</p></span>");
			$('#passLogin').addClass('borderColor');
                        return false;
                    } else{
                        return true;
                    }
        //fin validar password
            } 
    
        $('#btn_login').on('mouseover',function(){
            if(validarNick() && validarPassword()){
               
            //Se ponen dos false por que no queremos 
            //enviar el formulario hasta que lo valide PHP
           
                $('#form_login').submit(function(){ 
                    $("#login_form").removeClass('mostrar_formulario login_form_tamanyo').addClass('oculto');	
                    $("#ocultar").removeClass('mostrar_tranparencia').addClass('oculto');      
                    
                     
                });
             
                return false; 
            } else{
                $('#form_login').submit(function(){                     
                     return false;                   
               });
            }
        });
   
   
        //Estos dos métodos nos sirven para mostrar
	//o ocultar la capa semitransparente de fondo
	function mostrarCapaOpaca(){
	$("#ocultar").removeClass('oculto').addClass('mostrar_transparencia');
        mostrarLogin();
								
	}
	
        //Metodos que nos permiten mostrar los formularios
        function mostrarLogin(){
	$("#login_form").removeClass('oculto').addClass('mostrar_formulario');
        //fin mostra rLogin
    }

//fin cuerpo  

});
