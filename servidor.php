<?php
//https://www.developer.com/lang/php/consuming-web-services-with-php-using-nusoap.html
require_once "nusoap/lib/nusoap.php";
require_once "conexion.php";
$server = new soap_server();
$server->configureWSDL("funcionesKit", "urn:funciones");

$server->register("getPrueba",
    array("parametro" => "xsd:string"),
    array("return" => "xsd:string"),
    "urn:administracion", "urn:administracion#getPrueba", "rpc", "encoded", "Prueba funcionamiento");

function getPrueba($parametro) {
    $respuesta = "Prueba de funcionamiento parametro: " . $parametro;
    return $respuesta;
}


$server->register("getEstadoGuia",
    array("parametro" => "xsd:string"),
    array("return" => "xsd:string"),
    "urn:administracion", "urn:administracion#getEstadoGuia", "rpc", "encoded", "Estado de la guia");

    function getEstadoGuia($parametro) {
        http://php.net/manual/es/simplexml.examples-basic.php
        $elementos = new SimpleXMLElement($parametro);
        $respuesta = "Prueba de funcionamiento" . $elementos->pelicula[0]->argumento;
        return $respuesta;
    }

$server->register("getCrearGuia",
    array("parametro" => "xsd:string"),
    array("return" => "xsd:string"),
    "urn:administracion", "urn:administracion#getCrearGuia", "rpc", "encoded", "Crear");

function getCrearGuia($parametro) {
    //http://php.net/manual/es/simplexml.examples-basic.php
    //https://diego.com.es/tutorial-de-simplexml
    $respuesta = "";
    if($xml = simplexml_load_string($parametro)) {
        $respuesta = "Guias creadas correctamente";
    } else {
        $respuesta = "No se pudo cargar xml";
    }
    return $respuesta;
}

    if (!isset($HTTP_RAW_POST_DATA))
        $HTTP_RAW_POST_DATA = file_get_contents('php://input');
    
    $server->service($HTTP_RAW_POST_DATA);
?>