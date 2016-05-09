<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Carlos Neira Sanchez
 */
interface Interf_comprobar{
   public function validateField($nombreCampo, $camposPerdidos);
   public function setValue($nombreCampo);
   public function eliminarObjeto($obj);
}
