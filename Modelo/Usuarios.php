<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Usuarios
 *
 * @author Carlos Neira Sanchez
 */
require_once('../Sistema/Conne.php');
require_once('DataObj.php');



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
            "admin" => ""    
        );
    
    
     /**
     * Metodo public and static
     * it recives id from user
     * @param type $id
     * @return \Member
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
            
            if($row) return new Member($row);
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
     * Metodo que devuelve un usuario por nombre
     * @param type $username
     * @return \Member
     */
    public static function getByUsername($nick){
        
        $con = Conne::connect();
        $sql = "Select * FROM ".TBL_USUARIO. " WHERE  nick = :nick";
        
        try{
            $st = $con->prepare($sql);
            $st ->bindValue(":nick", $nick, PDO::PARAM_STR);
            $st ->execute();
            $row = $st->fetch();
            if($row) return new Usuarios($row);
            $st->closeCursor();
            Conne::disconnect($con);
        } catch (Exception $ex) {
            echo $ex->getFile();
            echo '<br>';
            echo $ex->getLine();
            Conne::disconnet($con);
            die("Query failed: ".$ex->getMessage());
        }
   //fin getByUsername
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
            if($row) return new Usuarios($row);    
        } catch(Exception $ex) {
            Conne::disconnect($con);
            echo $ex->getLine().'<br>';
            echo $ex->getFile().'<br>';
            die("Query failed: ".$ex->getMessage());
        }
        
    //fin getByEmailAddress    
    }
    
    
    /**
     * public and static
     * Metodo que devuelve un usuario por
     * por un password recivido
     * @param type $password
     */
    public static function getByPassword($password){
        
        $con = Conne::connect();
        $sql = "SELECT * FROM ".TBL_USUARIO." WHERE password = password(:password)";
        try{
            $st = $con->prepare($sql);
            $st->bindValue(":password", $password, PDO::PARAM_STR);
            $st->execute();
            $row = $st->fetch();
            $st->closeCursor();
            Conne::disconnect($con);
            if($row) return new Member($row);    
        } catch(Exception $ex) {
            Conne::disconnect($con);
            echo $ex->getLine().'<br>';
            echo $ex->getFile().'<br>';
            die("Query failed: ".$ex->getMessage());
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
       
        $con = Conne::connect();
        if($opc){
            $sql = "Select * FROM ".TBL_USUARIO. " WHERE nick = :nick AND password = :password ";
            //echo $sql.'<br>';
        } else{ 
            $sql = "Select * FROM ".TBL_USUARIO. " WHERE nick = :nick AND  email = :email";
            echo $sql;
            
        }   
        try{
                //
            $st = $con->prepare($sql);
            $st->bindValue(":nick", $this->data["nick"], PDO::PARAM_STR);
            if($opc){$st->bindValue(":password", $this->data["password"], PDO::PARAM_STR);}
            if(!$opc){ $st->bindValue(":email", $this->data["email"], PDO::PARAM_STR); }  
            $st->execute();
            $row =  $st->fetch();
            //var_dump($row);
           Conne::disconnect($con);
           if($row) return new Usuarios($row);
        } catch (Exception $ex) {
           echo $ex->getCode();
           echo '<br>';
           echo $ex->getLine().'<br>';
            echo $ex->getFile().'<br>';
           Conne::disconnect($con);
           die("Query failed: ".$ex->getMessage());
        }
       
  //fin authenticate     
   }
   
         /**
     * Metodo public
     * insert object
     */
    public function insert(){
        global $inicio;
        try{
        $con = Conne::connect();
        $sql = "INSERT INTO ".TBL_USUARIO. "(
            
            nick,
            password,
            email,
            fecha
                   
            ) VALUES (
            :nick,
            :password,
            :email,
            :fecha            
            );";
        
        //echo "sql nick: ".$sql.'<br>';
      
            $date = date('Y-m-d');
            $st = $con->prepare($sql);
            $st->bindValue(":nick", $this->data["nick"], PDO::PARAM_STR);
            $st->bindValue(":password", $this->data["password"], PDO::PARAM_STR);
            $st->bindValue(":email", $this->data["email"], PDO::PARAM_STR);
            $st->bindValue(":fecha", $date, PDO::PARAM_STR);

            $st->execute();

                try{
                    $sql = "INSERT INTO ".TBL_DATOS_USUARIO." ( idDatosUsuario, idGenero, nombre, apellido_1, apellido_2, telefono)".
                            "VALUES".
                            "((SELECT idUsuario FROM ".TBL_USUARIO." where nick = :nick),
                            (SELECT idGenero FROM ".TBL_GENERO." WHERE genero = :genero),
                             :nombre, :apellido_1, :apellido_2, :telefono);";
                    //echo 'datos usuario'.$sql.'<br>';

                        $st = $con->prepare($sql);
                        $st->bindValue(":nick", $this->data["nick"], PDO::PARAM_STR);
                        $st->bindValue(":genero", $this->data["genero"], PDO::PARAM_STR);
                        $st->bindValue(":nombre", $this->data["nombre"], PDO::PARAM_STR);
                        $st->bindValue(":apellido_1", $this->data["apellido_1"], PDO::PARAM_STR);
                        $st->bindValue(":apellido_2", $this->data["apellido_2"], PDO::PARAM_STR);
                        $st->bindValue(":telefono", $this->data["telefono"], PDO::PARAM_STR);
                        
                        $st->execute();
                        
                } catch (Exception $ex) {
                        //Si ha ocurrido un error eliminamos al usuario de la tabla
                        $this->deleteFrom('usuario');
                        echo 'El error se produce en la línea: '.$ex->getLine().'<br>';
                        die("Query failed: ".$ex->getMessage());
                }
                        
                            try{
                                
                                $sql = "INSERT INTO ".TBL_DIRECCION." (idDireccion, calle, numeroPortal, ptr, codigoPostal, ciudad, provincias_idprovincias, pais)".
                                        " VALUES ".
                                    "((SELECT idUsuario FROM ".TBL_USUARIO. " where nick = :nick),".
                                    ":calle, :numeroPortal, :ptr, :codigoPostal, :ciudad, ".
                                    "(SELECT idProvincias FROM ".TBL_PROVINCIAS. " WHERE nombre= :provincia), :pais);";
                                //echo 'sql Direccion: '.$sql;
                               
                                
                                    $st = $con->prepare($sql);
                                    $st->bindValue(":nick", $this->data["nick"], PDO::PARAM_STR);
                                    $st->bindValue(":calle", $this->data["calle"], PDO::PARAM_STR);
                                    $st->bindValue(":numeroPortal", $this->data["numeroPortal"], PDO::PARAM_STR);
                                    $st->bindValue(":ptr", $this->data["ptr"], PDO::PARAM_STR);
                                    $st->bindValue(":codigoPostal", $this->data["codigoPostal"], PDO::PARAM_STR);
                                    $st->bindValue(":ciudad", $this->data["ciudad"], PDO::PARAM_STR);
                                    $st->bindValue(":provincia", $this->data["provincia"], PDO::PARAM_STR);
                                    $st->bindValue(":pais", $this->data["pais"], PDO::PARAM_STR);
                        
                                    $inicio = $st->execute();
                                    
                            } catch (Exception $ex) {
                                    //Si ha ocurrido un error eliminamos al usuario de la tabla
                                    //y sus datos
                                    $this->deleteFrom('datos_usuario');
                                    $this->deleteFrom('usuario');
                                    echo 'El error se produce en la línea: '.$ex->getLine().'<br>';
                                    return $inicio;
                                    
                                    
                            }

           Conne::disconnect($con);
           return $inicio;
        } catch (Exception $ex) {
            Conne::disconnect($con);
            echo $ex->getLine().'<br>';
            echo $ex->getFile().'<br>';
            die("Query failed: ".$ex->getMessage());
        }
   
    //fin insert    
    }

    /**
     * Metodo que nos devuelve el id de un usuario
     */

    public function devuelveId(){
        
       try{
            $con = Conne::connect();
            $sql = "Select idUsuario from ".TBL_USUARIO. " WHERE nick = :nick";
            //echo "sql devuelveId ".$sql.'<br>';
            
                $st = $con->prepare($sql);
                $st->bindValue(":nick", $this->data['nick'], PDO::PARAM_INT);
                $st->execute();
                $row = $st->fetch();
                $st->closeCursor();
                if($row) return $row;
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
    * Metodo public 
    * Recive un id para eliminar un usuario
    * delete obj
    */
    public function deleteFrom($var){
        
        $con = Conne::connect();
        $tmp = $this->devuelveId();
        
        $id = (int) $tmp[0];
        
        if($var == 'usuario'){
            $sql = " DELETE FROM ".TBL_USUARIO. " WHERE idUsuario = :idUsuario";
        } elseif($var == 'datos_usuario') {
            $sql = " DELETE FROM ".TBL_DATOS_USUARIO. " WHERE idDatosUsuario = :idUsuario";
        }
        //echo 'sql en deletefrom: '.$sql.'<br>';
        try{
            $st = $con->prepare($sql);
            $st->bindValue(":idUsuario", $id, PDO::PARAM_INT);
            $st->execute();
            Conne::disconnect($con);
        } catch (Exception $ex) {
            Conne::disconnect($con);
            echo $ex->getLine().'<br>';
            echo $ex->getFile().'<br>';
            die("Query failed: ".$ex->getMessage());
        }
    //fin delete    
    }           
       
   




//fin Usuarios    
}
