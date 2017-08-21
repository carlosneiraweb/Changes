<?php

echo '$_SERVER[DOCUMENT_ROOT]';
echo '<br />';
echo $_SERVER['DOCUMENT_ROOT'];
echo '<br />';
echo '__DIR__';
echo __DIR__;
echo '<br />';
echo '__FILE__';
echo __FILE__;
echo '<br />';
echo "DIRNAME: => Devuelve el directorio padre echo dirname(Changes/Modelo/rutas.php', 2)=> ".dirname('Changes/Modelo/rutas.php',2).'<br />';
echo "Dirname se usa con __FILE__ => dirname(__FILE__)". dirname(__FILE__);
echo '<br />';
echo '<br />';
echo 'Ejemplo de pathInfo => $partes_ruta = pathinfo("/www/htdocs/inc/lib.inc.php")';
echo '<br />';
echo '$partes_ruta ["dirname"], => /www/htdocs/inc'.'<br />';
echo '$partes_ruta ["basename"], => lib.inc.php'.'<br />';
echo '$partes_ruta ["extension"], => php'.'<br />';
echo '$partes_ruta ["filename"], => lib.inc'.'<br />'; // desde PHP 5.2.0';
echo '<br /> <br />';
echo 'realpath() - Devuelve el nombre de la ruta absoluta canonizado';
echo '<br />';
echo 'Devuelve el nombre de la ruta absoluta canonizado en caso de éxito. <br />'.
'La ruta resultante no tendrá componentes de enlaces simbólicos, "/./" o "/../" <br />'.
    ' Los delimitadores finales, como \ y /, también son eliminados. ';
echo '<br /><br />  ';
$real  = <<<identificadorPropio
chdir('/var/www/')=> cambia de directorio . <br />
echo realpath('./../../etc/passwd') . <br />
echo realpath('/tmp/') . PHP_EOL .<br />
      <br />
El resultado sería: <br />
/etc/passwd <br />
/tmp
        
        <br /><br />
realpath('/windows/system32') <br />
C:\Archivos de programa <br />
realpath('C:\Archivos de programa\\')
        <br />
El resultado: <br />
C:\WINDOWS\System32 <br />
C:\Archivos de programa
identificadorPropio;
echo $real;