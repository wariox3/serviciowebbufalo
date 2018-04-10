<?php
//https://www.developer.com/lang/php/consuming-web-services-with-php-using-nusoap.html
require_once "nusoap/lib/nusoap.php";
require_once "conexionkit.php";
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
    $servidor = conectar();
    $respuesta = "";
    $error = "";
    $xml = simplexml_load_string($parametro);
    //Validar
    foreach ($xml as $guia){
        if(!validarGuia($servidor, $guia->consecutivo)) {
            $error = "La guia " . $guia->consecutivo . " ya existe y no se puede volver a crear";
            break;
        }
    }
    if($error == "") {
        foreach ($xml as $guia){
            $sql = "INSERT INTO guias (CreadoWs, Guia, CR, Remitente, IdCliente, DocCliente, NmDestinatario, DirDestinatario,
                TelDestinatario, FhEntradaBodega, VrDeclarado, VrFlete, VrManejo, Unidades, KilosReales, KilosFacturados,
                KilosVolumen, Estado, IdFactura, Observaciones, COIng, Cuenta, Cliente, Recaudo,GuiaTipo, TipoCobro, CodigoBarrasCliente
                ) VALUE (1, ".$guia->consecutivo.", ".$guia->operacion.", '".$guia->remitente."',".$guia->operacion.",
                '".$guia->documento."','".$guia->destinatario."','".$guia->direccion."','".$guia->telefono."', now(),
                ".$guia->declarado.", ".$guia->flete.",".$guia->manejo.",".$guia->unidades.",
                ".$guia->pesoreal.",".$guia->pesoreal.",".$guia->pesovolumen.", 'I', 0,'".$guia->comentario."',
                ".$guia->operacion.", '".$guia->nit."', '".$guia->razonsocial."', ".$guia->recaudo.", ".$guia->tipoguia.",
                ".$guia->tipocobro.", '".$guia->cbarra."' 
                )";
            if (!$resultado = $servidor->query($sql)) {
                $error = "Se presento un error insertando la guia " . $guia->consecutivo . " Error:" . $servidor->error ." Sql:". $sql;
                break;
            }
        }
        if($error == "") {
            $respuesta = "Guias creadas con exito";
        } else {
            $respuesta = $error;
        }
    } else {
        $respuesta = $error;
    }
    return $respuesta;
}

    if (!isset($HTTP_RAW_POST_DATA))
        $HTTP_RAW_POST_DATA = file_get_contents('php://input');
    
    $server->service($HTTP_RAW_POST_DATA);
?>