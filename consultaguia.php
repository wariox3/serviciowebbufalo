<?php

$despacho = $_REQUEST['despacho'];

$usuario = "root";
$password = "70143086";
$servidor = "181.49.169.98";
$basededatos = "bdkl";

// creación de la conexión a la base de datos con mysql_connect()
$conexion = mysqli_connect($servidor, $usuario, $password) or die("No se ha podido conectar al servidor de Base de datos");

// Selección del a base de datos a utilizar
$db = mysqli_select_db($conexion, $basededatos) or die("Upps! Pues va a ser que no se ha podido conectar a la base de datos");

// establecer y realizar consulta. guardamos en variable.
$consulta = "SELECT Guia as guia FROM guias WHERE IdDespacho = " . $despacho;
$resultado = mysqli_query($conexion, $consulta) or die("Algo ha ido mal en la consulta a la base de datos");
$arrGuias = array();    
while ($columna = mysqli_fetch_array( $resultado )) {
    $arrGuias[] = array('guia' => $columna['guia']);
}
$datos = array('estado' => 1, 'guias' => $arrGuias);

echo json_encode($datos);

mysqli_close( $conexion );
