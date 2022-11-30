<?php
/**
 * @author Carlos Neira Sanchez
 * @mail arj.123@hotmail.es
 * @telefono ""
 * @nameAndExt Usuarios.php
 * @fecha 04-oct-2020
 */
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Constantes/ConstantesBbdd.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Constantes/ConstantesErrores.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Modelo/DataObj.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Email/mandarEmails.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/System.php'); 
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Sistema/Conne.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Changes/Controlador/Validar/MisExcepciones.php');



/**
 * Description of Usuarios
 * Esta clase extiende DataObj
 * Crea usuarios y dispone de varios metodos para 
 * insertar, actualizar o borrar un obj de Usuarios
 */ 


class Usuarios extends DataObj{

    protected $data = array(
            "nombre" => "",
            "apellido_1" =>"",
            "apellido_2" => "",
            "calle" => "",
            "numeroPortal" =>"",
            "ptr" => "",
            "ciudad" => "",
            "codigoPostal" => "",
            "provincia" => "",
            "telefono" => "",
            "pais" => "",
            "genero" => "",
            "email" => "",
            "nick" => "",
            "password" => "",
            "password_2" => "",
            "admin" => "",
            "activo"=>"",
            "idUsuario" =>"",
            "bloqueado" =>""
        );
    

    
     /**

      *       * Metodo static que recibe un id 
      * nos devuelve un usuario
      * @param type $id
      * @return Un Usuario
      */
    public static function getMember($id){
        
        $con =  Conne::connect();
        $sql = "SELECT * FROM ".TBL_USUARIO." WHERE id = :id";
        try{
            $st = $con->prepare($sql);
            $st->bindValue(":id", $id, PDO::PARAM_INT);
            $st->execute();
            $row = $st->fetch();
            $st->closeCursor();
            Conne::disconnect($con);
            
            if($row){return new Usuarios($row);}
        } catch (Exception $ex) {
            echo $ex->getLine().'<br>';
            echo $ex->getFile().'<br>';
            Conne::disconnect($con);
            die("Query failed: ".$ex->getMessage());
        }
        
    //fin getMember
    }
    
     /**
     * public and static
     * Metodo que devuelve un usuario por nombre de usuario
     * @param type $nick
     * @return un array con los datos del usuario
      * Objeto Usarios
     */
    public static function getByUsername($nick){
        
        $con = Conne::connect();
        $sql = "Select * FROM ".TBL_USUARIO. " WHERE  nick = :nick";
        
        try{
            $st = $con->prepare($sql);
            $st ->bindValue(":nick", $nick, PDO::PARAM_STR);
            $st ->execute();
            $row = $st->fetch();
            if($row){return new Usuarios($row);}
            $st->closeCursor();
            Conne::disconnect($con);
        } catch (Exception $ex) {
            echo $ex->getFile();
           
            echo $ex->getCode();
            echo '<br>';
            echo $ex->getLine();
            Conne::disconnet($con);
        }
   //fin getByUsername
    }

    /**
     * Metodo que recive un nick
     * y comprueba si esta en la bbdd
     * @param nick <br>
     * String
     * @return nick usuario
     */
public static function getUserName($nick){
        
        $con = Conne::connect();
        $sql = "Select * FROM ".TBL_USUARIO. " WHERE  nick = :nick";
        
        try{
            $st = $con->prepare($sql);
            $st ->bindValue(":nick", $nick, PDO::PARAM_STR);
            $st ->execute();
            $row = $st->fetchAll();
            if($row){return $row[0][1];}
            $st->closeCursor();
            Conne::disconnect($con);
        } catch (Exception $ex) {
            echo $ex->getFile();
            echo $ex->getCode();
            echo '<br>';
            echo $ex->getLine();
            Conne::disconnet($con);
        }
        
    //getUsername
}


    /**
     * public and static
     * Metodo que devuelve un usuario por
     * por un email recivido
     * @param type $emailAddress
     */
    public static function getByEmailAddress($emailAddress){
        
        $con = Conne::connect();
        $sql = "SELECT * FROM ".TBL_USUARIO." WHERE email = :emailaddress";
        try{
            $st = $con->prepare($sql);
            $st->bindValue(":emailaddress", $emailAddress, PDO::PARAM_STR);
            $st->execute();
            $row = $st->fetch();
            $st->closeCursor();
            Conne::disconnect($con);
            if($row){return new Usuarios($row);}    
        } catch(Exception $ex) {
            Conne::disconnect($con);
            echo $ex->getLine().'<br>';
            echo $ex->getFile().'<br>';
        }
        
    //fin getByEmailAddress    
    }
    
  
   
    /**
     * Metodo public para autentificar 
     * un usuario. Este metodo debe recibir un parametro:
     * True para validar el Login, se comprueba el nick y el password
     * False Para validar el nick, password y email, para asegurarse
     * que no existe un usuario con esos datos en el registro.
     * 
     * @return \Member
     */
   public function authenticate($opc){
       
      
       //Mandamos recuperar la contraseña encriptada de la bbddd del usuario
        $hash =  Usuarios::recuperarHash($this->data["nick"]);
       //Comprobamos que la contraseña encriptada de la bbdd sea
       //igual a la introducida por el usuario en el formulario
        if (System::comparaHash( $this->data["password"],$hash)) {
           
            $con = Conne::connect();
                if($opc){
                    $sql = "Select * FROM ".TBL_USUARIO. " WHERE nick = :nick AND password = :password ";
                    //echo $sql.'<br>';
                } else{ 
                    $sql = "Select * FROM ".TBL_USUARIO. " WHERE nick = :nick  AND password = :password AND  email = :email";
                    //echo $sql;

                }   
                try{
                        //password_hash($this->data["password"], PASSWORD_DEFAULT)
                    $st = $con->prepare($sql);
                    $st->bindValue(":nick", $this->data["nick"], PDO::PARAM_STR);
                    if($opc){$st->bindValue(":password", $hash, PDO::PARAM_STR);}
                    if(!$opc){ $st->bindValue(":email", $this->data["email"], PDO::PARAM_STR); }  
                    $st->execute();
                    $row =  $st->fetch();
                    
                     $st->closeCursor();
                     Conne::disconnect($con);
                            //Si todo va bien devolvemos la instancia de un usuario
                        if($row){return new Usuarios($row);}
                     
                } catch (Exception $ex) {
                   echo $ex->getCode();
                   echo '<br>';
                   echo $ex->getLine().'<br>';
                    echo $ex->getFile().'<br>';
                   Conne::disconnect($con);
                }
        //En caso que la comparación con la contraseña introducida no sea correcta        
        }else{
            return 0;
        }
  //fin authenticate     
   }
  


/**
 * Metodo que inserta en la bbdd un usuario
 */    
public final function insert(){
    $con = Conne::connect();
    $idUsu;
    
    
    
        try{
        
        $sql = "INSERT INTO ".TBL_USUARIO. "(
            
            nick,
            password,
            email,
            fecha,
            admin,
            bloqueado
            
                   
            ) VALUES (
            :nick,
            :password,
            :email,
            :fecha,
            :admin,
            :bloqueado
            
            );";
           
            $date = date('Y-m-d');
            $st = $con->prepare($sql);
            $st->bindValue(":nick", $this->data["nick"], PDO::PARAM_STR);
            $st->bindValue(":password",  System::generoHash($this->data["password"]) , PDO::PARAM_STR);
            $st->bindValue(":email", $this->data["email"], PDO::PARAM_STR);
            $st->bindValue(":fecha", $date, PDO::PARAM_STR);
            $st->bindValue(":admin", '0', PDO::PARAM_STR);
            $st->bindValue(":bloqueado", '0', PDO::PARAM_STR);
            
            
            
            
                        
            
            $con->beginTransaction();
            
                $st->execute(); 
                $idUsu = $con->lastInsertId();
                
                
                $sqlDatosUsuario = "INSERT INTO ".TBL_DATOS_USUARIO." ( idDatosUsuario, genero, nombre, "
                           . "apellido_1, apellido_2, telefono)".
                            "VALUES".
                            "( :idDatosUsuario, :genero, :nombre, :apellido_1, :apellido_2, :telefono);";
                    
                        $stDatosUsuario = $con->prepare($sqlDatosUsuario);
                        $stDatosUsuario->bindParam("idDatosUsuario", $idUsu, PDO::PARAM_INT);
                        $stDatosUsuario->bindParam(":genero", $this->data["genero"], PDO::PARAM_STR);                      
                        $stDatosUsuario->bindValue(":nombre", $this->data["nombre"], PDO::PARAM_STR);
                        $stDatosUsuario->bindValue(":apellido_1", $this->data["apellido_1"], PDO::PARAM_STR);
                        $stDatosUsuario->bindValue(":apellido_2", $this->data["apellido_2"], PDO::PARAM_STR);
                        $stDatosUsuario->bindValue(":telefono", $this->data["telefono"], PDO::PARAM_STR);
                        
                $stDatosUsuario->execute(); 
                
                $sqlDireccion = "INSERT INTO ".TBL_DIRECCION." (idDireccion, calle, numeroPortal, ptr, codigoPostal, ciudad, provincia, pais)".
                    " VALUES ".
                        "(:idDireccion, :calle, :numeroPortal, :ptr, :codigoPostal, :ciudad, :provincia, :pais);";
                                    
                             
                        $stDireccion = $con->prepare($sqlDireccion);
                        $stDireccion->bindParam(":idDireccion", $idUsu, PDO::PARAM_INT);
                        $stDireccion->bindValue(":calle", $this->data["calle"], PDO::PARAM_STR);
                        $stDireccion->bindValue(":numeroPortal", $this->data["numeroPortal"], PDO::PARAM_STR);
                        $stDireccion->bindValue(":ptr", $this->data["ptr"], PDO::PARAM_STR);
                        $stDireccion->bindValue(":codigoPostal", $this->data["codigoPostal"], PDO::PARAM_STR);
                        $stDireccion->bindValue(":ciudad", $this->data["ciudad"], PDO::PARAM_STR);
                        $stDireccion->bindValue(":provincia", $this->data["provincia"], PDO::PARAM_STR);
                        $stDireccion->bindValue(":pais", $this->data["pais"], PDO::PARAM_STR);
                $stDireccion->execute();             

                
            
            $con->commit();
            Conne::disconnect($con);
            return $idUsu;
        } catch (Exception $ex) {
            //echo $ex->getMessage();
            $excepciones = new MisExcepciones(CONST_ERROR_BBDD_REGISTRAR_USUARIO[1],CONST_ERROR_BBDD_REGISTRAR_USUARIO[0],$ex);
            Conne::disconnect($con);
           
            $con->rollBack();
            
            $excepciones->redirigirPorErrorSistema("RegistrarUsuarioBBDD",true);
           
        }
        
    //fin insert    
} 
   
  
    /**
     * Metodo publico que recive
     * el nick de un usuario y nos devuelve el id
     *  @return type id usuario
     */

    public  function devuelveId(){
        $con = Conne::connect();
        
        try{
            
            $sql = "Select idUsuario from ".TBL_USUARIO. " WHERE nick = :nick";
          
                $st = $con->prepare($sql);
                $st->bindValue(":nick", $this->getValue('nick'), PDO::PARAM_STR);
                $st->execute();
                $row = $st->fetch();
                
                if($row){return $row[0];}
                $st->closeCursor();
                
                Conne::disconnect($con);
                
        } catch (Exception $ex) {
            Conne::disconnect($con);
            echo $ex->getLine().'<br>';
            echo $ex->getFile().'<br>';
            die("Query failed: ".$ex->getMessage());
        }
   //fin devuelve id     
    }

/**
 * Metodo que elimina un usuario por su id<br>
 * @param id <br>
 * String con el id del usuario a eliminar
 */
 public function eliminarPorId($id){
    
    $con = Conne::connect();
    $sql = " DELETE FROM ".TBL_USUARIO. " WHERE idUsuario = :idUsuario";
        $id = (int) $id;
        try{
            $st = $con->prepare($sql);
            $st->bindValue(":idUsuario", $id, PDO::PARAM_INT);
            $st->execute();

            $st->closeCursor();
            Conne::disconnect($con);
 
        } catch (Exception $ex) {
            Conne::disconnect($con);
            echo $ex->getLine().'<br>';
            echo $ex->getFile().'<br>';
            die("Query failed: ".$ex->getMessage());
        }
     
     
  //fin eliminarPorId   
 }
    
 
    
   /**
    * Metodo public 
    * Recive un id para eliminar los datos de un usuario.
    * Recive como parametro el nombre de la tabla 
    * de la cual eliminar los datos, 
    * usuario para los datos de la tabla usuario
    * o datos_usuario para eliminar de la tabla datos_usuario
    *  @param type tabla de la bbdd
     * 
    */
    public function deleteFrom($var){
        
        $con = Conne::connect();
        $tmp = $this->devuelveId();
        
        $id = (int) $tmp;
        
        if($var == 'usuario'){
            $sql = " DELETE FROM ".TBL_USUARIO. " WHERE idUsuario = :idUsuario";
        }  
        try{
            $st = $con->prepare($sql);
            $st->bindValue(":idUsuario", $id, PDO::PARAM_INT);
            $st->execute();
            $result = $st->rowCount();
            
            $st->closeCursor();
            Conne::disconnect($con);
            
            return $result;
            
        } catch (Exception $ex) {
            Conne::disconnect($con);
            echo $ex->getLine().'<br>';
            echo $ex->getFile().'<br>';
            die("Query failed: ".$ex->getMessage());
        }
    //fin delete    
    }           
       
   
    /**
     * Metodo que recupera el Hash del usuario de la bbdd
     * Devuelve el hash si es posible o false 
     * en caso negativo.
     * Recive el nick del usuario.
     * @param type nick de usuario
     *  @return type columna de la tabla el Hash
     */
    final public static function recuperarHash($nick){
        
        $conHash = Conne::connect();
        $sqlHash = null;
            //Recuperamos el Hash del usuario "Contraseña encriptada"
        
        try{
            $sqlHash = "Select password FROM ".TBL_USUARIO. " WHERE nick = :nick; ";
            $stHash = $conHash->prepare($sqlHash);
            $stHash->bindValue(":nick", $nick, PDO::PARAM_STR);
            $stHash->execute();
            $rowHash = $stHash->fetch();
            
            $stHash->closeCursor();
            Conne::disconnect($conHash);
            
            if ($rowHash[0]) {
                return $rowHash[0];
            }else{
                return 0;
            }
            
               
        } catch (Exception $ex) {
            echo $ex->getCode();
            echo '<br>';
            echo $ex->getLine().'<br>';
            echo $ex->getFile().'<br>';
           Conne::disconnect($conHash);
          
        }
        
    //fin recuperarHash    
    }

/**
 * Metodo que recive el id del usuario logeado
 * y devuelve los posibles usuarios bloqueados
 * @param type idUsuario
 * @return type array de ids de usuarios
 */
public final function devuelveUsuariosBloqueadosTotal($id){
    
   
    $conBloqueo = Conne::connect();
   
    
        try{

            $sqlBloqueoTotal = "Select idUsuarioBloqueado 
            from ".TBL_BLOQUEADOS_TOTAL." where usuarioidUsuario = :usuarioIdUsuario;";
           
            $stmBloqueoTotal = $conBloqueo->prepare($sqlBloqueoTotal);
            $stmBloqueoTotal->bindValue(":usuarioIdUsuario",$id, PDO::PARAM_INT);
            $stmBloqueoTotal->execute();
            $usuBloqueados = $stmBloqueoTotal->fetchAll();
            $stmBloqueoTotal->closeCursor();
            
            Conne::disconnect($conBloqueo);
            
            return $usuBloqueados;
        } catch (Exception $ex) {
            Conne::disconnect($conBloqueo);
            echo $ex->getCode();
            echo '<br>';
            echo $ex->getLine().'<br>';
            echo $ex->getFile().'<br>';
        }

 // devuelveUsuariosBloqueados()  
}
    
/**
 * Metodo que recive el id del usuario logeado
 * y devuelve los posibles usuarios bloqueados
 * @param type idUsuario
 * @return type array de ids de usuarios
 */
public final function devuelveUsuariosBloqueadosParcial($id){
    
   
    $conBloqueo = Conne::connect();
    
    
        try{

            
            $sqlBloqueoParcial = "Select idUsuarioBloqueado 
            from ".TBL_BLOQUEADOS_PARCIAL."  where usuarioIdUsuario = :usuarioIdUsuario;";

            $stmBloqueoParcial = $conBloqueo->prepare($sqlBloqueoParcial);
            $stmBloqueoParcial->bindValue(":usuarioIdUsuario",$id, PDO::PARAM_INT);
            $stmBloqueoParcial->execute();
            $usuBloqueados = $stmBloqueoParcial->fetchAll();
            $stmBloqueoParcial->closeCursor();
            
           
            Conne::disconnect($conBloqueo);
            
            return $usuBloqueados;
        } catch (Exception $ex) {
            Conne::disconnect($conBloqueo);
            echo $ex->getCode();
            echo '<br>';
            echo $ex->getLine().'<br>';
            echo $ex->getFile().'<br>';
        }

 // devuelveUsuariosBloqueados()  
}
    


/**
 * Metodo que devuelve la direcion 
 * de un susuario
 * @return array direccion
 */

public function retornoDireccionUsuario(){
    
    $conDireccion = Conne::connect();
        
        try{

            $sqlBloqueo = "Select calle, numeroPortal, ptr,
                codigoPostal, ciudad, provincia, pais
            from direccion where idDireccion = :usuario_idUsuario;";

            $stmDireccion = $conDireccion->prepare($sqlBloqueo);
            $stmDireccion->bindValue(":usuario_idUsuario", $this->devuelveId());
            $stmDireccion->execute();
            $direccion = $stmDireccion->fetchAll();
           
            Conne::disconnect($conDireccion);
            
            return $direccion;
        } catch (Exception $ex) {
            Conne::disconnect($conBloqueo);
            echo $ex->getCode();
            echo '<br>';
            echo $ex->getLine().'<br>';
            echo $ex->getFile().'<br>';
        }

    
    
//fin devuelvoDireccion    
}  
  

/**
 * Metodo que recive un id de
 * Usuario y devuelve el email
 * @return  String email usuario
 * @param type String idUsuario
 * 
 */

public function devuelveEmailPorId($id){
    
    $con = Conne::connect();
        $sql = "SELECT email FROM ".TBL_USUARIO." WHERE idUsuario = :idUsuario";
        try{
            $st = $con->prepare($sql);
            $st->bindValue(":idUsuario", $id, PDO::PARAM_INT);
            $st->execute();
            $row = $st->fetch();
            $st->closeCursor();
            Conne::disconnect($con);
            return $row; 
        } catch(Exception $ex) {
            Conne::disconnect($con);
            echo $ex->getLine().'<br>';
            echo $ex->getFile().'<br>';
        }
    
    
    
//fin devuelveEmailPorId    
}


/**
 * Metodo que actualia los datos 
 * de un  usuario
 *
 */
public function actualizoDatosUsuario(){
     
    $con = Conne::connect();
    
    
    try{

        $idUsuViejo =  $_SESSION['actualizo']->getValue("idUsuario");
        
        //Comprobamos que el usuario no ha cambiado el password
        //Si lo ha cambiado tenemos que generar un hash nuevo
        
           
        if($_SESSION['error'] == ERROR_ACTUALIZAR_USUARIO){
            $password = $_SESSION['actualizo']->getValue('password');
        }else{
            $password = System::generoHash($_SESSION["usuRegistro"]->getValue('password'));
        }
        
        $sqlActualiarUsuario = " Update ".TBL_USUARIO." set nick = :nick, password = :password, email = :email
            where idUsuario = :idUsuario;";
        $stmActualizarUsuario =   $con->prepare($sqlActualiarUsuario);  
        $stmActualizarUsuario->bindValue(":nick", $this->getValue('nick'), PDO::PARAM_STR);
        
        $stmActualizarUsuario->bindValue(":password",$password, PDO::PARAM_STR );
        
        $stmActualizarUsuario->bindValue(":email", $this->getValue('email'), PDO::PARAM_STR );
        $stmActualizarUsuario->bindValue(":idUsuario", $idUsuViejo, PDO::PARAM_INT);
                  
            $sqlActualiarDatos = "Update ".TBL_DATOS_USUARIO." SET "
        . "nombre = :nombre, apellido_1= :apellido_1, apellido_2 = :apellido_2,"
        . " telefono = :telefono, genero = :genero "
        . " where idDatosUsuario = :idDatosUsuario;"; 
                 
                $stmActualizarDatos = $con->prepare($sqlActualiarDatos);
                $stmActualizarDatos->bindValue(":nombre", $this->getValue('nombre'), pdo::PARAM_STR);
                $stmActualizarDatos->bindValue(":apellido_1", $this->getValue('apellido_1'), pdo::PARAM_STR);
                $stmActualizarDatos->bindValue(":apellido_2", $this->getValue('apellido_2'), pdo::PARAM_STR);
                $stmActualizarDatos->bindValue(":telefono", $this->getValue('telefono'), pdo::PARAM_STR);
                $stmActualizarDatos->bindValue(":genero", $this->getValue('genero'), pdo::PARAM_STR);
                $stmActualizarDatos->bindValue(":idDatosUsuario", $idUsuViejo, PDO::PARAM_INT);
                
            
                $sqlActualiarDireccion = "Update ".TBL_DIRECCION. " SET "
        ." calle = :calle, numeroPortal = :numeroPortal, ptr = :ptr, "
        . " codigoPostal = :codigoPostal, ciudad = :ciudad, provincia = :provincia, pais = :pais "
        . " where idDireccion = :idDireccion;";
                        
                $stmActualizarDireccion = $con->prepare($sqlActualiarDireccion);
                $stmActualizarDireccion->bindValue(":calle", $this->data["calle"], PDO::PARAM_STR);
                $stmActualizarDireccion->bindValue(":numeroPortal", $this->data["numeroPortal"], PDO::PARAM_STR);
                $stmActualizarDireccion->bindValue(":ptr", $this->data["ptr"], PDO::PARAM_STR);
                $stmActualizarDireccion->bindValue(":codigoPostal", $this->data["codigoPostal"], PDO::PARAM_STR);
                $stmActualizarDireccion->bindValue(":ciudad", $this->data["ciudad"], PDO::PARAM_STR);
                $stmActualizarDireccion->bindValue(":provincia", $this->data["provincia"], PDO::PARAM_STR);
                $stmActualizarDireccion->bindValue(":pais", $this->data["pais"], PDO::PARAM_STR);
                $stmActualizarDireccion->bindValue(":idDireccion", $idUsuViejo, PDO::PARAM_INT);
        
                
                
        $con->beginTransaction();   
        
            $stmActualizarUsuario->execute();
            $stmActualizarDatos->execute();
            $stmActualizarDireccion->execute(); 
            
       $con->commit();

        Conne::disconnect($con);
 
       
    }catch (Exception $ex){
      
       $con->rollBack();
        $excepciones = new MisExcepciones(CONST_ERROR_BBDD_ACTUALIZAR_USUARIO[1],CONST_ERROR_BBDD_ACTUALIZAR_USUARIO[0],$ex);
        $excepciones->redirigirPorErrorSistema("ActualizarUsuarioBBDD",true);              

    
    }finally {
        Conne::disconnect($con);
        
    }
    
    
}

    
//fin Usuarios    
}
