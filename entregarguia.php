<?php

$guia = $_REQUEST['guia'];
$fechaEntrega = $_REQUEST['fecha'];
$horaEntrega = $_REQUEST['hora'];
$usuario = "root";
$password = "70143086";
$servidor = "181.49.169.98";
$servidor = "192.168.1.104";
$basededatos = "bdkl";

// creación de la conexión a la base de datos con mysql_connect()
$conexion = mysqli_connect($servidor, $usuario, $password) or die("No se ha podido conectar al servidor de Base de datos");

// Selección del a base de datos a utilizar
$db = mysqli_select_db($conexion, $basededatos) or die("Upps! Pues va a ser que no se ha podido conectar a la base de datos");

// establecer y realizar consulta. guardamos en variable.
$consulta = "UPDATE guias SET Entregada = 1, FhEntregaMercancia = '" . $fechaEntrega . " " . $horaEntrega . "' WHERE Guia = " . $guia;
$resultado = mysqli_query($conexion, $consulta) or die(json_encode(array('estado' => 2)));
$estado = 1;
if (!$resultado) {  
    $estado = 2;
}
$datos = array('estado' => $estado);
echo json_encode($datos);
mysqli_close( $conexion );
