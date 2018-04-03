<?php

require_once "nusoap/lib/nusoap.php";
$cliente = new nusoap_client("http://localhost/serviciowebbufalo/servidor.php");

$error = $cliente->getError();
if ($error) {
    echo "<h2>Constructor error</h2><pre>" . $error . "</pre>";
}

//Crea la variable $xmlstr para enviar por parametro
include 'xml/prueba.php';

$result = $cliente->call("getPrueba",
    array("parametro" => "Mario Estrada"));

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