<?php

set_time_limit(0);
ini_set("memory_limit", -1);

$conexion = mysqli_connect("192.168.1.161", "root", "70143086") or die("No se ha podido conectar al servidor de Base de datos");
$bdOrigen = mysqli_select_db($conexion, "bdkl") or die("Upps! Pues va a ser que no se ha podido conectar a la base de datos");

$mysqli = new mysqli("192.168.1.200", "administrador", "Nor4m628", "bdlogicuartas");
if ($mysqli->connect_errno) {
    echo "Falló la conexión con MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

// establecer y realizar consulta. guardamos en variable.
$consulta = "SELECT Guia  
            FROM guias
            WHERE Guia <> 0
            ORDER BY Guia DESC LIMIT 900000";
$resultado = mysqli_query($conexion, $consulta) or die("Algo ha ido mal en la consulta a la base de datos de consulta");
echo "Numero filas: " . $resultado->num_rows . "<br/>";
$arrGuias = array();
while ($columna = mysqli_fetch_array( $resultado )) {
    $guiaCromo = mysqli_query($conexion, "SELECT codigo_guia_pk FROM tte_guia WHERE numero = " . $columna['Guia']);
    if ($guiaCromo->num_rows === 0) {
        echo "No existe " . $columna['Guia'] . "<br/>";
    }
}



