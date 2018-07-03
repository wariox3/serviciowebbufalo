<?php

set_time_limit(0);
ini_set("memory_limit", -1);

$mysqli = new mysqli("192.168.1.200", "administrador", "Nor4m628", "bdlogicuartas");
if ($mysqli->connect_errno) {
    echo "Falló la conexión con MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
$consulta = "SELECT codigo_guia_pk, numero 
            FROM tte_guia";

if (!$resultado = $mysqli->query($consulta)) {
    echo "Falló la creación de la tabla: (" . $mysqli->errno . ") " . $mysqli->error;
} else {
    echo "Numero filas: " . $resultado->num_rows . "<br/>";
    $mysqliDestino = new mysqli("192.168.1.162", "root", "70143086", "bdkl");
    $contador = 0;
    $contadorGeneral = 0;
    $strInsertarEstructura = "INSERT INTO guia_alterna (codigo_guia_pk, numero ) 
                        VALUES ";
    $sqlInsertar .= $strInsertarEstructura;
    while ($columna = mysqli_fetch_array( $resultado )) {
        $sqlInsertar .= "(". $columna['codigo_guia_pk'] . ",". $columna['numero'] . ")";
        $contador++;
        $contadorGeneral++;
        if($contador == 5000) {
            if (!$mysqliDestino->multi_query($sqlInsertar)) {
                echo "Fallo al insertar: (" . $mysqliDestino->errno . ") " . $mysqliDestino->error . " " . $sqlInsertar;
            } else {
                echo "Exitoso " . "<br/>";
                $mysqliDestino->close();
                $mysqliDestino = new mysqli("192.168.1.162", "root", "70143086", "bdkl");

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
        if (!$mysqliDestino->multi_query($sqlInsertar)) {
            echo "Fallo al insertar: (" . $mysqliDestino->errno . ") " . $mysqliDestino->error . " " . $sqlInsertar;
        } else {
            echo "Exitoso " . "<br/>";
            $mysqliDestino->close();
        }
    }
}



