$(document).ready(function(){
       
	var passReg = /^[0-9a-zA-Z]{6,15}$/;
	//Recojemos valores
        var nick = $("#nick");
        var pass = $("#email");
        
    $("#ingresar").on('click', mostrarCapaOpaca); 
      
            //validamos formulario login                                               
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
            
            function validarPassword(){
            //|| !passReg.test(pass.val())
                $(".error").remove();
		    if(pass.val() === "" ){
                    
                        $("#email").focus().after("<span class='error'><p>El password solo puede tener letras y números, minimo 6 y máximo 10</p></span>");
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
//                $('#form_login').submit(function(){ 
//                    $("#login_form").removeClass('mostrar_formulario login_form_tamanyo').addClass('oculto');	
//                    $("#ocultar").removeClass('mostrar_tranparencia').addClass('oculto');      
//                    
//                     
//                });
//             
//                return false; 
//            } else{
//                $('#form_login').submit(function(){                     
//                     return false;                   
//               });
//            }
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
