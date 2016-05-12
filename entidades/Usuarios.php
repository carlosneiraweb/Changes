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
require_once 'Conexion/Conne.php';
require_once 'DataObj.php';



class Usuarios extends DataObj{
    
    protected $data = array(
            "nombre" => "",
            "apellido_1" =>"",
            "apellido_2" => "",
            "calle" => "",
            "numero" =>"",
            "ciudad" => "",
            "provincia" => "",
            "telefono" => "",
            "pais" => "",
            "sexo" => "",
            "email" => "",
            "nick" => "",
            "password" => "",
            "password_2" => ""
            
            
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
            Conne::disconnect($con);
            
            if($row) return new Member($row);
        } catch (Exception $ex) {
            echo $ex->getLine();
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
            if($row) return new Member($row);
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
            Conne::disconnect($con);
            if($row) return new Member($row);    
        } catch(Exception $ex) {
            Conne::disconnect($con);
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
            Conne::disconnect($con);
            if($row) return new Member($row);    
        } catch(Exception $ex) {
            Conne::disconnect($con);
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
            $sql = "Select * FROM ".TBL_USUARIO. " WHERE nick = :nick AND email = :email ";
            //echo $sql.'<br>';
        } else{ 
            $sql = "Select * FROM ".TBL_USUARIO. " WHERE nick = :nick AND password = password(:password) AND email = :email";
        }   
        try{
          
            $st = $con->prepare($sql);
            $st->bindValue(":nick", $this->data["nick"], PDO::PARAM_STR);
            if(!$opc){ $st->bindValue(":password", $this->data["password"], PDO::PARAM_STR);}
            $st->bindValue(":email", $this->data["email"], PDO::PARAM_STR);
            $st->execute();
            $row =  $st->fetch();
            //var_dump($row);
           Conne::disconnect($con);
           if($row) return new Usuarios($row);
        } catch (Exception $ex) {
           echo $ex->getCode();
           echo '<br>';
           echo $ex->getLine();
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
        
        $con = Conne::connect();
        $sql = "INSERT INTO ".TBL_USUARIO. "(
            
            nick,
            password,
            email,
            fecha
                   
            ) VALUES (
            :nick,
            password(:password),
            :email,
            :fecha            
            );";
        
        try{
            $date = date(Y-m-d);
            $st = $con->prepare($sql);
            $st->bindValue(":nick", $this->data["nick"], PDO::PARAM_STR);
            $st->bindValue(":password", $this->data["password"], PDO::PARAM_STR);
            $st->bindValue(":email", $this->data["email"], PDO::PARAM_STR);
            $st->bindValue(":fecha", $date, PDO::PARAM_STR);
            
  
            $total = $st->execute();
           // echo 'total campos: '.$total.'<br>';
           
                if($total){
                    $sql = "INSERT INTO ".TBL_DATOS_USUARIO." ( idDatosUsuario, idGenero, nombre, apellido_1, apellido_2)".
                            "VALUES".
                            "((SELECT idUsuario FROM ".TBL_USUARIO." where nick = :nick),
                            (SELECT idGenero FROM ".TBL_GENERO." WHERE idGenero = :genero),
                            ':nombre',':apellido_1',':apellido_2');";
                    //echo $sql

                    try{
                        
                        $st = $con->prepare($sql);
                        $st->bindValue(":nick", $this->data["nick"], PDO::PARAM_STR);
                        $st->bindValue(":genero", $this->data["genero"], PDO::PARAM_STR);
                        $st->bindValue(":nombre", $this->data["nombre"], PDO::PARAM_STR);
                        $st->bindValue(":apellido_1", $this->data["apellido_1"], PDO::PARAM_STR);
                        $st->bindValue(":apellido_2", $this->data["apellido_2"], PDO::PARAM_STR);
                        
                        $total = $st->execute();
                            // echo 'total campos: '.$total.'<br>';
                        
                            if($total){
                                
                                $sql = "INSERT INTO ".TBL_DIRECCION." (idDireccion, calle, numero, ciudad, provincias_idprovincias, pais)".
                                        " VALUES ".
                                    "((SELECT idUsuario FROM ".TBL_USUARIO. " where nick = :nick),".
                                    "':calle', ':numero', ':ciudad', ".
                                    "(SELECT idProvincias FROM ".TBL_PROVINCIAS. " WHERE nombre=':nombre'),':pais');";
                                //echo $sql
                                try{
                                
                                    $st = $con->prepare($sql);
                                    $st->bindValue(":nick", $this->data["nick"], PDO::PARAM_STR);
                                    $st->bindValue(":calle", $this->data["calle"], PDO::PARAM_STR);
                                    $st->bindValue(":numero", $this->data["numero"], PDO::PARAM_STR);
                                    $st->bindValue(":ciudad", $this->data["ciudad"], PDO::PARAM_STR);
                                    $st->bindValue(":nombre", $this->data["nombre"], PDO::PARAM_STR);
                                    $st->bindValue(":pais", $this->data["pais"], PDO::PARAM_STR);
                        
                                    $st->execute();
                                } catch (Exception $ex) {
                                    Connection::disconnect($con);
                                    echo 'El error se produce en la línea: '.$ex->getLine().'<br>';
                                    echo 'Del archivo: '.$ex->getFile();
                                    echo'<br>';
                                    echo $ex->getCode().'<br>';
                                    die("Query failed: ".$ex->getMessage());
                                }
                            
                            }
                    } catch (Exception $ex) {
                        Conne::disconnect($con);
                        echo 'El error se produce en la línea: '.$ex->getLine().'<br>';
                        echo 'Del archivo: '.$ex->getFile();
                        echo'<br>';
                        echo $ex->getCode().'<br>';
                        die("Query failed: ".$ex->getMessage());
                    }
                }
           Conne::disconnect($con);
        } catch (Exception $ex) {
            Conne::disconnect($con);
            echo 'El error se produce en la línea: '.$ex->getLine().'<br>';
            echo 'Del archivo: '.$ex->getFile();
            echo'<br>';
            echo $ex->getCode().'<br>';
            die("Query failed: ".$ex->getMessage());
        }
   
    //fin insert    
    }

   
  
   /**
    * Metodo public 
    * Recive un id para eliminar un usuario
    * delete obj
    */
    public function delete(){
        
        $con = Conne::connect();
        $sql = " DELETE FROM ".TBL_USUARIO. " WHERE id = :id";
        
        try{
            $st = $con->prepare($sql);
            $st->bindValue(":id", $this->data["id"], PDO::PARAM_INT);
            $st->execute();
            Conne::disconnect($con);
        } catch (Exception $ex) {
            Conne::disconnect($con);
            die("Query failed: ".$ex->getMessage());
        }
        
        
        
    //fin delete    
    }           
       









//fin Usuarios    
}
