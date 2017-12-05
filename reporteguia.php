<?php

$empresa = $_REQUEST['empresa'];
$fechaDesde = $_REQUEST['desde'];
$fechaHasta = $_REQUEST['hasta'];

$usuario = "root";
$password = "70143086";
$servidor = "181.49.169.98";
//$servidor = "192.168.1.104";
$basededatos = "bdkl";

// creación de la conexión a la base de datos con mysql_connect()
$conexion = mysqli_connect($servidor, $usuario, $password) or die("No se ha podido conectar al servidor de Base de datos");

// Selección del a base de datos a utilizar
$db = mysqli_select_db($conexion, $basededatos) or die("Upps! Pues va a ser que no se ha podido conectar a la base de datos");

// establecer y realizar consulta. guardamos en variable.
$consulta = "SELECT Guia, DATE_FORMAT(FhEntradaBodega, '%Y-%m-%d') as FhEntradaBodega, DocCliente, NmDestinatario, Unidades, VrFlete, VrManejo, VrDeclarado, EnNovedad, Entregada FROM guias "
        . "WHERE Cuenta = $empresa AND FhEntradaBodega BETWEEN '$fechaDesde 00:00' AND '$fechaHasta 23:59'  limit 10";
$resultado = mysqli_query($conexion, $consulta) or die("Algo ha ido mal en la consulta a la base de datos");
$arrGuias = array();    
while ($columna = mysqli_fetch_array( $resultado )) {
    $arrGuias[] = array(
        'Guia' => $columna['Guia'], 
        'FhEntradaBodega' => $columna['FhEntradaBodega'], 
        'DocCliente' => $columna['DocCliente'],
        'NmDestinatario' => $columna['NmDestinatario'],
        'Unidades' => $columna['Unidades'],
        'VrFlete' => $columna['VrFlete'],
        'VrManejo' => $columna['VrManejo'],
        'VrDeclarado' => $columna['VrDeclarado'],
        'EnNovedad' => $columna['EnNovedad'],
        'Entregada' => $columna['Entregada']
        );
}
$datos = array('estado' => 1, 'guias' => $arrGuias);

echo json_encode($datos);

mysqli_close( $conexion );
