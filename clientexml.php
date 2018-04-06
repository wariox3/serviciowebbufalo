<?php

require_once "nusoap/lib/nusoap.php";
$cliente = new nusoap_client("http://localhost:8081/serviciowebbufalo/servidor.php");

$error = $cliente->getError();
if ($error) {
    echo "<h2>Constructor error</h2><pre>" . $error . "</pre>";
}
//include 'xml/guia.php';
//$parametro = $xmlguia;
/*https://diego.com.es/tutorial-de-simplexml
$parametro = $_POST['parametro'];
$elementos = new SimpleXMLElement($parametro);
$xml = simplexml_load_string($parametro);
foreach ($xml as $guia){
    echo 'Guia: '.$guia->consecutivo.'<br>';
}*/
$parametro = $_POST['parametro'];
$result = $cliente->call("getCrearGuia",
    array("parametro" => $parametro));

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