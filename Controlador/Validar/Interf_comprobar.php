<?php


/**
 *
 * @author Carlos Neira Sanchez
 */
interface Interf_comprobar{
   public static function validateField($nombreCampo, $camposPerdidos);
   public static function comprobarCheck($nombreCampo);
   public static function campoVacio($elemento);
}
