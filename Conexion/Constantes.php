<?php

class Constantes{
	const SV = 'localhost';   
        const BD = 'portal';
        const US = 'root';
        const PASS = "";

        public static function getSV(){
            return self::SV;
        }
        public static function getBD(){
            return self::BD;
        }
        public static function getUS(){
            return self::US;
        }
        public static function getPASS(){
            return self::PASS;
        }
}
