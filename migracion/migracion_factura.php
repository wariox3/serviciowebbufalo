<?php

set_time_limit(0);
ini_set("memory_limit", -1);

$conexion = mysqli_connect("192.168.1.161", "root", "70143086") or die("No se ha podido conectar al servidor de Base de datos");
//$conexion = mysqli_connect("190.85.62.78", "root", "70143086") or die("No se ha podido conectar al servidor de Base de datos");
$bdOrigen = mysqli_select_db($conexion, "bdkl") or die("Upps! Pues va a ser que no se ha podido conectar a la base de datos");

$mysqli = new mysqli("192.168.1.200", "administrador", "Nor4m628", "bdlogicuartas");
//$mysqli = new mysqli("localhost", "root", "70143086", "bdlogicuartas");
if ($mysqli->connect_errno) {
    echo "Falló la conexión con MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

// establecer y realizar consulta. guardamos en variable.
$consulta = "
SELECT IdFactura, FhFac, FhVenceFac, facturas.IdCliente, Estado, Notas, TFlete, TManejo, TOtros, DctoComercial, BaseCcial, DctoFinanciero,
BaseFin, AntesDeDcto, Abonos, TotalFactura, Saldo, NroGuias, NroPlanillas, NroConceptos, facturas.Plazo, facturas.IdFormaPago, codigo_centro_operaciones_fk,
IdTipoFactura, Exportada, IdEmpresa, ValorEnLetras, CodigoBarras, t.CodigoInterface AS codigoCliente 
FROM facturas LEFT JOIN terceros AS t ON facturas.IdCliente = t.IDTercero WHERE IdFactura > 0 ORDER BY IdFactura ASC limit 9000000";

$resultado = mysqli_query($conexion, $consulta) or die("Algo ha ido mal en la consulta a la base de datos de consulta");
echo "Numero filas: " . $resultado->num_rows . "<br/>";
$contador = 0;
$contadorGeneral = 0;
$strInsertarEstructura = "INSERT INTO tte_factura (codigo_factura_pk, codigo_factura_tipo_fk, codigo_cliente_fk, numero, fecha, fecha_vence, vr_flete, vr_manejo, vr_subtotal, vr_otros,
vr_total, guias, comentario, plazo_pago, estado_autorizado, estado_aprobado, estado_anulado) 
                        VALUES ";
$sqlInsertar .= $strInsertarEstructura;
while ($columna = mysqli_fetch_array($resultado)) {
    $estadoAprobado = 0;
    if($columna['Estado'] == 'I') {
        $estadoAprobado = 1;
    }
    $estadoAnulado = 0;
    if($columna['Estado'] == 'A') {
        $estadoAnulado = 1;
    }
    $estadoAutorizado = 0;
    if($columna['Estado'] != 'D') {
        $estadoAutorizado = 1;
    }

    $sqlInsertar .= "(" . $columna['IdFactura'] . ",'COR',". $columna['codigoCliente'] . ", " . $columna['IdFactura'] . ", '" . $columna['FhFac'] . "', '" . $columna['FhVenceFac'] . "', 
    " . $columna['TFlete'] . ", " . $columna['TManejo'] . ", " . $columna['TOtros'] . ", 0, 0, 0, '" . $columna['Notas'] . "',". $columna['Plazo'] . ", $estadoAutorizado, $estadoAprobado, $estadoAnulado)";
    $contador++;
    $contadorGeneral++;
    if($contador == 5000) {
        if (!$mysqli->multi_query($sqlInsertar)) {
            echo "Fallo al insertar: (" . $mysqli->errno . ") " . $mysqli->error . " " . $sqlInsertar;
        } else {
            echo "Exitoso " . "<br/>";
            $mysqli->close();
            $mysqli = new mysqli("192.168.1.200", "administrador", "Nor4m628", "bdlogicuartas");
            //$mysqli = new mysqli("localhost", "root", "70143086", "bdlogicuartas");
            $sqlInsertar = $strInsertarEstructura;
            $contador = 0;
        }
    } else {
        if($contadorGeneral != $resultado->num_rows) {
            $sqlInsertar .= ",";
        }
    }
}
if ($contador != 0) {
    if (!$mysqli->multi_query($sqlInsertar)) {
        echo "Fallo al insertar: (" . $mysqli->errno . ") " . $mysqli->error . " " . $sqlInsertar;
    } else {
        echo "Exitoso " . "<br/>";
        $mysqli->close();
    }
}




