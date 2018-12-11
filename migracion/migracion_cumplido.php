<?php

set_time_limit(0);
ini_set("memory_limit", -1);

//$conexion = mysqli_connect("181.49.169.98", "root", "70143086") or die("No se ha podido conectar al servidor de Base de datos");
$conexion = mysqli_connect("localhost", "root", "70143086") or die("No se ha podido conectar al servidor de Base de datos");
$bdOrigen = mysqli_select_db($conexion, "bdkl") or die("Upps! Pues va a ser que no se ha podido conectar a la base de datos");
$mysqli = new mysqli("localhost", "root", "70143086", "bdcotrascal");
if ($mysqli->connect_errno) {
    echo "Falló la conexión con MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

// establecer y realizar consulta. guardamos en variable.
$consulta = "
SELECT relentregadoc.IDRel, relentregadoc.Fecha, relentregadoc.IdTercero,
relentregadoc.Comentarios, t.CodigoInterface AS codigoCliente 
FROM relentregadoc LEFT JOIN terceros AS t ON relentregadoc.IdTercero = t.IDTercero WHERE t.CodigoInterface IS NOT NULL AND IDRel > 0 ORDER BY IDRel ASC limit 1000000";

$resultado = mysqli_query($conexion, $consulta) or die("Algo ha ido mal en la consulta a la base de datos de consulta");
echo "Numero filas: " . $resultado->num_rows . "<br/>";
$contador = 0;
$contadorGeneral = 0;
$strInsertarEstructura = "INSERT INTO tte_cumplido (codigo_cumplido_pk, codigo_cliente_fk, fecha,
comentario, cantidad, estado_autorizado, estado_aprobado) 
                        VALUES ";
$sqlInsertar .= $strInsertarEstructura;
while ($columna = mysqli_fetch_array($resultado)) {
    $comentarios = preg_replace('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u','', utf8_decode($columna['Comentarios']));
    $sqlInsertar .= "(" . $columna['IDRel'] . ", ". $columna['codigoCliente'] . ", '" . $columna['Fecha'] . "', '" . $comentarios . "', 
    0, 0, 0)";
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
}
if ($contador != 0) {
    if (!$mysqli->multi_query($sqlInsertar)) {
        echo "Fallo al insertar: (" . $mysqli->errno . ") " . $mysqli->error . " " . $sqlInsertar;
    } else {
        echo "Exitoso " . "<br/>";
        $mysqli->close();
    }
}




