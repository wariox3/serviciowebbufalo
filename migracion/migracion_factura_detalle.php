<?php

set_time_limit(0);
ini_set("memory_limit", -1);

$conexion = mysqli_connect("192.168.1.104", "root", "70143086") or die("No se ha podido conectar al servidor de Base de datos");
$bdOrigen = mysqli_select_db($conexion, "bdkl") or die("Upps! Pues va a ser que no se ha podido conectar a la base de datos");
$mysqli = new mysqli("localhost", "root", "70143086", "bdcotrascal");
if ($mysqli->connect_errno) {
    echo "Falló la conexión con MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

$consulta = "SELECT Guia, IdFactura
            FROM guias
            LEFT JOIN terceros AS t ON guias.Cuenta = t.IDTercero WHERE t.CodigoInterface IS NOT NULL AND Guia > 0 and IdFactura IS NOT NULL and IdFactura <> 0 
            ORDER BY Guia ASC LIMIT 9000000";
$resultado = mysqli_query($conexion, $consulta) or die("Algo ha ido mal en la consulta a la base de datos de consulta");
echo "Numero filas: " . $resultado->num_rows . "<br/>";
$arrGuias = array();
$contador = 0;
$contadorGeneral = 0;
$strInsertarEstructura = "INSERT INTO tte_factura_detalle (codigo_factura_fk, codigo_guia_fk 
                                    ) 
                        VALUES ";
$sqlInsertar .= $strInsertarEstructura;
while ($columna = mysqli_fetch_array( $resultado )) {

    $sqlInsertar .= "(". $columna['IdFactura'] . ",". $columna['Guia'] . ")";
    $contador++;
    $contadorGeneral++;
    if($contador == 5000) {
        if (!$mysqli->multi_query($sqlInsertar)) {
            echo "Fallo al insertar: (" . $mysqli->errno . ") " . $mysqli->error . " " . $sqlInsertar;
        } else {
            echo "Exitoso " . "<br/>";
            $mysqli->close();
            $mysqli = new mysqli("localhost", "root", "70143086", "bdcotrascal");
            //$mysqli = new mysqli("localhost", "root", "70143086", "bdlogicuartas");
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




