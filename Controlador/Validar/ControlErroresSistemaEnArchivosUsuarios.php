
<?php
 

require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/DataObj.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/Usuarios.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Controlador/Validar/MisExcepciones.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Directorios.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Controlador/Validar/ValidoForm.php'); 
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Email/mandarEmails.php');

//require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Vista/registrarse.php');
/**
 * Controla los errores del sistema
 * al eliminar, crear o copiar
 * Directorios archivos, etc
 * al registrarse o actualizar un usuario
 * Extiende MixExcepciones
 * @author carlos
 */

if(!isset($_SESSION)){
    
 session_start();

}


//session_start();

  if(!isset($_SESSION["paginaError"])){
            $_SESSION["paginaError"] = "registrarse.php";
        }
 //Variable global para mostrar los errores de validacion
        
        global $mensajeReg;
        global $usuActualiza;
        global $mandarEmail;
        $objMandarEmails = new mandarEmails();
        
        
     
        //Si existe $_SESSION["userTMP"]
        //Es que el usuario se ha logeado
        
        if(isset($_SESSION["userTMP"])){          
            $usuActualiza = $_SESSION["userTMP"]->getValue('nick');
        }
       
/**
 * @param $dir <br/>
 * Array con la ruta donde crear los directorios <br/>
 * y la opcion por si hay algun error poder <br/>
 * tratarla adecuadamente <br/>
 */                              
 function crearDirectorios($dir){
             
            //Creamos los directorios del registro
        //echo 'crear Directorios '.$dir[1].' '.$dir[3];  
        Directorios::crearDirectorio($dir[0],$dir[3]);
        Directorios::crearDirectorio($dir[1],$dir[3]);
        Directorios::crearDirectorio($dir[2],$dir[3]);
        
        
   //fin crearDirectorios                                     
    }
   
    /**
     * Crea un array que contiene <br>
     * la ruta donde se crearan las <br>
     * carpetas donde se guardara la informacion <br>
     * @param $id 
     * Id del usuario
     */
    function crearRutasDirectorios($id){
    
       
        global $tmpNuevosDatos;
       
        
        //Este array contiene la ruta para crear los directorios 
        //que se necesitan en el sistema al registrarse
                                            
            $tmpNuevosDatos = array( "../photos/".$id,
                              "../datos_usuario/".$id,
                              "../Videos/".$id,
                              "registrar"
                       );
        
        
        
        
    //fin  crearRutasDirectorios()   
    }
 
/**
 * Este metodo comprueba en caso el usuario <br/>
 * cambie de correo electronico <br/>
 * que el introducido no se encuentre <br/>
 * ya en uso.Recordemos que <br/>
 * $_SESSION['actualizo']['correo'] se instancia cuando se accede <br/>
 * al formulario de actualizar para ir mostrando los datos <br/>
 * y <br/>
 * $_SESSION['usuario']['email'] son los datos que el usuario <br/>
 * va introduciendo en el formulario <br/>
 * @param @nombre usuario <br/>
 *  @tipo objeto usuario <br/>
 */
function comprobarEmailNuevo($user){
   
    if($_SESSION['actualizo']['correo'] != $_SESSION['usuario']['email']){
                                
        if($user->getByEmailAddress($_SESSION['usuario']['email'])){
            return 1;
        }else{
            return 0;
        }
    }      
    
    //fin  comprobarNickNuevo   
    }
    
    


/**
 * Este metodo crea los directorios </br>
 * necesarios cuando un usuario </br>
 * se registra y sube una foto </br>
 * para el perfil </br>
 * @param Boolean  $var <br>
 * Parametro para saber si el usuario sube <br>
 * una foto o le ponemos una por default.
 */

 function crearDirectoriosRegistro($var){
     
    global $tmpNuevosDatos;
    global $destino;
    global $foto;
    global $objMandarEmails;
     
     
    crearRutasDirectorios($_SESSION["datos"]["id"]);
    crearDirectorios($tmpNuevosDatos);
    
        if($var == "0"){
            Directorios::moverImagen($foto,$destino , $tmpNuevosDatos[3]);
        }else{
            Directorios::copiarFoto("../datos_usuario/desconocido.jpg", $destino, $tmpNuevosDatos[3]);
        }
        
        Directorios::renombrarFotoPerfil($destino, $_SESSION["datos"]["id"]); 
        
             $objMandarEmails->mandarEmailWelcome();
             
             
    if(isset($_SESSION["datos"])){unset($_SESSION["datos"]);}
    if(isset($_SESSION["usuRegistro"])){unset($_SESSION["usuRegistro"]);}
    if(isset($_SESSION['usuario'])){unset($_SESSION['usuario']);}
  //fin crearDirectoriosRegistro  
    
 }   

 /**
  * Metodo que modifica los archivos <br>
  * si un usuario actualiza <br>
  * sus datos. <br>
  *
  */
    
function modificarDirectoriosUsuario(){

    
    global $destino;
    global $foto;

        Directorios::eliminarImagen($destino, "actualizar");
        Directorios::moverImagen($foto, $destino , "actualizar");
        Directorios::renombrarFotoActualiazar($destino, $destino,"actualizar");

    if(isset($_SESSION["datos"])){unset($_SESSION["datos"]);}
    if(isset($_SESSION["usuRegistro"])){unset($_SESSION["usuRegistro"]);}
    if(isset($_SESSION['usuario'])){unset($_SESSION['usuario']);}
    
         abandonarSession();
    //fin crearDirectoriosRegistro
}

  /**
     * Metodo que valida los datos introducidos por el usuario al registrarse. </br>
     * Valida los campos con los metodos static de ValidaForm </br>
     * Valida los datos de la bbdd con un objeto de la clase Usuarios </br>
     * Este metodo </br>
     * VALIDAMOS TANTO REGISTRO COMO ACTUALIZACION USUARIO  </br>
     * 
     * @param  user </br>
     * Objeto tipo usuario
     * @param name st </br>
     *    NUmero de paso que hay que validar </br>
     * 
     * @return boolean
     */
function validarCamposRegistro($st, $user,$id){
        
        $testValidoReg = array(null, true);
        global $destino;
        global $foto;
        global $usuActualiza;
        
        
        
        //Llama metodo mismo archivo
        //crearRutasDirectorios();
        
        switch ($st){
           
            case "step1":


                
                //En caso de que exista el nombre de usuario o email
                //Los passwords se repitan o el email sea incorrecto
                    
           
                        //En caso se este registrando
                        if(!isset($_SESSION["userTMP"])){
                         
                            if($user->getUserName($_SESSION['usuario']['nick']) !== null){
                                                 
                               
                                $testValidoReg[0] = ERROR_NOMBRE_USUARIO_EXISTE;
                                $testValidoReg[1] =  false;
                                
                                
                            }
                            
                        }else{
                            
                            //En caso se este actualizando y cambie
                            //de nick se comprueba que el nuevo que ha elegido
                            //no este ya en uso. 
                            //$usuActualiza se instancia en el metodo 
                            //con la variable de sesion $_SESSION["userTMP"]
                            //cuando un usuario se logea
                                
                            if($usuActualiza !== $_SESSION['usuario']['nick'] ){
                        
                                if($_SESSION["userTMP"]->getByUserName($_SESSION['usuario']['nick'])){
                                    
                                        $testValidoReg[0] = ERROR_NOMBRE_USUARIO_EXISTE;
                                        $testValidoReg[1] =  false;
                                        
                                }
                      
                            }
                        }
                    
                    if(!ValidoForm::validarPassword($_SESSION['usuario']['password'])){
                        $testValidoReg[0] =  ERROR_PASSWORD_INCORRECTO;
                        $testValidoReg[1] = false;
                           
                    }
                    if(ValidoForm::validarIgualdadPasswords($_SESSION['usuario']['password'], $_POST['passReg2'])){
                        $testValidoReg[0] =  ERROR_IGUALDAD_PASSWORD;
                        $testValidoReg[1] = false;
                           
                    }
                    if(!ValidoForm::validarEmail($_SESSION['usuario']['email'])){
                        $testValidoReg[0] = ERROR_EMAIL_INCORRECTO;
                        $testValidoReg[1] = false;
                    //           
                    }
                    
                    if($user->getByEmailAddress($_SESSION['usuario']['email'])){
                        
                        //Se comprueba que se esta registrando
                        //Si se esta actualizando no se hace esa comprobacion
                        if(!isset($_SESSION["userTMP"])){
                            
                            $testValidoReg[0] = ERROR_EMAIL_EXISTE;
                            $testValidoReg[1] = false;
                            
                        }elseif(isset($_SESSION["userTMP"])){ 
                            
                            //Mandamos comprobar que el nuevo correo
                            //no esta ya en uso si el usuario desea cambiarlo
                            if(comprobarEmailNuevo($user)){
                                
                                $testValidoReg[0] = ERROR_EMAIL_EXISTE;
                                $testValidoReg[1] = false;  
                            }
                            
                        }
                         
                         
                    }
                
                if(
                    ValidoForm::campoVacio($_POST['nick']) == 0 ||
                    ValidoForm::campoVacio($_POST['password']) == 0 ||
                    ValidoForm::campoVacio($_POST['passReg2']) == 0 ||
                    ValidoForm::campoVacio($_POST['email']) == 0 
                ){ 
                    $testValidoReg[0] = ERROR;
                    $testValidoReg[1] =  false;
                }
                
                return $testValidoReg;     

                case 'step2':
                
                    if(!ValidoForm::validaTelefono($_SESSION['usuario']['telefono'])){
                        $testValidoReg[0] =  ERROR_TELEFONO_INCORRECTO;
                        $testValidoReg[1] = false;
                         
                    }
               
                    if(
                        ValidoForm::campoVacio($_POST['nombre']) == 0 ||
                        ValidoForm::campoVacio($_POST['telefono']) == 0 
                        ){ 
                        $testValidoReg[0] = ERROR;
                        $testValidoReg[1] =  false;
                    }
             
                return $testValidoReg;
                   
            case 'step3':
                
                    if(!ValidoForm::validarCodPostal($_SESSION['usuario']['codPostal'])){
                        $testValidoReg[0] =  ERROR_CODIGO_POSTAL;
                        $testValidoReg[1] = false;
    
                    }
                    
                    if(
                        ValidoForm::campoVacio($_POST['ciudad']) == 0 ||
                        ValidoForm::campoVacio($_POST['codPostal']) == 0 
                        ){ 
                        $testValidoReg[0] = ERROR;
                        $testValidoReg[1] =  false;
                    }
             
                        
                return $testValidoReg;
                
            case 'step4':
                
            $test =  Directorios::validarFoto(); 
                
            
            if($test != "0" && $test != "4"){
                //SI hay algun problema validar foto
                $testValidoReg[1] =  false;
                $_SESSION['paginaError'] = 'registrarse.php';
                mostrarErrorRegistro();  
            }else{
                //Si todo ha ido bien
                $testValidoReg[1] = true;
                //ingresamos usuario en la bbdd
                
                    ingresarUsuario();
                    
                
                $foto = $_FILES['photoArticulo']['tmp_name']; 
                //Si el usuario no se ha logeado
                //entonces se esta registrando    
                if(!isset($_SESSION["userTMP"])){  
                    //SI todo ha ido bien 
                    //Creamos los direcotios para el usuario 
                    //Y subimos la foto de perfil
                    $destino = "../datos_usuario/".$_SESSION['datos']['id'].'/'.$_SESSION['datos']['id'].'.jpg';
                        crearDirectoriosRegistro($test);
                
                //Si se ha logeado esta actualizando sus datos       
                }else{
                    $id = $_SESSION["userTMP"]->getValue('idUsuario');
                    $destino = "../datos_usuario/$id/$id.jpg";
                        modificarDirectoriosUsuario();
                }
                        
            return $testValidoReg;
               
            }
                case 'step5':
                    
       
                        break;
                            
                  

                //return $test;  
                
                    default :
                        //nada
                        
        
           
   
        }
  //fin validarCamposRegistro      
      }
 
      

