<?php

$conexion = mysqli_connect("190.85.62.78", "root", "70143086") or die("No se ha podido conectar al servidor de Base de datos");
$bdOrigen = mysqli_select_db($conexion, "bdkl") or die("Upps! Pues va a ser que no se ha podido conectar a la base de datos");

// establecer y realizar consulta. guardamos en variable.
$consulta = "SELECT Guia as guia FROM guias ORDER BY Guia DESC LIMIT 10";
$resultado = mysqli_query($conexion, $consulta) or die("Algo ha ido mal en la consulta a la base de datos");
$arrGuias = array();    
while ($columna = mysqli_fetch_array( $resultado )) {
    echo $columna['guia'] . "<br />";
}
