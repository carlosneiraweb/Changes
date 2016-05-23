<?php

/**
 * Description of Post
 *
 * @author Carlos Neira Sanchez
 */
require_once('Sistema/Conne.php');
require_once('DataObj.php');

class Post extends DataObj{
    
    protected $data = array(
        
        "idUsuario" => "",
        "secciones_idsecciones" => "",
        "tiempo_cambio_idTiempoCambio" => "",
        "titulo" => "",
        "comentario" => "",
        "precio" => "",
        "Pa_buscadas" => array(),
        "Pa_ofrecidas" => array(),
        "fechaPost" => ""
       
    );
    
public function insertArticulo(){
    
    global $total;
        try{
        $con = Conne::connect();
        //Select idTiempoCambio from ".TBL_TIEMPO_CAMBIO. " WHERE tiempo = '".$tiempoCamb."'";
            $sql = " INSERT INTO ".TBL_POST. "(
                   
                   idUsuario,
                   secciones_idsecciones,
                   tiempo_cambio_idTiempoCambio,
                   titulo,
                   comentario,
                   precio,
                   fechaPost
                   
                   ) VALUES (
                   (SELECT idUsuario FROM ".TBL_USUARIO. " WHERE nick = :nick),
                   (SELECT idSecciones FROM ".TBL_SECCIONES. " WHERE nombre_seccion = :nombre_seccion),
                   (SELECT idTiempoCambio FROM ".TBL_TIEMPO_CAMBIO. " WHERE tiempo = :tiempo_cambio_idTiempoCambio),
                   :titulo,
                   :comentario,
                   :precio,
                   :fechaPost
                   
                   );";
            var_dump($this);
            echo $sql;
            
            $date = date('Y-m-d');
            $st = $con->prepare($sql);
            $st->bindValue(":nick", $this->data["idUsuario"], PDO::PARAM_STR);
            $st->bindValue(":secciones_idsecciones", $this->data["secciones_idsecciones"], PDO::PARAM_STR);
            $st->bindValue(":tiempo_cambio_idTiempoCambio", $this->data["tiempo_cambio_idTiempoCambio"], PDO::PARAM_STR);
            $st->bindValue(":titulo", $this->data["titulo"], PDO::PARAM_STR);
            $st->bindValue(":comentario", $this->data["comentario"], PDO::PARAM_STR);
            $st->bindValue(":precio", $this->data["precio"], PDO::PARAM_STR);
            $st->bindValue(":fechaPost", $date, PDO::PARAM_STR);

            $st->execute();
        
                try{
                    $total_palabras = count($this->data['Pa_busacadas']);
                    echo $total_palabras.'<br>';
                    //$sql = 'INSERT INTO '.TBL_PBS_BUSCADAS. '(
                           
                    
                    
                } catch (Exception $ex) {

                }
        
        
        
            Conne::disconnect($con);
        }catch(Exception $ex){
            Conne::disconnect($con);
            echo 'El error se produce en la línea: '.$ex->getLine().'<br>';
            die("Query failed: ".$ex->getMessage());
        }
    
    
    
//fin inserArticulo    
}    
    
    
//fin de clase Post    
}
