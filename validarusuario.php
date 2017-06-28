<?php

$usu = $_REQUEST['usuario'];
$clave = $_REQUEST['clave'];

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
$consulta = "SELECT usuario FROM app_usuario WHERE usuario = '" . $usu . "' AND clave = '" . $clave . "'";
$resultado = mysqli_query($conexion, $consulta) or die("Algo ha ido mal en la consulta a la base de datos");

$numfilas = $resultado->num_rows;
if($numfilas > 0) {
    echo json_encode(array('validacion' => 1));    
} else {
    echo json_encode(array('validacion' => 2));    
}
mysqli_close( $conexion );
