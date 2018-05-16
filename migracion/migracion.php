<?php

$conexion = mysqli_connect("190.85.62.78", "root", "70143086") or die("No se ha podido conectar al servidor de Base de datos");
$bdOrigen = mysqli_select_db($conexion, "bdkl") or die("Upps! Pues va a ser que no se ha podido conectar a la base de datos");

$mysqli = new mysqli("localhost", "root", "70143086", "bdlogicuartas");
if ($mysqli->connect_errno) {
    echo "Falló la conexión con MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

// establecer y realizar consulta. guardamos en variable.
$consulta = "SELECT Guia, Unidades, KilosReales, KilosFacturados, KilosVolumen, VrDeclarado, VrFlete, VrManejo, Recaudo,
              Abonos, t.CodigoInterface AS codigoCliente, IdCiuOrigen, IdCiuDestino, DocCliente, Remitente, NmDestinatario,
              DirDestinatario, TelDestinatario, FhEntradaBodega, FhDespacho, FhEntregaMercancia, FhDescargada  
            FROM guias
            LEFT JOIN terceros AS t ON guias.Cuenta = t.IDTercero 
            ORDER BY Guia DESC LIMIT 3";
$resultado = mysqli_query($conexion, $consulta) or die("Algo ha ido mal en la consulta a la base de datos");
$arrGuias = array();    
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
    $strInsertarGuia = "INSERT INTO tte_guia (numero, unidades, peso_real, peso_facturado, peso_volumen, vr_declara, vr_flete, vr_manejo, vr_recaudo, vr_abono,
                                    codigo_operacion_ingreso_fk, codigo_operacion_cargo_fk, codigo_cliente_fk, codigo_ciudad_origen_fk, codigo_ciudad_destino_fk,
                                    documento_cliente, Remitente, nombre_destinatario, direccion_destinatario, telefono_destinatario, fecha_ingreso, fecha_despacho, 
                                    fecha_entrega, fecha_cumplido, fecha_soporte
                                    ) 
                        VALUES(". $columna['Guia'] . ",". $columna['Unidades'] . ",". $columna['KilosReales'] . ",". $columna['KilosFacturados'] . ",". $columna['KilosVolumen'] . "
                        ,". $columna['VrDeclarado'] . ",". $columna['VrFlete'] . ",". $columna['VrManejo'] . ",". $columna['Recaudo'] . ",". $columna['Abonos'] . ", 'MED', 'MED'
                        ,". $columna['codigoCliente'] . ",". $columna['IdCiuOrigen'] . ",". $columna['IdCiuDestino'] . ",'". $columna['DocCliente'] . "','". $columna['Remitente'] . "'
                        ,'". $columna['NmDestinatario'] . "','". $columna['DirDestinatario'] . "','". $columna['TelDestinatario'] . "','". $columna['FhEntradaBodega'] . "'
                        ,'". $columna['FhDespacho'] . "','". $columna['FhEntregaMercancia'] . "','". $columna['FhDescargada'] . "','". $columna['FhDescargada'] . "')";
    if (!$mysqli->query($strInsertarGuia)) {
        echo "Fallo al insertar: (" . $mysqli->errno . ") " . $mysqli->error . " " . $strInsertarGuia;
        break;
    }
    //$bdDestino->close();
}
