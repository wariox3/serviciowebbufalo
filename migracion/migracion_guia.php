<?php

set_time_limit(0);
ini_set("memory_limit", -1);

$conexion = mysqli_connect("192.168.1.104", "root", "70143086") or die("No se ha podido conectar al servidor de Base de datos");
$bdOrigen = mysqli_select_db($conexion, "bdkl") or die("Upps! Pues va a ser que no se ha podido conectar a la base de datos");
//$mysqli = new mysqli("192.168.1.200", "administrador", "Nor4m628", "bdlogicuartas");
$mysqli = new mysqli("localhost", "root", "70143086", "bdcotrascal");
if ($mysqli->connect_errno) {
    echo "Falló la conexión con MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

// establecer y realizar consulta. guardamos en variable.
$consulta = "SELECT Guia, Unidades, KilosReales, KilosFacturados, KilosVolumen, VrDeclarado, VrFlete, VrManejo, Recaudo,
              Abonos, t.CodigoInterface AS codigoCliente, IdCiuOrigen, IdCiuDestino, DocCliente, Remitente, NmDestinatario,
              DirDestinatario, TelDestinatario, FhEntradaBodega, FhDespacho, FhEntregaMercancia, FhDescargada, GuiaTipo, 
              TpServicio, Estado, Entregada, Descargada, Relacionada, IdFactura, Anulada, Observaciones, IdDespacho,
              IdRuta, Orden, guias.IdCliente, IdDespacho, IdRelEntrega 
            FROM guias
            LEFT JOIN terceros AS t ON guias.Cuenta = t.IDTercero WHERE t.CodigoInterface IS NOT NULL AND Guia > 0 
            ORDER BY Guia ASC LIMIT 1500000";
$resultado = mysqli_query($conexion, $consulta) or die("Algo ha ido mal en la consulta a la base de datos de consulta");
echo "Numero filas: " . $resultado->num_rows . "<br/>";
$arrGuias = array();
$contador = 0;
$contadorGeneral = 0;
$strInsertarEstructura = "INSERT INTO tte_guia (codigo_guia_pk, numero, unidades, peso_real, peso_facturado, peso_volumen, vr_declara, vr_flete, vr_manejo, vr_recaudo, vr_abono,
                                    codigo_operacion_ingreso_fk, codigo_operacion_cargo_fk, codigo_cliente_fk, codigo_ciudad_origen_fk, codigo_ciudad_destino_fk,
                                    documento_cliente, Remitente, nombre_destinatario, direccion_destinatario, telefono_destinatario, fecha_ingreso, fecha_despacho, 
                                    fecha_entrega, fecha_cumplido, fecha_soporte, codigo_guia_tipo_fk, codigo_servicio_fk, estado_impreso, estado_embarcado, 
                                    estado_despachado, estado_entregado, estado_soporte, estado_cumplido, estado_facturado, estado_factura_generada, estado_anulado,
                                    comentario, factura, codigo_empaque_fk, codigo_ruta_fk, orden_ruta, codigo_condicion_fk, codigo_despacho_fk, codigo_factura_fk,
                                    codigo_cumplido_fk 
                                    ) 
                        VALUES ";
$sqlInsertar .= $strInsertarEstructura;
while ($columna = mysqli_fetch_array( $resultado )) {
    //echo $columna['guia'] . "<br />";
    if($columna['FhDespacho'] == '') {
        $columna['FhDespacho'] = $columna['FhEntradaBodega'];
    }
    if($columna['FhEntregaMercancia'] == '') {
        $columna['FhEntregaMercancia'] = $columna['FhEntradaBodega'];
    }
    if($columna['FhDescargada'] == '') {
        $columna['FhDescargada'] = $columna['FhEntradaBodega'];
    }
    $tipo = "";
    switch ($columna['GuiaTipo']) {
        case 1:
            $tipo = "COR";
            break;
        case 2:
            $tipo = "CON";
            break;
        case 3:
            $tipo = "DES";
            break;
        case 4:
            $tipo = "CTS";
            break;
    }
    $factura = 0;
    if($tipo == "CON" || $tipo == "DES") {
        $factura = 1;
    }
    $servicio = "";
    switch ($columna['TpServicio']) {
        case 0:
            $servicio = "PAQ";
            break;
        case 1:
            $servicio = "SMA";
            break;
        case 2:
            $servicio = "MAS";
            break;
        case 3:
            $servicio = "URB";
            break;
        case 4:
            $servicio = "ENC";
            break;
        case 5:
            $servicio = "DEV";
            break;
        case 6:
            $servicio = "REC";
            break;
    }
    $estadoImpreso = 0;
    if($columna['Estado'] != 'D') {
        $estadoImpreso = 1;
    }
    $estadoEmbarcadoDespachado = 0;
    if($columna['IdDespacho'] != '') {
        $estadoEmbarcadoDespachado = 1;
    }
    $estadoFacturado = 0;
    if($columna['IdFactura'] != '') {
        $estadoFacturado = 1;
    }
    $codigoDespacho = "NULL";
    if($columna['IdDespacho'] && $columna['IdDespacho'] != 0) {
        $codigoDespacho = $columna['IdDespacho'];
    }
    $codigoFactura = "NULL";
    if($columna['IdFactura'] && $columna['IdFactura'] != 0) {
        $codigoFactura = $columna['IdFactura'];
    }
    $codigoCumplido = "NULL";
    if($columna['IdRelEntrega'] && $columna['IdRelEntrega'] != 0) {
        $codigoCumplido = $columna['IdRelEntrega'];
    }
    $destinatario = preg_replace('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u','', utf8_decode($columna['NmDestinatario']));
    $direccionDestinatario = preg_replace('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u','', utf8_decode($columna['DirDestinatario']));
    $telefonoDestinatario = preg_replace('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u','', utf8_decode($columna['TelDestinatario']));
    $remitente = preg_replace('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u','', utf8_decode($columna['Remitente']));
    $documento = preg_replace('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u','', utf8_decode($columna['DocCliente']));
    $comentario = preg_replace('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u','', utf8_decode($columna['Observaciones']));
    $sqlInsertar .= "(". $columna['Guia'] . ",". $columna['Guia'] . ",". $columna['Unidades'] . ",". $columna['KilosReales'] . ",". $columna['KilosFacturados'] . ",". $columna['KilosVolumen'] . "
                        ,". $columna['VrDeclarado'] . ",". $columna['VrFlete'] . ",". $columna['VrManejo'] . ",". $columna['Recaudo'] . ",". $columna['Abonos'] . ",'MED','MED'
                        ,". $columna['codigoCliente'] . ",". $columna['IdCiuOrigen'] . ",". $columna['IdCiuDestino'] . ",'". $documento . "','". $remitente . "'
                        ,'". $destinatario . "','". $direccionDestinatario . "','". $telefonoDestinatario . "','". $columna['FhEntradaBodega'] . "'
                        ,'". $columna['FhDespacho'] . "','". $columna['FhEntregaMercancia'] . "','". $columna['FhDescargada'] . "','". $columna['FhDescargada'] . "'
                        ,'$tipo','$servicio', $estadoImpreso,$estadoEmbarcadoDespachado,$estadoEmbarcadoDespachado," . $columna['Entregada'] . "," . $columna['Descargada'] . "
                        ," . $columna['Relacionada'] . ",$estadoFacturado,$estadoFacturado," . $columna['Anulada'] . ",'" . $comentario . "',$factura
                        ,'VAR'," . $columna['IdRuta'] . "," . $columna['Orden'] . "," . $columna['IdCliente'] . "," . $codigoDespacho . "," . $codigoFactura . ",
                        " . $codigoCumplido . ")";
    $contador++;
    $contadorGeneral++;
    if($contador == 5000) {
        if (!$mysqli->multi_query($sqlInsertar)) {
            echo "Fallo al insertar: (" . $mysqli->errno . ") " . $mysqli->error . " " . $sqlInsertar;
            break;
        } else {
            echo "Exitoso " . "<br/>";
            $mysqli->close();
//            $mysqli = new mysqli("192.168.1.200", "administrador", "Nor4m628", "bdlogicuartas");
            $mysqli = new mysqli("localhost", "root", "70143086", "bdcotrascal");

            $sqlInsertar = $strInsertarEstructura;
            $contador = 0;
        }
    } else {
        if($contadorGeneral != $resultado->num_rows) {
            $sqlInsertar .= ",";
        }
    }
    //$bdDestino->close();
}
if($contador != 0) {
    if (!$mysqli->multi_query($sqlInsertar)) {
        echo "Fallo al insertar: (" . $mysqli->errno . ") " . $mysqli->error . " " . $sqlInsertar;
    } else {
        echo "Exitoso " . "<br/>";
        $mysqli->close();
    }
}




