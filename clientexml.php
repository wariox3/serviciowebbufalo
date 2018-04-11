<?php

require_once "nusoap/lib/nusoap.php";
$cliente = new nusoap_client("http://localhost:8081/serviciowebbufalo/servidor.php");
//$cliente = new nusoap_client("http://localhost/serviciowebbufalo/servidor.php"); //Pruebas

$error = $cliente->getError();
if ($error) {
    echo "<h2>Constructor error</h2><pre>" . $error . "</pre>";
}

$parametro = $_POST['parametro'];
$tipo = $_POST['tipo'];
if($tipo == "guia") {
    $result = $cliente->call("getCrearGuia",
        array("parametro" => $parametro));
}
if($tipo == "recogida") {
    $result = $cliente->call("getCrearRecogida",
        array("parametro" => $parametro));
}


if ($cliente->fault) {
    echo "<h2>Fault</h2><pre>";
    print_r($result);
    echo "</pre>";
} else {
    $error = $cliente->getError();
    if ($error) {
        echo "<h2>Error</h2><pre>" . $error . "</pre>";
    } else {
        echo "<h2>Resultado de la llamada</h2><pre>";
        echo $result;
        echo "</pre>";
    }
}
?>