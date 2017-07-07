<?php

$guia = $_REQUEST['guia'];

$usuario = "root";
$password = "70143086";
$servidor = "181.49.169.98";
$servidor = "192.168.1.104";
$basededatos = "bdkl";

// creación de la conexión a la base de datos con mysql_connect()
$conexion = mysqli_connect($servidor, $usuario, $password) or die("No se ha podido conectar al servidor de Base de datos");

// Selección del a base de datos a utilizar
$db = mysqli_select_db($conexion, $basededatos) or die("Upps! Pues va a ser que no se ha podido conectar a la base de datos");
$arrDatos = array('estadoRecibida' => 0, 'fechaEntrada' => '', 'estadoReparto' => 0, 'estadoEntregada' => 0, 'fechaEntrega' => '', 'estadoCumplida' => 0);

// establecer y realizar consulta. guardamos en variable.
$consulta = "SELECT Guia, FhEntradaBodega, Despachada, Entregada, FhEntregaMercancia, Relacionada, Facturada FROM guias WHERE Guia = " . $guia;
$resultado = mysqli_query($conexion, $consulta) or die(json_encode("Algo ha ido mal en la consulta a la base de datos"));
$arGuia = mysqli_fetch_assoc($resultado);
if($arGuia) {
    $arrDatos['estadoRecibida'] = 1;
    $arrDatos['fechaEntrada'] = $arGuia['FhEntradaBodega'];
    if($arGuia['Despachada'] == 1) {
        $arrDatos['estadoReparto'] = 1;
    }
    if($arGuia['Entregada'] == 1) {
        $arrDatos['estadoEntregada'] = 1;
        $arrDatos['fechaEntrega'] = $arGuia['FhEntregaMercancia'];
    }    
    if($arGuia['Relacionada'] == 1 || $arGuia['Facturada'] == 1) {
        $arrDatos['estadoCumplida'] = 1;
    }    
}

echo json_encode($arrDatos);

mysqli_close( $conexion );
