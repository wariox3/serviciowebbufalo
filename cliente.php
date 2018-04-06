<?php
require_once "conexionkit.php";
require_once "nusoap/lib/nusoap.php";
$cliente = new nusoap_client("http://localhost/serviciowebbufalo/servidor.php");

$error = $cliente->getError();
if ($error) {
    echo "<h2>Constructor error</h2><pre>" . $error . "</pre>";
}

$servidor = conectar();
//Crea la variable $xmlstr para enviar por parametro
include 'xml/prueba.php';
include 'xml/guia.php';

/*$error = "";
$xml = simplexml_load_string($xmlguia);
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
                KilosVolumen, Estado, IdFactura, Observaciones, COIng, Cuenta, Cliente, Recaudo,GuiaTipo, TipoCobro
                ) VALUE (1, ".$guia->consecutivo.", ".$guia->operacion.", '".$guia->remitente."',".$guia->operacion.",
                '".$guia->documento."','".$guia->destinatario."','".$guia->direccion."','".$guia->telefono."', now(),
                ".$guia->declarado.", ".$guia->flete.",".$guia->manejo.",".$guia->unidades.",
                ".$guia->pesoreal.",".$guia->pesoreal.",".$guia->pesovolumen.", 'I', 0,'".$guia->comentario."',
                ".$guia->operacion.", '".$guia->nit."', '".$guia->razonsocial."', ".$guia->recaudo.", ".$guia->tipoguia.",
                ".$guia->tipocobro." 
                )";
        if (!$resultado = $servidor->query($sql)) {
            $error = "Se presento un error insertando la guia " . $guia->consecutivo . " Error:" . $servidor->error ." Sql:". $sql;
            echo $error;
            break;
        }
    }
    if ($error == "") {
        echo "Las guias se insertaron satisfactoriamente";
    }
} else {
    echo $error . "<br/>";
}*/



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