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
SELECT IdManifiesto, OrdDespacho, Tipo, FhExpedicion, FhCumplidos, IdVehiculo, despachos.IdConductor, IdRuta, IdCiudadOrigen, IdCiudadDestino, Estado,
Remesas, Unidades, KilosReales, KilosVol, FleteCobra, ManejoCobra, FleteCE, ManejoCE, VrFlete, VrAnticipo, VrDctoPapeleria, VrDctoSeguridad,
VrDctoCargue, VrDctoEstampilla, VrFleteAdicional, VrDctoIndCom, VrDctoRteFte, VrOtrosDctos, SaldoDesp, TotalViaje, CO, Observaciones, TRecaudo,
VrDeclaradoTotal, ManElectronico, NmConductor, Cerrado, Liquidado, IdUsuario, IdEmpresa, Exportado, LugarPago, FhPagoSaldo, PagoCargue, PagoDescargue,
Estado1, AbonosCE, FletesNoCancelados, EnviadoMT, EnviadoGuia, TotalCE, FleteContado, ManejoContado, FleteCorriente, ManejoCorriente, FleteCETotal, ManejoCETotal,
ExportadoContabilidad, conductores.CodigoInterface AS codigoConductor FROM despachos 
LEFT JOIN conductores ON despachos.IdConductor = conductores.IdConductor ";
$resultado = mysqli_query($conexion, $consulta) or die("Algo ha ido mal en la consulta a la base de datos de consulta");
echo "Numero filas: " . $resultado->num_rows . "<br/>";
$contador = 0;
$contadorGeneral = 0;
$strInsertarEstructura = "INSERT INTO tte_despacho (codigo_despacho_pk, numero, codigo_operacion_fk, codigo_ciudad_origen_fk, codigo_ciudad_destino_fk,
codigo_vehiculo_fk, codigo_conductor_fk, codigo_ruta_fk, fecha_registro, fecha_salida, cantidad, unidades, peso_real, peso_volumen, vr_declara, vr_flete_pago, vr_anticipo, 
estado_autorizado, estado_aprobado, comentario) 
                        VALUES ";
$sqlInsertar .= $strInsertarEstructura;
while ($columna = mysqli_fetch_array($resultado)) {
    //echo $columna['OrdDespacho'] . "<br />";
    $sqlInsertar .= "(". $columna['OrdDespacho'] . ",". $columna['IdManifiesto'] . ", 'MED', '" . $columna['IdCiudadOrigen'] . "', '" . $columna['IdCiudadDestino'] . "', 
    '" . $columna['IdVehiculo'] . "', " . $columna['codigoConductor'] . ", '" . $columna['IdRuta'] . "','". $columna['FhExpedicion'] . "','". $columna['FhExpedicion'] . "',". $columna['Remesas'] . ",
    " . $columna['Unidades'] . ", " . $columna['KilosReales'] . ", " . $columna['KilosVol'] . ", " . $columna['VrDeclaradoTotal'] . ", " . $columna['VrFlete'] . ", 
    " . $columna['VrAnticipo'] . ", 1, 1, '" .  utf8_decode($columna['Observaciones']) . "')";
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




